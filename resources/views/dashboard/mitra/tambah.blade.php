@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Mitra Kerja</h1>
        <a href="{{ route('mitra.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Mitra</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('mitra.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nama Mitra -->
                <div class="form-group">
                    <label for="nama" class="font-weight-bold">Nama Mitra <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('nama') is-invalid @enderror" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama') }}"
                           placeholder="Masukkan nama mitra"
                           required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Logo -->
                <div class="form-group">
                    <label for="logo" class="font-weight-bold">Logo <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" 
                               class="custom-file-input @error('logo') is-invalid @enderror" 
                               id="logo" 
                               name="logo"
                               accept="image/*"
                               onchange="previewImage(event)"
                               required>
                        <label class="custom-file-label" for="logo">Pilih logo...</label>
                    </div>
                    @error('logo')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <small class="form-text text-muted">
                        Format: JPG, JPEG, PNG, GIF. Maksimal 2MB
                    </small>
                    
                    <!-- Preview Image -->
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>

                <hr>

                <!-- Buttons -->
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('mitra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Preview image
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');
        const label = event.target.nextElementSibling;

        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
            label.textContent = file.name;
        } else {
            imagePreview.style.display = 'none';
            label.textContent = 'Pilih logo...';
        }
    }
</script>
@endsection