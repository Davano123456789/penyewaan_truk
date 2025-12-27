@extends('layouts.masterHome')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    body {
        font-family: 'Poppins', sans-serif;
    }
</style>

<div class="min-h-screen bg-gray-50 py-12" style="font-family: 'Poppins', sans-serif;">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Back Button -->
        <a href="{{ route('daftarArmada') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 mb-6 transition-colors">
            <i class="fas fa-chevron-left"></i>
            <span>Kembali ke Daftar Armada</span>
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content - Left (2 columns) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">
                        <!-- Left: Image -->
                        <div>
                            @if($armada->gambar)
                                <img src="{{ $armada->gambar }}" 
                                     alt="{{ $armada->merek }}" 
                                     class="w-full h-80 object-cover rounded-lg">
                            @else
                                <div class="bg-gray-100 w-full h-80 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-truck text-gray-300 text-7xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Right: Details -->
                        <div>
                            <!-- Title -->
                            <h1 class="text-3xl font-bold text-gray-900 mb-1">{{ $armada->merek }}</h1>
                            <p class="text-sm text-gray-500 mb-6">{{ $armada->jenis ?? 'N/A' }}</p>

                            <!-- Key Info -->
                            <div class="space-y-3 mb-6">
                                <!-- Kapasitas -->
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-cube text-sm"></i>
                                        <span class="text-sm">Kapasitas</span>
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">{{ $armada->kapasitas ?? '0' }} Ton</span>
                                </div>

                                <!-- No Polisi -->
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-tag text-sm"></i>
                                        <span class="text-sm">No. Polisi</span>
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">{{ $armada->no_polisi ?? '-' }}</span>
                                </div>

                                <!-- Status -->
                                <div class="flex items-center justify-between py-3">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <i class="fas fa-check-circle text-sm"></i>
                                        <span class="text-sm">Status</span>
                                    </div>
                                    <span class="text-lg font-semibold">
                                        @if($armada->status === 'tersedia')
                                            <span class="text-green-600">Tersedia</span>
                                        @elseif($armada->status === 'tidak tersedia')
                                            <span class="text-red-600">Tidak Tersedia</span>
                                        @elseif($armada->status === 'maintenance')
                                            <span class="text-amber-600">Maintenance</span>
                                        @else
                                            <span class="text-gray-900">{{ ucfirst($armada->status ?? '-') }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <a href="{{ route('daftarArmada') }}" 
                               class="block w-full bg-blue-600 text-white py-3 rounded-lg text-center font-medium hover:bg-blue-700 transition-colors">
                                Sewa Armada Ini
                            </a>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($armada->deskripsi)
                        <div class="px-6 md:px-8 pb-6 md:pb-8">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2 uppercase tracking-wide">Keterangan</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $armada->deskripsi }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar - Right (1 column) -->
            <div class="lg:col-span-1">
                @php
                    $relatedArmadas = \App\Models\Armada::where('jenis', $armada->jenis)
                        ->where('id', '!=', $armada->id)
                        ->limit(4)
                        ->get();
                @endphp

                @if($relatedArmadas->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-6">
                        <h2 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wide">Armada Sejenis</h2>
                        <div class="space-y-4">
                            @foreach($relatedArmadas as $related)
                                <a href="{{ route('armada.detail', $related->id) }}" class="block group">
                                    <div class="flex gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                        <!-- Thumbnail -->
                                        <div class="flex-shrink-0">
                                            @if($related->gambar)
                                                <img src="{{ $related->gambar }}" 
                                                     alt="{{ $related->merek }}" 
                                                     class="w-20 h-20 object-cover rounded-lg">
                                            @else
                                                <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-truck text-gray-300 text-xl"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Info -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold text-gray-900 mb-1 truncate group-hover:text-blue-600 transition-colors">
                                                {{ $related->merek ?? 'N/A' }}
                                            </h3>
                                            <p class="text-xs text-gray-500 mb-2">{{ $related->jenis ?? 'N/A' }}</p>
                                            
                                            <div class="flex items-center gap-3 text-xs text-gray-600">
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-cube text-gray-400"></i>
                                                    {{ $related->kapasitas ?? '0' }} Ton
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-circle text-xs {{ $related->status === 'tersedia' ? 'text-green-500' : ($related->status === 'maintenance' ? 'text-amber-500' : 'text-red-500') }}"></i>
                                                    {{ ucfirst($related->status ?? '-') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Arrow -->
                                        <div class="flex-shrink-0 flex items-center">
                                            <i class="fas fa-chevron-right text-gray-300 text-xs group-hover:text-blue-600 transition-colors"></i>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- View All Link -->
                        <a href="{{ route('daftarArmada') }}" class="block mt-4 pt-4 border-t text-center text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                            Lihat Semua Armada →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection