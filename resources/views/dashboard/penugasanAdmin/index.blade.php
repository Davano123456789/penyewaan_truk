@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Validasi Penugasan Selesai</h1>
    </div>



    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Penugasan Menunggu Validasi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Kode Transaksi</th>
                            <th>Sopir</th>
                            <th>Armada</th>
                            <th>Tujuan</th>
                            <th>Bukti Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penugasans as $p)
                            <tr>
                                <td>
                                    <strong>#{{ $p->penyewaan->kode_transaksi }}</strong><br>
                                    <span class="badge badge-info">{{ $p->kode_keranjang }}</span><br>
                                    <small class="text-muted">{{ $p->updated_at->format('d M Y, H:i') }}</small>
                                </td>
                                <td>{{ $p->sopir->nama }}</td>
                                <td>{{ $p->armada->merk }} ({{ $p->armada->no_polisi }})</td>
                                <td>{{ $p->tempat_antar }}</td>
                                <td class="text-center">
                                    @if($p->bukti_selesai)
                                        <a href="{{ $p->bukti_selesai }}" target="_blank">
                                            <img src="{{ $p->bukti_selesai }}" style="width: 100px; height: 100px; object-fit: cover;" class="rounded border shadow-sm">
                                        </a>
                                        <br>
                                        <small class="text-primary mt-1 d-block" style="cursor: pointer;" data-toggle="modal" data-target="#modalBukti{{ $p->id }}">
                                            <i class="fas fa-search-plus"></i> Perbesar
                                        </small>
                                    @else
                                        <span class="badge badge-secondary">Tidak ada bukti</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('penugasanAdmin.validasi', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm mb-1" onclick="return confirm('Validasi penugasan ini sebagai SELESAI?')">
                                            <i class="fas fa-check-circle"></i> Validasi
                                        </button>
                                    </form>
                                    <button class="btn btn-danger btn-sm mb-1" data-toggle="modal" data-target="#modalTolak{{ $p->id }}">
                                        <i class="fas fa-times-circle"></i> Tolak
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Perbesar Bukti -->
                            <div class="modal fade" id="modalBukti{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Bukti Selesai Penugasan #{{ $p->penyewaan->kode_transaksi }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ $p->bukti_selesai }}" class="img-fluid rounded shadow">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Tolak -->
                            <div class="modal fade" id="modalTolak{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('penugasanAdmin.tolak', $p->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">Tolak Bukti Penugasan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Alasan Penolakan:</label>
                                                    <textarea name="alasan" class="form-control" rows="3" placeholder="Contoh: Foto tidak jelas, Silakan upload ulang foto bukti di lokasi..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-clipboard-check fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 mb-0">Belum ada penugasan yang menunggu validasi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
