@extends('layouts.masterDashboard')

@section('content_dashboard')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Penugasan</h1>
        </div>

        <!-- Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Penugasan Saya</h6>
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

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="12%">Kode Keranjang</th>
                                <th width="15%">Armada</th>
                                <th width="15%">Tanggal Mulai</th>
                                <th width="12%">Estimasi Hari</th>
                                <th width="20%">Rute</th>
                                <th width="12%" class="text-center">Pembayaran</th>
                                <th width="12%" class="text-center">Status</th>
                                <th class="text-center" width="18%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penugasans as $key => $penugasan)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $penugasan->kode_keranjang ?? '-' }}</span>
                                    </td>
                                    <td>{{ $penugasan->armada->no_polisi ?? '-' }}</td>
                                    <td>{{ $penugasan->tanggal_mulai ? \Carbon\Carbon::parse($penugasan->tanggal_mulai)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td>{{ $penugasan->estimasi_hari ?? '-' }}</td>
                                    <td>
                                        <small>
                                            <strong>Jemput:</strong> {{ Str::limit($penugasan->rute->tempat_jemput ?? $penugasan->tempat_jemput, 30) }}<br>
                                            <strong>Antar:</strong> {{ Str::limit($penugasan->rute->tempat_antar ?? $penugasan->tempat_antar, 30) }}
                                        </small>
                                    </td>
                                    <!-- Kolom Pembayaran -->
                                    <td class="text-center">
                                        @if($penugasan->penyewaan && $penugasan->penyewaan->pembayaran)
                                            <span class="badge badge-light border text-dark">{{ ucfirst($penugasan->penyewaan->pembayaran->jenis) }}</span>
                                            <br>
                                            <small class="p-1 font-weight-bold text-{{ $penugasan->penyewaan->pembayaran->status == 'lunas' ? 'success' : 'primary' }}">
                                                {{ str_replace('_', ' ', ucfirst($penugasan->penyewaan->pembayaran->status)) }}
                                            </small>
                                        @else
                                            <span class="badge badge-secondary font-weight-normal small">Belum Diproses</span>
                                        @endif
                                    </td>

                                    <!-- Kolom Status Penugasan -->
                                    <td class="text-center">
                                        @if($penugasan->status == 'pending')
                                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                        @elseif($penugasan->status == 'aktif')
                                            <span class="badge badge-success"><i class="fas fa-play-circle"></i> Aktif</span>
                                        @elseif($penugasan->status == 'revisi_bukti')
                                            <span class="badge badge-danger"><i class="fas fa-exclamation-circle"></i> Revisi
                                                Bukti</span>
                                        @elseif($penugasan->status == 'selesai')
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Selesai</span>
                                        @elseif($penugasan->status == 'menunggu_konfirmasi_selesai')
                                            <span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Menunggu
                                                Validasi</span>
                                        @else
                                            <span class="badge badge-info">{{ ucfirst($penugasan->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <!-- Tombol Detail -->
                                        <a href="{{ route('penugasan.show', $penugasan->id) }}" class="btn btn-info btn-sm"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>

                                        <!-- Tombol Cetak (Hanya jika pembayaran 50% / menunggu pelunasan) -->
                                        @if($penugasan->penyewaan && $penugasan->penyewaan->pembayaran && $penugasan->penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                        <a href="{{ route('penugasan.invoice', $penugasan->id) }}" class="btn btn-secondary btn-sm"
                                            title="Cetak Invoice Penagihan">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                        @endif

                                        <!-- Tombol Upload (jika belum selesai) -->
                                        @if(in_array($penugasan->status, ['aktif', 'revisi_bukti']))
                                            <button type="button"
                                                class="btn btn-{{ $penugasan->status == 'revisi_bukti' ? 'danger' : 'success' }} btn-sm btn-upload"
                                                data-id="{{ $penugasan->id }}" title="Upload Bukti Selesai">
                                                <i class="fas fa-upload"></i>
                                                {{ $penugasan->status == 'revisi_bukti' ? 'Upload Ulang' : 'Upload' }}
                                            </button>
                                        @elseif($penugasan->status == 'menunggu_konfirmasi_selesai')
                                            <span class="badge badge-warning p-2 d-inline-block mt-1">
                                                <i class="fas fa-hourglass-half"></i> Validasi
                                            </span>
                                        @elseif($penugasan->status == 'selesai')
                                            <span class="badge badge-success p-2 d-inline-block mt-1">
                                                <i class="fas fa-check-circle"></i> Selesai
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada penugasan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Upload Bukti - DIPERBESAR -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="uploadForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-upload"></i> Upload Bukti Selesai Penugasan
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Info Alert -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Informasi:</strong> Upload foto bukti pengiriman/pekerjaan telah selesai
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold h5 mb-3">
                                <i class="fas fa-image"></i> Bukti Selesai <span class="text-danger">*</span>
                            </label>

                            <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="bukti_selesai" name="bukti_selesai"
                                    accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)" required>
                                <label class="custom-file-label" for="bukti_selesai">Pilih foto bukti selesai...</label>
                            </div>

                            <small class="form-text text-muted">
                                <i class="fas fa-check-circle text-success"></i> Format: JPG, JPEG, PNG<br>
                                <i class="fas fa-check-circle text-success"></i> Ukuran maksimal: 2MB<br>
                                <i class="fas fa-check-circle text-success"></i> Pastikan foto jelas dan terbaca
                            </small>
                        </div>

                        <!-- Preview Image Container -->
                        <div id="imagePreview" class="mt-4" style="display: none;">
                            <div class="text-center">
                                <p class="font-weight-bold h5 mb-3">
                                    <i class="fas fa-eye"></i> Preview Foto:
                                </p>
                                <div class="border rounded p-2 bg-light">
                                    <img id="preview" src="" alt="Preview" class="img-fluid rounded shadow"
                                        style="max-height: 400px; width: auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi pencarian
        document.getElementById('searchInput').addEventListener('keyup', function () {
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

        // PERBAIKAN: Handle upload button click - Gunakan jQuery
        $(document).ready(function () {
            // Handle upload button click
            $('.btn-upload').on('click', function () {
                const penugasanId = $(this).data('id');
                const uploadForm = $('#uploadForm');
                uploadForm.attr('action', '/dashboard/penugasan/' + penugasanId + '/upload-bukti');

                console.log('Button clicked, Penugasan ID:', penugasanId); // Debug
                console.log('Form action set to:', uploadForm.attr('action')); // Debug

                $('#uploadModal').modal('show');
            });

            // Loading saat submit
            $('#uploadForm').on('submit', function () {
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Mengupload...');
            });
        });

        // Preview image
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            const imagePreview = document.getElementById('imagePreview');
            const label = event.target.nextElementSibling;

            if (file) {
                // Validasi ukuran (2MB)
                if (file.size > 2048000) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal 2MB',
                    });
                    event.target.value = '';
                    imagePreview.style.display = 'none';
                    label.textContent = 'Pilih foto...';
                    return;
                }

                // Validasi tipe file
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format File Tidak Valid',
                        text: 'Hanya file JPG, JPEG, dan PNG yang diperbolehkan',
                    });
                    event.target.value = '';
                    imagePreview.style.display = 'none';
                    label.textContent = 'Pilih foto...';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
                label.textContent = file.name;
            } else {
                imagePreview.style.display = 'none';
                label.textContent = 'Pilih foto...';
            }
        }
    </script>

    @if($errors->any())
        <script>
            $(document).ready(function () {
                $('#uploadModal').modal('show');
            });
        </script>
    @endif
@endsection