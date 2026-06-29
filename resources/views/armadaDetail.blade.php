@extends('layouts.masterHome')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-100/40 to-blue-50/20 py-12" style="font-family: 'Poppins', sans-serif;">
        <div class="container mx-auto px-4 max-w-7xl">
            <!-- Back Button -->
            <a href="{{ route('daftarArmada') }}"
                class="group inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 mb-6 font-semibold transition-all duration-300">
                <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
                <span>Kembali ke Daftar Armada</span>
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content - Left (2 columns) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl shadow-slate-100/50 border border-slate-150/80 overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">
                            <!-- Left: Image -->
                            <div class="relative group overflow-hidden rounded-xl border border-slate-100 shadow-sm bg-slate-50">
                                @if($armada->gambar)
                                    <img src="{{ $armada->gambar }}" alt="{{ $armada->merek }}"
                                        class="w-full h-80 object-cover transition-all duration-500 group-hover:scale-105">
                                @else
                                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 w-full h-80 rounded-xl flex flex-col items-center justify-center">
                                        <i class="fas fa-truck text-slate-300 text-7xl mb-2"></i>
                                        <span class="text-xs text-slate-400 font-medium">Gambar Tidak Tersedia</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Right: Details -->
                            <div class="flex flex-col justify-between">
                                <div>
                                    <!-- Title & Badge -->
                                    <div class="mb-3">
                                        <span class="inline-block bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                            {{ $armada->jenis ?? 'Truk' }}
                                        </span>
                                    </div>
                                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-4">{{ $armada->merek }}</h1>

                                    <!-- Key Info Container -->
                                    <div class="bg-gradient-to-br from-slate-50 to-slate-100/50 rounded-2xl p-5 border border-slate-200/60 space-y-4">
                                        <!-- Kapasitas -->
                                        <div class="flex items-center justify-between py-2 border-b border-slate-200/50">
                                            <div class="flex items-center gap-2.5 text-gray-600">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-gray-800 border border-slate-200/40">
                                                    <i class="fas fa-cube text-sm"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700">Kapasitas Maksimal</span>
                                            </div>
                                            <span class="text-base font-bold text-gray-900">{{ $armada->kapasitas ?? '0' }} Ton</span>
                                        </div>

                                        <!-- No Polisi -->
                                        <div class="flex items-center justify-between py-2 border-b border-slate-200/50">
                                            <div class="flex items-center gap-2.5 text-gray-600">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-gray-800 border border-slate-200/40">
                                                    <i class="fas fa-tag text-sm"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700">Nomor Polisi</span>
                                            </div>
                                            <span class="text-sm font-bold text-gray-950 bg-white border border-slate-200 px-3 py-1 rounded-md font-mono shadow-sm">
                                                {{ $armada->no_polisi ?? '-' }}
                                            </span>
                                        </div>

                                        <!-- Status -->
                                        <div class="flex items-center justify-between py-2">
                                            <div class="flex items-center gap-2.5 text-gray-600">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-gray-800 border border-slate-200/40">
                                                    <i class="fas fa-check-circle text-sm"></i>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700">Status Keterisian</span>
                                            </div>
                                            <div>
                                                @if($armada->status === 'tersedia')
                                                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 border border-green-250 px-3 py-1 rounded-full text-xs font-bold shadow-sm shadow-green-500/5">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                                        Tersedia
                                                    </span>
                                                @elseif($armada->status === 'tidak tersedia')
                                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 border border-red-200/80 px-3 py-1 rounded-full text-xs font-bold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                        Tidak Tersedia
                                                    </span>
                                                @elseif($armada->status === 'maintenance')
                                                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200/80 px-3 py-1 rounded-full text-xs font-bold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                        Maintenance
                                                    </span>
                                                @else
                                                    <span class="bg-gray-100 text-gray-700 border border-gray-200 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($armada->status ?? '-') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($armada->deskripsi)
                            <div class="px-6 md:px-8 pb-8 pt-6 border-t border-slate-100">
                                <h3 class="text-sm font-bold text-gray-800 mb-3 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-align-left text-slate-700"></i>
                                    Keterangan Armada
                                </h3>
                                <div class="bg-slate-50 rounded-xl p-5 border border-slate-200/60 leading-relaxed text-gray-600 text-sm shadow-inner">
                                    {{ $armada->deskripsi }}
                                </div>
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
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-xl shadow-blue-500/10 p-6 sticky top-6 border border-blue-500/20">
                            <h2 class="text-sm font-bold text-white mb-4 uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-white/10">
                                <i class="fas fa-truck-moving text-blue-200"></i>
                                Armada Sejenis
                            </h2>
                            <div class="space-y-3">
                                @foreach($relatedArmadas as $related)
                                    <a href="{{ route('armada.detail', $related->id) }}" class="block group">
                                        <div class="flex gap-3 p-2.5 rounded-xl hover:bg-white/10 border border-transparent hover:border-white/10 transition-all duration-300">
                                            <!-- Thumbnail -->
                                            <div class="flex-shrink-0 relative overflow-hidden rounded-lg border border-white/10 shadow-sm bg-white/5">
                                                @if($related->gambar)
                                                    <img src="{{ $related->gambar }}" alt="{{ $related->merek }}"
                                                        class="w-16 h-16 object-cover transition-all duration-500 group-hover:scale-105">
                                                @else
                                                    <div class="w-16 h-16 bg-white/10 flex items-center justify-center">
                                                        <i class="fas fa-truck text-blue-200 text-lg"></i>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Info -->
                                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                                <h3 class="text-sm font-bold text-white mb-0.5 truncate group-hover:text-blue-100 transition-colors">
                                                    {{ $related->merek ?? 'N/A' }}
                                                </h3>
                                                <p class="text-xs text-blue-200/80 mb-1.5">{{ $related->jenis ?? 'N/A' }}</p>

                                                <div class="flex items-center gap-3 text-[11px] text-blue-100/90">
                                                    <span class="flex items-center gap-1">
                                                        <i class="fas fa-cube text-blue-300"></i>
                                                        {{ $related->kapasitas ?? '0' }} Ton
                                                    </span>
                                                    <span class="flex items-center gap-1.5">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $related->status === 'tersedia' ? 'bg-green-400' : ($related->status === 'maintenance' ? 'bg-amber-400' : 'bg-red-400') }}"></span>
                                                        {{ ucfirst($related->status ?? '-') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Arrow -->
                                            <div class="flex-shrink-0 flex items-center pr-1.5">
                                                <i class="fas fa-chevron-right text-blue-300 text-[10px] transition-transform group-hover:translate-x-0.5"></i>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <!-- View All Link -->
                            <a href="{{ route('daftarArmada') }}"
                                class="block mt-4 pt-4 border-t border-white/10 text-center text-sm text-white hover:text-blue-100 font-semibold transition-colors">
                                Lihat Semua Armada →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection