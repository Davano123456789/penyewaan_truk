@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Pembayaran</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Pembayaran</h6>
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
                        @php
                            $client = $pembayaran->penyewaan ? $pembayaran->penyewaan->client : null;
                        @endphp
                        <input type="text" class="form-control {{ !$client ? 'is-invalid text-danger font-italic' : '' }}" 
                               value="{{ $client ? ($client->nama ?? $client->email) : (!$pembayaran->penyewaan ? 'Data Transaksi Hilang' : 'User Dihapus') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Jumlah</label>
                        <input type="text" class="form-control" value="Rp {{ number_format($pembayaran->jumlah_bayar ?? 0,0,',','.') }}" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Metode</label>
                        <input type="text" class="form-control" value="{{ $pembayaran->metode ?? '-' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Jenis</label>
                        <input type="text" class="form-control" value="{{ $pembayaran->jenis == 'tunai' ? 'Tunai' : (ucfirst($pembayaran->jenis) ?? '-') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Status</label>
                        <input type="text" class="form-control" value="{{ $pembayaran->status ?? '-' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Tanggal Bayar</label>
                        <input type="text" class="form-control" value="{{ optional($pembayaran->tanggal_bayar)->format('Y-m-d') ?? '-' }}" readonly>
                    </div>

                    <div class="form-group text-center">
                        <label class="font-weight-bold d-block">Bukti Transfer</label>
                        @if($pembayaran->bukti_transfer)
                            <a href="{{ $pembayaran->bukti_transfer }}" target="_blank">Lihat Bukti Transfer</a>
                        @else
                            <p class="text-muted">Tidak ada bukti transfer</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="font-weight-bold">Items (Keranjang)</h6>
                    @if($pembayaran->penyewaan)
                        <ul class="list-group">
                            @foreach($pembayaran->penyewaan->keranjangs as $k)
                                <li class="list-group-item">
                                    <strong>{{ $k->armada->merek ?? '-' }}</strong> — Rp {{ number_format($k->harga_sewa ?? 0,0,',','.') }}
                                    <div class="small text-muted">Tanggal Mulai: {{ optional($k->tanggal_mulai)->format('Y-m-d') ?? '-' }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-warning">Data rincian keranjang tidak tersedia karena data transaksi telah dihapus.</div>
                    @endif
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ route('pembayaranAdmin.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pembayaran
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data ini tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection
