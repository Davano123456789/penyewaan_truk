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
                    <table class="table table-bordered table-hover" id="dataTable" style="min-width:900px;" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="min-width:50px;">No</th>
                                <th style="min-width:140px;">Kode Keranjang</th>
                                <th style="min-width:140px;">Armada</th>
                                <th style="min-width:130px;">Tanggal Mulai</th>
                                <th style="min-width:110px;">Estimasi Hari</th>
                                <th style="min-width:200px;">Rute</th>
                                <th style="min-width:120px;" class="text-center">Pembayaran</th>
                                <th style="min-width:110px;" class="text-center">Status</th>
                                <th class="text-center" style="min-width:300px;">Aksi</th>
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
                                             @if($penugasan->penyewaan->pembayaran->status == 'lunas')
                                                 <span class="badge badge-success">Lunas</span>
                                             @elseif($penugasan->penyewaan->pembayaran->status == 'menunggu_konfirmasi')
                                                 <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                             @elseif($penugasan->penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                                 <span class="badge badge-info">Menunggu Pelunasan</span>
                                             @elseif($penugasan->penyewaan->pembayaran->status == 'menunggu_konfirmasi_pelunasan')
                                                 <span class="badge badge-primary">Menunggu Konfirmasi Pelunasan</span>
                                             @elseif($penugasan->penyewaan->pembayaran->status == 'ditolak')
                                                 <span class="badge badge-danger">Ditolak</span>
                                             @else
                                                 <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $penugasan->penyewaan->pembayaran->status)) }}</span>
                                             @endif
                                         @else
                                             <span class="badge badge-secondary">Belum Diproses</span>
                                         @endif
                                     </td>

                                    <!-- Kolom Status Penugasan -->
                                    <td class="text-center">
                                        @if($penugasan->status == 'pending')
                                            <span class="badge badge-warning"><i class="fas fa-clock"></i> Pending</span>
                                        @elseif($penugasan->status == 'aktif')
                                            <span class="badge badge-success"><i class="fas fa-play-circle"></i> Aktif</span>
                                        @elseif($penugasan->status == 'truk_sampai')
                                            <span class="badge badge-info"><i class="fas fa-map-marker-alt"></i> Truk Sampai</span>
                                        @elseif($penugasan->status == 'revisi_bukti')
                                            <span class="badge badge-danger"><i class="fas fa-exclamation-circle"></i> Revisi Bukti</span>
                                        @elseif($penugasan->status == 'selesai')
                                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> Selesai</span>
                                        @elseif($penugasan->status == 'menunggu_konfirmasi_selesai')
                                            <span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Menunggu Validasi</span>
                                        @else
                                            <span class="badge badge-info">{{ ucfirst($penugasan->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-nowrap justify-content-center" style="gap:6px;">

                                            {{-- Tombol Detail --}}
                                            <a href="{{ route('penugasan.show', $penugasan->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                                Detail
                                            </a>

                                            {{-- Tombol Cetak (Hanya jika menunggu pelunasan) --}}
                                            @if($penugasan->penyewaan && $penugasan->penyewaan->pembayaran && $penugasan->penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                            <a href="{{ route('penugasan.invoice', $penugasan->id) }}" class="btn btn-secondary btn-sm" title="Cetak Invoice Penagihan">
                                                Cetak
                                            </a>
                                            @endif

                                            {{-- Tombol Upload (hanya jika bisa diupload) --}}
                                            @if(in_array($penugasan->status, ['truk_sampai', 'revisi_bukti']))
                                                @if($penugasan->penyewaan && $penugasan->penyewaan->pembayaran && $penugasan->penyewaan->pembayaran->status == 'menunggu_pelunasan')
                                                    <span class="badge badge-danger text-wrap" style="max-width: 150px;">
                                                        <i class="fas fa-money-bill-wave"></i> Selesaikan Pelunasan Dahulu
                                                    </span>
                                                @else
                                                    <button type="button"
                                                        class="btn btn-{{ $penugasan->status == 'revisi_bukti' ? 'danger' : 'success' }} btn-sm btn-upload"
                                                        data-id="{{ $penugasan->id }}" 
                                                        data-status="{{ $penugasan->status }}"
                                                        data-alasan="{{ $penugasan->penugasan->catatan_penugasan ?? $penugasan->catatan_penugasan ?? '' }}"
                                                        title="Upload Bukti Selesai">
                                                        {{ $penugasan->status == 'revisi_bukti' ? 'Upload Ulang' : 'Upload' }}
                                                    </button>
                                                @endif
                                            @elseif($penugasan->status == 'aktif')
                                                <span class="badge badge-secondary text-wrap" style="max-width: 150px;">
                                                    <i class="fas fa-hourglass-half"></i> Menunggu Klien Konfirmasi
                                                </span>
                                            @endif

                                        </div>
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
                        <!-- Alasan Penolakan dari Admin (Hanya tampil jika status revisi_bukti) -->
                        <div id="rejectionReasonContainer" class="alert alert-danger border-left-danger shadow-sm mb-4" style="display: none;">
                            <h6 class="font-weight-bold">Alasan Penolakan dari Admin:</h6>
                            <p id="rejectionReasonText" class="mb-0 font-italic"></p>
                        </div>

                        <div class="form-group">
 
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
        var currentPage = 1;
        var rowsPerPage = 10;

        function filterTable() {
            var searchValue = document.getElementById('searchInput').value.toUpperCase();
            var table = document.getElementById('dataTable');
            var tr = table.getElementsByTagName('tr');
            
            var matchingRows = [];

            for (var i = 1; i < tr.length; i++) {
                var row = tr[i];
                
                // Lewati jika baris kosong
                var cells = row.getElementsByTagName('td');
                if (cells.length < 5) continue; 

                var textMatch = false;

                // Filter pencarian
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
            
            // Previous
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

            // Next
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

        $(document).ready(function () {
            // Run initial filter
            filterTable();

            document.getElementById('searchInput').addEventListener('keyup', function() {
                currentPage = 1;
                filterTable();
            });

            // Handle upload button click
            $('.btn-upload').on('click', function () {
                const id = $(this).data('id');
                const status = $(this).data('status');
                const alasan = $(this).data('alasan');

                $('#uploadForm').attr('action', `/dashboard/penugasan/${id}/upload-bukti`);

                if (status === 'revisi_bukti') {
                    $('#rejectionReasonText').text('"' + alasan + '"');
                    $('#rejectionReasonContainer').show();
                } else {
                    $('#rejectionReasonContainer').hide();
                }

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