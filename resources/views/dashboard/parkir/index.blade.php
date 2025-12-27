@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Parkiran</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Parkiran</h6>
            <a href="{{ route('parkir.tambah') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Parkir
            </a>
        </div>
        <div class="card-body">
            <!-- Search Bar -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari data..." id="searchInput">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="8%">No</th>
                            <th width="25%">Nama</th>
                            <th width="30%">Alamat</th>
                            <th width="20%">Daftar Armada</th>
                            <th class="text-center" width="17%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($parkirs as $index => $parkir)
                         <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $parkir->nama }}</td>
                            <td>{{ $parkir->alamat }}</td>
                            <td class="text-center">
                                <span class="badge badge-info">
                                    {{ $parkir->armadas->count() }} Armada
                                </span>
                                <a href="{{ route('parkir.armada', $parkir->id) }}" 
                                   class="btn btn-sm btn-primary ml-2" 
                                   title="Lihat Daftar Armada">
                                    <i class="fas fa-list"></i> Lihat
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('parkir.show', $parkir->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('parkir.edit', $parkir->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('parkir.destroy', $parkir->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data parkiran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection

{{--  🔽 Tempatkan script di section scripts agar ikut di-render oleh masterDashboard --}}
@section('scripts')
<script>
    // SweetAlert Konfirmasi Hapus
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data ini tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Fungsi pencarian sederhana
    document.getElementById('searchInput').addEventListener('keyup', function() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        table = document.getElementById('dataTable');
        tr = table.getElementsByTagName('tr');

        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = 'none';
            td = tr[i].getElementsByTagName('td');
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                        break;
                    }
                }
            }
        }
    });
</script>
@endsection