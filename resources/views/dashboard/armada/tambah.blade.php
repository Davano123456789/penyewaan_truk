@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Armada</h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Armada</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('armada.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <!-- No Polisi -->
                        <div class="form-group">
                            <label for="no_polisi" class="font-weight-bold">No Polisi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('no_polisi') is-invalid @enderror" 
                                   id="no_polisi" name="no_polisi" value="{{ old('no_polisi') }}" 
                                   placeholder="Contoh: B 1234 XYZ" required>
                            @error('no_polisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sopir -->
                        <div class="form-group">
                            <label for="sopir_id" class="font-weight-bold">Sopir</label>
                            <select class="form-control @error('sopir_id') is-invalid @enderror" 
                                    id="sopir_id" name="sopir_id">
                                <option value="">-- Pilih Sopir --</option>
                                @foreach($sopirs as $sopir)
                                    <option value="{{ $sopir->id }}" {{ old('sopir_id') == $sopir->id ? 'selected' : '' }}>
                                        {{ $sopir->nama }} - {{ $sopir->email }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                <i class="fas fa-user"></i> Hanya menampilkan user dengan role Sopir
                            </small>
                            @error('sopir_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Parkir -->
                        <div class="form-group">
                            <label for="parkir_id" class="font-weight-bold">Lokasi Parkir</label>
                            <select class="form-control @error('parkir_id') is-invalid @enderror" 
                                    id="parkir_id" name="parkir_id">
                                <option value="">-- Pilih Lokasi Parkir --</option>
                                @foreach($parkirs as $parkir)
                                    <option value="{{ $parkir->id }}" {{ old('parkir_id') == $parkir->id ? 'selected' : '' }}>
                                        {{ $parkir->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parkir_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Merek -->
                        <div class="form-group">
                            <label for="merek" class="font-weight-bold">Merek</label>
                            <input type="text" class="form-control @error('merek') is-invalid @enderror" 
                                   id="merek" name="merek" value="{{ old('merek') }}" 
                                   placeholder="Contoh: Hino, Mitsubishi">
                            @error('merek')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jenis -->
                     <div class="form-group">
    <label for="jenis" class="font-weight-bold">Jenis</label>
    <select class="form-control @error('jenis') is-invalid @enderror" id="jenis" name="jenis">
        <option value="">-- Pilih Jenis --</option>
        <option value="CDD" {{ old('jenis') == 'CDD' ? 'selected' : '' }}>CDD</option>
        <option value="BOX" {{ old('jenis') == 'BOX' ? 'selected' : '' }}>BOX</option>
        <option value="WINGBOX" {{ old('jenis') == 'WINGBOX' ? 'selected' : '' }}>WINGBOX</option>
        <option value="TERBUKA" {{ old('jenis') == 'TERBUKA' ? 'selected' : '' }}>TERBUKA</option>
    </select>
    @error('jenis')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <!-- Kapasitas -->
                        <div class="form-group">
                            <label for="kapasitas" class="font-weight-bold">Kapasitas (Ton)</label>
                            <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" 
                                   id="kapasitas" name="kapasitas" value="{{ old('kapasitas') }}" 
                                   placeholder="Contoh: 5" min="0" step="0.1">
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status" class="font-weight-bold">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status">
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="tidak_tersedia" {{ old('status') == 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia (Sedang Dipesan)</option>
                                <option value="perawatan" {{ old('status') == 'perawatan' ? 'selected' : '' }}>Sedang Perawatan (Maintenance)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label for="deskripsi" class="font-weight-bold">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="3" 
                                      placeholder="Keterangan tambahan tentang armada">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gambar -->
                        <div class="form-group">
                            <label for="gambar" class="font-weight-bold">Gambar Armada</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('gambar') is-invalid @enderror" 
                                       id="gambar" name="gambar" accept="image/*" onchange="previewImage(event)">
                                <label class="custom-file-label" for="gambar">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">
                                Format: JPG, JPEG, PNG. Maksimal 2MB
                            </small>
                            @error('gambar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview Image -->
                            <div id="preview-container" class="mt-3" style="display: none;">
                                <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->

                <!-- Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('armada.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="reset" class="btn btn-warning" onclick="resetForm()">
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
    // Preview image sebelum upload
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview-image');
        const container = document.getElementById('preview-container');
        const label = document.querySelector('.custom-file-label');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
            label.textContent = input.files[0].name;
        }
    }

    // Reset form
    function resetForm() {
        document.getElementById('preview-container').style.display = 'none';
        document.querySelector('.custom-file-label').textContent = 'Pilih file...';
    }
</script>
@endsection