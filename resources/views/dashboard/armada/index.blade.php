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
            <!-- Search Bar & Filter Jenis -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-6 mb-2 mb-md-0">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari data..." id="searchInput">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end">
                    <select class="form-control" id="filterJenis" style="max-width: 200px;">
                        <option value="">Semua Jenis</option>
                        <option value="CDD">CDD</option>
                        <option value="BOX">BOX</option>
                        <option value="WINGBOX">WINGBOX</option>
                    </select>
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
                <th width="11%" class="text-center">Status</th>
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
                        <span class="badge badge-{{ $armada->status_badge }}">{{ $armada->status_label }}</span>
                    </td>
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
                    <td colspan="8" class="text-center text-muted">Belum ada data armada</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" id="paginationInfo">
                        Menampilkan 0 sampai 0 dari 0 data
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate float-right">
                        <ul class="pagination" id="paginationControls">
                            <!-- Pagination buttons dynamically rendered via JS -->
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
    var currentPage = 1;
    var rowsPerPage = 10;

    // Filter, Search, dan Paginasi Real-time
    function filterTable() {
        var searchValue = document.getElementById('searchInput').value.toUpperCase();
        var jenisValue = document.getElementById('filterJenis').value.toUpperCase();
        var table = document.getElementById('dataTable');
        var tr = table.getElementsByTagName('tr');
        
        var matchingRows = [];

        for (var i = 1; i < tr.length; i++) {
            var row = tr[i];
            
            // Lewati jika baris kosong
            var cells = row.getElementsByTagName('td');
            if (cells.length < 5) continue; 

            var textMatch = false;
            var jenisMatch = false;

            // 1. Filter berdasarkan pencarian kata
            for (var j = 0; j < cells.length; j++) {
                if (cells[j]) {
                    var cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toUpperCase().indexOf(searchValue) > -1) {
                        textMatch = true;
                        break;
                    }
                }
            }

            // 2. Filter berdasarkan Jenis (kolom ke-4, index 3)
            var jenisCell = cells[3];
            if (jenisCell) {
                var jenisText = (jenisCell.textContent || jenisCell.innerText).trim().toUpperCase();
                if (jenisValue === '' || jenisText === jenisValue) {
                    jenisMatch = true;
                }
            } else {
                jenisMatch = (jenisValue === '');
            }

            if (textMatch && jenisMatch) {
                matchingRows.push(row);
            } else {
                row.style.display = 'none';
            }
        }

        // Paginasi
        var totalRows = matchingRows.length;
        var totalPages = Math.ceil(totalRows / rowsPerPage);
        if (totalPages < 1) totalPages = 1;
        if (currentPage > totalPages) currentPage = totalPages;

        var startIdx = (currentPage - 1) * rowsPerPage;
        var endIdx = startIdx + rowsPerPage;

        for (var k = 0; k < totalRows; k++) {
            if (k >= startIdx && k < endIdx) {
                matchingRows[k].style.display = '';
            } else {
                matchingRows[k].style.display = 'none';
            }
        }

        // Info data
        var infoStart = totalRows > 0 ? startIdx + 1 : 0;
        var infoEnd = endIdx > totalRows ? totalRows : endIdx;
        document.getElementById('paginationInfo').innerText = "Menampilkan " + infoStart + " sampai " + infoEnd + " dari " + totalRows + " data";

        // Render tombol paginasi
        var controlsHtml = '';
        
        // Tombol Previous
        if (currentPage === 1) {
            controlsHtml += '<li class="paginate_button page-item previous disabled"><a href="#" class="page-link">Previous</a></li>';
        } else {
            controlsHtml += '<li class="paginate_button page-item previous"><a href="#" class="page-link" onclick="changePage(' + (currentPage - 1) + '); return false;">Previous</a></li>';
        }

        // Nomor Halaman
        for (var p = 1; p <= totalPages; p++) {
            if (p === currentPage) {
                controlsHtml += '<li class="paginate_button page-item active"><a href="#" class="page-link">' + p + '</a></li>';
            } else {
                controlsHtml += '<li class="paginate_button page-item"><a href="#" class="page-link" onclick="changePage(' + p + '); return false;">' + p + '</a></li>';
            }
        }

        // Tombol Next
        if (currentPage === totalPages) {
            controlsHtml += '<li class="paginate_button page-item next disabled"><a href="#" class="page-link">Next</a></li>';
        } else {
            controlsHtml += '<li class="paginate_button page-item next"><a href="#" class="page-link" onclick="changePage(' + (currentPage + 1) + '); return false;">Next</a></li>';
        }

        document.getElementById('paginationControls').innerHTML = controlsHtml;
    }

    function changePage(page) {
        currentPage = page;
        filterTable();
    }

    document.getElementById('searchInput').addEventListener('keyup', function() {
        currentPage = 1;
        filterTable();
    });
    
    document.getElementById('filterJenis').addEventListener('change', function() {
        currentPage = 1;
        filterTable();
    });

    // Jalankan filter/paginasi pertama kali saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        filterTable();
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
@endsection
