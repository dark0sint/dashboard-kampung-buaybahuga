@extends('layouts.app')
@section('title', 'Ubah Data Kampung')

@section('content')
<div class="bb-panel">
    <div class="bb-panel-title">Ubah Data Kampung — {{ $kampung->nama_kampung }}</div>
    <form method="POST" action="{{ route('kampung.update', $kampung) }}">
        @method('PUT')
        @include('kampung._form')
    </form>
</div>
@endsection
