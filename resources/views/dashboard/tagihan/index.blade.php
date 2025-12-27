@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Tagihan</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Tagihan Penyewaan</h6>
            <a href="" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Tagihan
            </a>
        </div>
        <div class="card-body">
            <!-- Search Bar & Filter -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari nomor tagihan..." id="searchInput">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="belum-lunas">Belum Lunas</option>
                        <option value="lunas">Lunas</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="filterMetode">
                        <option value="">Semua Metode Pembayaran</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="cash">Cash</option>
                        <option value="e-wallet">E-Wallet</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="12%">No. Tagihan</th>
                            <th width="15%">Penyewaan ID</th>
                            <th width="15%">Total Tagihan</th>
                            <th width="12%">Metode Pembayaran</th>
                            <th width="12%">Email Terkirim</th>
                            <th class="text-center" width="12%">Status</th>
                            <th class="text-center" width="17%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data 1 -->
                        <tr>
                            <td class="text-center">1</td>
                            <td><strong>INV-2025-001</strong></td>
                            <td>PSW-2025-0012</td>
                            <td><strong class="text-success">Rp 15.500.000</strong></td>
                            <td>
                                <span class="badge badge-info">Transfer Bank</span>
                            </td>
                            <td>
                                <i class="fas fa-check-circle text-success"></i> Ya
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">Lunas</span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-success btn-sm" title="Cetak Invoice">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="#" class="btn btn-primary btn-sm" title="Kirim Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 2 -->
                        <tr>
                            <td class="text-center">2</td>
                            <td><strong>INV-2025-002</strong></td>
                            <td>PSW-2025-0015</td>
                            <td><strong class="text-success">Rp 8.750.000</strong></td>
                            <td>
                                <span class="badge badge-secondary">Cash</span>
                            </td>
                            <td>
                                <i class="fas fa-times-circle text-danger"></i> Tidak
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning">Belum Lunas</span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-primary btn-sm" title="Kirim Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 3 -->
                        <tr>
                            <td class="text-center">3</td>
                            <td><strong>INV-2025-003</strong></td>
                            <td>PSW-2025-0018</td>
                            <td><strong class="text-success">Rp 12.200.000</strong></td>
                            <td>
                                <span class="badge badge-primary">E-Wallet</span>
                            </td>
                            <td>
                                <i class="fas fa-check-circle text-success"></i> Ya
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">Lunas</span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-success btn-sm" title="Cetak Invoice">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="#" class="btn btn-primary btn-sm" title="Kirim Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 4 -->
                        <tr>
                            <td class="text-center">4</td>
                            <td><strong>INV-2025-004</strong></td>
                            <td>PSW-2025-0020</td>
                            <td><strong class="text-success">Rp 9.500.000</strong></td>
                            <td>
                                <span class="badge badge-info">Transfer Bank</span>
                            </td>
                            <td>
                                <i class="fas fa-check-circle text-success"></i> Ya
                            </td>
                            <td class="text-center">
                                <span class="badge badge-danger">Overdue</span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" title="Kirim Reminder">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 5 -->
                        <tr>
                            <td class="text-center">5</td>
                            <td><strong>INV-2025-005</strong></td>
                            <td>PSW-2025-0022</td>
                            <td><strong class="text-success">Rp 18.900.000</strong></td>
                            <td>
                                <span class="badge badge-info">Transfer Bank</span>
                            </td>
                            <td>
                                <i class="fas fa-times-circle text-danger"></i> Tidak
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning">Belum Lunas</span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-primary btn-sm" title="Kirim Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 6 -->
                        <tr>
                            <td class="text-center">6</td>
                            <td><strong>INV-2025-006</strong></td>
                            <td>PSW-2025-0025</td>
                            <td><strong class="text-success">Rp 6.800.000</strong></td>
                            <td>
                                <span class="badge badge-secondary">Cash</span>
                            </td>
                            <td>
                                <i class="fas fa-check-circle text-success"></i> Ya
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success">Lunas</span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-success btn-sm" title="Cetak Invoice">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="#" class="btn btn-primary btn-sm" title="Kirim Email">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Data 7 -->
                        <tr>
                            <td class="text-center">7</td>
                            <td><strong>INV-2025-007</strong></td>
                            <td>PSW-2025-0028</td>
                            <td><strong class="text-success">Rp 11.300.000</strong></td>
                            <td>
                                <span class="badge badge-primary">E-Wallet</span>
                            </td>
                            <td>
                                <i class="fas fa-times-circle text-danger"></i> Tidak
                            </td>
                            <td class="text-center">
                                <span class="badge badge-warning">Belum Lunas</span>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-primary btn-sm" title="Kirim Email">
                                    <i class="fas fa-envelope"></i>
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
                        Menampilkan 1 sampai 7 dari 7 data
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

    // Filter by status
    document.getElementById('filterStatus').addEventListener('change', function() {
        var filter = this.value.toUpperCase();
        var table = document.getElementById('dataTable');
        var tr = table.getElementsByTagName('tr');

        for (var i = 1; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName('td')[6]; // Kolom status
            if (td) {
                var txtValue = td.textContent || td.innerText;
                if (filter === '' || txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }
    });

    // Filter by metode pembayaran
    document.getElementById('filterMetode').addEventListener('change', function() {
        var filter = this.value.toUpperCase();
        var table = document.getElementById('dataTable');
        var tr = table.getElementsByTagName('tr');

        for (var i = 1; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName('td')[4]; // Kolom metode pembayaran
            if (td) {
                var txtValue = td.textContent || td.innerText;
                if (filter === '' || txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }
    });
</script>
@endsection