@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Armada - {{ $parkir->nama }}</h1>
    </div>

  

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Armada
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $parkir->armadas->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Armada Tersedia
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $parkir->armadas->where('status', 'tersedia')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Armada Disewa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $parkir->armadas->where('status', 'tidak_tersedia')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Armada -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Daftar Armada
            </h6>
        </div>
        <div class="card-body">
            @if($parkir->armadas->count() > 0)
                <!-- Search Bar -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari armada..." id="searchInput">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="12%">No Polisi</th>
                                <th width="15%">Merek</th>
                                <th width="12%">Jenis</th>
                                <th width="10%">Kapasitas</th>
                                <th width="15%">Sopir</th>
                                <th class="text-center" width="10%">Status</th>
                                <th width="15%">Deskripsi</th>
                                <th class="text-center" width="11%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parkir->armadas as $index => $armada)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $armada->no_polisi }}</strong></td>
                                <td>{{ $armada->merek }}</td>
                                <td>{{ $armada->jenis }}</td>
                                <td>{{ $armada->kapasitas }} Ton</td>
                                <td>{{ $armada->sopir->nama ?? 'Belum Ada' }}</td>
                                <td class="text-center">
                                    @if($armada->status == 'tersedia')
                                        <span class="badge badge-success">Tersedia</span>
                                    @elseif($armada->status == 'tidak_tersedia')
                                        <span class="badge badge-warning">Disewa</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($armada->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($armada->deskripsi, 50) ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('armada.show', $armada->id) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Detail Armada">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
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
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Belum ada armada yang terdaftar di parkiran ini.
                </div>
            @endif

            <!-- Tombol Kembali -->
            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{ route('parkir.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Parkir
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection

@section('scripts')
<script>
    var currentPage = 1;
    var rowsPerPage = 10;

    function filterTable() {
        var searchValue = document.getElementById('searchInput') ? document.getElementById('searchInput').value.toUpperCase() : '';
        var table = document.getElementById('dataTable');
        if (!table) return;
        var tr = table.getElementsByTagName('tr');
        
        var matchingRows = [];

        for (var i = 1; i < tr.length; i++) {
            var row = tr[i];
            
            // Lewati jika baris kosong
            var cells = row.getElementsByTagName('td');
            if (cells.length < 5) continue; 

            var textMatch = false;

            // Filter pencarian
            for (var j = 0; j < cells.length; j++) {
                if (cells[j]) {
                    var cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toUpperCase().indexOf(searchValue) > -1) {
                        textMatch = true;
                        break;
                    }
                }
            }

            if (textMatch) {
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
        var infoEl = document.getElementById('paginationInfo');
        if (infoEl) {
            infoEl.innerText = "Menampilkan " + infoStart + " sampai " + infoEnd + " dari " + totalRows + " data";
        }

        // Render tombol paginasi
        var controlsHtml = '';
        
        // Previous
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

        // Next
        if (currentPage === totalPages) {
            controlsHtml += '<li class="paginate_button page-item next disabled"><a href="#" class="page-link">Next</a></li>';
        } else {
            controlsHtml += '<li class="paginate_button page-item next"><a href="#" class="page-link" onclick="changePage(' + (currentPage + 1) + '); return false;">Next</a></li>';
        }

        var controlsEl = document.getElementById('paginationControls');
        if (controlsEl) {
            controlsEl.innerHTML = controlsHtml;
        }
    }

    function changePage(page) {
        currentPage = page;
        filterTable();
    }

    document.addEventListener('DOMContentLoaded', function() {
        filterTable();

        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            currentPage = 1;
            filterTable();
        });
    });
</script>
@endsection