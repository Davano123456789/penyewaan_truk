@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Masukan</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Masukan & Review Client</h6>
            <a href="" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Masukan
            </a>
        </div>
        <div class="card-body">
            <!-- Search Bar & Filter -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari masukan..." id="searchInput">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="filterRating">
                        <option value="">Semua Rating</option>
                        <option value="5">⭐⭐⭐⭐⭐ (5 Bintang)</option>
                        <option value="4">⭐⭐⭐⭐ (4 Bintang)</option>
                        <option value="3">⭐⭐⭐ (3 Bintang)</option>
                        <option value="2">⭐⭐ (2 Bintang)</option>
                        <option value="1">⭐ (1 Bintang)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="filterClient">
                        <option value="">Semua Client</option>
                        <option value="1">PT. Maju Jaya</option>
                        <option value="2">CV. Sukses Makmur</option>
                        <option value="3">PT. Karya Abadi</option>
                        <option value="4">UD. Berkah Jaya</option>
                        <option value="5">PT. Tekstil Indonesia</option>
                    </select>
                </div>
            </div>

          

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="15%">Client</th>
                            <th width="12%">Penyewaan ID</th>
                            <th width="10%">Rating</th>
                            <th width="35%">Deskripsi</th>
                            <th width="10%">Tanggal</th>
                            <th class="text-center" width="13%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data 1 -->
                        <tr>
                            <td class="text-center">1</td>
                            <td>
                                <strong>PT. Maju Jaya</strong><br>
                                <small class="text-muted">admin@majujaya.com</small>
                            </td>
                            <td>PSW-2025-0012</td>
                            <td>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span class="badge badge-success ml-1">5.0</span>
                                </div>
                            </td>
                            <td>Pelayanan sangat memuaskan! Driver profesional dan armada dalam kondisi sangat baik. Pengiriman tepat waktu.</td>
                            <td>12 Okt 2025</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus masukan ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 2 -->
                        <tr>
                            <td class="text-center">2</td>
                            <td>
                                <strong>CV. Sukses Makmur</strong><br>
                                <small class="text-muted">info@suksesmakmur.com</small>
                            </td>
                            <td>PSW-2025-0015</td>
                            <td>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <span class="badge badge-primary ml-1">4.0</span>
                                </div>
                            </td>
                            <td>Secara keseluruhan bagus. Hanya saja komunikasi bisa diperbaiki lagi untuk informasi real-time tracking.</td>
                            <td>10 Okt 2025</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus masukan ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 3 -->
                        <tr>
                            <td class="text-center">3</td>
                            <td>
                                <strong>PT. Karya Abadi</strong><br>
                                <small class="text-muted">contact@karyaabadi.com</small>
                            </td>
                            <td>PSW-2025-0018</td>
                            <td>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span class="badge badge-success ml-1">5.0</span>
                                </div>
                            </td>
                            <td>Luar biasa! Armada besar yang kami butuhkan tersedia dan kondisi prima. Harga juga kompetitif. Recommended!</td>
                            <td>08 Okt 2025</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus masukan ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 4 -->
                        <tr>
                            <td class="text-center">4</td>
                            <td>
                                <strong>UD. Berkah Jaya</strong><br>
                                <small class="text-muted">berkah@gmail.com</small>
                            </td>
                            <td>PSW-2025-0020</td>
                            <td>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="badge badge-primary ml-1">4.5</span>
                                </div>
                            </td>
                            <td>Pelayanan ramah dan cepat. Proses booking mudah. Akan menggunakan jasa ini lagi untuk kebutuhan selanjutnya.</td>
                            <td>06 Okt 2025</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus masukan ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 5 -->
                        <tr>
                            <td class="text-center">5</td>
                            <td>
                                <strong>PT. Tekstil Indonesia</strong><br>
                                <small class="text-muted">cs@tekstilindonesia.com</small>
                            </td>
                            <td>PSW-2025-0022</td>
                            <td>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <span class="badge badge-warning ml-1">3.0</span>
                                </div>
                            </td>
                            <td>Cukup baik, namun ada sedikit keterlambatan dalam pengiriman. Perlu peningkatan dalam ketepatan waktu.</td>
                            <td>05 Okt 2025</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus masukan ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 6 -->
                        <tr>
                            <td class="text-center">6</td>
                            <td>
                                <strong>PT. Global Logistik</strong><br>
                                <small class="text-muted">global@logistik.id</small>
                            </td>
                            <td>PSW-2025-0025</td>
                            <td>
                                <div class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span class="badge badge-success ml-1">5.0</span>
                                </div>
                            </td>
                            <td>Sangat puas dengan layanan! Driver berpengalaman, armada terawat dengan baik. Pengiriman aman dan tepat waktu. Terima kasih!</td>
                            <td>03 Okt 2025</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus masukan ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
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

    // Filter, Search, dan Paginasi Real-time
    function filterTable() {
        var searchValue = document.getElementById('searchInput').value.toUpperCase();
        var ratingValue = document.getElementById('filterRating').value.toUpperCase();
        var clientValue = document.getElementById('filterClient').value;
        var table = document.getElementById('dataTable');
        var tr = table.getElementsByTagName('tr');
        
        var matchingRows = [];

        for (var i = 1; i < tr.length; i++) {
            var row = tr[i];
            
            // Lewati jika baris kosong
            var cells = row.getElementsByTagName('td');
            if (cells.length < 5) continue; 

            var textMatch = false;
            var ratingMatch = false;
            var clientMatch = false;

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

            // 2. Filter berdasarkan rating (kolom ke-4, index 3)
            var ratingCell = cells[3];
            if (ratingCell) {
                var badge = ratingCell.querySelector('.badge');
                if (badge) {
                    var ratingText = badge.textContent.trim();
                    if (ratingValue === '' || ratingText.startsWith(ratingValue)) {
                        ratingMatch = true;
                    }
                } else {
                    ratingMatch = (ratingValue === '');
                }
            } else {
                ratingMatch = (ratingValue === '');
            }

            // 3. Filter berdasarkan client (kolom ke-2, index 1)
            var clientCell = cells[1];
            if (clientCell) {
                var clientText = (clientCell.textContent || clientCell.innerText).trim().toUpperCase();
                
                // Mapping dummy filter values to actual text strings for mock data
                var clientNameFilter = '';
                if (clientValue === '1') clientNameFilter = 'PT. MAJU JAYA';
                else if (clientValue === '2') clientNameFilter = 'CV. SUKSES MAKMUR';
                else if (clientValue === '3') clientNameFilter = 'PT. KARYA ABADI';
                else if (clientValue === '4') clientNameFilter = 'UD. BERKAH JAYA';
                else if (clientValue === '5') clientNameFilter = 'PT. TEKSTIL INDONESIA';

                if (clientValue === '' || clientText.indexOf(clientNameFilter) > -1) {
                    clientMatch = true;
                }
            } else {
                clientMatch = (clientValue === '');
            }

            if (textMatch && ratingMatch && clientMatch) {
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

    document.getElementById('filterRating').addEventListener('change', function() {
        currentPage = 1;
        filterTable();
    });

    document.getElementById('filterClient').addEventListener('change', function() {
        currentPage = 1;
        filterTable();
    });

    // Jalankan filter/paginasi pertama kali saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        filterTable();
    });
</script>
@endsection