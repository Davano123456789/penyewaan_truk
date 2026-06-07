@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Penyewaan</h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Konfirmasi Pembayaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Penyewaan::where('status', 'menunggu_konfirmasi_pembayaran')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Penyewaan::where('status', 'aktif')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Menunggu Pembayaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Penyewaan::where('status', 'menunggu_pembayaran')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Penyewaan</h6>
        </div>
        <div class="card-body">
            <!-- Filter & Search -->
            <div class="mb-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" 
                                   id="searchInput" 
                                   class="form-control" 
                                   placeholder="Cari ID Pesanan, Nama, atau Email Customer...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="filterStatus" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="PENDING">Pending</option>
                            <option value="MENUNGGU PEMBAYARAN">Menunggu Pembayaran</option>
                            <option value="MENUNGGU KONFIRMASI PEMBAYARAN">Menunggu Konfirmasi Pembayaran</option>
                            <option value="AKTIF">Aktif</option>
                            <option value="MENUNGGU PELUNASAN">Menunggu Pelunasan</option>
                            <option value="MENUNGGU KONFIRMASI PELUNASAN">Menunggu Konfirmasi Pelunasan</option>
                            <option value="SELESAI">Selesai</option>
                            <option value="DIBATALKAN">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="btnResetFilter" class="btn btn-secondary btn-block">
                            <i class="fas fa-redo"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" style="min-width: 1000px;" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="min-width: 50px;">No</th>
                            <th style="min-width: 140px;">Kode Transaksi</th>
                            <th style="min-width: 140px;">Customer</th>
                            <th style="min-width: 130px;">Tanggal</th>
                            <th style="min-width: 130px;">Total Harga</th>
                            <th style="min-width: 120px;">Status</th>
                            <th style="min-width: 150px;">Status Pembayaran</th>
                            <th class="text-center" style="min-width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penyewaans as $index => $penyewaan)
                        <tr>
                             <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                {{ $penyewaan->kode_transaksi ?? '-' }}
                            </td>
                            <td>
                                <strong>{{ $penyewaan->client ? $penyewaan->client->nama : 'User Tidak Ditemukan' }}</strong>
                            </td>
                            <td>{{ $penyewaan->created_at->format('d M Y H:i') }}</td>
                            <td><strong class="text-primary">Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($penyewaan->status == 'pending')
                                    <span class="badge badge-secondary">Pending</span>
                                @elseif($penyewaan->status == 'menunggu_pembayaran')
                                    <span class="badge badge-info">Menunggu Pembayaran</span>
                                @elseif($penyewaan->status == 'menunggu_konfirmasi_pembayaran')
                                    <span class="badge badge-warning">Menunggu Konfirmasi Pembayaran</span>
                                @elseif($penyewaan->status == 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($penyewaan->status == 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($penyewaan->status == 'dibatalkan')
                                    <span class="badge badge-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                @if($penyewaan->pembayaran)
                                    @if($penyewaan->pembayaran->status == 'lunas')
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                    @elseif($penyewaan->pembayaran->status == 'menunggu_konfirmasi')
                                        <span class="badge badge-info"><i class="fas fa-hourglass-start"></i> Menunggu Konfirmasi</span>
                                    @elseif($penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Pelunasan</span>
                                    @elseif($penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan')
                                        <span class="badge badge-info"><i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi Pelunasan</span>
                                    @elseif($penyewaan->pembayaran->status == 'ditolak')
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($penyewaan->pembayaran->status) }}</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center flex-nowrap" style="gap: 4px;">
                                    <!-- Tombol Detail -->
                                    <a href="{{ route('penyewaanAdmin.show', $penyewaan->id) }}" 
                                       class="btn btn-info btn-sm text-nowrap" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('penyewaanAdmin.destroy', $penyewaan->id) }}" 
                                          method="POST" 
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete text-nowrap" title="Hapus Penyewaan">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Belum ada data penyewaan
                            </td>
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var currentPage = 1;
    var rowsPerPage = 10;

    function filterTable() {
        var searchValue = document.getElementById('searchInput').value.toUpperCase();
        var statusValue = document.getElementById('filterStatus').value.toUpperCase();
        var table = document.getElementById('dataTable');
        var tr = table.getElementsByTagName('tr');
        
        var matchingRows = [];

        for (var i = 1; i < tr.length; i++) {
            var row = tr[i];
            
            // Lewati jika baris kosong
            var cells = row.getElementsByTagName('td');
            if (cells.length < 5) continue; 

            var textMatch = false;
            var statusMatch = false;

            // 1. Filter pencarian
            for (var j = 0; j < cells.length; j++) {
                if (cells[j]) {
                    var cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toUpperCase().indexOf(searchValue) > -1) {
                        textMatch = true;
                        break;
                    }
                }
            }

            // 2. Filter Status (kolom ke-6, index 5)
            var statusCell = cells[5];
            if (statusCell) {
                var statusText = (statusCell.textContent || statusCell.innerText).trim().toUpperCase();
                if (statusValue === '' || statusText === statusValue) {
                    statusMatch = true;
                }
            } else {
                statusMatch = (statusValue === '');
            }

            if (textMatch && statusMatch) {
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

    document.getElementById('searchInput').addEventListener('keyup', function() {
        currentPage = 1;
        filterTable();
    });

    document.getElementById('filterStatus').addEventListener('change', function() {
        currentPage = 1;
        filterTable();
    });

    document.getElementById('btnResetFilter').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterStatus').value = '';
        currentPage = 1;
        filterTable();
    });

    // Jalankan filter saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        filterTable();
    });

    // SweetAlert Konfirmasi Pembayaran
    document.querySelectorAll('.btn-konfirmasi').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.konfirmasi-form');

            Swal.fire({
                title: 'Konfirmasi Pembayaran?',
                text: "Pastikan bukti transfer sudah sesuai! Penyewaan akan menjadi AKTIF.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Konfirmasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // SweetAlert Hapus Penyewaan
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.delete-form');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Penyewaan beserta semua data terkaitnya akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times"></i> Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection