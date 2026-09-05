<?php

use App\Models\Kampung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Endpoint terbatas token Sanctum, dikonsumsi oleh layanan Python
// dan pihak kecamatan untuk mengambil data mentah 9 kampung.
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/kampung', function () {
        return Kampung::withCount('penduduks')->get();
    });

    Route::get('/kampung/{kampung}/penduduk', function (Kampung $kampung) {
        return $kampung->penduduks()->paginate(50);
    });
});
