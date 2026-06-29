@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Upload Bukti Pembayaran</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Rekening Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-university"></i> Nomor Rekening Tujuan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 text-center">
                                <h6 class="font-weight-bold text-primary">Bank BCA</h6>
                                <p class="h5 mb-1">1234567890</p>
                                <small class="text-muted">PT Truck Rental Indonesia</small>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 text-center">
                                <h6 class="font-weight-bold text-info">Bank BRI</h6>
                                <p class="h5 mb-1">5555666677</p>
                                <small class="text-muted">PT Truck Rental Indonesia</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-upload"></i> Form Upload Bukti Transfer - Pesanan #{{ $penyewaan->kode_transaksi }}
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Info Pembayaran -->
                    @if($penyewaan->pembayaran && $penyewaan->pembayaran->status == 'ditolak')
                    <!-- NOTIFIKASI PENOLAKAN -->
                    <div class="alert alert-danger border-left-danger shadow-sm mb-4">
                        <h5 class="alert-heading font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Pembayaran Ditolak Admin</h5>
                        <hr>
                        <p class="mb-1"><strong>Catatan Penolakan:</strong></p>
                        <p class="mb-3 font-italic">"{{ $penyewaan->pembayaran->catatan ?? 'Tidak ada catatan' }}"</p>
                        <p class="mb-0">Silakan unggah ulang bukti transfer pembayaran yang valid.</p>
                    </div>
                    @endif

                    @if($penyewaan->status == 'menunggu_pembayaran')
                    <!-- PEMBAYARAN PERTAMA -->
                    <div class="alert alert-info">
                        <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Pembayaran Pertama</h5>
                        <hr>
                        <p class="mb-1"><strong>Total Pesanan:</strong></p>
                        <h3 class="text-primary mb-0">Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</h3>
                    </div>
                    @elseif($penyewaan->status == 'aktif' && $penyewaan->pembayaran && $penyewaan->pembayaran->jenis == 'talangan' && in_array($penyewaan->pembayaran->status, ['menunggu_pelunasan', 'ditolak']))
                    <!-- PEMBAYARAN SISA (TALANGAN) -->
                    <div class="alert alert-warning">
                        <h5 class="alert-heading"><i class="fas fa-money-bill-wave"></i> Pembayaran Sisa - Melunasi</h5>
                        <hr>
                        <p class="mb-1"><strong>Total Pesanan:</strong></p>
                        <h4 class="text-danger mb-3">Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</h4>
                        <p class="mb-2"><strong>Pembayaran Pertama (50%):</strong> Rp {{ number_format($penyewaan->pembayaran->jumlah_bayar, 0, ',', '.') }} <span class="badge badge-success">Sudah Dibayar</span></p>
                        <p class="mb-3"><strong>Sisa yang Harus Dibayar (50%):</strong> <span class="text-danger h5">Rp {{ number_format($penyewaan->harga_total / 2, 0, ',', '.') }}</span></p>
                        <p class="mb-0"><i class="fas fa-info-circle"></i> Setelah upload bukti pelunasan, admin akan mengkonfirmasi. Pesanan Anda akan dianggap LUNAS setelah dikonfirmasi admin.</p>
                    </div>
                    @endif

                    <!-- Jumlah Bayar Display -->
                    @if($penyewaan->status == 'menunggu_pembayaran')
                    <div class="alert alert-warning" id="jumlahBayarAlert" style="display: none;">
                        <h5 class="alert-heading"><i class="fas fa-money-bill-wave"></i> Jumlah yang Harus Dibayar Sekarang</h5>
                        <hr>
                        <h3 class="text-danger mb-2" id="jumlahBayarDisplay">Rp 0</h3>
                        <p class="mb-0 text-muted" id="keteranganJenis"></p>
                    </div>
                    @elseif($penyewaan->status == 'aktif' && $penyewaan->pembayaran && $penyewaan->pembayaran->jenis == 'talangan' && in_array($penyewaan->pembayaran->status, ['menunggu_pelunasan', 'ditolak']))
                    <div class="alert alert-info">
                        <h5 class="alert-heading"><i class="fas fa-money-bill-wave"></i> Jumlah Pembayaran Sisa yang Harus Dibayar</h5>
                        <hr>
                        <h3 class="text-danger mb-3">Rp {{ number_format($penyewaan->harga_total / 2, 0, ',', '.') }}</h3>
                        <p class="mb-0"><i class="fas fa-check-circle"></i> Ini adalah sisa 50% dari total Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }} yang masih harus dilunasi</p>
                    </div>
                    @endif



                    <!-- Form Upload -->
                    <form action="{{ route('pembayaran.store', $penyewaan->id) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf

                        <!-- Jenis Pembayaran - HANYA UNTUK PEMBAYARAN PERTAMA -->
                        @if($penyewaan->status == 'menunggu_pembayaran')
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fas fa-hand-holding-usd"></i> Jenis Pembayaran <span class="text-danger">*</span>
                            </label>
                            <div class="custom-control custom-radio">
                                <input type="radio" 
                                       class="custom-control-input jenis-pembayaran" 
                                       id="jenisCash" 
                                       name="jenis" 
                                       value="tunai" 
                                       {{ old('jenis') == 'tunai' ? 'checked' : '' }}
                                       onchange="hitungJumlahBayar()" 
                                       required>
                                <label class="custom-control-label" for="jenisCash">
                                    <strong>Tunai (100% - Langsung Lunas)</strong>
                                    <br>
                                    <small class="text-muted">Bayar penuh sekarang = Rp <span id="cashAmount">{{ number_format($penyewaan->harga_total, 0, ',', '.') }}</span></small>
                                </label>
                            </div>
                            <div class="custom-control custom-radio mt-2">
                                <input type="radio" 
                                       class="custom-control-input jenis-pembayaran" 
                                       id="jenisTalangan" 
                                       name="jenis" 
                                       value="talangan" 
                                       {{ old('jenis') == 'talangan' ? 'checked' : '' }}
                                       onchange="hitungJumlahBayar()" 
                                       required>
                                <label class="custom-control-label" for="jenisTalangan">
                                    <strong>Talangan (50% Bayar Sekarang + 50% Kemudian)</strong>
                                    <br>
                                    <small class="text-muted">Bayar sekarang = Rp <span id="talanganAmount">{{ number_format($penyewaan->harga_total / 2, 0, ',', '.') }}</span> (sisanya setelah selesai)</small>
                                </label>
                            </div>
                            @error('jenis')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        @else
                        <!-- PEMBAYARAN SISA - JENIS OTOMATIS TALANGAN -->
                        <input type="hidden" name="jenis" value="talangan">
                        @endif

                        <!-- Metode Pembayaran -->
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fas fa-university"></i> Metode Pembayaran <span class="text-danger">*</span>
                            </label>
                            <select name="metode" class="form-control @error('metode') is-invalid @enderror" required>
                                <option value="">-- Pilih Bank Transfer --</option>
                                <option value="transfer_bca" {{ old('metode') == 'transfer_bca' ? 'selected' : '' }}>
                                    Transfer Bank BCA (1234567890)
                                </option>
                                <option value="transfer_bri" {{ old('metode') == 'transfer_bri' ? 'selected' : '' }}>
                                    Transfer Bank BRI (5555666677)
                                </option>
                            </select>
                            @error('metode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Transfer -->
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fas fa-calendar"></i> Tanggal Transfer <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   name="tanggal_bayar" 
                                   class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                                   value="{{ old('tanggal_bayar', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            @error('tanggal_bayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Pilih tanggal ketika Anda melakukan transfer</small>
                        </div>

                        <!-- Upload Bukti Transfer -->
                        <div class="form-group">
                            <label class="font-weight-bold">
                                <i class="fas fa-image"></i> Bukti Transfer <span class="text-danger">*</span>
                            </label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('bukti_transfer') is-invalid @enderror" 
                                       id="bukti_transfer" 
                                       name="bukti_transfer"
                                       accept="image/jpeg,image/png,image/jpg"
                                       onchange="previewImage(event)"
                                       required>
                                <label class="custom-file-label" for="bukti_transfer">Pilih file bukti transfer...</label>
                            </div>
                            @error('bukti_transfer')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Format: JPG, JPEG, PNG. Maksimal 2MB. 
                                Upload foto struk ATM atau screenshot bukti transfer.
                            </small>

                            <!-- Preview Image -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <p class="font-weight-bold mb-2">Preview:</p>
                                <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 400px; max-height: 400px;">
                            </div>
                        </div>

                        <hr>

                        <!-- Informasi Penting -->
                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Penting!</h6>
                            <ul class="mb-0 pl-3">
                                <li>Pastikan bukti transfer yang diupload <strong>jelas dan terbaca</strong></li>
                                <li>Jumlah transfer harus <strong>sesuai dengan jumlah yang ditampilkan di atas</strong></li>
                                <li>Untuk <strong>Talangan</strong>, sisanya bisa dibayar setelah pengiriman selesai</li>
                                <li>Setelah upload, pesanan Anda akan <strong>menunggu konfirmasi pembayaran dari admin</strong></li>
                                <li>Proses verifikasi maksimal <strong>1x24 jam</strong></li>
                            </ul>
                        </div>

                        <!-- Buttons -->
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-success btn-lg btn-block" id="submitBtn">
                                <i class="fas fa-upload"></i> Upload Bukti Transfer
                            </button>
                            <a href="{{ route('penyewaan.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const totalHarga = {{ $penyewaan->harga_total }};
    const isPembayaranSisa = {{ ($penyewaan->status == 'aktif' && $penyewaan->pembayaran && $penyewaan->pembayaran->jenis == 'talangan' && $penyewaan->pembayaran->status == 'menunggu_pelunasan') ? 'true' : 'false' }};

    function hitungJumlahBayar() {
        // Skip calculation untuk pembayaran sisa (jenis sudah fixed di hidden input)
        if (isPembayaranSisa) {
            return;
        }

        const jumlahBayarAlert = document.getElementById('jumlahBayarAlert');
        const jumlahBayarDisplay = document.getElementById('jumlahBayarDisplay');
        const keteranganJenis = document.getElementById('keteranganJenis');
        
        const jenisPembayaran = document.querySelector('input[name="jenis"]:checked')?.value;
        let jumlahBayar = 0;
        let keterangan = '';

        if (jenisPembayaran === 'tunai') {
            jumlahBayar = totalHarga;
            keterangan = '✓ Pembayaran tunai - Status otomatis: <strong>LUNAS</strong>';
        } else if (jenisPembayaran === 'talangan') {
            jumlahBayar = totalHarga / 2;
            keterangan = '⏳ Pembayaran talangan - Bayar 50% sekarang, status: <strong>Menunggu Konfirmasi Pembayaran</strong> (sisanya setelah pengiriman)';
        }

        if (jenisPembayaran) {
            jumlahBayarDisplay.textContent = 'Rp ' + jumlahBayar.toLocaleString('id-ID', {maximumFractionDigits: 0});
            keteranganJenis.innerHTML = keterangan;
            jumlahBayarAlert.style.display = 'block';
        } else {
            jumlahBayarAlert.style.display = 'none';
        }
    }

    // Trigger calculation saat page load jika ada default value
    document.addEventListener('DOMContentLoaded', function() {
        hitungJumlahBayar();
    });

    // Preview image
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');
        const label = event.target.nextElementSibling;

        if (file) {
            // Validasi ukuran (2MB)
            if (file.size > 2048000) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 2MB',
                });
                event.target.value = '';
                imagePreview.style.display = 'none';
                label.textContent = 'Pilih file bukti transfer...';
                return;
            }

            // Validasi tipe file
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Tidak Valid',
                    text: 'Hanya file JPG, JPEG, dan PNG yang diperbolehkan',
                });
                event.target.value = '';
                imagePreview.style.display = 'none';
                label.textContent = 'Pilih file bukti transfer...';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
            label.textContent = file.name;
        } else {
            imagePreview.style.display = 'none';
            label.textContent = 'Pilih file bukti transfer...';
        }
    }

    // Loading saat submit
    document.getElementById('uploadForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengupload...';
    });
</script>

@if($errors->any())
<script>
    $(document).ready(function() {
        // Jika ada error validasi, mungkin tampilkan modal kembali jika itu tujuannya 
        // tapi SweetAlert global sudah menampilkan daftar errornya.
    });
</script>
@endif
@endsection