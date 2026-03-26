@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Penugasan</h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Informasi Penugasan #{{ $penugasan->id }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <h5 class="font-weight-bold text-primary mb-3">
                        <i class="fas fa-truck"></i> Informasi Armada
                    </h5>

                    <div class="form-group">
                        <label class="font-weight-bold">No Polisi</label>
                        <input type="text" class="form-control" value="{{ $penugasan->armada->no_polisi }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Merek & Jenis</label>
                        <input type="text" class="form-control" value="{{ $penugasan->armada->merek }} - {{ $penugasan->armada->jenis }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Kapasitas</label>
                        <input type="text" class="form-control" value="{{ $penugasan->armada->kapasitas }} Ton" readonly>
                    </div>

                    <h5 class="font-weight-bold text-success mb-3 mt-4">
                        <i class="fas fa-calendar-alt"></i> Informasi Waktu
                    </h5>

                    <div class="form-group">
                        <label class="font-weight-bold">Tanggal Mulai</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($penugasan->tanggal_mulai)->format('d F Y') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Estimasi Hari</label>
                        <input type="text" class="form-control" value="{{ $penugasan->estimasi_hari }} Hari" readonly>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <h5 class="font-weight-bold text-info mb-3">
                        <i class="fas fa-map-marker-alt"></i> Informasi Rute
                    </h5>

                    <div class="form-group">
                        <label class="font-weight-bold">Tempat Jemput</label>
                        <textarea class="form-control" rows="2" readonly>{{ $penugasan->tempat_jemput }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Tempat Antar</label>
                        <textarea class="form-control" rows="2" readonly>{{ $penugasan->tempat_antar }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Total Jarak</label>
                        <input type="text" class="form-control" value="{{ $penugasan->total_jarak }} km" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Barang Muatan</label>
                        <textarea class="form-control" rows="2" readonly>{{ $penugasan->barang_muatan }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Status</label>
                        <div>
                            @if($penugasan->status == 'pending')
                                <span class="badge badge-secondary p-2">Pending (Menunggu Pembayaran)</span>
                            @elseif($penugasan->status == 'aktif')
                                <span class="badge badge-success p-2">Aktif / Berjalan</span>
                            @elseif($penugasan->status == 'revisi_bukti')
                                <span class="badge badge-danger p-2">Revisi Bukti Selesai</span>
                            @elseif($penugasan->status == 'selesai')
                                <span class="badge badge-primary p-2">Selesai</span>
                            @elseif($penugasan->status == 'menunggu_konfirmasi_selesai')
                                <span class="badge badge-warning p-2">Menunggu Validasi Admin</span>
                            @else
                                <span class="badge badge-info p-2">{{ ucfirst($penugasan->status) }}</span>
                            @endif
                        </div>
                    </div>

                    @if($penugasan->catatan_penugasan)
                    <div class="alert alert-warning border-left-warning shadow-sm">
                        <h6 class="font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Catatan dari Admin:</h6>
                        <p class="mb-0 italic">"{{ $penugasan->catatan_penugasan }}"</p>
                        <hr class="my-2">
                        <small class="text-dark">Silakan periksa kembali bukti yang Anda unggah dan lakukan upload ulang jika diperlukan.</small>
                    </div>
                    @endif

                    <!-- Tambahan Bukti Selesai -->
                    <div class="form-group">
                        <label class="font-weight-bold">Bukti Selesai</label>
                        <div>
                            @if($penugasan->bukti_selesai)
                                <img src="{{ $penugasan->bukti_selesai }}" 
                                     alt="Bukti Selesai" 
                                     class="img-thumbnail" 
                                     style="max-width: 300px;">
                            @else
                                <div class="bg-light border rounded p-4 text-center">
                                    <i class="fas fa-image fa-3x text-gray-300 mb-2"></i>
                                    <p class="text-muted small mb-0">Belum ada bukti selesai yang diunggah.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{ route('penugasan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
