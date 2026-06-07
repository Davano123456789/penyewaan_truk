@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pembayaran</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Pembayaran</h6>
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

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="15%">Kode Transaksi</th>
                            <th>Client</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th class="text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayarans as $index => $p)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><span class="badge badge-light border">{{ $p->penyewaan->kode_transaksi ?? $p->penyewaan_id }}</span></td>
                                <td>
                                    @if($p->penyewaan && $p->penyewaan->client)
                                        {{ $p->penyewaan->client->nama ?? $p->penyewaan->client->email }}
                                    @else
                                        <span class="text-danger font-italic"><i class="fas fa-exclamation-circle text-xs"></i> 
                                            {{ !$p->penyewaan ? 'Data Transaksi Hilang' : 'User Dihapus' }}
                                        </span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($p->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $p->metode }}</td>
                                <td>{{ $p->jenis }}</td>
                                <td>
                                    @if($p->status == 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($p->status == 'menunggu_konfirmasi')
                                        <span class="badge badge-info text-white">Menunggu Konfirmasi</span>
                                    @elseif($p->status == 'menunggu_pelunasan')
                                        <span class="badge badge-warning text-dark">Menunggu Pelunasan</span>
                                    @elseif($p->status == 'menunggu_konfirmasi_pelunasan')
                                        <span class="badge badge-info text-white">Menunggu Konfirmasi Pelunasan</span>
                                    @elseif($p->status == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $p->status }}</span>
                                    @endif
                                </td>
                                <td>{{ optional($p->tanggal_bayar)->format('Y-m-d') ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('pembayaranAdmin.show', $p->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">Belum ada data pembayaran</td>
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
