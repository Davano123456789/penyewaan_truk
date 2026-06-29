@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Mitra Kerja</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Mitra</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('mitra.update', $mitra->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama" class="font-weight-bold">Nama Mitra <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('nama') is-invalid @enderror" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama', $mitra->nama) }}"
                           required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="logo" class="font-weight-bold">Logo</label>
                    
                    @if($mitra->logo)
                    <div class="mb-2">
                        <img src="{{ $mitra->logo }}" alt="Logo saat ini" class="img-thumbnail" style="max-width: 200px;">
                        <p class="text-muted small">Logo saat ini</p>
                    </div>
                    @endif
                    
                    <div class="custom-file">
                        <input type="file" 
                               class="custom-file-input @error('logo') is-invalid @enderror" 
                               id="logo" 
                               name="logo"
                               accept="image/*"
                               onchange="previewImage(event)">
                        <label class="custom-file-label" for="logo">Pilih logo baru...</label>
                    </div>
                    @error('logo')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah logo</small>
                    
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>

                <hr>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('mitra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
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
        }
    }
</script>
@endsection