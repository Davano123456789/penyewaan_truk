@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Sopir</h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Sopir</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama</label>
                        <input type="text" class="form-control" value="{{ $sopir->nama }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Email</label>
                        <input type="text" class="form-control" value="{{ $sopir->email }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Alamat</label>
                        <textarea class="form-control" rows="3" readonly>{{ $sopir->alamat ?? '-' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Telepon</label>
                        <input type="text" class="form-control" value="{{ $sopir->telepon ?? '-' }}" readonly>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6 text-center">
                    <div class="form-group">
                        <label class="font-weight-bold d-block">Foto Sopir</label>
                        @if ($sopir->gambar)
                            <img src="{{ $sopir->gambar }}"
                                 alt="Foto Sopir"
                                 class="rounded-circle shadow mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center shadow mb-3" 
                                 style="width: 150px; height: 150px;">
                                <i class="fas fa-user text-white" style="font-size: 60px;"></i>
                            </div>
                            <p class="text-muted font-italic">Belum ada foto sopir</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Umur</label>
                        <input type="text" class="form-control text-center" value="{{ $sopir->umur ?? '-' }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{ route('sopir.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Sopir
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection