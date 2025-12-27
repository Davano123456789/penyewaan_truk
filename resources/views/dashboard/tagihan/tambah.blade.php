@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Tagihan</h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Tagihan</h6>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <!-- No Tagihan -->
                        <div class="form-group">
                            <label for="no_tagihan" class="font-weight-bold">No. Tagihan</label>
                            <input type="text" class="form-control @error('no_tagihan') is-invalid @enderror" 
                                   id="no_tagihan" name="no_tagihan" value="{{ old('no_tagihan', 'INV-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)) }}" 
                                   placeholder="INV-2025-001" required readonly>
                            <small class="form-text text-muted">Nomor tagihan dibuat otomatis</small>
                            @error('no_tagihan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Penyewaan ID -->
                        <div class="form-group">
                            <label for="penyewaan_id" class="font-weight-bold">Penyewaan <span class="text-danger">*</span></label>
                            <select class="form-control @error('penyewaan_id') is-invalid @enderror" 
                                    id="penyewaan_id" name="penyewaan_id" required>
                                <option value="">Pilih Penyewaan</option>
                                <option value="1" {{ old('penyewaan_id') == '1' ? 'selected' : '' }}>PSW-2025-0012 - Elektronik (5 Ton) - PT. Maju Jaya</option>
                                <option value="2" {{ old('penyewaan_id') == '2' ? 'selected' : '' }}>PSW-2025-0015 - Furniture (3 Ton) - CV. Sukses Makmur</option>
                                <option value="3" {{ old('penyewaan_id') == '3' ? 'selected' : '' }}>PSW-2025-0018 - Bahan Bangunan (8 Ton) - PT. Karya Abadi</option>
                                <option value="4" {{ old('penyewaan_id') == '4' ? 'selected' : '' }}>PSW-2025-0020 - Makanan & Minuman (2 Ton) - UD. Berkah Jaya</option>
                                <option value="5" {{ old('penyewaan_id') == '5' ? 'selected' : '' }}>PSW-2025-0022 - Tekstil & Pakaian (4 Ton) - PT. Tekstil Indonesia</option>
                            </select>
                            @error('penyewaan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Total Tagihan -->
                        <div class="form-group">
                            <label for="total_tagihan" class="font-weight-bold">Total Tagihan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control @error('total_tagihan') is-invalid @enderror" 
                                       id="total_tagihan" name="total_tagihan" value="{{ old('total_tagihan') }}" 
                                       placeholder="0" required>
                            </div>
                            <small class="form-text text-muted">Masukkan nominal tanpa titik atau koma</small>
                            @error('total_tagihan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="total_display" class="mt-2 text-success font-weight-bold"></div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="form-group">
                            <label for="metode_pembayaran" class="font-weight-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-control @error('metode_pembayaran') is-invalid @enderror" 
                                    id="metode_pembayaran" name="metode_pembayaran" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="e-wallet" {{ old('metode_pembayaran') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                            </select>
                            @error('metode_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Detail Transfer (Hidden by default) -->
                        <div id="detail_transfer" style="display: none;">
                            <div class="form-group">
                                <label for="nama_bank" class="font-weight-bold">Nama Bank</label>
                                <select class="form-control" id="nama_bank" name="nama_bank">
                                    <option value="">Pilih Bank</option>
                                    <option value="BCA">BCA</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="BNI">BNI</option>
                                    <option value="BRI">BRI</option>
                                    <option value="CIMB">CIMB Niaga</option>
                                    <option value="Permata">Permata Bank</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="no_rekening" class="font-weight-bold">No. Rekening</label>
                                <input type="text" class="form-control" id="no_rekening" name="no_rekening" placeholder="Masukkan nomor rekening">
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <!-- Status -->
                        <div class="form-group">
                            <label for="status" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="belum-lunas" {{ old('status') == 'belum-lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                   

                        <!-- Email Penerima (Tampil jika checkbox dicentang) -->
                        <div id="email_penerima_group" style="display: none;">
                            <div class="form-group">
                                <label for="email_penerima" class="font-weight-bold">Email Penerima</label>
                                <input type="email" class="form-control" id="email_penerima" name="email_penerima" placeholder="client@email.com" readonly>
                                <small class="form-text text-muted">Email akan otomatis terisi dari data penyewaan</small>
                            </div>
                        </div>

                        <!-- Tanggal Jatuh Tempo -->
                        <div class="form-group">
                            <label for="tanggal_jatuh_tempo" class="font-weight-bold">Tanggal Jatuh Tempo</label>
                            <input type="date" class="form-control @error('tanggal_jatuh_tempo') is-invalid @enderror" 
                                   id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}">
                            @error('tanggal_jatuh_tempo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                       
                    </div>
                </div>

                <!-- Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Tagihan
                        </button>
                        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="reset" class="btn btn-warning">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection

@section('scripts')
<script>
    // Format currency input
    document.getElementById('total_tagihan').addEventListener('keyup', function(e) {
        let value = this.value.replace(/\D/g, '');
        
        // Display formatted
        if (value) {
            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);
            document.getElementById('total_display').textContent = formatted;
        } else {
            document.getElementById('total_display').textContent = '';
        }
    });

    // Show/hide transfer details
    document.getElementById('metode_pembayaran').addEventListener('change', function() {
        const detailTransfer = document.getElementById('detail_transfer');
        if (this.value === 'transfer') {
            detailTransfer.style.display = 'block';
        } else {
            detailTransfer.style.display = 'none';
        }
    });

    // Show/hide email penerima
    document.getElementById('email_terkirim').addEventListener('change', function() {
        const emailGroup = document.getElementById('email_penerima_group');
        if (this.checked) {
            emailGroup.style.display = 'block';
        } else {
            emailGroup.style.display = 'none';
        }
    });

    // Auto fill email from penyewaan selection (simulasi)
    document.getElementById('penyewaan_id').addEventListener('change', function() {
        const emailInput = document.getElementById('email_penerima');
        const emails = {
            '1': 'admin@majujaya.com',
            '2': 'info@suksesmakmur.com',
            '3': 'contact@karyaabadi.com',
            '4': 'berkah@gmail.com',
            '5': 'cs@tekstilindonesia.com'
        };
        
        if (this.value && emails[this.value]) {
            emailInput.value = emails[this.value];
        } else {
            emailInput.value = '';
        }
    });

    // Set minimum date for jatuh tempo (today)
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal_jatuh_tempo').setAttribute('min', today);

    // Konfirmasi sebelum reset
    document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
        if (!confirm('Yakin ingin mereset semua input?')) {
            e.preventDefault();
        }
    });

    // Validasi before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const totalTagihan = document.getElementById('total_tagihan').value;
        if (!totalTagihan || parseInt(totalTagihan) <= 0) {
            e.preventDefault();
            alert('Total tagihan harus lebih dari 0!');
            return false;
        }
    });
</script>
@endsection