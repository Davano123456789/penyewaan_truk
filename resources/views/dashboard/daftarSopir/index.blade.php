@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Sopir</h1>
        <a href="{{ route('sopir.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Sopir
        </a>
    </div>

    <div class="card shadow mb-4">
       

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th class="text-center">Foto</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sopirs as $index => $sopir)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $sopir->nama }}</td>
                            <td>{{ $sopir->email }}</td>
                            <td>{{ $sopir->telepon ?? '-' }}</td>
                            <td>{{ $sopir->alamat ?? '-' }}</td>
                          <td class="text-center">
    @if($sopir->gambar)
        <img src="{{ $sopir->gambar }}" 
             alt="Foto Sopir" 
             width="60" 
             height="60" 
             class="rounded-circle shadow-sm">
    @else
        <span class="text-muted">Tidak ada</span>
    @endif
</td>

                            <td class="text-center">
                                <a href="{{ route('sopir.show', $sopir->id) }}" class="btn btn-info btn-sm" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('sopir.edit', $sopir->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('sopir.destroy', $sopir->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data sopir</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Konfirmasi Hapus dengan SweetAlert
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data ini tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
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
