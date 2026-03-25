@extends('layouts.masterDashboard')

@section('content_dashboard')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pusat Notifikasi</h1>
        <div>
            <button id="page-mark-all-read" class="btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-check fa-sm text-white-50"></i> Tandai Semua Dibaca
            </button>
            <button id="page-delete-all" class="btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-trash fa-sm text-white-50"></i> Hapus Semua
            </button>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Notifikasi Anda</h6>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @forelse ($notifikasis as $n)
                    <div class="list-group-item list-group-item-action page-notification-item {{ $n->is_read ? '' : 'bg-light border-left-primary shadow-sm' }} d-flex align-items-center p-0" 
                       data-id="{{ $n->id }}" data-url="{{ $n->url }}" style="cursor: pointer;">
                        <div class="p-3 flex-grow-1 notification-content">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <h5 class="mb-1 h6 {{ $n->is_read ? 'text-gray-800' : 'font-weight-bold text-primary' }}">
                                    @if(!$n->is_read)
                                        <i class="fas fa-circle text-primary mr-1" style="font-size: 0.6rem;"></i>
                                    @endif
                                    {{ $n->judul }}
                                    @if(!$n->is_read)
                                        <span class="badge badge-primary badge-pill ml-2">Baru</span>
                                    @endif
                                </h5>
                                <small class="{{ $n->is_read ? 'text-muted' : 'font-weight-bold text-primary' }}">
                                    {{ $n->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <p class="mb-1 {{ $n->is_read ? 'text-gray-600' : 'text-gray-900 font-weight-500' }}">{{ $n->pesan }}</p>
                            <small class="text-gray-500">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ $n->created_at->format('d M Y, H:i') }}
                            </small>
                        </div>
                        <div class="p-3">
                            <button class="btn btn-sm btn-outline-danger btn-delete-notif" data-id="{{ $n->id }}" title="Hapus Notifikasi">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash fa-3x text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada notifikasi untuk Anda.</p>
                    </div>
                @endforelse
            </div>
        </div>
        @if($notifikasis->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    {{ $notifikasis->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle clicking a notification
        document.querySelectorAll('.notification-content').forEach(content => {
            content.addEventListener('click', function() {
                const parent = this.closest('.page-notification-item');
                const id = parent.dataset.id;
                const url = parent.dataset.url;

                fetch(`/api/notifikasi/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    window.location.href = url;
                });
            });
        });

        // Handle delete single notification
        document.querySelectorAll('.btn-delete-notif').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const id = this.dataset.id;
                
                Swal.fire({
                    title: 'Hapus Notifikasi?',
                    text: "Notifikasi ini akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/api/notifikasi/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            }
                        });
                    }
                });
            });
        });

        // Handle mark all read
        document.getElementById('page-mark-all-read').addEventListener('click', function() {
            fetch('{{ route("notifikasi.readAll") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                window.location.reload();
            });
        });

        // Handle delete all notifications
        document.getElementById('page-delete-all').addEventListener('click', function() {
            Swal.fire({
                title: 'Hapus Semua Notifikasi?',
                text: "Semua riwayat notifikasi Anda akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("notifikasi.destroyAll") }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
