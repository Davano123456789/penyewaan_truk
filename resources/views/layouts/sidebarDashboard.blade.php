<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-truck"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Sistem Armada</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- ============================================================== -->
    <!-- MENU ADMIN (Peran = 1) -->
    <!-- ============================================================== -->
    @if(Auth::user()->peran_id == 1)
    
    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Parkiran -->
    <li class="nav-item {{ Request::is('dashboard/parkir*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('parkir.index') }}">
            <i class="fas fa-fw fa-parking"></i>
            <span>Parkiran</span>
        </a>
    </li>

    <!-- Nav Item - Armada -->
    <li class="nav-item {{ Request::is('dashboard/armada*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('armada.index') }}">
            <i class="fas fa-fw fa-truck-moving"></i>
            <span>Armada</span>
        </a>
    </li>

    <!-- Nav Item - Sopir -->
    <li class="nav-item {{ Request::is('dashboard/sopir*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('sopir.index') }}">
            <i class="fas fa-fw fa-id-card"></i>
            <span>Sopir</span>
        </a>
    </li>

    <!-- Nav Item - Client -->
    <li class="nav-item {{ Request::is('dashboard/client*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('client.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Client</span>
        </a>
    </li>

    <!-- Nav Item - Mitra Kerja -->
    <li class="nav-item {{ Request::is('dashboard/mitra*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('mitra.index') }}">
            <i class="fas fa-fw fa-handshake"></i>
            <span>Mitra Kerja</span>
        </a>
    </li>

    <!-- Nav Item - Keunggulan -->
    <li class="nav-item {{ Request::is('dashboard/keunggulan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('keunggulan.index') }}">
            <i class="fas fa-fw fa-award"></i>
            <span>Keunggulan</span>
        </a>
    </li>

    <!-- Nav Item - Penyewaan Admin -->
    <li class="nav-item {{ Request::is('dashboard/penyewaan-admin*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('penyewaanAdmin.index') }}">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Penyewaan Admin</span>
        </a>
    </li>
    
    @endif

    <!-- ============================================================== -->
    <!-- MENU CLIENT (Peran = 2) -->
    <!-- ============================================================== -->
    @if(Auth::user()->peran_id == 2)

    <!-- Nav Item - Riwayat Pembayaran -->
    <li class="nav-item {{ Request::is('dashboard/pembayaran/riwayat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('pembayaran.riwayat') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat Pembayaran</span>
        </a>
    </li>

    <!-- Nav Item - Penyewaan -->
    <li class="nav-item {{ Request::is('dashboard/penyewaan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('penyewaan.index') }}">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Penyewaan</span>
        </a>
    </li>

    @endif

    <!-- ============================================================== -->
    <!-- MENU SOPIR (Peran = 3) -->
    <!-- ============================================================== -->
    @if(Auth::user()->peran_id == 3)

    <!-- Nav Item - Penugasan -->
    <li class="nav-item {{ Request::is('dashboard/penugasan*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('penugasan.index') }}">
            <i class="fas fa-fw fa-tasks"></i>
            <span>Penugasan</span>
        </a>
    </li>

    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Profil -->
    <li class="nav-item {{ Request::is('dashboard/profil*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('profil.index') }}">
            <i class="fas fa-fw fa-user-circle"></i>
            <span>Profil</span>
        </a>
    </li>

    <!-- Nav Item - Logout -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </li>

</ul>
<!-- End of Sidebar -->
