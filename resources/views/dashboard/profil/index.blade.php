@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil Saya</h1>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(Auth::user()->gambar)
                            <img src="{{ Auth::user()->gambar }}" 
                                 alt="Foto Profil" 
                                 class="rounded-circle img-thumbnail" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 150px; height: 150px; font-size: 48px;">
                                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h5 class="font-weight-bold">{{ Auth::user()->nama }}</h5>
                    <p class="text-muted mb-1">{{ Auth::user()->email }}</p>
                    <p class="text-muted">
                        <span class="badge badge-primary">
                            {{ Auth::user()->peran->nama_peran ?? 'User' }}
                        </span>
                    </p>
                </div>
            </div>

          
        </div>

        <!-- Edit Profile Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Profil</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')



                        <!-- Foto Profil -->
                        <div class="form-group">
                            <label for="gambar" class="font-weight-bold">Foto Profil</label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('gambar') is-invalid @enderror" 
                                       id="gambar" 
                                       name="gambar"
                                       accept="image/jpeg,image/png,image/jpg"
                                       onchange="previewImage(event)">
                                <label class="custom-file-label" for="gambar">Pilih foto...</label>
                            </div>
                            @error('gambar')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <small class="form-text text-muted">
                                Format: JPG, JPEG, PNG. Maksimal 2MB
                            </small>
                            
                            <!-- Preview Image -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <hr>

                        <!-- Nama -->
                        <div class="form-group">
                            <label for="nama" class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" 
                                   name="nama" 
                                   value="{{ old('nama', Auth::user()->nama) }}"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="font-weight-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', Auth::user()->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Umur -->
                        <div class="form-group">
                            <label for="umur" class="font-weight-bold">Umur</label>
                            <input type="number" 
                                   class="form-control @error('umur') is-invalid @enderror" 
                                   id="umur" 
                                   name="umur" 
                                   min="17"
                                   max="100"
                                   value="{{ old('umur', Auth::user()->umur) }}">
                            @error('umur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div class="form-group">
                            <label for="telepon" class="font-weight-bold">Nomor Telepon</label>
                            <input type="tel" 
                                   class="form-control @error('telepon') is-invalid @enderror" 
                                   id="telepon" 
                                   name="telepon" 
                                   value="{{ old('telepon', Auth::user()->telepon) }}"
                                   placeholder="08xxxxxxxxxx">
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="form-group">
                            <label for="alamat" class="font-weight-bold">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" 
                                      name="alamat" 
                                      rows="3"
                                      placeholder="Alamat lengkap">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <!-- Buttons -->
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection

@section('scripts')
<script>
    // Preview image sebelum upload
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
            label.textContent = 'Pilih foto...';
        }
    }


</script>
@endsection