@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Keunggulan</h1>
        <a href="{{ route('keunggulan.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Keunggulan</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('keunggulan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="judul" class="font-weight-bold">Judul <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="gambar" class="font-weight-bold">Gambar</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('gambar') is-invalid @enderror" id="gambar" name="gambar" accept="image/*" onchange="previewImage(event)">
                        <label class="custom-file-label" for="gambar">Pilih gambar...</label>
                    </div>
                    @error('gambar')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi" class="font-weight-bold">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('keunggulan.index') }}" class="btn btn-secondary">
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
            label.textContent = 'Pilih gambar...';
        }
    }
</script>
@endsection
