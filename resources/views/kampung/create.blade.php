@extends('layouts.app')
@section('title', 'Tambah Kampung')

@section('content')
<div class="bb-panel">
    <div class="bb-panel-title">Tambah Data Kampung Baru</div>
    <form method="POST" action="{{ route('kampung.store') }}">
        @include('kampung._form')
    </form>
</div>
@endsection
