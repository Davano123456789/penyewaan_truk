@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Penyewaan #{{ $penyewaan->id }}</h1>
        <div>
            <a href="{{ route('penyewaanAdmin.invoice', $penyewaan->id) }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-file-pdf"></i> Cetak Invoice
            </a>
            <a href="{{ route('penyewaanAdmin.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Client -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-header border-0 bg-transparent">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Client</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Nama Customer</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $penyewaan->client->nama ?? '-' }}</div>
                            <hr class="my-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Email / Telepon</div>
                            <div class="small mb-0 text-gray-800">{{ $penyewaan->client->email ?? '-' }}</div>
                            <div class="small mb-0 text-gray-800">{{ $penyewaan->client->telepon ?? '-' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="col-xl-8 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-header border-0 bg-transparent">
                    <h6 class="m-0 font-weight-bold text-info">Ringkasan Pesanan #{{ $penyewaan->kode_transaksi }}</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Unit</div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $penyewaan->keranjangs->count() }} unit truk</div>
                                </div>
                                <div class="col-6 text-right">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Harga</div>
                                    <div class="h5 mb-0 font-weight-bold text-primary">Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Status Pesanan</div>
                            <div class="h6 mb-0">
                                @if($penyewaan->status == 'pending')
                                    <span class="badge badge-secondary">Pending</span>
                                @elseif($penyewaan->status == 'menunggu_pembayaran')
                                    <span class="badge badge-info">Menunggu Pembayaran</span>
                                @elseif($penyewaan->status == 'menunggu_konfirmasi_pembayaran')
                                    <span class="badge badge-warning">Menunggu Konfirmasi Pembayaran</span>
                                @elseif($penyewaan->status == 'aktif')
                                    <span class="badge badge-success">Aktif / Berjalan</span>
                                @elseif($penyewaan->status == 'selesai')
                                    <span class="badge badge-primary">Selesai</span>
                                @elseif($penyewaan->status == 'dibatalkan')
                                    <span class="badge badge-danger">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            <th width="15%">Armada</th>
                            <th width="15%">Sopir</th>
                            <th width="12%">Tanggal Mulai</th>
                            <th width="10%">Estimasi</th>
                            <th width="18%">Rute (Jemput & Antar)</th>
                            <th width="15%">Status Item</th>
                            <th width="10%">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penyewaan->keranjangs as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->armada->jenis ?? 'N/A' }}</strong><br>
                                <small class="badge badge-secondary">{{ $item->armada->no_polisi ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <i class="fas fa-user-circle fa-lg text-gray-400"></i>
                                    </div>
                                    <div>
                                        <div class="small font-weight-bold">{{ $item->sopir->nama ?? 'Belum Ditugaskan' }}</div>
                                        <div class="small text-muted">{{ $item->sopir->telepon ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="small font-weight-bold">{{ $item->estimasi_hari }} Hari</div>
                                <div class="small text-muted">{{ $item->total_jarak }} Km</div>
                            </td>
                            <td>
                                <div class="small mb-1"><i class="fas fa-map-marker-alt text-danger"></i> {{ $item->tempat_jemput }}</div>
                                <div class="small"><i class="fas fa-flag-checkered text-success"></i> {{ $item->tempat_antar }}</div>
                            </td>
                            <td class="text-center">
                                @if($item->status == 'pending')
                                    <span class="badge badge-secondary">Pending</span>
                                @elseif($item->status == 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($item->status == 'revisi_bukti')
                                    <span class="badge badge-danger">Revisi Bukti</span>
                                @elseif($item->status == 'selesai')
                                    <span class="badge badge-primary">Selesai</span>
                                @elseif($item->status == 'menunggu_konfirmasi_batal')
                                    <span class="badge badge-warning">Menunggu Konfirmasi Batal</span>
                                @elseif($item->status == 'dibatalkan')
                                    <span class="badge badge-danger">Dibatalkan</span>
                                @else
                                    <span class="badge badge-info">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td class="text-right"><strong class="text-primary">Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="font-weight-bold">
                        <tr class="bg-light">
                            <td colspan="7" class="text-right">TOTAL:</td>
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

            @if($penyewaan->status == 'menunggu_konfirmasi_pembayaran' || ($penyewaan->status == 'aktif' && $penyewaan->pembayaran && $penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan'))
            <hr>
            <div class="text-center py-3">
                <button type="button" class="btn btn-primary btn-lg px-5 shadow-sm" data-toggle="modal" data-target="#modalVerifikasi">
                    <i class="fas fa-shield-alt"></i> Verifikasi Pembayaran
                </button>
            </div>

            <!-- Modal Verifikasi -->
            <div class="modal fade" id="modalVerifikasi" tabindex="-1" role="dialog" aria-labelledby="modalVerifikasiLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="modalVerifikasiLabel">
                                @if($penyewaan->status == 'menunggu_konfirmasi_pembayaran')
                                    Verifikasi Pembayaran Pertama
                                @else
                                    Verifikasi Pelunasan
                                @endif
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-left">
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Pilih Keputusan <span class="text-danger">*</span></label>
                                <div class="d-flex border rounded p-3 bg-light">
                                    <div class="custom-control custom-radio mr-4">
                                        <input type="radio" id="verifApprove" name="decision" value="approve" class="custom-control-input decision-radio" required>
                                        <label class="custom-control-label font-weight-bold text-success" for="verifApprove">Setujui</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="verifReject" name="decision" value="reject" class="custom-control-input decision-radio" required>
                                        <label class="custom-control-label font-weight-bold text-danger" for="verifReject">Tolak</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Setuju -->
                            <div id="sectionApprove" class="verification-section" style="display: none;">
                                <div class="alert alert-success d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x mr-3"></i>
                                    @if($penyewaan->status == 'menunggu_konfirmasi_pembayaran')
                                        <div>Penyewaan akan otomatis menjadi <strong>AKTIF</strong> dan sopir akan mendapatkan notifikasi penugasan.</div>
                                    @else
                                        <div>Pembayaran akan otomatis menjadi <strong>LUNAS</strong>.</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Section Tolak -->
                            <div id="sectionReject" class="verification-section" style="display: none;">
                                <div class="form-group mt-3">
                                    <label class="font-weight-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                    <textarea id="catatan_verifikasi" name="catatan" class="form-control" rows="3" placeholder="Contoh: Bukti transfer tidak valid atau jumlah tidak sesuai..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="button" id="btnSubmitVerifikasi" class="btn btn-primary" disabled style="min-width: 120px;">
                                <i class="fas fa-paper-plane"></i> Proses Verifikasi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Forms for Actions -->
            <form id="formApprove" action="{{ route('penyewaanAdmin.konfirmasi', $penyewaan->id) }}" method="POST" style="display: none;">@csrf</form>
            <form id="formReject" action="{{ route('penyewaanAdmin.tolak', $penyewaan->id) }}" method="POST" style="display: none;">@csrf <input type="hidden" name="catatan" id="hiddenCatatan"></form>
            @endif
        </div>
    </div>
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
    // Handle Verification Modal Decision
    document.querySelectorAll('.decision-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const decision = this.value;
            const submitBtn = document.getElementById('btnSubmitVerifikasi');
            
            document.getElementById('sectionApprove').style.display = 'none';
            document.getElementById('sectionReject').style.display = 'none';
            
            if (decision === 'approve') {
                document.getElementById('sectionApprove').style.display = 'block';
            } else {
                document.getElementById('sectionReject').style.display = 'block';
            }
            
            submitBtn.disabled = false;
        });
    });

    // Handle Modal Submit
    document.getElementById('btnSubmitVerifikasi')?.addEventListener('click', function() {
        const decision = document.querySelector('input[name="decision"]:checked').value;
        const catatan = document.getElementById('catatan_verifikasi').value;
        
        if (decision === 'approve') {
            document.getElementById('formApprove').submit();
        } else {
            if (!catatan.trim()) {
                Swal.fire({
                    title: 'Error',
                    text: 'Alasan penolakan wajib diisi!',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
            
            document.getElementById('hiddenCatatan').value = catatan;
            document.getElementById('formReject').submit();
        }
    });
</script>

@endsection
