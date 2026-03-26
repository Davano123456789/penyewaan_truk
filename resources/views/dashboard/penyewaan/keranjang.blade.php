@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Daftar Keranjang - Pesanan #{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}
        </h1>
        <a href="{{ route('penyewaan.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Info Penyewaan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Pesanan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="mb-1 text-muted">ID Pesanan</p>
                    <h5><strong>#{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</strong></h5>
                </div>
                <div class="col-md-3">
                    <p class="mb-1 text-muted">Status</p>
                    <h5>
                        @if($penyewaan->status == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($penyewaan->status == 'menunggu_pembayaran')
                            <span class="badge badge-info">Menunggu Pembayaran</span>
                        @elseif($penyewaan->status == 'menunggu_konfirmasi_pembayaran')
                            <span class="badge badge-primary">Menunggu Konfirmasi Pembayaran</span>
                        @elseif($penyewaan->status == 'selesai')
                            <span class="badge badge-primary">Selesai</span>
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
                    <h5 class="text-success"><strong>Rp {{ number_format($penyewaan->harga_total_aktif, 0, ',', '.') }}</strong></h5>
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
                <p class="mb-0"><strong>Alasan:</strong> {{ $penyewaan->pembayaran->catatan ?? 'Tidak ada alasan spesifik.' }}</p>
                <hr class="my-2">
                <a href="{{ route('pembayaran.show', $penyewaan->id) }}" class="btn btn-danger btn-sm">
                    <i class="fas fa-sync-alt"></i> Upload Ulang Bukti Transfer
                </a>
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
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body py-4 px-3">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <div class="mb-2">
                                <span class="badge badge-primary mr-2">Item #{{ $index + 1 }}</span>
                                @if($item->status == 'pending')
                                    <span class="badge badge-secondary mr-2"><i class="fas fa-clock"></i> Pending</span>
                                @elseif(in_array($item->status, ['menunggu_pembayaran', 'menunggu_konfirmasi_pembayaran']))
                                    <span class="badge badge-secondary mr-2"><i class="fas fa-hourglass-half"></i> Persiapan</span>
                                @elseif(in_array($item->status, ['aktif', 'revisi_bukti', 'menunggu_konfirmasi_selesai']))
                                    <span class="badge badge-info mr-2"><i class="fas fa-truck-moving"></i> Sedang Berjalan</span>
                                @elseif($item->status == 'selesai')
                                    <span class="badge badge-success mr-2"><i class="fas fa-check-circle"></i> Selesai</span>
                                @elseif($item->status == 'menunggu_konfirmasi_batal')
                                    <span class="badge badge-warning mr-2"><i class="fas fa-times-circle"></i> Pengajuan Batal</span>
                                @elseif($item->status == 'dibatalkan')
                                    <span class="badge badge-danger mr-2"><i class="fas fa-ban"></i> Dibatalkan</span>
                                @endif
                                <span class="font-weight-bold text-dark">{{ $item->armada->no_polisi ?? '-' }}</span>
                            </div>
                            <div class="mb-1">
                                <i class="fas fa-map-marker-alt text-success"></i>
                                <strong>Jemput:</strong> {{ $item->tempat_jemput }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-flag-checkered text-info"></i>
                                <strong>Antar:</strong> {{ $item->tempat_antar }}
                            </div>
                            <div class="mb-1">
                                <i class="fas fa-calendar text-warning"></i>
                                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-road text-secondary"></i>
                                <strong>Jarak:</strong> {{ $item->total_jarak }} km
                                <span class="mx-2">|</span>
                                <strong>Estimasi:</strong> {{ $item->estimasi_hari }} hari
                            </div>
                            <div class="mb-1">
                                <i class="fas fa-box text-info"></i>
                                <strong>Muatan:</strong> {{ $item->barang_muatan }}
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <h4 class="text-success mb-2">
                                <strong>Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</strong>
                            </h4>
                            <div class="mb-2">
                                @if(in_array($penyewaan->status, ['pending', 'menunggu_pembayaran']))
                                <a href="{{ route('keranjang.edit', $item->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Ubah
                                </a>
                                <form action="{{ route('keranjang.destroy', $item->id) }}" method="POST" class="delete-form d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                                @elseif($item->status == 'aktif')
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalBatal{{ $item->id }}">
                                    <i class="fas fa-times-circle"></i> Batalkan
                                </button>
                                <!-- Modal Batal -->
                                <div class="modal fade" id="modalBatal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalBatalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('keranjang.ajukan-batal', $item->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalBatalLabel{{ $item->id }}">Ajukan Pembatalan</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-left">
                                                    <div class="form-group">
                                                        <label for="alasan_batal">Alasan Pembatalan</label>
                                                        <textarea class="form-control" name="alasan_batal" rows="3" required placeholder="Jelaskan alasan pembatalan..."></textarea>
                                                    </div>
                                                    <p class="text-danger small">* Pembatalan memerlukan persetujuan admin. Dana akan dikembalikan sesuai kebijakan.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-danger">Ajukan Pembatalan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @elseif($item->status == 'menunggu_konfirmasi_batal')
                                <span class="badge badge-warning">Menunggu Konfirmasi Batal</span>
                            @elseif($item->status == 'dibatalkan')
                                <span class="badge badge-danger mb-2">Dibatalkan</span>
                                @if($item->nominal_refund > 0)
                                    <br>
                                    <small class="text-success font-weight-bold">Refund: Rp {{ number_format($item->nominal_refund, 0, ',', '.') }}</small>
                                    @if($item->bukti_refund)
                                        <br>
                                        <a href="{{ $item->bukti_refund }}" target="_blank" class="btn btn-sm btn-info mt-1">
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
        button.addEventListener('click', function(e) {
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
</script>

@endsection