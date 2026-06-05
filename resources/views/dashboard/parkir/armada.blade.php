@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Armada - {{ $parkir->nama }}</h1>
    </div>

  

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Armada
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $parkir->armadas->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Armada Tersedia
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $parkir->armadas->where('status', 'tersedia')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Armada Disewa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $parkir->armadas->where('status', 'tidak_tersedia')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ban fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Armada -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Daftar Armada
            </h6>
        </div>
        <div class="card-body">
            @if($parkir->armadas->count() > 0)
                <!-- Search Bar -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari armada..." id="searchInput">
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
                                <th width="12%">No Polisi</th>
                                <th width="15%">Merek</th>
                                <th width="12%">Jenis</th>
                                <th width="10%">Kapasitas</th>
                                <th width="15%">Sopir</th>
                                <th class="text-center" width="10%">Status</th>
                                <th width="15%">Deskripsi</th>
                                <th class="text-center" width="11%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parkir->armadas as $index => $armada)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $armada->no_polisi }}</strong></td>
                                <td>{{ $armada->merek }}</td>
                                <td>{{ $armada->jenis }}</td>
                                <td>{{ $armada->kapasitas }} Ton</td>
                                <td>{{ $armada->sopir->nama ?? 'Belum Ada' }}</td>
                                <td class="text-center">
                                    @if($armada->status == 'tersedia')
                                        <span class="badge badge-success">Tersedia</span>
                                    @elseif($armada->status == 'tidak_tersedia')
                                        <span class="badge badge-warning">Disewa</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($armada->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($armada->deskripsi, 50) ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('armada.show', $armada->id) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Detail Armada">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Belum ada armada yang terdaftar di parkiran ini.
                </div>
            @endif

            <!-- Tombol Kembali -->
            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{ route('parkir.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Parkir
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection

@section('scripts')
<script>
    // Fungsi pencarian
    document.getElementById('searchInput')?.addEventListener('keyup', function() {
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
</script>
@endsection