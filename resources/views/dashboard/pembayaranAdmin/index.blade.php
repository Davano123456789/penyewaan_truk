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
                                <td class="text-center">{{ $index + $pembayarans->firstItem() }}</td>
                                <td><span class="badge badge-light border">{{ $p->penyewaan->kode_transaksi ?? $p->penyewaan_id }}</span></td>
                                <td>
                                    @if($p->penyewaan->client)
                                        {{ $p->penyewaan->client->nama ?? $p->penyewaan->client->email }}
                                    @else
                                        <span class="text-danger font-italic"><i class="fas fa-exclamation-circle text-xs"></i> User Dihapus</span>
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

            <div class="row mt-3">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info">
                        Menampilkan {{ $pembayarans->firstItem() ?? 0 }} sampai {{ $pembayarans->lastItem() ?? 0 }} dari {{ $pembayarans->total() ?? 0 }} data
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate float-right">
                        {{ $pembayarans->withQueryString()->links() }}
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
    // Pencarian sederhana
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
