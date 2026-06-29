@extends('layouts.masterDashboard')

@section('content_dashboard')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                Daftar Keranjang
            </h1>
            <div>
                <a href="{{ route('penyewaan.invoice', $penyewaan->id) }}" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-print"></i> Cetak Invoice
                </a>
                <a href="{{ route('penyewaan.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Info Penyewaan -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Pesanan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p class="mb-1 text-muted">Kode Transaksi</p>
                        <h6><strong>{{ $penyewaan->kode_transaksi }}</strong></h6>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-1 text-muted">Status</p>
                        <h5>
                            @if($penyewaan->status == 'menunggu_pembayaran')
                                <span class="badge badge-info">Menunggu Pembayaran</span>
                            @elseif($penyewaan->status == 'menunggu_konfirmasi_pembayaran')
                                <span class="badge badge-primary">Menunggu Konfirmasi Pembayaran</span>
                            @elseif($penyewaan->status == 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @elseif($penyewaan->status == 'selesai')
                                <span class="badge badge-dark">Selesai</span>
                            @elseif($penyewaan->status == 'dibatalkan')
                                <span class="badge badge-danger">Dibatalkan</span>
                            @endif
                        </h5>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-1 text-muted">Jumlah Item</p>
                        <h5><strong>{{ $penyewaan->keranjangs->count() }} Item</strong></h5>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-1 text-muted">Total Harga</p>
                        <h5 class="text-success"><strong>Rp
                                {{ number_format($penyewaan->harga_total_aktif, 0, ',', '.') }}</strong></h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Alert untuk Pembayaran Ditolak -->
        @if($penyewaan->pembayaran && $penyewaan->pembayaran->status == 'ditolak')
            <div class="alert alert-danger mb-4 shadow-sm border-left-danger">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <div class="col">
                        <h5 class="font-weight-bold mb-1">Pembayaran Terakhir Ditolak</h5>
                        <p class="mb-0"><strong>Alasan:</strong>
                            {{ $penyewaan->pembayaran->catatan ?? 'Tidak ada alasan spesifik.' }}</p>
                        <hr class="my-2">
                        <a href="{{ route('pembayaran.show', $penyewaan->id) }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-sync-alt"></i> Upload Ulang Bukti Transfer
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Informasi Pembayaran Talangan -->
        @if($penyewaan->pembayaran && $penyewaan->pembayaran->jenis === 'talangan')
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-money-bill-wave"></i> Informasi Pembayaran Talangan (DP)</h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="mb-2">Anda memilih metode pembayaran <strong>Talangan / DP (50%)</strong>.</p>
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <span class="text-muted small">Total Biaya</span>
                                    <h5 class="font-weight-bold text-dark">Rp {{ number_format($penyewaan->harga_total_aktif, 0, ',', '.') }}</h5>
                                </div>
                                <div class="col-sm-4 border-right">
                                    <span class="text-muted small">Sudah Dibayar</span>
                                    <h5 class="font-weight-bold text-success">Rp {{ number_format($penyewaan->pembayaran->jumlah_bayar, 0, ',', '.') }}</h5>
                                </div>
                                <div class="col-sm-4">
                                    @php
                                        $sisaTagihan = max(0, $penyewaan->harga_total_aktif - $penyewaan->pembayaran->jumlah_bayar);
                                    @endphp
                                    <span class="text-muted small">Sisa Tagihan</span>
                                    <h5 class="font-weight-bold text-danger">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</h5>
                                </div>
                            </div>
                            <div class="mt-3">
                                <span class="badge badge-secondary px-3 py-2 text-capitalize">
                                    Status Pembayaran: <strong>{{ str_replace('_', ' ', $penyewaan->pembayaran->status) }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            @if(in_array($penyewaan->pembayaran->status, ['menunggu_pelunasan', 'ditolak']))
                                <a href="{{ route('pembayaran.show', $penyewaan->id) }}" class="btn btn-warning btn-block">
                                    <i class="fas fa-upload"></i> {{ $penyewaan->pembayaran->status === 'ditolak' ? 'Bayar Ulang Sisa Tagihan' : 'Bayar Sisa Tagihan (Pelunasan)' }}
                                </a>
                            @elseif($penyewaan->pembayaran->status === 'menunggu_konfirmasi_pelunasan')
                                <div class="alert alert-info text-center mb-0">
                                    <i class="fas fa-hourglass-half"></i> Pelunasan Sedang Diverifikasi
                                </div>
                            @elseif($penyewaan->pembayaran->status === 'lunas')
                                <div class="alert alert-success text-center mb-0">
                                    <i class="fas fa-check-circle"></i> Lunas Sepenuhnya
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Daftar Item -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Item Keranjang</h6>
            </div>
            <div class="card-body">
                @forelse($penyewaan->keranjangs as $index => $item)
                    @php
                        $borderClass = 'border-left-secondary';
                        if ($item->status == 'pending' || in_array($item->status, ['menunggu_pembayaran', 'menunggu_konfirmasi_pembayaran'])) {
                            $borderClass = 'border-left-warning';
                        } elseif (in_array($item->status, ['aktif', 'truk_sampai', 'revisi_bukti', 'menunggu_konfirmasi_selesai'])) {
                            $borderClass = 'border-left-info';
                        } elseif ($item->status == 'selesai') {
                            $borderClass = 'border-left-success';
                        } elseif ($item->status == 'dibatalkan' || $item->status == 'menunggu_konfirmasi_batal') {
                            $borderClass = 'border-left-danger';
                        }
                    @endphp
                    <div class="card mb-4 shadow-sm {{ $borderClass }}">
                        <div class="card-body py-4 px-4">
                            <div class="row align-items-center">
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <span class="badge badge-light border text-secondary mr-2">{{ $item->kode_keranjang }}</span>
                                        @if($item->status == 'pending')
                                            <span class="badge badge-secondary mr-2"><i class="fas fa-clock"></i> Pending</span>
                                        @elseif(in_array($item->status, ['menunggu_pembayaran', 'menunggu_konfirmasi_pembayaran']))
                                            <span class="badge badge-secondary mr-2"><i class="fas fa-hourglass-half"></i> Persiapan</span>
                                        @elseif($item->status == 'aktif')
                                            <span class="badge badge-info mr-2"><i class="fas fa-truck-moving"></i> Sedang Berjalan</span>
                                        @elseif($item->status == 'truk_sampai')
                                            <span class="badge badge-primary mr-2"><i class="fas fa-map-marker-alt"></i> Truk Sampai (Menunggu Bukti Sopir)</span>
                                        @elseif($item->status == 'revisi_bukti')
                                            <span class="badge badge-danger mr-2"><i class="fas fa-exclamation-circle"></i> Revisi Bukti</span>
                                        @elseif($item->status == 'menunggu_konfirmasi_selesai')
                                            <span class="badge badge-warning mr-2"><i class="fas fa-hourglass-half"></i> Menunggu Validasi</span>
                                        @elseif($item->status == 'selesai')
                                            <span class="badge badge-success mr-2"><i class="fas fa-check-circle"></i> Selesai</span>
                                        @elseif($item->status == 'menunggu_konfirmasi_batal')
                                            <span class="badge badge-warning mr-2"><i class="fas fa-times-circle"></i> Pengajuan Batal</span>
                                        @elseif($item->status == 'dibatalkan')
                                            <span class="badge badge-danger mr-2"><i class="fas fa-ban"></i> Dibatalkan</span>
                                        @endif
                                        <span class="font-weight-bold text-dark ml-1">
                                            @if($item->armada)
                                                {{ $item->armada->merek }} {{ $item->armada->jenis }} ({{ $item->armada->no_polisi }})
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <!-- Rute Penjemputan & Pengantaran Grid -->
                                    <div class="row mb-3">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="p-3 bg-light rounded" style="border-left: 4px solid #28a745; min-height: 90px;">
                                                <span class="text-success font-weight-bold small uppercase d-block mb-1">
                                                    <i class="fas fa-map-marker-alt"></i> Alamat Penjemputan
                                                </span>
                                                <p class="mb-0 text-dark small font-weight-bold" style="line-height: 1.5;">
                                                    {{ $item->rute->tempat_jemput }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded" style="border-left: 4px solid #17a2b8; min-height: 90px;">
                                                <span class="text-info font-weight-bold small uppercase d-block mb-1">
                                                    <i class="fas fa-flag-checkered"></i> Alamat Pengantaran
                                                </span>
                                                <p class="mb-0 text-dark small font-weight-bold" style="line-height: 1.5;">
                                                    {{ $item->rute->tempat_antar }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informasi Detail Item -->
                                    <div class="row pt-3 border-top">
                                        <div class="col-md-4 mb-2 mb-md-0">
                                            <span class="text-muted small d-block"><i class="fas fa-calendar text-warning mr-1"></i> Tanggal Mulai</span>
                                            <span class="text-dark font-weight-bold" style="font-size: 0.9rem;">
                                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                            </span>
                                        </div>
                                        <div class="col-md-4 mb-2 mb-md-0">
                                            <span class="text-muted small d-block"><i class="fas fa-road text-secondary mr-1"></i> Jarak & Estimasi</span>
                                            <span class="text-dark font-weight-bold" style="font-size: 0.9rem;">
                                                {{ $item->rute->total_jarak }} km <span class="text-muted font-weight-normal">/</span> {{ $item->estimasi_hari }} Hari
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="text-muted small d-block"><i class="fas fa-box text-info mr-1"></i> Muatan & Bobot</span>
                                            <span class="text-dark font-weight-bold" style="font-size: 0.9rem;">
                                                {{ $item->barang_muatan }} @if($item->bobot) <span class="text-muted font-weight-normal">({{ $item->bobot }} Ton)</span> @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-right">
                                    <h4 class="text-success mb-2">
                                        <strong>Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</strong>
                                    </h4>
                                    <div class="mb-2">
                                        @if($penyewaan->status == 'menunggu_pembayaran')
                                            <a href="{{ route('keranjang.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Ubah
                                            </a>
                                            <form action="{{ route('keranjang.destroy', $item->id) }}" method="POST"
                                                class="delete-form d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @elseif($item->status == 'aktif')
                                            <button type="button" class="btn btn-warning btn-sm mr-1" data-toggle="modal"
                                                data-target="#modalBatal{{ $item->id }}">
                                                <i class="fas fa-times-circle"></i> Batalkan
                                            </button>
                                            <form action="{{ route('keranjang.konfirmasi-sampai', $item->id) }}" method="POST"
                                                class="confirm-arrived-form d-inline">
                                                @csrf
                                                <button type="button" class="btn btn-success btn-sm btn-confirm-arrived">
                                                    <i class="fas fa-check"></i> Truk Sudah Sampai
                                                </button>
                                            </form>
                                            <!-- Modal Batal -->
                                            <div class="modal fade" id="modalBatal{{ $item->id }}" tabindex="-1" role="dialog"
                                                aria-labelledby="modalBatalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="{{ route('keranjang.ajukan-batal', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalBatalLabel{{ $item->id }}">Ajukan
                                                                    Pembatalan</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-left">
                                                                <div class="form-group">
                                                                    <label for="alasan_batal">Alasan Pembatalan</label>
                                                                    <textarea class="form-control" name="alasan_batal" rows="3"
                                                                        required
                                                                        placeholder="Jelaskan alasan pembatalan..."></textarea>
                                                                </div>
                                                                <p class="text-danger small">* Pembatalan memerlukan persetujuan
                                                                    admin. Dana akan dikembalikan sesuai kebijakan.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-danger">Ajukan
                                                                    Pembatalan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($item->status == 'menunggu_konfirmasi_batal')
                                            <span class="badge badge-warning">Menunggu Konfirmasi Batal</span>
                                        @elseif($item->status == 'dibatalkan')
                                            <span class="badge badge-danger mb-2">Dibatalkan</span>
                                            @php
                                                $nominalRefund = $item->pembatalan?->nominal_refund ?? 0;
                                                $buktiRefund = $item->pembatalan?->bukti_refund ?? null;
                                            @endphp
                                            @if($nominalRefund > 0)
                                                <br>
                                                <small class="text-success font-weight-bold">Refund: Rp
                                                    {{ number_format($nominalRefund, 0, ',', '.') }}</small>
                                                @if($buktiRefund)
                                                    <br>
                                                    <a href="{{ $buktiRefund }}" target="_blank" class="btn btn-sm btn-info mt-1">
                                                        <i class="fas fa-receipt"></i> Bukti Refund
                                                    </a>
                                                @endif
                                            @endif
                                        @else
                                            <span class="badge badge-secondary">Tidak dapat dihapus</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                @empty
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Belum ada item dalam keranjang
                        </div>
                    @endforelse

                    @if($penyewaan->keranjangs->count() > 0)
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9 text-right">
                                        <h4 class="mb-0">TOTAL PEMBAYARAN:</h4>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <h3 class="text-success mb-0">
                                            <strong>Rp {{ number_format($penyewaan->harga_total_aktif, 0, ',', '.') }}</strong>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
@endsection

    @section('scripts')
        <script>
            // SweetAlert Konfirmasi Hapus Item
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');

                    Swal.fire({
                        title: 'Yakin ingin menghapus item ini?',
                        text: "Total harga akan diperbarui otomatis!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // SweetAlert Konfirmasi Truk Sampai
            document.querySelectorAll('.btn-confirm-arrived').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = this.closest('.confirm-arrived-form');

                    Swal.fire({
                        title: 'Konfirmasi Kedatangan Truk',
                        text: "Apakah armada truk penjemput/pengantar memang sudah sampai di lokasi tujuan?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Truk Sudah Sampai!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>

    @endsection
