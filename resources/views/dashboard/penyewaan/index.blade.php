@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Penyewaan</h1>
        <a href="{{ route('pemesanan') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Buat Pesanan Baru
        </a>
    </div>

    <!-- Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Penyewaan</h6>
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
                <div class="col-md-6 text-right">
                    <select class="form-control w-auto d-inline-block" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                        <option value="menunggu_konfirmasi_pembayaran">Menunggu Konfirmasi Pembayaran</option>
                        <option value="aktif">Aktif</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Total Harga</th>
                            <th width="15%">Status Pesanan</th>
                            <th width="15%">Status Pembayaran</th>
                             <th class="text-center" width="10%">Unit</th>
                            <th class="text-center" width="23%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penyewaans as $index => $penyewaan)
                        <tr data-status="{{ $penyewaan->status }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $penyewaan->created_at->format('d M Y') }}</td>
                            <td><strong>Rp {{ number_format($penyewaan->harga_total_aktif, 0, ',', '.') }}</strong></td>
                            
                            <!-- Status Pesanan -->
                            <td>
                                @if($penyewaan->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($penyewaan->status == 'menunggu_pembayaran')
                                    <span class="badge badge-info">Menunggu Pembayaran</span>
                                @elseif($penyewaan->status == 'menunggu_konfirmasi_pembayaran')
                                    <span class="badge badge-primary">Menunggu Konfirmasi Pembayaran</span>
                                @elseif($penyewaan->status == 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($penyewaan->status == 'selesai')
                                    <span class="badge badge-dark">Selesai</span>
                                @elseif($penyewaan->status == 'dibatalkan')
                                    <span class="badge badge-danger">Dibatalkan</span>
                                @endif
                            </td>
                            
                            <!-- Status Pembayaran -->
                            <td>
                                @if($penyewaan->pembayaran)
                                    @if($penyewaan->pembayaran->status == 'menunggu_konfirmasi')
                                        <span class="badge badge-info"><i class="fas fa-hourglass-start"></i> Menunggu Konfirmasi</span>
                                    @elseif($penyewaan->pembayaran->status == 'lunas')
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                    @elseif($penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Pelunasan</span>
                                    @elseif($penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan')
                                        <span class="badge badge-info"><i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi Pelunasan</span>
                                    @elseif($penyewaan->pembayaran->status == 'ditolak')
                                        <span class="badge badge-danger"><i class="fas fa-times-circle"></i> Pembayaran Ditolak</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $penyewaan->pembayaran->status }}</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                                                        <td class="text-center">
                                 <strong>{{ $penyewaan->keranjangs_count }}</strong> Item
                             </td>
                            
                             <td class="text-center">
                                 <a href="{{ route('penyewaan.keranjang', $penyewaan->id) }}" 
                                    class="btn btn-info btn-sm" 
                                    title="Lihat Detail Pesanan">
                                     <i class="fas fa-eye"></i> Detail
                                 </a>

                                 @if($penyewaan->status == 'menunggu_pembayaran')
                                     <a href="{{ route('pembayaran.show', $penyewaan->id) }}" 
                                        class="btn btn-success btn-sm" 
                                        title="Bayar">
                                         <i class="fas fa-credit-card"></i> Bayar
                                     </a>
                                     
                                 @elseif($penyewaan->status == 'menunggu_konfirmasi_pembayaran')
                                     <!-- Lanjutkan Menunggu -->
                                 @elseif(in_array($penyewaan->status, ['aktif', 'selesai']) && $penyewaan->pembayaran && $penyewaan->pembayaran->jenis == 'talangan' && $penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                     <a href="{{ route('pembayaran.show', $penyewaan->id) }}" 
                                        class="btn btn-warning btn-sm" 
                                        title="Bayar Sisa">
                                         <i class="fas fa-money-bill-wave"></i> Bayar Sisa
                                     </a>
                                 @elseif($penyewaan->pembayaran && $penyewaan->pembayaran->status == 'ditolak')
                                     <a href="{{ route('pembayaran.show', $penyewaan->id) }}" 
                                        class="btn btn-danger btn-sm" 
                                        title="Bayar Ulang">
                                         <i class="fas fa-sync-alt"></i> Bayar Ulang
                                     </a>
                                 @endif
                             </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data penyewaan</td>
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
<script>
    var currentPage = 1;
    var rowsPerPage = 10;

    // Filter, Search, dan Paginasi Real-time
    function filterTable() {
        var searchValue = document.getElementById('searchInput').value.toUpperCase();
        var statusValue = document.getElementById('filterStatus').value;
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

            // 2. Filter berdasarkan status pesanan (menggunakan attribute data-status pada tr)
            var rowStatus = row.getAttribute('data-status');
            if (statusValue === '' || rowStatus === statusValue) {
                statusMatch = true;
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

    document.getElementById('filterStatus').addEventListener('change', function() {
        currentPage = 1;
        filterTable();
    });

    // Jalankan filter/paginasi pertama kali saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        filterTable();
    });

    // SweetAlert Konfirmasi Bayar
    document.querySelectorAll('.btn-bayar').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.bayar-form');

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Lanjutkan ke proses pembayaran?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection