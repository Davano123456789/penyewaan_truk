@extends('layouts.masterHome')

@section('title', 'Form Pemesanan - Penyewaan Truk')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 400px; width: 100%; }
    .info-box {
        background: #f8f9fa;
        border-left: 4px solid #007bff;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .armada-card {
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
    }
    .armada-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .armada-card.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-top: none;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .search-result-item {
        padding: 12px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
    }
    .search-result-item:hover {
        background-color: #f8f9fa;
    }
    .jenis-truk-btn {
        padding: 12px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    .jenis-truk-btn:hover {
        border-color: #3b82f6;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .jenis-truk-btn.active {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    .jenis-truk-btn i {
        font-size: 24px;
    }
</style>
@endsection

@section('content')
    <!-- Hero Header -->
    <section class="relative pt-40 pb-24 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1600" alt="Hero Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/50 to-gray-50"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center" data-aos="fade-up">
                <span class="inline-block px-4 py-1 rounded-full bg-blue-600/20 text-blue-400 font-semibold text-sm mb-4 border border-blue-600/30">
                    Sistem Pemesanan Cerdas
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6">
                    {{ isset($editItem) ? 'Edit' : 'Form' }} <span class="text-blue-500">Pemesanan Truk</span>
                </h1>
                <p class="text-gray-300 text-lg max-w-2xl mx-auto font-light">
                    Tentukan spesifikasi armada dan rute pengiriman Anda untuk mendapatkan layanan logistik terbaik.
                </p>
            </div>
        </div>
    </section>

    <div class="pb-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="-mt-12 relative z-20">

        <form action="{{ isset($editItem) ? route('keranjang.update', $editItem->id) : route('pemesanan.store') }}" 
              method="POST" 
              id="formPemesanan" 
              class="max-w-7xl mx-auto">
            @csrf
            @if(isset($editItem))
                @method('PUT')
            @endif
            
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Left Side - Map & Location -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Pilih Jenis Truk -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-truck text-blue-600"></i> Pilih Jenis Truk
                        </h2>
                        
                        <div class="grid grid-cols-4 gap-4">
                            <div class="jenis-truk-btn" onclick="selectJenisTruk('CDD')">
                                <i class="fas fa-truck text-blue-600"></i>
                                <span class="font-semibold text-sm">CDD</span>
                            </div>
                            <div class="jenis-truk-btn" onclick="selectJenisTruk('BOX')">
                                <i class="fas fa-cube text-green-600"></i>
                                <span class="font-semibold text-sm">BOX</span>
                            </div>
                            <div class="jenis-truk-btn" onclick="selectJenisTruk('WINGBOX')">
                                <i class="fas fa-box-open text-purple-600"></i>
                                <span class="font-semibold text-sm">WINGBOX</span>
                            </div>
                            <div class="jenis-truk-btn" onclick="selectJenisTruk('TERBUKA')">
                                <i class="fas fa-truck-loading text-orange-600"></i>
                                <span class="font-semibold text-sm">TERBUKA</span>
                            </div>
                        </div>
                        <input type="hidden" name="jenis_truk" id="jenis_truk" required>
                        <p class="text-xs text-gray-500 mt-2">*Pilih jenis truk terlebih dahulu sebelum memilih lokasi</p>
                    </div>

                    <!-- Map -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-map-marked-alt text-blue-600"></i> Pilih Lokasi
                        </h2>
                        
                        <div class="mb-4">
                            <div class="flex gap-2 mb-3">
                                <button type="button" onclick="setMapMode('jemput')" id="btnJemput" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold">
                                    <i class="fas fa-map-marker-alt"></i> Set Titik Jemput
                                </button>
                                <button type="button" onclick="setMapMode('antar')" id="btnAntar" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold">
                                    <i class="fas fa-flag-checkered"></i> Set Titik Antar
                                </button>
                            </div>
                            
                            <div class="relative">
                                <input type="text" 
                                       id="searchLocation" 
                                       placeholder="Ketik alamat dan tekan Enter atau klik tombol cari..." 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg pr-12"
                                       autocomplete="off">
                                <button type="button" onclick="searchLocation()" class="absolute right-2 top-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-search"></i>
                                </button>
                                <div id="searchResults" class="search-results" style="display: none;"></div>
                            </div>
                        </div>

                        <div id="map" class="rounded-lg border-2 border-gray-300"></div>
                        
                        <div class="mt-4 grid md:grid-cols-2 gap-4">
                            <div class="info-box">
                                <p class="font-semibold text-blue-600 mb-1"><i class="fas fa-map-marker-alt"></i> Titik Jemput:</p>
                                <p id="jemputAddress" class="text-sm text-gray-700">Belum dipilih</p>
                            </div>
                            <div class="info-box">
                                <p class="font-semibold text-green-600 mb-1"><i class="fas fa-flag-checkered"></i> Titik Antar:</p>
                                <p id="antarAddress" class="text-sm text-gray-700">Belum dipilih</p>
                            </div>
                        </div>
                    </div>

                    <!-- Parkir Terdekat Info -->
                    <div class="bg-white rounded-lg shadow-lg p-6" id="parkirInfo" style="display:none;">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-parking text-indigo-600"></i> Parkir Terdekat
                        </h3>
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <p class="font-semibold text-indigo-800"><i class="fas fa-map-pin"></i> <span id="parkirNama">-</span></p>
                            <p class="text-sm text-gray-600 mt-1"><span id="parkirAlamat">-</span></p>
                            <p class="text-sm text-indigo-600 mt-2"><i class="fas fa-ruler-horizontal"></i> Jarak ke titik jemput: <strong><span id="parkirJarak">-</span> km</strong></p>
                        </div>
                    </div>

                    <!-- Route Info -->
                    <div class="bg-white rounded-lg shadow-lg p-6" id="routeInfo" style="display:none;">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-route text-purple-600"></i> Informasi Rute
                        </h3>
                        <div class="grid md:grid-cols-4 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <i class="fas fa-road text-blue-600 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">Total Jarak</p>
                                <p class="text-2xl font-bold text-blue-600"><span id="totalJarak">0</span> km</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <i class="fas fa-money-bill-wave text-green-600 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">Estimasi Biaya</p>
                                <p class="text-2xl font-bold text-green-600">Rp <span id="estimasiBiaya">0</span></p>
                            </div>
                            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                <i class="fas fa-clock text-yellow-600 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">Estimasi Waktu</p>
                                <p class="text-2xl font-bold text-yellow-600"><span id="estimasiWaktu">0</span> jam</p>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <i class="fas fa-calendar-alt text-purple-600 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">Estimasi Hari</p>
                                <p class="text-2xl font-bold text-purple-600"><span id="estimasiHariDisplay">0</span> hari</p>
                                <p class="text-xs text-gray-500 mt-1">(50km/hari)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pemesanan -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-clipboard-list text-indigo-600"></i> Detail Pemesanan
                        </h3>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Estimasi Hari</label>
                                <input type="number" name="estimasi_hari" id="estimasi_hari" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Otomatis dihitung" readonly required>
                                <p class="text-xs text-gray-500 mt-1">*Dihitung otomatis: 50km per hari</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 gap-6 mt-4">
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-semibold mb-2">
                                    <i class="fas fa-box text-blue-500"></i> Barang Muatan
                                </label>
                                <textarea name="barang_muatan" id="barang_muatan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Deskripsi barang yang akan dimuat..." required></textarea>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    <i class="fas fa-weight-hanging text-blue-500"></i> Bobot Muatan (Ton)
                                </label>
                                <input type="number" name="bobot" id="bobot" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: 5" required oninput="checkCapacity()">
                                <p id="bobot-warning" class="text-xs text-red-500 mt-2 font-semibold hidden"></p>
                            </div>
                        </div>
                        <div class="mt-4">
    <label class="block text-gray-700 font-semibold mb-2">
        <i class="fas fa-money-bill-wave"></i> Harga Tawar (Opsional)
    </label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500">Rp</span>
        </div>
        <input type="number" 
               name="harga_tawar" 
               id="harga_tawar" 
               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
               placeholder="Masukkan harga tawar (maks 10% dari harga asli)"
               oninput="validateHargaTawar()">
    </div>
    <div id="hargaTawarInfo" class="mt-2 text-sm hidden">
        <p class="text-gray-600">
            <i class="fas fa-info-circle"></i> 
            Harga Asli: <strong>Rp <span id="hargaAsliDisplay">0</span></strong>
        </p>
        <p class="text-gray-600">
            <i class="fas fa-calculator"></i> 
            Minimal Tawar (90%): <strong>Rp <span id="minHargaTawar">0</span></strong>
        </p>
        <p id="selisihInfo" class="hidden">
            <i class="fas fa-arrow-down"></i> 
            Selisih: <strong class="text-green-600">Rp <span id="selisihTawar">0</span></strong>
        </p>
    </div>
    <p id="errorHargaTawar" class="text-red-600 text-sm mt-1 font-semibold hidden">
        <i class="fas fa-exclamation-triangle"></i> 
        Harga tawar tidak boleh kurang dari 90% harga asli!
    </p>
</div>
                    </div>
                </div>

                <!-- Right Side - Armada Selection -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-truck text-red-600"></i> Armada Tersedia
                        </h3>
                        
                        <div id="armadaList" class="space-y-4 max-h-[600px] overflow-y-auto">
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-info-circle text-4xl mb-3"></i>
                                <p class="text-sm">Pilih jenis truk dan titik jemput untuk melihat armada tersedia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Inputs -->
            <input type="hidden" name="client_id" value="2">
            <input type="hidden" name="armada_id" id="armada_id">
            <input type="hidden" name="tempat_jemput" id="tempat_jemput">
            <input type="hidden" name="tempat_antar" id="tempat_antar">
            <input type="hidden" name="latitude_penjemputan" id="latitude_penjemputan">
            <input type="hidden" name="longitude_penjemputan" id="longitude_penjemputan">
            <input type="hidden" name="latitude_antar" id="latitude_antar">
            <input type="hidden" name="longitude_antar" id="longitude_antar">
            <input type="hidden" name="parkir_latitude" id="parkir_latitude">
            <input type="hidden" name="parkir_longitude" id="parkir_longitude">
            <input type="hidden" name="harga_sewa" id="harga_sewa">
            <input type="hidden" name="total_jarak" id="total_jarak">

            <!-- Submit Button -->
            <div class="mt-8 text-center">
                <button type="submit" class="bg-blue-600 text-white px-12 py-4 rounded-lg font-bold text-lg hover:bg-blue-700 transition-all duration-300 inline-flex items-center gap-3">
                    <i class="fas fa-{{ isset($editItem) ? 'save' : 'shopping-cart' }}"></i>
                    <span>{{ isset($editItem) ? 'Simpan Perubahan' : 'Tambah ke Keranjang' }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map, markerJemput, markerAntar, markerParkir, routeLayer;
let currentMode = 'jemput';
let jemputCoords = null;
let antarCoords = null;
let selectedJenisTruk = null;
let nearestParkir = null;
let parkirs = @json($parkirs);
let searchTimeout;
let hargaAsli = 0;

// Initialize variables for edit mode
let editMode = @json(isset($editItem));
let editData = @json($editItem ?? null);
let currentArmadaId = editData ? editData.armada_id : null;
let selectedArmadaKapasitas = null;

function checkCapacity() {
    const bobotInput = document.getElementById('bobot');
    const warningElement = document.getElementById('bobot-warning');
    if (!bobotInput || !warningElement) return;

    const bobotVal = parseFloat(bobotInput.value);
    
    if (selectedArmadaKapasitas && bobotVal > selectedArmadaKapasitas) {
        warningElement.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Peringatan: Bobot muatan (${bobotVal} Ton) melebihi kapasitas maksimal armada (${selectedArmadaKapasitas} Ton)!`;
        warningElement.classList.remove('hidden');
    } else {
        warningElement.classList.add('hidden');
        warningElement.innerHTML = '';
    }
}

// Initialize map
document.addEventListener('DOMContentLoaded', function() {
    map = L.map('map').setView([-7.250445, 112.768845], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    parkirs.forEach(parkir => {
        L.marker([parkir.latitude, parkir.longitude], {
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-grey.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).addTo(map).bindPopup(`<b>${parkir.nama}</b><br>${parkir.alamat}`);
    });

    map.on('click', function(e) {
        setLocation(e.latlng.lat, e.latlng.lng);
    });

    document.getElementById('tanggal_mulai').min = new Date().toISOString().split('T')[0];

    const searchInput = document.getElementById('searchLocation');
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            searchLocation();
        } else {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (searchInput.value.length > 2) {
                    showSearchSuggestions(searchInput.value);
                } else {
                    hideSearchResults();
                }
            }, 300);
        }
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#searchLocation') && !e.target.closest('#searchResults')) {
            hideSearchResults();
        }
    });

    // Handle Edit Mode Initialization
    if (editMode && editData) {
        // 1. Set Jenis Truk
        if (editData.armada) {
            selectJenisTruk(editData.armada.jenis);
        }

        // 2. Set Pin & Address
        let editLatJemput = (editData.rute && editData.rute.latitude_penjemputan) ? editData.rute.latitude_penjemputan : editData.latitude_penjemputan;
        let editLngJemput = (editData.rute && editData.rute.longitude_penjemputan) ? editData.rute.longitude_penjemputan : editData.longitude_penjemputan;
        let editTempatJemput = (editData.rute && editData.rute.tempat_jemput) ? editData.rute.tempat_jemput : editData.tempat_jemput;

        let editLatAntar = (editData.rute && editData.rute.latitude_antar) ? editData.rute.latitude_antar : editData.latitude_antar;
        let editLngAntar = (editData.rute && editData.rute.longitude_antar) ? editData.rute.longitude_antar : editData.longitude_antar;
        let editTempatAntar = (editData.rute && editData.rute.tempat_antar) ? editData.rute.tempat_antar : editData.tempat_antar;

        if (editLatJemput && editLngJemput) {
            setLocation(editLatJemput, editLngJemput, 'jemput', editTempatJemput);
        }
        if (editLatAntar && editLngAntar) {
            setLocation(editLatAntar, editLngAntar, 'antar', editTempatAntar);
        }

        // 3. Set Inputs
        document.getElementById('tanggal_mulai').value = editData.tanggal_mulai;
        document.getElementById('barang_muatan').value = editData.barang_muatan;
        document.getElementById('bobot').value = editData.bobot || '';
        
        // 4. Set Price Tawar if different from base (assuming we store final price in harga_sewa)
        // This part depends on how you want to handle existing pricing
    }
});

function selectJenisTruk(jenis) {
    selectedJenisTruk = jenis;
    document.getElementById('jenis_truk').value = jenis;
    
    document.querySelectorAll('.jenis-truk-btn').forEach(btn => {
        btn.classList.remove('active');
        // Cari button yang teksnya sesuai dengan jenis
        if (btn.querySelector('span').textContent.trim() === jenis) {
            btn.classList.add('active');
        }
    });
    
    if (jemputCoords) {
        loadArmada(jemputCoords.lat, jemputCoords.lng);
    }
}

function setMapMode(mode) {
    currentMode = mode;
    
    document.getElementById('btnJemput').className = mode === 'jemput' 
        ? 'flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold'
        : 'flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold';
    
    document.getElementById('btnAntar').className = mode === 'antar'
        ? 'flex-1 px-4 py-2 bg-green-600 text-white rounded-lg font-semibold'
        : 'flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-semibold';

    // Sinkronkan input pencarian dengan alamat yang sudah dipilih di mode tersebut
    const searchInput = document.getElementById('searchLocation');
    if (mode === 'jemput') {
        const addr = document.getElementById('jemputAddress').textContent;
        searchInput.value = (addr !== 'Belum dipilih') ? addr : '';
    } else {
        const addr = document.getElementById('antarAddress').textContent;
        searchInput.value = (addr !== 'Belum dipilih') ? addr : '';
    }
    hideSearchResults();
}

async function setLocation(lat, lng, modeOverride = null, addressOverride = null) {
    try {
        let mode = modeOverride || currentMode;
        let address = addressOverride;
        
        if (!address) {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
            const data = await response.json();
            address = data.display_name;
        }

        if (mode === 'jemput') {
            jemputCoords = { lat, lng };
            
            if (markerJemput) map.removeLayer(markerJemput);
            markerJemput = L.marker([lat, lng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map).bindPopup('Titik Jemput').openPopup();

            document.getElementById('jemputAddress').textContent = address;
            document.getElementById('tempat_jemput').value = address;
            document.getElementById('latitude_penjemputan').value = lat;
            document.getElementById('longitude_penjemputan').value = lng;

            if (selectedJenisTruk) {
                findNearestParkir(lat, lng);
                loadArmada(lat, lng);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Pilih jenis truk terlebih dahulu!',
                    confirmButtonColor: '#3b82f6'
                });
            }
        } else {
            antarCoords = { lat, lng };
            
            if (markerAntar) map.removeLayer(markerAntar);
            markerAntar = L.marker([lat, lng], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map).bindPopup('Titik Antar').openPopup();

            document.getElementById('antarAddress').textContent = address;
            document.getElementById('tempat_antar').value = address;
            document.getElementById('latitude_antar').value = lat;
            document.getElementById('longitude_antar').value = lng;
        }

        if (jemputCoords && antarCoords && nearestParkir) {
            calculateRouteWithParkir(nearestParkir.latitude, nearestParkir.longitude);
        }
    } catch (error) {
        console.error('Error getting address:', error);
    }
}

function updateParkirSelection(parkir) {
    nearestParkir = parkir;
    
    if (markerParkir) map.removeLayer(markerParkir);
    
    markerParkir = L.marker([parkir.latitude, parkir.longitude], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map).bindPopup(`<b>Parkir Terdekat</b><br>${parkir.nama}`);

    document.getElementById('parkirInfo').style.display = 'block';
    document.getElementById('parkirNama').textContent = parkir.nama;
    document.getElementById('parkirAlamat').textContent = parkir.alamat;
    
    let distance = parseFloat(parkir.distance);
    if (isNaN(distance) && jemputCoords) {
        distance = calculateDistance(jemputCoords.lat, jemputCoords.lng, parkir.latitude, parkir.longitude);
    }
    
    document.getElementById('parkirJarak').textContent = !isNaN(distance) ? distance.toFixed(2) : '-';
    
    document.getElementById('parkir_latitude').value = parkir.latitude;
    document.getElementById('parkir_longitude').value = parkir.longitude;
}

function findNearestParkir(lat, lng) {
    let minDistance = Infinity;
    let nearest = null;

    parkirs.forEach(parkir => {
        const distance = calculateDistance(lat, lng, parkir.latitude, parkir.longitude);
        if (distance < minDistance) {
            minDistance = distance;
            nearest = parkir;
        }
    });

    if (nearest) {
        updateParkirSelection(nearest);
    }
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

async function showSearchSuggestions(query) {
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`);
        const data = await response.json();

        const resultsDiv = document.getElementById('searchResults');
        
        if (data.length > 0) {
            resultsDiv.innerHTML = data.map(item => `
                <div class="search-result-item" onclick="selectSearchResult(${item.lat}, ${item.lon}, '${item.display_name.replace(/'/g, "\\'")}')">
                    <div class="font-semibold text-sm">${item.display_name.split(',')[0]}</div>
                    <div class="text-xs text-gray-500">${item.display_name}</div>
                </div>
            `).join('');
            resultsDiv.style.display = 'block';
        } else {
            resultsDiv.innerHTML = '<div class="search-result-item text-gray-500">Tidak ada hasil ditemukan</div>';
            resultsDiv.style.display = 'block';
        }
    } catch (error) {
        console.error('Error searching:', error);
    }
}

function selectSearchResult(lat, lng, address) {
    map.setView([lat, lng], 16);
    setLocation(lat, lng);
    document.getElementById('searchLocation').value = address.split(',').slice(0, 3).join(',');
    hideSearchResults();
}

function hideSearchResults() {
    document.getElementById('searchResults').style.display = 'none';
}

async function searchLocation() {
    const query = document.getElementById('searchLocation').value;
    if (!query) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Masukkan alamat yang ingin dicari!',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&addressdetails=1`);
        const data = await response.json();

        if (data.length > 0) {
            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);
            map.setView([lat, lng], 16);
            setLocation(lat, lng);
            hideSearchResults();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lokasi Tidak Ditemukan',
                text: 'Coba gunakan kata kunci yang lebih spesifik.',
                confirmButtonColor: '#ef4444'
            });
        }
    } catch (error) {
        console.error('Error searching location:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mencari lokasi. Silakan coba lagi.',
            confirmButtonColor: '#ef4444'
        });
    }
}

