@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Pembayaran Saya</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pembayaran</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                         
                            <th>Jumlah Bayar</th>
                            <th>Metode</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayarans as $index => $p)
                            <tr>
                                <td class="text-center">{{ $index + $pembayarans->firstItem() }}</td>
                             
                                <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                                <td>
                                    @if($p->metode == 'transfer_bca') Bank BCA
                                    @elseif($p->metode == 'transfer_mandiri') Bank Mandiri
                                    @elseif($p->metode == 'transfer_bri') Bank BRI
                                    @elseif($p->metode == 'transfer_bni') Bank BNI
                                    @else {{ $p->metode }}
                                    @endif
                                </td>
                                <td>
                                    @if($p->jenis == 'cash')
                                        <span class="badge badge-success">Cash (Lunas)</span>
                                    @else
                                        <span class="badge badge-warning">Talangan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status == 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($p->status == 'menunggu_konfirmasi')
                                        <span class="badge badge-info">Menunggu Konfirmasi</span>
                                    @elseif($p->status == 'menunggu_pelunasan')
                                        <span class="badge badge-warning">Menunggu Pelunasan</span>
                                    @elseif($p->status == 'menunggu_konfirmasi_pelunasan')
                                        <span class="badge badge-info">Menunggu Konfirmasi Pelunasan</span>
                                    @elseif($p->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $p->status }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('pembayaran.detail', $p->id) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada riwayat pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info">
                        Menampilkan {{ $pembayarans->firstItem() ?? 0 }} sampai {{ $pembayarans->lastItem() ?? 0 }} dari {{ $pembayarans->total() ?? 0 }} data
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate float-right">
                        {{ $pembayarans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection
