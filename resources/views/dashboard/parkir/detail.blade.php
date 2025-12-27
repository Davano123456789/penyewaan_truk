@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Lokasi Parkir</h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Lokasi Parkir</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Kolom Utama -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-bold">ID Parkir</label>
                        <input type="text" class="form-control" value="{{ $parkir->id }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Lokasi Parkir</label>
                        <input type="text" class="form-control" value="{{ $parkir->nama }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Alamat</label>
                        <textarea class="form-control" rows="4" readonly>{{ $parkir->alamat }}</textarea>
                    </div>
                </div>
            </div>

        

      

            <!-- Tombol Aksi -->
            <div class="row mt-4">
                <div class="col-12 ">
                    <a href="{{ route('parkir.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Parkir
                    </a>
                   
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection