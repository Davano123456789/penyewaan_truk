<!-- Navbar -->
<nav class="bg-white shadow-md fixed w-full top-0 z-50">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="text-2xl font-bold text-blue-600">
                <i class="fas fa-truck mr-2"></i>TruckRental
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 transition">Beranda</a>
                <a href="{{ route('daftarArmada') }}" class="text-gray-700 hover:text-blue-600 transition">Armada</a>
                <a href="{{ route('pemesanan') }}" class="text-blue-600 font-semibold">Penyewaan</a>
                @auth
                    @if(Auth::user()->peran_id == 1)
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Dashboard</a>
                    @elseif(Auth::user()->peran_id == 2)
                        <a href="{{ route('penyewaan.index') }}" class="text-gray-700 hover:text-blue-600 transition">Dashboard</a>
                    @elseif(Auth::user()->peran_id == 3)
                        <a href="{{ route('penugasan.index') }}" class="text-gray-700 hover:text-blue-600 transition">Dashboard</a>
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
                            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
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
            
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 transition">Beranda</a>
                <a href="{{ route('daftarArmada') }}" class="text-gray-700 hover:text-blue-600 transition">Armada</a>
                <a href="{{ route('pemesanan') }}" class="text-blue-600 font-semibold">Penyewaan</a>
                @auth
                    @if(Auth::user()->peran_id == 1)
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 transition">Dashboard</a>
                    @elseif(Auth::user()->peran_id == 2)
                        <a href="{{ route('penyewaan.index') }}" class="text-gray-700 hover:text-blue-600 transition">Dashboard</a>
                    @elseif(Auth::user()->peran_id == 3)
                        <a href="{{ route('penugasan.index') }}" class="text-gray-700 hover:text-blue-600 transition">Dashboard</a>
                    @endif
                @endauth
                
                @auth
                    <span class="text-gray-700 font-semibold">
                        <i class="fas fa-user-circle mr-2"></i>{{ Auth::user()->nama }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition text-left">
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
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>