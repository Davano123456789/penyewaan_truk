@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Sopir</h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Sopir</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('sopir.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <!-- Nama -->
                        <div class="form-group">
                            <label for="nama" class="font-weight-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="font-weight-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Telepon -->
                        <div class="form-group">
                            <label for="telepon" class="font-weight-bold">Telepon</label>
                            <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon') }}">
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="font-weight-bold">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Alamat -->
                        <div class="form-group">
                            <label for="alamat" class="font-weight-bold">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Gambar -->
                        <div class="form-group">
                            <label for="gambar" class="font-weight-bold">Foto Sopir</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('gambar') is-invalid @enderror" id="gambar" name="gambar" accept="image/*" onchange="previewImage(event)">
                                <label class="custom-file-label" for="gambar">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                            @error('gambar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="preview-container" class="mt-3" style="display: none;">
                                <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>
                        <!-- Umur -->
                        <div class="form-group">
                            <label for="umur" class="font-weight-bold">Umur</label>
                            <input type="number" class="form-control @error('umur') is-invalid @enderror" id="umur" name="umur" min="18" value="{{ old('umur') }}">
                            @error('umur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('sopir.index') }}" class="btn btn-secondary">
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
@endsection

@section('scripts')
<script>
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
    function resetForm() {
        document.getElementById('preview-container').style.display = 'none';
        document.querySelector('.custom-file-label').textContent = 'Pilih file...';
    }
</script>
@endsection