async function loadArmada(lat, lng) {
    if (!selectedJenisTruk) {
        document.getElementById('armadaList').innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                <p>Pilih jenis truk terlebih dahulu!</p>
            </div>
        `;
        return;
    }

    try {
        const response = await fetch(`/api/armada-tersedia?lat=${lat}&lng=${lng}&jenis=${selectedJenisTruk}${currentArmadaId ? '&current_armada_id='+currentArmadaId : ''}`);
        const result = await response.json();

        // Update parkir selection dynamically if the backend recommended a different parking lot
        if (result.parkir) {
            updateParkirSelection(result.parkir);
        }

        const armadaList = document.getElementById('armadaList');
        
        if (result.data.length === 0) {
            armadaList.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                    <p>Tidak ada armada <strong>${selectedJenisTruk}</strong> tersedia di parkir terdekat</p>
                </div>
            `;
            return;
        }

        armadaList.innerHTML = result.data.map(armada => `
            <div class="armada-card p-4 rounded-lg cursor-pointer" onclick="selectArmada(${armada.id}, ${armada.kapasitas})">
                <div class="flex items-start gap-3">
                    <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-truck text-blue-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800">${armada.no_polisi}</h4>
                        <p class="text-sm text-gray-600">${armada.merek} - ${armada.jenis}</p>
                        <p class="text-sm text-gray-600"><i class="fas fa-weight-hanging"></i> ${armada.kapasitas} Ton</p>
                        <p class="text-sm text-blue-600"><i class="fas fa-user"></i> ${armada.sopir}</p>
                    </div>
                </div>
            </div>
        `).join('');

        // Auto-select current armada if editing
        if (editMode && currentArmadaId) {
            const currentCard = document.querySelector(`.armada-card[onclick*="selectArmada(${currentArmadaId},"]`);
            if (currentCard) {
                currentCard.click();
            }
        }
    } catch (error) {
        console.error('Error loading armada:', error);
    }
}

