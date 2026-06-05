@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Armada</h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Armada</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">No Polisi</label>
                        <input type="text" class="form-control" value="{{ $armada->no_polisi }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Sopir</label>
                        <input type="text" class="form-control" value="{{ $armada->sopir->nama ?? 'Belum Ada' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Lokasi Parkir</label>
                        <input type="text" class="form-control" value="{{ $armada->parkir->nama ?? '-' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Merek</label>
                        <input type="text" class="form-control" value="{{ $armada->merek }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Jenis</label>
                        <input type="text" class="form-control" value="{{ $armada->jenis }}" readonly>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Kapasitas (Ton)</label>
                        <input type="text" class="form-control" value="{{ $armada->kapasitas }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Status</label>
                        <input type="text" class="form-control text-capitalize" value="{{ $armada->status }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Deskripsi</label>
                        <textarea class="form-control" rows="3" readonly>{{ $armada->deskripsi }}</textarea>
                    </div>

                    <div class="form-group text-center">
                        <label class="font-weight-bold d-block">Gambar Armada</label>
                        @if($armada->gambar)
                            <img src="{{ $armada->gambar }}" alt="Gambar Armada" 
                                 class="img-thumbnail" style="max-width: 300px;">
                        @else
                            <p class="text-muted">Tidak ada gambar</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tombol Kembali -->
            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{ route('armada.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection
