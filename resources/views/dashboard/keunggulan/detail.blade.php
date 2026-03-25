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
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Keunggulan</h6>
        
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="font-weight-bold grey-800">Judul</label>
                <input type="text" class="form-control bg-light" value="{{ $keunggulan->judul }}" readonly disabled>
            </div>

            <div class="form-group">
                <label class="font-weight-bold grey-800">Gambar</label>
                <div class="mb-3">
                    @if($keunggulan->gambar)
                        <img src="{{ $keunggulan->gambar }}" alt="{{ $keunggulan->judul }}" class="img-thumbnail d-block" style="max-width: 300px;">
                    @else
                        <div class="alert alert-light border">Tidak ada gambar</div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label class="font-weight-bold grey-800">Deskripsi</label>
                <textarea rows="5" class="form-control bg-light" readonly disabled>{{ $keunggulan->deskripsi }}</textarea>
            </div>

            <div class="form-group mb-0">
                <a href="{{ route('keunggulan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
