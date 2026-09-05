"""
Layanan integrasi & analitik data kecamatan — Kecamatan Buay Bahuga.

Layanan Python ini bertugas menerima snapshot data (kampung, penduduk,
laporan) yang dikirim oleh backend Laravel, mengagregasinya menjadi
rekap resmi tingkat kecamatan, lalu menyimpan hasilnya sehingga dapat
ditampilkan kembali di dashboard (menu "Integrasi Kecamatan").

Jalankan:
    pip install -r requirements.txt
    python app.py
Default berjalan di http://127.0.0.1:5000
"""

from __future__ import annotations

import json
import os
from datetime import datetime
from functools import wraps
from pathlib import Path

from flask import Flask, jsonify, request

try:
    from dotenv import load_dotenv
    load_dotenv()
except ImportError:
    pass

try:
    from flask_cors import CORS
    _HAS_CORS = True
except ImportError:
    _HAS_CORS = False

app = Flask(__name__)
if _HAS_CORS:
    CORS(app)

DATA_FILE = Path(__file__).parent / "storage" / "rekap_kecamatan.json"
DATA_FILE.parent.mkdir(exist_ok=True)

SERVICE_TOKEN = os.environ.get("PYTHON_SERVICE_TOKEN", "ganti_dengan_token_rahasia")

DAFTAR_KAMPUNG = [
    "Bumiharjo", "Lebung Lawe", "Nuar Maju", "Punjul Agung", "Sri Tunggal",
    "Suka Agung", "Sukabumi", "Sukadana", "Way Agung",
]


def require_token(view_func):
    """Verifikasi header Authorization: Bearer <token> dari Laravel."""

    @wraps(view_func)
    def wrapper(*args, **kwargs):
        auth_header = request.headers.get("Authorization", "")
        token = auth_header.replace("Bearer ", "").strip()
        if not token or token != SERVICE_TOKEN:
            return jsonify({"error": "Token tidak valid."}), 401
        return view_func(*args, **kwargs)

    return wrapper


def simpan_rekap(data: dict) -> None:
    DATA_FILE.write_text(json.dumps(data, ensure_ascii=False, indent=2))


def muat_rekap() -> dict | None:
    if not DATA_FILE.exists():
        return None
    return json.loads(DATA_FILE.read_text())


@app.get("/api/health")
def health():
    return jsonify({"status": "ok", "layanan": "rekap-kecamatan-buay-bahuga"})


@app.get("/api/daftar-kampung")
@require_token
def daftar_kampung():
    """Referensi 9 kampung resmi Kecamatan Buay Bahuga."""
    return jsonify({"kecamatan": "Buay Bahuga", "kabupaten": "Way Kanan", "kampung": DAFTAR_KAMPUNG})


@app.post("/api/sinkronisasi")
@require_token
def sinkronisasi():
    """
    Menerima snapshot data dari Laravel (kampung, laporan terverifikasi,
    ringkasan penduduk per jenis kelamin), lalu mengagregasinya menjadi
    rekap resmi tingkat kecamatan.
    """
    payload = request.get_json(force=True) or {}

    kampung_list = payload.get("kampung", [])
    laporan_list = payload.get("laporan", [])
    ringkasan_penduduk = payload.get("ringkasan_penduduk", [])

    total_penduduk = sum(item.get("penduduks_count", 0) for item in kampung_list)
    total_kelahiran = sum(item.get("jumlah_kelahiran", 0) for item in laporan_list)
    total_kematian = sum(item.get("jumlah_kematian", 0) for item in laporan_list)
    total_pindah_masuk = sum(item.get("jumlah_pindah_masuk", 0) for item in laporan_list)
    total_pindah_keluar = sum(item.get("jumlah_pindah_keluar", 0) for item in laporan_list)

    total_laki = sum(i.get("total", 0) for i in ringkasan_penduduk if i.get("jenis_kelamin") == "L")
    total_perempuan = sum(i.get("total", 0) for i in ringkasan_penduduk if i.get("jenis_kelamin") == "P")

    rekap = {
        "kecamatan": "Buay Bahuga",
        "kabupaten": "Way Kanan",
        "jumlah_kampung": len(kampung_list) or len(DAFTAR_KAMPUNG),
        "total_penduduk": total_penduduk,
        "total_laki": total_laki,
        "total_perempuan": total_perempuan,
        "total_kelahiran": total_kelahiran,
        "total_kematian": total_kematian,
        "total_pindah_masuk": total_pindah_masuk,
        "total_pindah_keluar": total_pindah_keluar,
        "pertumbuhan_alami": total_kelahiran - total_kematian,
        "per_kampung": [
            {
                "nama_kampung": item.get("nama_kampung"),
                "jumlah_penduduk": item.get("penduduks_count", 0),
            }
            for item in kampung_list
        ],
        "diproses_pada": datetime.now().strftime("%d %B %Y, %H:%M WIB"),
    }

    simpan_rekap(rekap)

    return jsonify({"message": "Sinkronisasi berhasil.", "rekap": rekap}), 200


@app.get("/api/rekap-kecamatan")
@require_token
def rekap_kecamatan():
    """Dikonsumsi oleh dashboard Laravel (menu Integrasi Kecamatan)."""
    rekap = muat_rekap()
    if rekap is None:
        return jsonify({"message": "Belum ada data sinkronisasi."}), 404
    return jsonify(rekap)


if __name__ == "__main__":
    port = int(os.environ.get("PORT", 5000))
    app.run(host="0.0.0.0", port=port, debug=False)
