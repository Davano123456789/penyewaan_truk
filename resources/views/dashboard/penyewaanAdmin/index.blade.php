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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Konfirmasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Penyewaan::where('status', 'menunggu_konfirmasi')->count() }}
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
            <form method="GET" action="{{ route('penyewaanAdmin.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Cari ID Pesanan, Nama, atau Email Customer..." 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="menunggu_pembayaran" {{ request('status') == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                            <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        @if(request('search') || request('status'))
                        <a href="{{ route('penyewaanAdmin.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-redo"></i> Reset Filter
                        </a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th width="15%">Customer</th>
                            <th width="12%">Tanggal</th>
                            <th width="15%">Total Harga</th>
                            <th width="12%">Status</th>
                            <th width="12%">Status Pembayaran</th>
                            <th class="text-center" width="25%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penyewaans as $index => $penyewaan)
                        <tr>
                            <td class="text-center">{{ $penyewaans->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $penyewaan->client ? $penyewaan->client->nama : 'User Tidak Ditemukan' }}</strong><br>
                            </td>
                            <td>{{ $penyewaan->created_at->format('d M Y H:i') }}</td>
                            <td><strong class="text-primary">Rp {{ number_format($penyewaan->harga_total, 0, ',', '.') }}</strong></td>
                            <td>
                                @if($penyewaan->status == 'pending')
                                    <span class="badge badge-secondary">Pending</span>
                                @elseif($penyewaan->status == 'menunggu_pembayaran')
                                    <span class="badge badge-info">Menunggu Pembayaran</span>
                                @elseif($penyewaan->status == 'menunggu_konfirmasi')
                                    <span class="badge badge-warning">Menunggu Konfirmasi</span>
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
                                    @elseif($penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                        <span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu Pelunasan</span>
                                    @elseif($penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan')
                                        <span class="badge badge-danger"><i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi Pelunasan</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($penyewaan->pembayaran->status) }}</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <!-- Tombol Detail -->
                                <a href="{{ route('penyewaanAdmin.show', $penyewaan->id) }}" 
                                   class="btn btn-info btn-sm" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('penyewaanAdmin.destroy', $penyewaan->id) }}" 
                                      method="POST" 
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus Penyewaan">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>

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
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $penyewaans->firstItem() ?? 0 }} sampai {{ $penyewaans->lastItem() ?? 0 }} 
                    dari {{ $penyewaans->total() }} data
                </div>
                <div>
                    {{ $penyewaans->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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