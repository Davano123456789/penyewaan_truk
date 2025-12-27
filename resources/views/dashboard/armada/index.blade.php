@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Armada</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Armada</h6>
           <a href="{{ route('armada.tambah') }}" class="btn btn-primary btn-sm">
    <i class="fas fa-plus"></i> Tambah Armada
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
                <th class="text-center" width="5%">No</th>
                <th width="15%">Merek</th>
                <th width="15%">Sopir</th>
                <th width="12%">Jenis</th>
                <th width="12%">No Polisi</th>
                <th width="15%" class="text-center">Gambar</th>
                <th class="text-center" width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($armadas as $index => $armada)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $armada->merek ?? '-' }}</td>
                    <td>{{ $armada->sopir->nama ?? 'Belum Ada' }}</td>
                    <td>{{ $armada->jenis ?? '-' }}</td>
                    <td>{{ $armada->no_polisi ?? '-' }}</td>
                    <td class="text-center">
                        @if($armada->gambar)
                            <img src="{{ $armada->gambar }}" alt="Gambar Armada" width="80" class="rounded">
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                    <td class="text-center">
                       <a href="{{ route('armada.show', $armada->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
    <i class="fas fa-eye"></i>
</a>

                        <a href="{{ route('armada.edit', $armada->id) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('armada.destroy', $armada->id) }}" 
      method="POST" 
      class="d-inline form-delete">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger btn-sm" title="Hapus">
        <i class="fas fa-trash"></i>
    </button>
</form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Belum ada data armada</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info">
                        Menampilkan 1 sampai 5 dari 5 data
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate float-right">
                        <ul class="pagination">
                            <li class="paginate_button page-item previous disabled">
                                <a href="#" class="page-link">Previous</a>
                            </li>
                            <li class="paginate_button page-item active">
                                <a href="#" class="page-link">1</a>
                            </li>
                            <li class="paginate_button page-item">
                                <a href="#" class="page-link">2</a>
                            </li>
                            <li class="paginate_button page-item">
                                <a href="#" class="page-link">3</a>
                            </li>
                            <li class="paginate_button page-item next">
                                <a href="#" class="page-link">Next</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Pencarian sederhana
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

    // SweetAlert untuk konfirmasi hapus
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
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
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@endsection
