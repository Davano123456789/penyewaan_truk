@extends('layouts.masterHome')

@section('title', 'Daftar Armada - Penyewaan Truk')

@section('content')
    <!-- Main Content -->
    <div class="pt-24 pb-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Daftar Armada Kami</h1>
                <p class="text-gray-600">Pilih armada terbaik sesuai kebutuhan transportasi Anda</p>
            </div>

            <!-- Search & Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form action="{{ route('daftarArmada') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari berdasarkan merek, no polisi..."
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filter Jenis -->
                    <div class="md:w-64">
                        <select name="jenis" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisArmada as $jenis)
                                <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Button -->
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-all duration-300">
                            <i class="fas fa-filter mr-2"></i>
                            Filter
                        </button>
                        <a href="{{ route('daftarArmada') }}" 
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all duration-300">
                            <i class="fas fa-redo mr-2"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Result Info -->
            <div class="mb-6">
                <p class="text-gray-600">
                    Menampilkan <strong>{{ $armadas->count() }}</strong> armada
                    @if(request('jenis'))
                        dengan jenis <strong>{{ request('jenis') }}</strong>
                    @endif
                    @if(request('search'))
                        dengan kata kunci "<strong>{{ request('search') }}</strong>"
                    @endif
                </p>
            </div>
          
            <!-- Armada Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($armadas as $armada)
                <!-- Armada Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 group" 
                     data-aos="fade-up" 
                     data-aos-delay="{{ $loop->iteration * 100 }}">
                    <!-- Image Section -->
                    <div class="relative overflow-hidden h-48">
                        @if($armada->gambar)
                            <img src="{{ $armada->gambar }}" 
                                 alt="{{ $armada->merek }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="bg-gradient-to-br from-gray-300 to-gray-400 h-full flex items-center justify-center">
                                <i class="fas fa-truck text-gray-500 text-5xl"></i>
                            </div>
                        @endif
                        
                        <!-- Badge Jenis - Minimalis -->
                        <div class="absolute top-3 left-3 bg-white px-3 py-1 rounded-full shadow-sm">
                            <span class="text-gray-700 font-semibold text-xs">{{ $armada->jenis ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <!-- Content Section -->
                    <div class="p-4">
                        <!-- Judul -->
                        <h3 class="text-lg font-bold text-gray-800 mb-3">{{ $armada->merek ?? 'N/A' }}</h3>

                        <!-- Info Minimal -->
                        <div class="space-y-2 mb-3 text-xs text-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-cube text-gray-400 text-sm"></i>
                                    <span>Kapasitas</span>
                                </div>
                                <span class="font-semibold text-gray-800">{{ $armada->kapasitas ?? '0' }} Ton</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-tag text-gray-400 text-sm"></i>
                                    <span>No Polisi</span>
                                </div>
                                <span class="font-semibold text-gray-800">{{ $armada->no_polisi ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar text-gray-400 text-sm"></i>
                                    <span>Tahun</span>
                                </div>
                                <span class="font-semibold text-gray-800">{{ $armada->tahun ?? '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-gray-400 text-sm"></i>
                                    <span>Kondisi</span>
                                </div>
                                <span class="font-semibold">
                                    @if($armada->kondisi === 'baik')
                                        <span class="text-green-600">Baik</span>
                                    @elseif($armada->kondisi === 'sedang')
                                        <span class="text-yellow-600">Sedang</span>
                                    @else
                                        <span class="text-gray-800">{{ $armada->kondisi ?? '-' }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Button -->
                        <a href="{{ route('armada.detail', $armada->id) }}" 
                           class="block w-full bg-blue-600 text-white py-2 rounded text-sm font-semibold hover:bg-blue-700 transition-colors duration-300 text-center">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="col-span-full">
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-truck text-gray-400 text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">Tidak Ada Armada Ditemukan</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            @if(request('search') || request('jenis'))
                                Coba ubah kata kunci pencarian atau filter Anda untuk menemukan armada yang sesuai
                            @else
                                Belum ada armada yang terdaftar dalam sistem
                            @endif
                        </p>
                        <a href="{{ route('daftarArmada') }}" 
                           class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-all duration-300 shadow-md">
                            <i class="fas fa-redo mr-2"></i>
                            Tampilkan Semua Armada
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
