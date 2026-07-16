@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Greeting Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-3">
                    <h5 class="mb-0 text-gray-800">Selamat Datang {{ Auth::user()->peran_id == 4 ? 'Owner' : 'Admin' }}</h5>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->peran_id == 4)
    <!-- Layout Khusus Owner -->
    <div class="row">
        <!-- Total Seluruh Penyewaan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Seluruh Penyewaan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPenyewaan ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Armada Disewa -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Armada Disewa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalArmadaDisewa ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck-loading fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Armada Tersedia -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Armada Tersedia
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalArmadaTersedia ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pemasukan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pemasukan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($filteredOmset ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Laporan Keuangan -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary text-white rounded p-3 mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-filter fa-lg"></i>
                </div>
                <div>
                    <h5 class="m-0 font-weight-bold text-gray-800">Filter Laporan Keuangan</h5>
                    <small class="text-muted">Data akan tersaring secara otomatis saat filter diubah</small>
                </div>
            </div>
            <form action="{{ route('dashboard') }}" method="GET" id="filterForm">
                <div class="form-row">
                    <!-- Tanggal Mulai -->
                    <div class="col-md-2 mb-3">
                        <label class="font-weight-bold text-xs text-primary text-uppercase" for="start_date">
                            <i class="fas fa-calendar-alt mr-1"></i> Tanggal Mulai
                        </label>
                        <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                    </div>
                    <!-- Tanggal Selesai -->
                    <div class="col-md-2 mb-3">
                        <label class="font-weight-bold text-xs text-primary text-uppercase" for="end_date">
                            <i class="fas fa-calendar-alt mr-1"></i> Tanggal Selesai
                        </label>
                        <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                    </div>
                    <!-- Jenis Truk -->
                    <div class="col-md-2 mb-3">
                        <label class="font-weight-bold text-xs text-primary text-uppercase" for="jenis_truk">
                            <i class="fas fa-truck mr-1"></i> Jenis Truk
                        </label>
                        <select class="form-control" name="jenis_truk" id="jenis_truk">
                            <option value="">Semua Truk</option>
                            @foreach ($jenisTrukList as $jt)
                                <option value="{{ $jt }}" {{ request('jenis_truk') == $jt ? 'selected' : '' }}>
                                    {{ ucfirst($jt) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Status Bayar -->
                    <div class="col-md-3 mb-3">
                        <label class="font-weight-bold text-xs text-primary text-uppercase" for="status_pembayaran">
                            <i class="fas fa-wallet mr-1"></i> Status Bayar
                        </label>
                        <select class="form-control" name="status_pembayaran" id="status_pembayaran">
                            <option value="">Semua Status</option>
                            <option value="lunas" {{ request('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="menunggu_konfirmasi" {{ request('status_pembayaran') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="menunggu_pelunasan" {{ request('status_pembayaran') == 'menunggu_pelunasan' ? 'selected' : '' }}>Menunggu Pelunasan</option>
                            <option value="menunggu_konfirmasi_pelunasan" {{ request('status_pembayaran') == 'menunggu_konfirmasi_pelunasan' ? 'selected' : '' }}>Menunggu Konfirmasi Pelunasan</option>
                            <option value="ditolak" {{ request('status_pembayaran') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <!-- Metode -->
                    <div class="col-md-3 mb-3">
                        <label class="font-weight-bold text-xs text-primary text-uppercase" for="jenis_pembayaran">
                            <i class="fas fa-credit-card mr-1"></i> Metode
                        </label>
                        <select class="form-control" name="jenis_pembayaran" id="jenis_pembayaran">
                            <option value="">Semua Metode</option>
                            <option value="tunai" {{ request('jenis_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="talangan" {{ request('jenis_pembayaran') == 'talangan' ? 'selected' : '' }}>Talangan</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Rincian Keuangan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Rincian Keuangan</h6>
        </div>
        <div class="card-body">
            <!-- Search Bar -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari rincian keuangan..." id="searchInput">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableOwner" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="20%">Kode Transaksi</th>
                            <th width="15%">Tanggal</th>
                            <th>Client</th>
                            <th width="15%">Metode Bayar</th>
                            <th width="20%">Status Bayar</th>
                            <th class="text-right" width="15%">Jumlah Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanPenyewaans as $idx => $penyewaan)
                            <tr>
                                <td class="text-center">{{ $idx + 1 }}</td>
                                <td><span class="badge badge-light border">{{ $penyewaan->kode_transaksi }}</span></td>
                                <td>{{ $penyewaan->created_at->format('d M Y') }}</td>
                                <td>{{ $penyewaan->client->nama ?? $penyewaan->client->email }}</td>
                                <td>
                                    @if($penyewaan->pembayaran)
                                        {{ ucfirst($penyewaan->pembayaran->jenis) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($penyewaan->pembayaran)
                                        @if($penyewaan->pembayaran->status == 'lunas')
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Lunas</span>
                                        @elseif($penyewaan->pembayaran->status == 'menunggu_konfirmasi')
                                            <span class="badge badge-info px-2 py-1"><i class="fas fa-hourglass-start mr-1"></i> Menunggu Konfirmasi</span>
                                        @elseif($penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                            <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Menunggu Pelunasan</span>
                                        @elseif($penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan')
                                            <span class="badge badge-info px-2 py-1"><i class="fas fa-hourglass-half mr-1"></i> Menunggu Konfirmasi Pelunasan</span>
                                        @elseif($penyewaan->pembayaran->status == 'ditolak')
                                            <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">{{ $penyewaan->pembayaran->status }}</span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">Belum Bayar</span>
                                    @endif
                                </td>
                                 <td class="text-right font-weight-bold">
                                     @php
                                         $totalRefund = 0;
                                         foreach ($penyewaan->keranjangs as $item) {
                                             if ($item->status === 'dibatalkan' && $item->pembatalan) {
                                                 $totalRefund += (float)$item->pembatalan->nominal_refund;
                                             }
                                         }
                                         $jumlahBayar = (float)($penyewaan->pembayaran->jumlah_bayar ?? $penyewaan->harga_total ?? 0);
                                         $netBayar = max(0.0, $jumlahBayar - $totalRefund);
                                     @endphp
                                     @if($totalRefund > 0)
                                         <span class="text-xs text-muted d-block" style="text-decoration: line-through;">
                                             Rp {{ number_format($jumlahBayar, 0, ',', '.') }}
                                         </span>
                                         <span class="text-xs text-danger d-block">
                                             Refund: Rp -{{ number_format($totalRefund, 0, ',', '.') }}
                                         </span>
                                         <span class="text-success d-block">
                                             Rp {{ number_format($netBayar, 0, ',', '.') }}
                                         </span>
                                     @else
                                         <span class="text-primary">
                                             Rp {{ number_format($jumlahBayar, 0, ',', '.') }}
                                         </span>
                                     @endif
                                 </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada rincian keuangan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Laporan -->
            <div class="row mt-3">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" id="paginationInfoOwner">
                        Menampilkan 0 sampai 0 dari 0 data
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate float-right">
                        <ul class="pagination" id="paginationControlsOwner">
                            <!-- Pagination buttons dynamically rendered via JS -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Pemasukan Bulanan -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-1"></i> Grafik Pemasukan Bulanan (Tahun {{ date('Y') }})
                    </h6>
                </div>
                <div class="card-body">
                    <div style="height: 320px; position: relative;">
                        <canvas id="myAreaChartOwner"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Layout Asli Admin (3 Kolom per Baris tanpa Statistik Baru) -->
    <!-- Statistics Cards Row 1 -->
    <div class="row">
        <!-- Total Penyewaan Aktif -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penyewaan Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAktif ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penyewaan Menunggu Pembayaran -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Penyewaan menunggu pembayaran
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMenungguPembayaran ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penyewaan Menunggu Konfirmasi -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Penyewaan Menunggu Konfirmasi Pembayaran
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMenungguKonfirmasi ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards Row 2 -->
    <div class="row">
        <!-- Total Armada Tersedia -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Armada tersedia
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalArmadaTersedia ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Armada Disewa -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Armada disewa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalArmadaDisewa ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck-loading fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penyewaan Bulan Ini -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Total Penyewaan Bulan ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPenyewaanBulanIni ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
<!-- /.container-fluid -->
@endsection

@section('scripts')
<script>
    // Chart Configuration
    Chart.defaults.global.defaultFontFamily = 'Nunito, -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    @if(Auth::user()->peran_id == 4)
    // Auto-submit filter form on change
    $(document).ready(function() {
        $('#filterForm input, #filterForm select').on('change', function() {
            $('#filterForm').submit();
        });
    });

    // Pagination and search for Owner table
    var currentPageOwner = 1;
    var rowsPerPageOwner = 10;

    function filterTableOwner() {
        var searchValue = document.getElementById('searchInput').value.toUpperCase();
        var table = document.getElementById('dataTableOwner');
        var tr = table.getElementsByTagName('tr');
        
        var matchingRows = [];

        for (var i = 1; i < tr.length; i++) {
            var row = tr[i];
            
            // Skip if row doesn't have enough columns (e.g. empty message)
            var cells = row.getElementsByTagName('td');
            if (cells.length < 7) continue; 

            var textMatch = false;

            // Search filter
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

        // Pagination calculations
        var totalRows = matchingRows.length;
        var totalPages = Math.ceil(totalRows / rowsPerPageOwner);
        if (totalPages < 1) totalPages = 1;
        if (currentPageOwner > totalPages) currentPageOwner = totalPages;

        var startIdx = (currentPageOwner - 1) * rowsPerPageOwner;
        var endIdx = startIdx + rowsPerPageOwner;

        for (var k = 0; k < totalRows; k++) {
            if (k >= startIdx && k < endIdx) {
                matchingRows[k].style.display = '';
            } else {
                matchingRows[k].style.display = 'none';
            }
        }

        // Update pagination info text
        var infoStart = totalRows > 0 ? startIdx + 1 : 0;
        var infoEnd = endIdx > totalRows ? totalRows : endIdx;
        document.getElementById('paginationInfoOwner').innerText = "Menampilkan " + infoStart + " sampai " + infoEnd + " dari " + totalRows + " data";

        // Render pagination buttons
        var controlsHtml = '';
        
        // Previous Button
        if (currentPageOwner === 1) {
            controlsHtml += '<li class="paginate_button page-item previous disabled"><a href="#" class="page-link">Previous</a></li>';
        } else {
            controlsHtml += '<li class="paginate_button page-item previous"><a href="#" class="page-link" id="prevPageBtnOwner">Previous</a></li>';
        }

        // Page Numbers
        for (var p = 1; p <= totalPages; p++) {
            if (p === currentPageOwner) {
                controlsHtml += '<li class="paginate_button page-item active"><a href="#" class="page-link">' + p + '</a></li>';
            } else {
                controlsHtml += '<li class="paginate_button page-item"><a href="#" class="page-link page-num-btn-owner" data-page="' + p + '">' + p + '</a></li>';
            }
        }

        // Next Button
        if (currentPageOwner === totalPages) {
            controlsHtml += '<li class="paginate_button page-item next disabled"><a href="#" class="page-link">Next</a></li>';
        } else {
            controlsHtml += '<li class="paginate_button page-item next"><a href="#" class="page-link" id="nextPageBtnOwner">Next</a></li>';
        }

        document.getElementById('paginationControlsOwner').innerHTML = controlsHtml;
    }

    $(document).ready(function() {
        filterTableOwner();

        document.getElementById('searchInput').addEventListener('keyup', function() {
            currentPageOwner = 1;
            filterTableOwner();
        });

        // Event delegation for pagination buttons
        $(document).on('click', '.page-num-btn-owner', function(e) {
            e.preventDefault();
            currentPageOwner = parseInt($(this).attr('data-page'));
            filterTableOwner();
        });

        $(document).on('click', '#prevPageBtnOwner', function(e) {
            e.preventDefault();
            currentPageOwner--;
            filterTableOwner();
        });

        $(document).on('click', '#nextPageBtnOwner', function(e) {
            e.preventDefault();
            currentPageOwner++;
            filterTableOwner();
        });

        // Area Chart - Monthly Income
        var ctx = document.getElementById("myAreaChartOwner");
        if (ctx) {
            var myLineChart = new Chart(ctx, {
              type: 'line',
              data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
                datasets: [{
                  label: "Pemasukan Bersih",
                  lineTension: 0.3,
                  backgroundColor: "rgba(78, 115, 223, 0.05)",
                  borderColor: "rgba(78, 115, 223, 1)",
                  pointRadius: 3,
                  pointBackgroundColor: "rgba(78, 115, 223, 1)",
                  pointBorderColor: "rgba(78, 115, 223, 1)",
                  pointHoverRadius: 3,
                  pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                  pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                  pointHitRadius: 10,
                  pointBorderWidth: 2,
                  data: @json($monthlyIncomeData),
                }],
              },
              options: {
                maintainAspectRatio: false,
                layout: {
                  padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                  }
                },
                scales: {
                  xAxes: [{
                    gridLines: {
                      display: false,
                      drawBorder: false
                    },
                    ticks: {
                      maxTicksLimit: 12
                    }
                  }],
                  yAxes: [{
                    ticks: {
                      maxTicksLimit: 5,
                      padding: 10,
                      callback: function(value, index, values) {
                        return 'Rp ' + number_format(value);
                      }
                    },
                    gridLines: {
                      color: "rgb(234, 236, 244)",
                      zeroLineColor: "rgb(234, 236, 244)",
                      drawBorder: false,
                      borderDash: [2],
                      zeroLineBorderDash: [2]
                    }
                  }],
                },
                legend: {
                  display: false
                },
                tooltips: {
                  backgroundColor: "rgb(255,255,255)",
                  bodyFontColor: "#858796",
                  titleMarginBottom: 10,
                  titleFontColor: '#6e707e',
                  titleFontSize: 14,
                  borderColor: '#dddfeb',
                  borderWidth: 1,
                  xPadding: 15,
                  yPadding: 15,
                  displayColors: false,
                  intersect: false,
                  mode: 'index',
                  caretPadding: 10,
                  callbacks: {
                    label: function(tooltipItem, chart) {
                      var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                      return datasetLabel + ': Rp ' + number_format(tooltipItem.yLabel);
                    }
                  }
                }
              }
            });
        }
    });
    @endif
</script>
@endsection
