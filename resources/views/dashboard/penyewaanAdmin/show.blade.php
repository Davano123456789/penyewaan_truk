@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Penyewaan #{{ $penyewaan->id }}</h1>
        <a href="{{ route('penyewaanAdmin.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    

    <!-- Daftar Item Penyewaan -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-list"></i> Daftar Item Penyewaan ({{ $penyewaan->keranjangs->count() }} Item)
            </h6>
        </div>
        <div class="card-body">
            @if($penyewaan->keranjangs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="18%">Armada</th>
                            <th width="15%">Tanggal Mulai</th>
                            <th width="12%">Estimasi Hari</th>
                            <th width="20%">Tempat Jemput</th>
                            <th width="20%">Tempat Antar</th>
                            <th width="10%">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penyewaan->keranjangs as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->armada->jenis ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $item->armada->no_polisi ?? 'N/A' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                            <td class="text-center"><strong>{{ $item->estimasi_hari }} hari</strong></td>
                            <td>{{ $item->tempat_jemput }}</td>
                            <td>{{ $item->tempat_antar }}</td>
                            <td class="text-right"><strong class="text-success">Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="font-weight-bold">
                        <tr class="bg-light">
                            <td colspan="6" class="text-right">TOTAL:</td>
                            <td class="text-right"><h5 class="mb-0 text-primary">Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</h5></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle"></i> Belum ada item dalam penyewaan ini
            </div>
            @endif
        </div>
    </div>

    <!-- Bukti Pembayaran -->
    @if($penyewaan->pembayaran)
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-receipt"></i> Bukti Pembayaran
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <td width="40%"><strong>Metode Pembayaran</strong></td>
                            <td>
                                @if($penyewaan->pembayaran->metode == 'transfer_bca')
                                    <span class="badge badge-primary">Transfer BCA</span>
                                @elseif($penyewaan->pembayaran->metode == 'transfer_mandiri')
                                    <span class="badge badge-warning">Transfer Mandiri</span>
                                @elseif($penyewaan->pembayaran->metode == 'transfer_bri')
                                    <span class="badge badge-info">Transfer BRI</span>
                                @elseif($penyewaan->pembayaran->metode == 'transfer_bni')
                                    <span class="badge badge-success">Transfer BNI</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Bayar</strong></td>
                            <td><strong class="text-success h5">Rp {{ number_format($penyewaan->pembayaran->jumlah_bayar, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Transfer</strong></td>
                            <td>{{ \Carbon\Carbon::parse($penyewaan->pembayaran->tanggal_bayar)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Upload</strong></td>
                            <td>{{ $penyewaan->pembayaran->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jenis Pembayaran</strong></td>
                            <td>
                                @if($penyewaan->pembayaran->jenis == 'cash')
                                    <span class="badge badge-success">Cash (100% - Lunas)</span>
                                @elseif($penyewaan->pembayaran->jenis == 'talangan')
                                    <span class="badge badge-warning">Talangan (50% - Belum Lunas)</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status Pembayaran</strong></td>
                            <td>
                                @if($penyewaan->pembayaran->status == 'lunas')
                                    <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                @elseif($penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                    <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Pelunasan</span>
                                @elseif($penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan')
                                    <span class="badge badge-danger"><i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi Pelunasan</span>
                                @endif
                            </td>
                        </tr>
                    </table>

               
                </div>
                <div class="col-md-6">
                    <p class="font-weight-bold mb-3 text-center">Bukti Transfer:</p>
                    <div class="text-center">
                        <a href="{{ asset($penyewaan->pembayaran->bukti_transfer) }}" target="_blank" data-toggle="modal" data-target="#imageModal">
                            <img src="{{ asset($penyewaan->pembayaran->bukti_transfer) }}" 
                                 alt="Bukti Transfer" 
                                 class="img-thumbnail shadow-sm"
                                 style="max-height: 350px; cursor: pointer; border: 3px solid #ddd;">
                        </a>
                        <br>
                        <small class="text-muted">
                            <i class="fas fa-search-plus"></i> Klik gambar untuk memperbesar
                        </small>
                    </div>
                </div>
            </div>

            @if($penyewaan->status == 'menunggu_konfirmasi')
            <hr>
            <div class="text-center">
                <form action="{{ route('penyewaanAdmin.konfirmasi', $penyewaan->id) }}" 
                      method="POST" 
                      class="d-inline konfirmasi-form"
                      data-tipe="pembayaran_pertama">
                    @csrf
                    <button type="button" class="btn btn-success btn-lg px-5 btn-konfirmasi">
                        <i class="fas fa-check-circle"></i> Terima & Aktifkan Penyewaan
                    </button>
                </form>
                @if($penyewaan->status != 'selesai')
                <form action="{{ route('penyewaanAdmin.tolak', $penyewaan->id) }}" method="POST" class="d-inline simple-tolak-form">
                    @csrf
                    <button type="button" class="btn btn-danger btn-lg px-5 btn-tolak-simple">
                        <i class="fas fa-times-circle"></i> Tolak
                    </button>
                </form>
                @endif
            </div>
            @elseif($penyewaan->status == 'aktif' && $penyewaan->pembayaran && $penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan')
            <!-- KONFIRMASI PELUNASAN -->
            <hr>
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-circle"></i> Menunggu Konfirmasi Pelunasan</h5>
                <p class="mb-0">Customer telah mengupload pembayaran sisa/pelunasan. Silakan periksa bukti transfer kemudian konfirmasi untuk menyelesaikan pembayaran.</p>
            </div>
            <div class="text-center">
                <form action="{{ route('penyewaanAdmin.konfirmasi', $penyewaan->id) }}" 
                      method="POST" 
                      class="d-inline konfirmasi-form"
                      data-tipe="pelunasan">
                    @csrf
                    <button type="button" class="btn btn-success btn-lg px-5 btn-konfirmasi">
                        <i class="fas fa-check-circle"></i> Konfirmasi Pelunasan
                    </button>
                </form>

                @if($penyewaan->status != 'selesai')
                <form action="{{ route('penyewaanAdmin.tolak', $penyewaan->id) }}" method="POST" class="d-inline simple-tolak-form">
                    @csrf
                    <button type="button" class="btn btn-danger btn-lg px-5 btn-tolak-simple">
                        <i class="fas fa-times-circle"></i> Tolak Pelunasan
                    </button>
                </form>
                @endif
            </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @else
    <!-- Jika Belum Ada Pembayaran -->
    <div class="card shadow mb-4">
        <div class="card-body text-center py-5">
            <i class="fas fa-clock fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Belum Ada Bukti Pembayaran</h5>
            <p class="text-muted mb-0">Customer belum mengupload bukti transfer</p>
        </div>
    </div>
    @endif

 

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert Konfirmasi Pembayaran atau Pelunasan
    document.querySelectorAll('.btn-konfirmasi').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.konfirmasi-form');
            const tipe = form.dataset.tipe;

            let title, html, confirmButtonText;

            if (tipe === 'pembayaran_pertama') {
                title = 'Konfirmasi Pembayaran Pertama?';
                html = `
                    <p>Pastikan bukti transfer sudah sesuai!</p>
                    <p class="text-danger font-weight-bold">Penyewaan akan menjadi AKTIF setelah dikonfirmasi.</p>
                `;
                confirmButtonText = '<i class="fas fa-check"></i> Ya, Konfirmasi!';
            } else if (tipe === 'pelunasan') {
                title = 'Konfirmasi Pelunasan?';
                html = `
                    <p>Pastikan bukti transfer pelunasan sudah sesuai!</p>
                    <p class="text-danger font-weight-bold">Pembayaran akan LUNAS setelah dikonfirmasi.</p>
                `;
                confirmButtonText = '<i class="fas fa-check"></i> Ya, Konfirmasi Pelunasan!';
            }

            Swal.fire({
                title: title,
                html: html,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmButtonText,
                cancelButtonText: '<i class="fas fa-times"></i> Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // SweetAlert Tolak sederhana (tanpa alasan)
    document.querySelectorAll('.btn-tolak-simple').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.simple-tolak-form');

            Swal.fire({
                title: 'Yakin ingin tolak pembayaran?',
                text: "Pembayaran akan dibatalkan dan penyewaan kembali ke status 'menunggu_pembayaran'.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check"></i> Ya, Tolak',
                cancelButtonText: '<i class="fas fa-times"></i> Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error') }}",
        confirmButtonColor: '#ef4444'
    });
</script>
@endif
@endsection
