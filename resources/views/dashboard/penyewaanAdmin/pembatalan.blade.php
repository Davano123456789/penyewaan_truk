@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Permintaan Pembatalan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan Pembatalan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Client</th>
                            <th>Armada</th>

                            <th>Harga Sewa</th>
                            <th>Estimasi Refund</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keranjangs as $index => $item)
                        <tr>
                            <td>{{ $keranjangs->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $item->penyewaan->client->nama ?? '-' }}</strong><br>
                                <small>{{ $item->penyewaan->client->email ?? '-' }}</small>
                            </td>
                            <td>
                                {{ $item->armada->no_polisi ?? '-' }}<br>
                                <small>{{ $item->armada->merek ?? '-' }}</small>
                            </td>

                            <td>Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $denda = $item->harga_sewa * 0.3;
                                    $refund = 0;
                                    if($item->penyewaan->pembayaran) {
                                        $bayar = 0;
                                        if($item->penyewaan->pembayaran->jenis == 'cash' && $item->penyewaan->pembayaran->status == 'lunas') {
                                            $bayar = $item->harga_sewa;
                                        } elseif($item->penyewaan->pembayaran->jenis == 'talangan') {
                                            $bayar = $item->harga_sewa / 2;
                                        }
                                        $refund = max(0, $bayar - $denda);
                                    }
                                @endphp
                                <strong class="text-success">Rp {{ number_format($refund, 0, ',', '.') }}</strong>
                                <br>
                                <small class="text-danger">Potongan 30%: Rp {{ number_format($denda, 0, ',', '.') }}</small>
                            </td>
                            <td>
                                @if($item->status == 'menunggu_konfirmasi_batal')
                                    <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                @elseif($item->status == 'dibatalkan')
                                    <span class="badge badge-success">Disetujui</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalDetail{{ $item->id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                                
                                <!-- Modal Detail & Action -->
                                <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('penyewaanAdmin.prosesPembatalan', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="nominal_refund" value="{{ $refund }}">
                                                
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalDetailLabel{{ $item->id }}">
                                                        Detail Pembatalan 
                                                        @if($item->status == 'dibatalkan')
                                                            <span class="badge badge-success">Disetujui</span>
                                                        @endif
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Alasan Pembatalan (User)</label>
                                                        <textarea class="form-control" rows="3" readonly>{{ $item->alasan_batal }}</textarea>
                                                    </div>

                                                    <hr>
                                                    
                                                    <div class="alert alert-info">
                                                        <strong>Rincian Refund:</strong><br>
                                                        Harga Sewa: Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}<br>
                                                        Potongan (30%): Rp {{ number_format($denda, 0, ',', '.') }}<br>
                                                        <strong>Total Refund: Rp {{ number_format($refund, 0, ',', '.') }}</strong>
                                                    </div>
                                                    
                                                    @if($item->status == 'menunggu_konfirmasi_batal')
                                                        @if($refund > 0)
                                                        <div class="form-group">
                                                            <label>Upload Bukti Transfer Refund</label>
                                                            <input type="file" name="bukti_refund" class="form-control" accept="image/*">
                                                            <small class="text-muted">Wajib upload bukti transfer jika menyetujui pembatalan.</small>
                                                        </div>
                                                        @else
                                                        <p class="text-muted">Tidak ada dana yang perlu dikembalikan.</p>
                                                        @endif
                                                    @elseif($item->status == 'dibatalkan' && $item->bukti_refund)
                                                        <div class="form-group">
                                                            <label>Bukti Refund Terupload</label><br>
                                                            <a href="{{ $item->bukti_refund }}" target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-image"></i> Lihat Bukti
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    @if($item->status == 'menunggu_konfirmasi_batal')
                                                        <button type="submit" name="action" value="reject" class="btn btn-danger" formnovalidate>
                                                            <i class="fas fa-times"></i> Tolak
                                                        </button>
                                                        <button type="submit" name="action" value="approve" class="btn btn-success">
                                                            <i class="fas fa-check"></i> Setuju
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada permintaan pembatalan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $keranjangs->links() }}
        </div>
    </div>
</div>
@endsection
