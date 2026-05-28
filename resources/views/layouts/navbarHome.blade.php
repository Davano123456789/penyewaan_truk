<!-- Navbar -->
<nav class="bg-white shadow-md fixed w-full top-0 z-50">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('logo-sutra-jaya.png') }}" alt="Logo" class="h-10 w-auto object-contain">
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="{{ Request::is('/') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 transition' }}">Beranda</a>
                <a href="{{ route('daftarArmada') }}" class="{{ Request::is('daftar-armada*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 transition' }}">Armada</a>
                <a href="{{ url('/#keunggulan') }}" class="text-gray-700 hover:text-blue-600 transition">Keunggulan</a>
                <a href="{{ url('/#tentang') }}" class="text-gray-700 hover:text-blue-600 transition">Tentang Kami</a>
                <a href="{{ url('/#client') }}" class="text-gray-700 hover:text-blue-600 transition">Client</a>
                @auth
                <a href="{{ route('pemesanan') }}" class="{{ Request::is('pemesanan*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 transition' }}">Penyewaan</a>
                @endauth
                @auth
                    @if(Auth::user()->peran_id == 1)
                        <a href="{{ route('dashboard') }}" class="{{ Request::is('dashboard*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 transition' }}">Dashboard</a>
                    @elseif(Auth::user()->peran_id == 2)
                        <a href="{{ route('penyewaan.index') }}" class="{{ Request::is('dashboard*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 transition' }}">Dashboard</a>
                    @elseif(Auth::user()->peran_id == 3)
                        <a href="{{ route('penugasan.index') }}" class="{{ Request::is('dashboard*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 transition' }}">Dashboard</a>
                    @endif
                @endauth
                
                @auth
                    <!-- Kalau Sudah Login -->
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">
                            <i class="fas fa-user-circle mr-2"></i>{{ Auth::user()->nama }}
                        </span>
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" onclick="confirmLogout(event)" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Kalau Belum Login -->
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition font-semibold">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-user-plus mr-2"></i>Daftar
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-gray-700 focus:outline-none" id="mobile-menu-button">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div class="hidden md:hidden mt-4" id="mobile-menu">
            <div class="flex flex-col space-y-4">
            
                <a href="{{ route('home') }}" class="{{ Request::is('/') ? 'text-blue-600 font-semibold text-left' : 'text-gray-700 hover:text-blue-600 transition text-left' }}">Beranda</a>
                <a href="{{ route('daftarArmada') }}" class="{{ Request::is('daftar-armada*') ? 'text-blue-600 font-semibold text-left' : 'text-gray-700 hover:text-blue-600 transition text-left' }}">Armada</a>
                <a href="{{ url('/#keunggulan') }}" class="text-gray-700 hover:text-blue-600 transition text-left">Keunggulan</a>
                <a href="{{ url('/#tentang') }}" class="text-gray-700 hover:text-blue-600 transition text-left">Tentang Kami</a>
                <a href="{{ url('/#client') }}" class="text-gray-700 hover:text-blue-600 transition text-left">Client</a>
                @auth
                <a href="{{ route('pemesanan') }}" class="{{ Request::is('pemesanan*') ? 'text-blue-600 font-semibold text-left' : 'text-gray-700 hover:text-blue-600 transition text-left' }}">Penyewaan</a>
                @endauth
                @auth
                    @if(Auth::user()->peran_id == 1)
                        <a href="{{ route('dashboard') }}" class="{{ Request::is('dashboard*') ? 'text-blue-600 font-semibold text-left' : 'text-gray-700 hover:text-blue-600 transition text-left' }}">Dashboard</a>
                    @elseif(Auth::user()->peran_id == 2)
                        <a href="{{ route('penyewaan.index') }}" class="{{ Request::is('dashboard*') ? 'text-blue-600 font-semibold text-left' : 'text-gray-700 hover:text-blue-600 transition text-left' }}">Dashboard</a>
                    @elseif(Auth::user()->peran_id == 3)
                        <a href="{{ route('penugasan.index') }}" class="{{ Request::is('dashboard*') ? 'text-blue-600 font-semibold text-left' : 'text-gray-700 hover:text-blue-600 transition text-left' }}">Dashboard</a>
                    @endif
                @endauth
                
                @auth
                    <span class="text-gray-700 font-semibold">
                        <i class="fas fa-user-circle mr-2"></i>{{ Auth::user()->nama }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" onclick="confirmLogout(event)" class="w-full bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition text-left">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition font-semibold">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition text-center">
                        <i class="fas fa-user-plus mr-2"></i>Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- JavaScript untuk Mobile Menu -->
<script>
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });

    // Close mobile menu when any link is clicked
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    });
</script>