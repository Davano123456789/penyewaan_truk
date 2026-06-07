@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Permintaan Pembatalan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan Pembatalan</h6>
        </div>
        <div class="card-body">
            <!-- Search Bar -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari pengajuan..." id="searchInput">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Client</th>
                            <th>Armada</th>

                            <th>Harga Sewa</th>
                            <th>Estimasi Refund</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keranjangs as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->penyewaan?->client?->nama ?? '-' }}</strong><br>
                                <small>{{ $item->penyewaan?->client?->email ?? '-' }}</small>
                            </td>
                            <td>
                                {{ $item->armada->no_polisi ?? '-' }}<br>
                                <small>{{ $item->armada->merek ?? '-' }}</small>
                            </td>

                            <td>Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $denda = $item->harga_sewa * 0.3;
                                    $refund = 0;
                                    if($item->penyewaan && $item->penyewaan->pembayaran) {
                                        $bayar = 0;
                                        if($item->penyewaan->pembayaran->jenis == 'tunai' && $item->penyewaan->pembayaran->status == 'lunas') {
                                            $bayar = $item->harga_sewa;
                                        } elseif($item->penyewaan->pembayaran->jenis == 'talangan') {
                                            $bayar = $item->harga_sewa / 2;
                                        }
                                        $refund = max(0, $bayar - $denda);
                                    }
                                @endphp
                                <strong class="text-success">Rp {{ number_format($refund, 0, ',', '.') }}</strong>
                                <br>
                                <small class="text-danger">Potongan 30%: Rp {{ number_format($denda, 0, ',', '.') }}</small>
                            </td>
                            <td>
                                @if($item->status == 'menunggu_konfirmasi_batal')
                                    <span class="badge badge-warning">Menunggu Konfirmasi</span>
                                @elseif($item->status == 'dibatalkan')
                                    <span class="badge badge-success">Disetujui</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalDetail{{ $item->id }}" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <!-- Modal Detail & Action -->
                                <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('penyewaanAdmin.prosesPembatalan', $item->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="nominal_refund" value="{{ $refund }}">
                                                
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalDetailLabel{{ $item->id }}">
                                                        Detail Pembatalan 
                                                        @if($item->status == 'dibatalkan')
                                                            <span class="badge badge-success">Disetujui</span>
                                                        @endif
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Alasan Pembatalan (User)</label>
                                                        <textarea class="form-control" rows="3" readonly>{{ $item->pembatalan->alasan_batal }}</textarea>
                                                    </div>

                                                    <hr>
                                                    
                                                    <div class="alert alert-info">
                                                        <strong>Rincian Refund:</strong><br>
                                                        Harga Sewa: Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}<br>
                                                        Potongan (30%): Rp {{ number_format($denda, 0, ',', '.') }}<br>
                                                        <strong>Total Refund: Rp {{ number_format($refund, 0, ',', '.') }}</strong>
                                                    </div>
                                                    
                                                    @if($item->status == 'menunggu_konfirmasi_batal')
                                                        <div class="form-group">
                                                            <label class="font-weight-bold text-dark">Keputusan Admin <span class="text-danger">*</span></label>
                                                            <div class="d-flex border rounded p-3 bg-light">
                                                                <div class="custom-control custom-radio mr-4">
                                                                    <input type="radio" id="radioApprove{{ $item->id }}" name="action" value="approve" class="custom-control-input action-selector" data-id="{{ $item->id }}" required>
                                                                    <label class="custom-control-label font-weight-bold text-success" for="radioApprove{{ $item->id }}">Setujui</label>
                                                                </div>
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" id="radioReject{{ $item->id }}" name="action" value="reject" class="custom-control-input action-selector" data-id="{{ $item->id }}" required>
                                                                    <label class="custom-control-label font-weight-bold text-danger" for="radioReject{{ $item->id }}">Tolak</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Bagian Setuju: Refund -->
                                                        <div id="sectionApprove{{ $item->id }}" class="action-section" style="display: none;">
                                                            @if($refund > 0)
                                                            <div class="form-group mt-3">
                                                                <label class="font-weight-bold">Upload Bukti Transfer Refund <span class="text-danger">*</span></label>
                                                                <div class="custom-file">
                                                                    <input type="file" name="bukti_refund" class="custom-file-input input-refund-{{ $item->id }}" id="bukti_refund{{ $item->id }}" accept="image/*">
                                                                    <label class="custom-file-label" for="bukti_refund{{ $item->id }}">Pilih gambar...</label>
                                                                </div>
                                                                <small class="text-muted">Bukti transfer dana ke client sebesar Rp {{ number_format($refund, 0, ',', '.') }}</small>
                                                            </div>
                                                            @else
                                                            <div class="alert alert-warning mt-3">
                                                                <i class="fas fa-exclamation-circle"></i> Tidak ada dana yang perlu dikembalikan untuk item ini.
                                                            </div>
                                                            @endif
                                                        </div>

                                                        <!-- Bagian Tolak: Catatan -->
                                                        <div id="sectionReject{{ $item->id }}" class="action-section" style="display: none;">
                                                            <div class="form-group mt-3">
                                                                <label class="font-weight-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                                <textarea name="catatan" class="form-control input-reject-{{ $item->id }}" rows="3" placeholder="Contoh: Silakan selesaikan pengiriman terlebih dahulu..."></textarea>
                                                            </div>
                                                        </div>

                                                    @elseif($item->status == 'dibatalkan')
                                                        @php
                                                            $buktiRefund = $item->pembatalan->bukti_refund;
                                                            $nominalRefund = $item->pembatalan->nominal_refund;
                                                        @endphp
                                                        @if($buktiRefund)
                                                            <div class="form-group">
                                                                <label class="font-weight-bold text-dark">Bukti Refund Terupload</label><br>
                                                                <a href="{{ $buktiRefund }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    <i class="fas fa-image"></i> Lihat Bukti Refund
                                                                </a>
                                                                <div class="mt-2 p-2 bg-light border rounded">
                                                                    <small>Nominal: <strong>Rp {{ number_format($nominalRefund, 0, ',', '.') }}</strong></small>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    @if($item->status == 'menunggu_konfirmasi_batal')
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary btn-submit-pembatalan" id="btnSubmit{{ $item->id }}" disabled style="min-width: 120px;">
                                                            <i class="fas fa-paper-plane"></i> Proses
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-gray-300 mb-3 d-block"></i>
                                <span class="text-gray-500 italic">Tidak ada permintaan pembatalan saat ini.</span>
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
<script>
    var currentPage = 1;
    var rowsPerPage = 10;

    function filterTable() {
        var searchValue = document.getElementById('searchInput').value.toUpperCase();
        var table = document.querySelector('.table');
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
        // Run initial filter
        filterTable();

        // Handle Action Selector (Approve / Reject)
        document.querySelectorAll('.action-selector').forEach(radio => {
            radio.addEventListener('change', function() {
                const id = this.dataset.id;
                const action = this.value;
                const submitBtn = document.getElementById('btnSubmit' + id);
                
                // Hide all sections first
                document.getElementById('sectionApprove' + id).style.display = 'none';
                document.getElementById('sectionReject' + id).style.display = 'none';
                
                // Reset required attributes
                const refundInput = document.querySelector('.input-refund-' + id);
                const rejectInput = document.querySelector('.input-reject-' + id);
                if(refundInput) refundInput.required = false;
                if(rejectInput) rejectInput.required = false;

                // Show target section
                if (action === 'approve') {
                    document.getElementById('sectionApprove' + id).style.display = 'block';
                    if(refundInput) refundInput.required = true;
                } else {
                    document.getElementById('sectionReject' + id).style.display = 'block';
                    if(rejectInput) rejectInput.required = true;
                }

                // Enable submit button
                submitBtn.disabled = false;
            });
        });

        // Update file label name
        document.querySelectorAll('.custom-file-input').forEach(input => {
            input.addEventListener('change', function(e) {
                const fileName = e.target.files[0].name;
                const nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
        });

        document.getElementById('searchInput').addEventListener('keyup', function() {
            currentPage = 1;
            filterTable();
        });
    });
</script>
@endsection
