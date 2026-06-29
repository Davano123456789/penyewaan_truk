@extends('layouts.masterHome')

@section('title', 'Daftar Armada - Penyewaan Truk')

@section('content')
    <!-- Main Content -->
    <!-- Hero Header -->
    <section class="relative pt-40 pb-24 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=1600" alt="Hero Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/50 to-black"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center" data-aos="fade-up">
                <span class="inline-block px-4 py-1 rounded-full bg-blue-600/20 text-blue-400 font-semibold text-sm mb-4 border border-blue-600/30">
                    Armada PT Sutera Jaya
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6">
                    Daftar <span class="text-blue-500">Armada Kami</span>
                </h1>
                <p class="text-gray-300 text-lg max-w-2xl mx-auto font-light">
                    Pilih unit transportasi terbaik yang sesuai dengan spesifikasi dan kebutuhan logistik bisnis Anda.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="pb-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="-mt-12 relative z-20">
                <!-- Search & Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form id="filterForm" action="{{ route('daftarArmada') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   id="searchInput"
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari berdasarkan merek, kapasitas/bobot..."
                                   class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Filter Jenis -->
                    <div class="md:w-64">
                        <select id="jenisSelect"
                                name="jenis" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisArmada as $jenis)
                                <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <!-- Result Info -->
            <div id="resultInfo" class="mb-6">
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
            <div id="armadaGrid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($armadas as $armada)
                <!-- Armada Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden h-full flex flex-col group" 
                     data-aos="fade-up" 
                     data-aos-delay="{{ $loop->iteration * 100 }}">
                    <!-- Image Section -->
                    <div class="relative h-64 overflow-hidden">
                        @if($armada->gambar)
                            <img src="{{ $armada->gambar }}" alt="{{ $armada->merek }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="bg-gradient-to-br from-gray-100 to-gray-200 h-full flex flex-col items-center justify-center">
                                <i class="fas fa-truck text-gray-300 text-6xl mb-2"></i>
                                <span class="text-gray-400 text-xs font-bold uppercase tracking-widest">No Image</span>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 left-4">
                            @php
                                $statusColors = [
                                    'tersedia' => 'bg-green-500',
                                    'perawatan' => 'bg-amber-500',
                                    'tidak_tersedia' => 'bg-red-500'
                                ];
                                $color = $statusColors[$armada->status] ?? 'bg-gray-500';
                            @endphp
                            <span class="{{ $color }} text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg border border-white/20">
                                {{ str_replace('_', ' ', $armada->status) }}
                            </span>
                        </div>

                        <!-- Brand Overlay -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent p-6 pt-12">
                            <h3 class="text-white text-xl font-black uppercase tracking-tight">{{ $armada->merek ?? 'Armada Unit' }}</h3>
                            <p class="text-blue-300 text-xs font-bold">{{ $armada->jenis ?? 'General Cargo' }}</p>
                        </div>
                    </div>
                    
                    <!-- Content Section -->
                    <div class="p-6 flex-1 flex flex-col">
                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                                <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">Kapasitas</p>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-weight-hanging text-gray-800 text-xs"></i>
                                    <span class="text-sm font-bold text-gray-800">{{ $armada->kapasitas ?? '0' }} Ton</span>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                                <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-1">No Polisi</p>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-id-card text-gray-800 text-xs"></i>
                                    <span class="text-sm font-bold text-gray-800">{{ $armada->no_polisi ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description (Limited) -->
                        <div class="mb-8 flex-1">
                            <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 italic">
                                {{ $armada->deskripsi ?? 'Armada andalan PT Sutera Jaya untuk pengiriman logistik yang aman dan terpercaya ke seluruh wilayah.' }}
                            </p>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('armada.detail', $armada->id) }}" 
                           class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-blue-600/20 flex items-center justify-center gap-2 group/btn">
                            Lihat Detail
                            <i class="fas fa-eye text-[10px] group-hover/btn:scale-110 transition-transform"></i>
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

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JavaScript AJAX untuk Filter & Search Tanpa Refresh -->
    <script>
        $(document).ready(function() {
            const $form = $('#filterForm');
            const $searchInput = $('#searchInput');
            const $jenisSelect = $('#jenisSelect');

            function filterArmada() {
                const formData = $form.serialize();
                const url = $form.attr('action') + '?' + formData;
                
                // Perbarui URL di browser tanpa reload halaman
                history.pushState(null, '', url);
                
                // Berikan efek loading transparan pada grid
                $('#armadaGrid').css('opacity', '0.5');

                // Lakukan AJAX GET request ke server
                $.get(url, function(data) {
                    const $response = $(data);
                    const $newGrid = $response.find('#armadaGrid');
                    const $newResultInfo = $response.find('#resultInfo');
                    
                    // Ganti konten grid dan info hasil dengan data terbaru
                    $('#armadaGrid').html($newGrid.html()).css('opacity', '1');
                    $('#resultInfo').html($newResultInfo.html());
                    
                    // Refresh AOS animation untuk item baru yang dimuat
                    if (window.AOS) {
                        window.AOS.refresh();
                    }
                });
            }

            // Debounce untuk input pencarian (menunggu 300ms setelah berhenti mengetik)
            let timeout = null;
            $searchInput.on('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(filterArmada, 300);
            });

            // Submit otomatis saat select jenis armada diubah
            $jenisSelect.on('change', filterArmada);

            // Cegah reload ketika menekan enter / submit form manual
            $form.on('submit', function(e) {
                e.preventDefault();
                filterArmada();
            });
        });
    </script>
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