function selectArmada(id, kapasitas) {
    document.getElementById('armada_id').value = id;
    selectedArmadaKapasitas = kapasitas;
    
    // Check capacity dynamically
    checkCapacity();
    
    document.querySelectorAll('.armada-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    if (window.event && event.currentTarget) {
        event.currentTarget.classList.add('selected');
    } else {
        const card = document.querySelector(`.armada-card[onclick*="selectArmada(${id},"]`);
        if (card) card.classList.add('selected');
    }

    if (antarCoords && jemputCoords && nearestParkir) {
        calculateRouteWithParkir(nearestParkir.latitude, nearestParkir.longitude);
    }
}

async function calculateRouteWithParkir(parkirLat, parkirLng) {
    if (!jemputCoords || !antarCoords) return;

    try {
        const url = `https://router.project-osrm.org/route/v1/driving/${parkirLng},${parkirLat};${jemputCoords.lng},${jemputCoords.lat};${antarCoords.lng},${antarCoords.lat};${parkirLng},${parkirLat}?overview=full&geometries=geojson`;
        
        const response = await fetch(url);
        const data = await response.json();

        if (data.routes && data.routes.length > 0) {
            const route = data.routes[0];
            const distance = (route.distance / 1000).toFixed(2);
            const duration = (route.duration / 3600).toFixed(1);
            const harga = Math.round(distance * 100000);
            const estimasiHari = Math.ceil(distance / 50);

            // Set harga asli untuk validasi
            hargaAsli = harga;
            document.getElementById('hargaAsliDisplay').textContent = harga.toLocaleString('id-ID');
            document.getElementById('minHargaTawar').textContent = Math.round(harga * 0.9).toLocaleString('id-ID');

            document.getElementById('routeInfo').style.display = 'block';
            document.getElementById('totalJarak').textContent = distance;
            document.getElementById('estimasiBiaya').textContent = harga.toLocaleString('id-ID');
            document.getElementById('estimasiWaktu').textContent = duration;
            document.getElementById('estimasiHariDisplay').textContent = estimasiHari;
            document.getElementById('harga_sewa').value = harga;
            document.getElementById('total_jarak').value = distance;
            document.getElementById('estimasi_hari').value = estimasiHari;

            if (routeLayer) map.removeLayer(routeLayer);
            routeLayer = L.geoJSON(route.geometry, {
                style: { color: '#3b82f6', weight: 4, opacity: 0.7 }
            }).addTo(map);

            const bounds = L.latLngBounds([
                [parkirLat, parkirLng],
                [jemputCoords.lat, jemputCoords.lng],
                [antarCoords.lat, antarCoords.lng]
            ]);
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    } catch (error) {
        console.error('Error calculating route:', error);
    }
}

function validateHargaTawar() {
    const hargaTawarInput = document.getElementById('harga_tawar');
    const hargaTawar = parseFloat(hargaTawarInput.value) || 0;
    const minHarga = hargaAsli * 0.9;
    
    const errorDiv = document.getElementById('errorHargaTawar');
    const infoDiv = document.getElementById('hargaTawarInfo');
    const selisihInfo = document.getElementById('selisihInfo');
    
    if (hargaTawar > 0) {
        infoDiv.classList.remove('hidden');
        
        if (hargaTawar < minHarga) {
            hargaTawarInput.classList.add('border-red-500', 'bg-red-50');
            hargaTawarInput.classList.remove('border-green-500', 'bg-green-50');
            errorDiv.classList.remove('hidden');
            selisihInfo.classList.add('hidden');
        } else {
            hargaTawarInput.classList.remove('border-red-500', 'bg-red-50');
            hargaTawarInput.classList.add('border-green-500', 'bg-green-50');
            errorDiv.classList.add('hidden');
            
            const selisih = hargaAsli - hargaTawar;
            document.getElementById('selisihTawar').textContent = selisih.toLocaleString('id-ID');
            selisihInfo.classList.remove('hidden');
        }
    } else {
        hargaTawarInput.classList.remove('border-red-500', 'bg-red-50', 'border-green-500', 'bg-green-50');
        errorDiv.classList.add('hidden');
        infoDiv.classList.add('hidden');
    }
}

// Form validation - HANYA SATU KALI
document.getElementById('formPemesanan').addEventListener('submit', function(e) {
    if (!selectedJenisTruk) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Mohon pilih jenis truk terlebih dahulu!',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    if (!jemputCoords || !antarCoords) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Mohon pilih titik jemput dan titik antar!',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    if (!document.getElementById('armada_id').value) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Mohon pilih armada!',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    if (!document.getElementById('estimasi_hari').value) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: 'Estimasi hari belum dihitung. Mohon pilih lokasi dan armada terlebih dahulu!',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    // Validasi harga tawar
    const hargaTawarInput = document.getElementById('harga_tawar');
    const hargaTawar = parseFloat(hargaTawarInput.value) || 0;
    
    if (hargaTawar > 0) {
        const minHarga = hargaAsli * 0.9;
        
        if (hargaTawar < minHarga) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Harga Tawar Tidak Valid',
                text: `Harga tawar tidak boleh kurang dari Rp ${Math.round(minHarga).toLocaleString('id-ID')} (90% dari harga asli)`,
                confirmButtonColor: '#ef4444'
            });
            return;
        }
        
        // Update harga_sewa dengan harga tawar jika valid
        document.getElementById('harga_sewa').value = hargaTawar;
    }

    // Validasi bobot vs kapasitas armada
    const bobotVal = parseFloat(document.getElementById('bobot').value);
    if (selectedArmadaKapasitas && bobotVal > selectedArmadaKapasitas) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Bobot Melebihi Kapasitas',
            text: `Bobot muatan (${bobotVal} Ton) melebihi kapasitas maksimal armada yang dipilih (${selectedArmadaKapasitas} Ton)!`,
            confirmButtonColor: '#ef4444'
        });
        return;
    }
});
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error') }}",
        confirmButtonColor: '#ef4444'
    });
</script>
@endif
@endsection