@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Pembayaran Saya</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pembayaran</h6>
        </div>
        <div class="card-body">
            <!-- Search Bar -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari pembayaran..." id="searchInput">
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
                         
                            <th>Jumlah Bayar</th>
                            <th>Metode</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayarans as $index => $p)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                             
                                <td>Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                                <td>
                                    @if($p->metode == 'transfer_bca') Bank BCA
                                    @elseif($p->metode == 'transfer_mandiri') Bank Mandiri
                                    @elseif($p->metode == 'transfer_bri') Bank BRI
                                    @elseif($p->metode == 'transfer_bni') Bank BNI
                                    @else {{ $p->metode }}
                                    @endif
                                </td>
                                <td>
                                     @if($p->jenis == 'tunai')
                                         <span class="badge badge-success">Tunai</span>
                                     @else
                                         <span class="badge badge-warning">Talangan</span>
                                     @endif
                                </td>
                                <td>
                                    @if($p->status == 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($p->status == 'menunggu_konfirmasi')
                                        <span class="badge badge-info">Menunggu Konfirmasi</span>
                                    @elseif($p->status == 'menunggu_pelunasan')
                                        <span class="badge badge-warning">Menunggu Pelunasan</span>
                                    @elseif($p->status == 'menunggu_konfirmasi_pelunasan')
                                        <span class="badge badge-info">Menunggu Konfirmasi Pelunasan</span>
                                    @elseif($p->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $p->status }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d M Y') }}</td>
                                <td class="text-center">
                                     <a href="{{ route('pembayaran.detail', $p->id) }}" class="btn btn-sm btn-primary" title="Lihat Detail">
                                         <i class="fas fa-eye"></i>
                                     </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada riwayat pembayaran</td>
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
<script>
    var currentPage = 1;
    var rowsPerPage = 10;

    function filterTable() {
        var searchValue = document.getElementById('searchInput').value.toUpperCase();
        var table = document.getElementById('dataTable');
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
        document.getElementById('paginationInfo').innerText = "Menampilkan " + infoStart + " sampai " + infoEnd + " dari " + totalRows + " data";

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

        document.getElementById('paginationControls').innerHTML = controlsHtml;
    }

    function changePage(page) {
        currentPage = page;
        filterTable();
    }

    document.addEventListener('DOMContentLoaded', function() {
        filterTable();

        document.getElementById('searchInput').addEventListener('keyup', function() {
            currentPage = 1;
            filterTable();
        });
    });
</script>
@endsection
