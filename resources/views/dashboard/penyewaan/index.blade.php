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
                        <option value="dibayar">Dibayar</option>
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
                            <th width="12%">Jumlah Item</th>
                            <th class="text-center" width="23%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penyewaans as $index => $penyewaan)
                        <tr data-status="{{ $penyewaan->status }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $penyewaan->created_at->format('d M Y H:i') }}</td>
                            <td><strong>Rp {{ number_format($penyewaan->harga_total_aktif, 0, ',', '.') }}</strong></td>
                            
                            <!-- Status Pesanan -->
                            <td>
                                @if($penyewaan->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($penyewaan->status == 'menunggu_pembayaran')
                                    <span class="badge badge-info">Menunggu Pembayaran</span>
                                @elseif($penyewaan->status == 'menunggu_konfirmasi')
                                    <span class="badge badge-primary">Menunggu Konfirmasi</span>
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
                                    @if($penyewaan->status == 'menunggu_konfirmasi')
                                        <span class="badge badge-secondary">-</span>
                                    @elseif($penyewaan->pembayaran->status == 'lunas')
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                    @elseif($penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Pelunasan</span>
                                    @elseif($penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan')
                                        <span class="badge badge-danger"><i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi Pelunasan</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                            
                            <td class="text-center">
                                <span class="badge badge-info">
                                    {{ $penyewaan->keranjangs_count }} Item
                                </span>
                                <a href="{{ route('penyewaan.keranjang', $penyewaan->id) }}" 
                                   class="btn btn-sm btn-primary ml-2" 
                                   title="Lihat Daftar Item">
                                    <i class="fas fa-list"></i> Lihat
                                </a>
                            </td>
                            
                            <td class="text-center">
    @if($penyewaan->status == 'menunggu_pembayaran')
        <!-- Tombol Bayar (Upload Bukti) -->
        <a href="{{ route('pembayaran.show', $penyewaan->id) }}" 
           class="btn btn-success btn-sm" 
           title="Bayar">
            <i class="fas fa-credit-card"></i> Bayar
        </a>
        
    @elseif($penyewaan->status == 'menunggu_konfirmasi')
        <span class="badge badge-info p-2">
            <i class="fas fa-clock"></i> Menunggu Konfirmasi Admin
        </span>
    @elseif($penyewaan->status == 'aktif' && $penyewaan->pembayaran && $penyewaan->pembayaran->jenis == 'talangan' && $penyewaan->pembayaran->status == 'menunggu_pelunasan')
        <!-- Tombol Bayar Sisa (untuk Talangan) -->
        <a href="{{ route('pembayaran.show', $penyewaan->id) }}" 
           class="btn btn-warning btn-sm" 
           title="Bayar Sisa">
            <i class="fas fa-money-bill-wave"></i> Bayar Sisa
        </a>
    @elseif($penyewaan->status == 'dibayar')
        <span class="badge badge-success p-2">
            <i class="fas fa-check-circle"></i> Dibayar
        </span>
    @else
        <span class="badge badge-secondary p-2">
            <i class="fas fa-info-circle"></i> {{ ucfirst($penyewaan->status) }}
        </span>
    @endif
</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data penyewaan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
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

    
    // Fungsi pencarian
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

    // Filter Status
    document.getElementById('filterStatus').addEventListener('change', function() {
        var filter = this.value;
        var table = document.getElementById('dataTable');
        var tr = table.getElementsByTagName('tr');

        for (var i = 1; i < tr.length; i++) {
            if (filter === '' || tr[i].getAttribute('data-status') === filter) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error') }}",
        confirmButtonColor: '#ef4444'
    });
</script>
@endif
@endsection