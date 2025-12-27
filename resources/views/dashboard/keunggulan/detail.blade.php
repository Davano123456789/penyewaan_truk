@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Keunggulan</h1>
        <a href="{{ route('keunggulan.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h4>{{ $keunggulan->judul }}</h4>

            @if($keunggulan->gambar)
                <div class="mb-3">
                    <img src="{{ $keunggulan->gambar }}" alt="{{ $keunggulan->judul }}" class="img-fluid rounded" style="max-width: 400px;">
                </div>
            @endif

            <p>{!! nl2br(e($keunggulan->deskripsi)) !!}</p>
        </div>
    </div>

</div>
@endsection
