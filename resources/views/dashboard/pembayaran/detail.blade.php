@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pembayaran</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Informasi Pembayaran</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Kode Transaksi</label>
                        <input type="text" class="form-control" value="{{ $pembayaran->penyewaan->kode_transaksi ?? $pembayaran->penyewaan_id }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Client</label>
                        <input type="text" class="form-control" value="{{ Auth::user()->nama }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Jumlah Bayar</label>
                        <input type="text" class="form-control font-weight-bold text-primary" value="Rp {{ number_format($pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Metode</label>
                        <input type="text" class="form-control" value="{{ ucwords(str_replace('_', ' ', $pembayaran->metode)) ?? '-' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Jenis</label>
                        <input type="text" class="form-control" value="{{ ucfirst($pembayaran->jenis) ?? '-' }}" readonly>
                    </div>

                    <div class="form-group">
                        <div>
                            @if($pembayaran->status == 'lunas')
                                <span class="badge badge-success p-2">Lunas</span>
                            @elseif($pembayaran->status == 'menunggu_konfirmasi')
                                <span class="badge badge-info p-2">Menunggu Konfirmasi</span>
                            @elseif($pembayaran->status == 'menunggu_pelunasan')
                                <span class="badge badge-warning p-2">Menunggu Pelunasan</span>
                            @elseif($pembayaran->status == 'menunggu_konfirmasi_pelunasan')
                                <span class="badge badge-info p-2">Menunggu Konfirmasi Pelunasan</span>
                            @elseif($pembayaran->status == 'ditolak')
                                <span class="badge badge-danger p-2">Ditolak</span>
                            @else
                                <span class="badge badge-secondary p-2">{{ ucfirst($pembayaran->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Tanggal Bayar</label>
                        <input type="text" class="form-control" value="{{ optional($pembayaran->tanggal_bayar)->format('d M Y') ?? '-' }}" readonly>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Bukti Transfer</h6>
                    @if($pembayaran->bukti_transfer)
                        <div class="mt-2">
                            <img src="{{ $pembayaran->bukti_transfer }}" alt="Bukti Transfer" class="img-fluid border rounded" style="max-height: 400px;">
                            <div class="mt-2 text-center">
                                <a href="{{ $pembayaran->bukti_transfer }}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-search-plus"></i> Lihat Ukuran Penuh
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Tidak ada bukti transfer.</p>
                    @endif
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">Items dalam Pesanan</h6>
                    <div class="list-group">
                        @foreach($pembayaran->penyewaan->keranjangs as $k)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 font-weight-bold text-primary">{{ $k->armada->merek ?? 'Armada' }}</h6>
                                    <span class="text-muted">Rp {{ number_format($k->harga_sewa ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="small">
                                    <i class="fas fa-calendar-alt mr-1"></i> Mulai: {{ optional($k->tanggal_mulai)->format('d M Y') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($pembayaran->catatan)
                        <div class="alert alert-danger mt-3">
                            <h6 class="alert-heading font-weight-bold"><i class="fas fa-exclamation-circle"></i> Catatan Admin:</h6>
                            <p class="mb-0">{{ $pembayaran->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row mt-4 pt-3 border-top">
                <div class="col-12 text-center">
                    <a href="{{ route('pembayaran.riwayat') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection
