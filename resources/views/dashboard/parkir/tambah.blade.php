@extends('layouts.masterDashboard')

@section('content_dashboard')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>

<style>
    .leaflet-container {
        font-family: 'Nunito', sans-serif;
    }
    #map {
        z-index: 1;
    }
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #d1d3e2;
        border-radius: 0 0 8px 8px;
        max-height: 250px;
        overflow-y: auto;
        z-index: 1050;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }
    .search-result-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eaecf4;
        transition: background-color 0.2s;
    }
    .search-result-item:hover {
        background-color: #f8f9fc;
        color: #4e73df;
    }
</style>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Parkiran</h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Parkiran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('parkir.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <!-- Kolom Kiri - Form -->
                    <div class="col-md-6">
                        <!-- Nama -->
                        <div class="form-group">
                            <label for="nama" class="font-weight-bold">Nama Parkiran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama') }}" 
                                   placeholder="Masukkan nama parkiran" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="form-group">
                            <label for="alamat" class="font-weight-bold">Alamat</label>
                            <div class="position-relative">
                                <div class="input-group">
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                              id="alamat" name="alamat" rows="3" 
                                              placeholder="Ketik alamat lengkap atau klik di peta" autocomplete="off">{{ old('alamat') }}</textarea>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-info" id="searchBtn" title="Cari lokasi">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="searchResults" class="search-results" style="display: none;"></div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-lightbulb"></i> Ketik alamat untuk saran otomatis, atau langsung klik/geser di peta
                            </small>
                            @error('alamat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <!-- Latitude (Hidden) -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        
                        <!-- Longitude (Hidden) -->
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                        <!-- Koordinat Info (hidden, used by JS) -->
                        <span id="koordinat-info" class="d-none">Belum ada lokasi yang dipilih</span>

                        <!-- Info Box -->
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i> <strong>Petunjuk:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li>Nama parkiran wajib diisi</li>
                                <li>Klik pada peta untuk menentukan lokasi parkiran</li>
                                <li>Alamat akan otomatis terisi dari lokasi yang dipilih</li>
                                <li>Koordinat (latitude & longitude) tersimpan otomatis</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Kolom Kanan - Map -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Pilih Lokasi di Peta</label>
                            <div id="map" style="height: 500px; width: 100%; border-radius: 5px; border: 2px solid #dee2e6;"></div>
                            <small class="form-text text-muted">
                                <i class="fas fa-hand-pointer"></i> Klik pada peta untuk menentukan lokasi
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('parkir.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi peta
        var map = L.map('map').setView([-6.2088, 106.8456], 11);
        
        // Tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        setTimeout(function() {
            map.invalidateSize();
        }, 100);

        var marker = null;
        var searchTimeout;
        var currentSearchResults = [];

        function showSearchSuggestions(query) {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    const resultsDiv = document.getElementById('searchResults');
                    currentSearchResults = data;
                    
                    if (data.length > 0) {
                        resultsDiv.innerHTML = data.map((item, index) => `
                            <div class="search-result-item" data-index="${index}">
                                <div class="font-weight-bold small text-dark">${item.display_name.split(',')[0]}</div>
                                <div class="text-xs text-gray-500">${item.display_name}</div>
                            </div>
                        `).join('');
                        resultsDiv.style.display = 'block';
                    } else {
                        resultsDiv.innerHTML = '<div class="search-result-item text-gray-500 small">Tidak ada hasil ditemukan</div>';
                        resultsDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error searching:', error);
                });
        }

        function hideSearchResults() {
            document.getElementById('searchResults').style.display = 'none';
        }

        function selectSearchResult(lat, lng, address) {
            map.setView([lat, lng], 16);

            if (marker) map.removeLayer(marker);

            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.bindPopup("<b>Lokasi Ditemukan</b><br>" + address).openPopup();

            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            document.getElementById('koordinat-info').innerHTML = 
                '<strong>Latitude:</strong> ' + lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + lng.toFixed(6);
            document.getElementById('alamat').value = address;

            hideSearchResults();

            marker.on('dragend', function(e) {
                var pos = marker.getLatLng();
                document.getElementById('latitude').value = pos.lat.toFixed(6);
                document.getElementById('longitude').value = pos.lng.toFixed(6);
                document.getElementById('koordinat-info').innerHTML = 
                    '<strong>Latitude:</strong> ' + pos.lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + pos.lng.toFixed(6);
                getAddress(pos.lat, pos.lng);
            });
        }

        // Reverse Geocoding
        function getAddress(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        document.getElementById('alamat').value = data.display_name;
                    }
                })
                .catch(error => console.log('Error:', error));
        }

        // Forward Geocoding (Search)
        function searchLocation(alamat) {
            if (!alamat || alamat.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Masukkan alamat terlebih dahulu!',
                });
                return;
            }

            document.getElementById('searchBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            document.getElementById('searchBtn').disabled = true;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(alamat)}&limit=1`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('searchBtn').innerHTML = '<i class="fas fa-search"></i>';
                    document.getElementById('searchBtn').disabled = false;

                    if (data.length > 0) {
                        var lat = parseFloat(data[0].lat);
                        var lng = parseFloat(data[0].lon);

                        map.setView([lat, lng], 16);

                        if (marker) map.removeLayer(marker);

                        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                        marker.bindPopup("<b>Lokasi Ditemukan</b>").openPopup();

                        document.getElementById('latitude').value = lat.toFixed(6);
                        document.getElementById('longitude').value = lng.toFixed(6);
                        document.getElementById('koordinat-info').innerHTML = 
                            '<strong>Latitude:</strong> ' + lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + lng.toFixed(6);
                        document.getElementById('alamat').value = data[0].display_name;

                        Swal.fire({
                            icon: 'success',
                            title: 'Lokasi Ditemukan!',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        marker.on('dragend', function(e) {
                            var pos = marker.getLatLng();
                            document.getElementById('latitude').value = pos.lat.toFixed(6);
                            document.getElementById('longitude').value = pos.lng.toFixed(6);
                            document.getElementById('koordinat-info').innerHTML = 
                                '<strong>Latitude:</strong> ' + pos.lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + pos.lng.toFixed(6);
                            getAddress(pos.lat, pos.lng);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Alamat Tidak Ditemukan!',
                            text: 'Coba masukkan alamat yang lebih lengkap',
                        });
                    }
                })
                .catch(error => {
                    console.log('Error:', error);
                    document.getElementById('searchBtn').innerHTML = '<i class="fas fa-search"></i>';
                    document.getElementById('searchBtn').disabled = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: 'Gagal mencari lokasi',
                    });
                });
        }

        // Event Search Button
        document.getElementById('searchBtn').addEventListener('click', function() {
            searchLocation(document.getElementById('alamat').value.trim());
        });

        // Event listener keyup/keydown di Textarea untuk realtime search
        const searchInput = document.getElementById('alamat');
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchLocation(this.value.trim());
            } else {
                clearTimeout(searchTimeout);
                var val = this.value.trim();
                searchTimeout = setTimeout(() => {
                    if (val.length > 2) {
                        showSearchSuggestions(val);
                    } else {
                        hideSearchResults();
                    }
                }, 300);
            }
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
            }
        });

        // Event click pada search results suggestions
        document.getElementById('searchResults').addEventListener('click', function(e) {
            const itemEl = e.target.closest('.search-result-item');
            if (itemEl && itemEl.hasAttribute('data-index')) {
                const index = parseInt(itemEl.getAttribute('data-index'));
                const item = currentSearchResults[index];
                if (item) {
                    selectSearchResult(parseFloat(item.lat), parseFloat(item.lon), item.display_name);
                }
            }
        });

        // Sembunyikan search results saat klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#alamat') && !e.target.closest('#searchResults')) {
                hideSearchResults();
            }
        });

        // Event Click Map
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            
            if (marker) map.removeLayer(marker);
            
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.bindPopup("<b>Lokasi Dipilih</b>").openPopup();
            
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            document.getElementById('koordinat-info').innerHTML = 
                '<strong>Latitude:</strong> ' + lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + lng.toFixed(6);
            
            getAddress(lat, lng);

            marker.on('dragend', function(e) {
                var pos = marker.getLatLng();
                document.getElementById('latitude').value = pos.lat.toFixed(6);
                document.getElementById('longitude').value = pos.lng.toFixed(6);
                document.getElementById('koordinat-info').innerHTML = 
                    '<strong>Latitude:</strong> ' + pos.lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + pos.lng.toFixed(6);
                getAddress(pos.lat, pos.lng);
            });
        });

        // Old Value Handler
        @if(old('latitude') && old('longitude'))
            var oldLat = {{ old('latitude') }};
            var oldLng = {{ old('longitude') }};
            
            marker = L.marker([oldLat, oldLng], { draggable: true }).addTo(map);
            marker.bindPopup("<b>Lokasi Terpilih</b>").openPopup();
            map.setView([oldLat, oldLng], 15);
            
            document.getElementById('koordinat-info').innerHTML = 
                '<strong>Latitude:</strong> ' + oldLat.toFixed(6) + '<br><strong>Longitude:</strong> ' + oldLng.toFixed(6);

            marker.on('dragend', function(e) {
                var pos = marker.getLatLng();
                document.getElementById('latitude').value = pos.lat.toFixed(6);
                document.getElementById('longitude').value = pos.lng.toFixed(6);
                document.getElementById('koordinat-info').innerHTML = 
                    '<strong>Latitude:</strong> ' + pos.lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + pos.lng.toFixed(6);
                getAddress(pos.lat, pos.lng);
            });
        @endif
    });
</script>
@endsection