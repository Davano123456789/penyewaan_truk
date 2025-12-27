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
                    <div class="dataTables_info">
                        Menampilkan 1 sampai 6 dari 42 data
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
<script>
    // Simple search functionality
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

    // Filter by rating
    document.getElementById('filterRating').addEventListener('change', function() {
        var filter = this.value;
        var table = document.getElementById('dataTable');
        var tr = table.getElementsByTagName('tr');

        for (var i = 1; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName('td')[3]; // Kolom rating
            if (td) {
                var badge = td.querySelector('.badge');
                if (badge) {
                    var rating = badge.textContent.trim();
                    if (filter === '' || rating.startsWith(filter)) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }
    });

    // Filter by client
    document.getElementById('filterClient').addEventListener('change', function() {
        var filter = this.value;
        var table = document.getElementById('dataTable');
        var tr = table.getElementsByTagName('tr');

        for (var i = 1; i < tr.length; i++) {
            if (filter === '') {
                tr[i].style.display = '';
            } else {
                // Implement filter logic based on client data-id
                tr[i].style.display = '';
            }
        }
    });
</script>
@endsection