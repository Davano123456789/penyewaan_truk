                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter" id="notification-count" style="display: none;">0</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header d-flex justify-content-between align-items-center">
                                    Pusat Notifikasi
                                    <a href="#" class="text-white-50 small font-weight-normal" id="mark-all-read-topbar">Mark all as read</a>
                                </h6>
                                <div id="notification-items">
                                    <!-- Notifications will be loaded here via JS -->
                                    <div class="dropdown-item d-flex align-items-center text-center py-3 text-muted">
                                        Memuat notifikasi...
                                    </div>
                                </div>
                                <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifikasi.all') }}">Tampilkan Semua Notifikasi</a>
                            </div>
                        </li>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const notificationItems = document.getElementById('notification-items');
                                const notificationCount = document.getElementById('notification-count');
                                const markAllReadBtn = document.getElementById('mark-all-read');

                                function loadNotifications() {
                                    fetch('{{ route("notifikasi.index") }}')
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                // Update unread count
                                                if (data.unread_count > 0) {
                                                    notificationCount.innerText = data.unread_count > 9 ? '9+' : data.unread_count;
                                                    notificationCount.style.display = 'inline';
                                                } else {
                                                    notificationCount.style.display = 'none';
                                                }

                                                // Update notification items
                                                if (data.data.length > 0) {
                                                    let html = '';
                                                    data.data.forEach(notif => {
                                                        const date = new Date(notif.created_at);
                                                        const formattedDate = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                                                        const isUnreadClass = notif.is_read ? '' : 'font-weight-bold text-dark';
                                                        const unreadStyle = notif.is_read ? '' : 'background-color: #f8f9fc;';
                                                        const unreadDot = notif.is_read ? '' : '<span class="badge badge-primary badge-pill float-right" style="font-size: 0.5rem;">&nbsp;</span>';
                                                        
                                                        html += `
                                                            <a class="dropdown-item d-flex align-items-center notification-link" href="#" data-id="${notif.id}" data-url="${notif.url}" style="${unreadStyle}">
                                                                <div class="mr-3">
                                                                    <div class="icon-circle ${notif.is_read ? 'bg-secondary' : 'bg-primary'} shadow-sm">
                                                                        <i class="fas ${notif.penyewaan_id ? 'fa-file-alt' : 'fa-bell'} text-white"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="w-100">
                                                                    <div class="small text-gray-500 d-flex justify-content-between">
                                                                        <span>${formattedDate}</span>
                                                                        ${unreadDot}
                                                                    </div>
                                                                    <span class="${isUnreadClass}">${notif.judul}</span>
                                                                    <div class="small text-gray-600">${notif.pesan}</div>
                                                                </div>
                                                            </a>
                                                        `;
                                                    });
                                                    notificationItems.innerHTML = html;

                                                    // Add click event for notifications
                                                    document.querySelectorAll('.notification-link').forEach(link => {
                                                        link.addEventListener('click', function(e) {
                                                            e.preventDefault();
                                                            const id = this.dataset.id;
                                                            const url = this.dataset.url;

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
                                                } else {
                                                    notificationItems.innerHTML = '<div class="dropdown-item text-center py-3 text-muted">Tidak ada notifikasi</div>';
                                                }
                                            }
                                        });
                                }

                                // Initial load
                                loadNotifications();

                                // Mark all as read
                                document.getElementById('mark-all-read-topbar').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    fetch('{{ route("notifikasi.readAll") }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json'
                                        }
                                    }).then(() => {
                                        loadNotifications();
                                    });
                                });

                                // Poll for new notifications every 60 seconds
                                setInterval(loadNotifications, 60000);
                            });
                        </script>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->nama }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ Auth::user()->gambar ?? asset('dashboard_tamplate/img/undraw_profile.svg') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profil.index') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->