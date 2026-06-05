@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Client</h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Client</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                   

                    <div class="form-group">
                        <label class="font-weight-bold">Nama</label>
                        <input type="text" class="form-control" value="{{ $client->nama }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Email</label>
                        <input type="text" class="form-control" value="{{ $client->email }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Alamat</label>
                        <textarea class="form-control" rows="3" readonly>{{ $client->alamat ?? '-' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Telepon</label>
                        <input type="text" class="form-control" value="{{ $client->telepon ?? '-' }}" readonly>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6 text-center">
                    <div class="form-group">
                        <label class="font-weight-bold d-block">Foto Client</label>
                        @if ($client->gambar)
                            <img src="{{ $client->gambar }}"
                                 alt="Foto Client"
                                 class="rounded-circle shadow mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center shadow mb-3" 
                                 style="width: 150px; height: 150px;">
                                <i class="fas fa-user text-white" style="font-size: 60px;"></i>
                            </div>
                            <p class="text-muted font-italic">Belum ada foto client</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Umur</label>
                        <input type="text" class="form-control text-center" value="{{ $client->umur ?? '-' }}" readonly>
                    </div>

                    

                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{ route('client.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                  
                </div>
            </div>
        </div>
    </div>

</div>
@endsection