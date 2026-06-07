@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola & Validasi Penugasan Sopir</h1>
    </div>



    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Penugasan Sopir</h6>
        </div>
        <div class="card-body">
            <!-- Search Bar -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari penugasan..." id="searchInput">
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
                    <thead>
                        <tr>
                            <th>Kode Keranjang</th>
                            <th>Sopir</th>
                            <th>Armada</th>
                            <th>Bukti Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penugasans as $p)
                            <tr>
                                <td>
                                    <span class="badge badge-info">{{ $p->kode_keranjang ?? '-' }}</span>
                                </td>
                                <td>{{ $p->sopir->nama ?? 'Sopir Tidak Ada' }}</td>
                                <td>{{ $p->armada->merek ?? 'Armada Dihapus' }} ({{ $p->armada->no_polisi ?? '-' }})</td>
                                <td class="text-center">
                                    @if(($p->penugasan->bukti_selesai ?? $p->bukti_selesai))
                                        <a href="{{ $p->penugasan->bukti_selesai ?? $p->bukti_selesai }}" target="_blank">
                                            <img src="{{ $p->penugasan->bukti_selesai ?? $p->bukti_selesai }}" style="width: 100px; height: 100px; object-fit: cover;" class="rounded border shadow-sm">
                                        </a>
                                        <br>
                                        <small class="text-primary mt-1 d-block" style="cursor: pointer;" data-toggle="modal" data-target="#modalBukti{{ $p->id }}">
                                            <i class="fas fa-search-plus"></i> Perbesar
                                        </small>
                                    @else
                                        <span class="badge badge-secondary">Tidak ada bukti</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($p->status == 'aktif')
                                        <span class="badge badge-success"><i class="fas fa-play-circle"></i> Sedang Berjalan</span>
                                    @elseif($p->status == 'revisi_bukti')
                                        <span class="badge badge-danger"><i class="fas fa-exclamation-circle"></i> Revisi Bukti</span>
                                    @elseif($p->status == 'selesai')
                                        <span class="badge badge-primary"><i class="fas fa-check-circle"></i> Selesai</span>
                                    @elseif($p->status == 'menunggu_konfirmasi_selesai')
                                        <span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Menunggu Validasi</span>
                                    @else
                                        <span class="badge badge-info">{{ ucfirst($p->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status == 'menunggu_konfirmasi_selesai')
                                        <button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#modalVerifikasi{{ $p->id }}">
                                            <i class="fas fa-shield-alt"></i> Verifikasi
                                        </button>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
 
                            <!-- Modal Verifikasi -->
                            <div class="modal fade" id="modalVerifikasi{{ $p->id }}" tabindex="-1" role="dialog" aria-labelledby="modalVerifikasiLabel{{ $p->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="modalVerifikasiLabel{{ $p->id }}">Verifikasi Penugasan Selesai</h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
 
                                            <div class="form-group">
                                                <label class="font-weight-bold text-dark">Keputusan Admin <span class="text-danger">*</span></label>
                                                <div class="d-flex border rounded p-3 bg-light">
                                                    <div class="custom-control custom-radio mr-4">
                                                        <input type="radio" id="verifApprove{{ $p->id }}" name="decision_{{ $p->id }}" value="approve" class="custom-control-input decision-radio" data-id="{{ $p->id }}" required>
                                                        <label class="custom-control-label font-weight-bold text-success" for="verifApprove{{ $p->id }}">Setujui</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="verifReject{{ $p->id }}" name="decision_{{ $p->id }}" value="reject" class="custom-control-input decision-radio" data-id="{{ $p->id }}" required>
                                                        <label class="custom-control-label font-weight-bold text-danger" for="verifReject{{ $p->id }}">Tolak</label>
                                                    </div>
                                                </div>
                                            </div>
 
                                            <!-- Section Setuju -->
                                            <div id="sectionApprove{{ $p->id }}" class="verification-section" style="display: none;">
                                                <div class="alert alert-success d-flex align-items-center mb-0">
                                                    <i class="fas fa-check-circle fa-2x mr-3"></i>
                                                    <div>Penugasan akan dinyatakan <strong>SELESAI</strong>.</div>
                                                </div>
                                            </div>
 
                                            <!-- Section Tolak -->
                                            <div id="sectionReject{{ $p->id }}" class="verification-section" style="display: none;">
                                                <div class="form-group mb-0">
                                                    <label class="font-weight-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                    <textarea id="catatan_verifikasi{{ $p->id }}" class="form-control" rows="3" placeholder="Contoh: Foto tidak jelas, silakan upload ulang..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="button" id="btnSubmitVerifikasi{{ $p->id }}" class="btn btn-primary btn-submit-verif" data-id="{{ $p->id }}" disabled>
                                                <i class="fas fa-paper-plane"></i> Proses
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
 
                            <!-- Hidden Forms -->
                            <form id="formApprove{{ $p->id }}" action="{{ route('penugasanAdmin.validasi', $p->id) }}" method="POST" style="display: none;">@csrf</form>
                            <form id="formReject{{ $p->id }}" action="{{ route('penugasanAdmin.tolak', $p->id) }}" method="POST" style="display: none;">@csrf <input type="hidden" name="alasan" id="hiddenAlasan{{ $p->id }}"></form>
 
                            <!-- Modal Perbesar Bukti Tetap Ada -->
                            <div class="modal fade" id="modalBukti{{ $p->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Bukti Selesai Penugasan #{{ $p->kode_keranjang ?? optional($p->penyewaan)->kode_transaksi ?? '-' }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ $p->penugasan->bukti_selesai ?? $p->bukti_selesai }}" class="img-fluid rounded shadow">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-clipboard-check fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 mb-0">Belum ada data penugasan.</p>
                                </td>
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
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Handle Verification Modal Decision
    document.querySelectorAll('.decision-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const decision = this.value;
            const submitBtn = document.getElementById('btnSubmitVerifikasi' + id);
            
            document.getElementById('sectionApprove' + id).style.display = 'none';
            document.getElementById('sectionReject' + id).style.display = 'none';
            
            if (decision === 'approve') {
                document.getElementById('sectionApprove' + id).style.display = 'block';
            } else {
                document.getElementById('sectionReject' + id).style.display = 'block';
            }
            
            submitBtn.disabled = false;
        });
    });

    // Handle Modal Submit
    document.querySelectorAll('.btn-submit-verif').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const decision = document.querySelector(`input[name="decision_${id}"]:checked`).value;
            const catatan = document.getElementById('catatan_verifikasi' + id).value;
            
            if (decision === 'approve') {
                document.getElementById('formApprove' + id).submit();
            } else {
                if (!catatan.trim()) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Alasan penolakan wajib diisi!',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                document.getElementById('hiddenAlasan' + id).value = catatan;
                document.getElementById('formReject' + id).submit();
            }
        });
    });

    var currentPage = 1;
    var rowsPerPage = 10;

    function filterTable() {
        var searchValue = document.getElementById('searchInput').value.toUpperCase();
        var table = document.getElementById('dataTable');
        var tr = table.getElementsByTagName('tr');
        
        var matchingRows = [];

        for (var i = 1; i < tr.length; i++) {
            var row = tr[i];
            
            // Lewati jika baris kosong atau modal
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

    document.addEventListener('DOMContentLoaded', function() {
        filterTable();

        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            currentPage = 1;
            filterTable();
        });
    });
</script>
@endsection
