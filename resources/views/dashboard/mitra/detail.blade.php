@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Mitra Kerja</h1>
    </div>

    <!-- Kartu Informasi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Mitra</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Kolom Logo -->
                <div class="col-md-4 text-center mb-4">
                    @if($mitra->logo)
                        <img src="{{ $mitra->logo }}" alt="Logo {{ $mitra->nama }}" class="img-fluid rounded shadow-sm" style="max-height: 180px; object-fit: contain;">
                    @else
                        <div class="bg-light p-5 rounded">
                            <i class="fas fa-image text-muted" style="font-size: 100px;"></i>
                            <p class="text-muted mt-2">Tidak ada logo</p>
                        </div>
                    @endif
                </div>

                <!-- Kolom Informasi -->
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="font-weight-bold">Nama</label>
                        <input type="text" class="form-control" value="{{ $mitra->nama }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{ route('mitra.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Mitra
                    </a>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data mitra ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection
