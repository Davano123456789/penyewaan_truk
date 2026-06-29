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
        <h1 class="h3 mb-0 text-gray-800">Edit Parkiran</h1>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Parkiran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('parkir.update', $parkir->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Kolom Kiri - Form -->
                    <div class="col-md-6">
                        <!-- Nama -->
                        <div class="form-group">
                            <label for="nama" class="font-weight-bold">Nama Parkiran</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama', $parkir->nama) }}" 
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
                                              placeholder="Ketik alamat lengkap atau klik di peta" autocomplete="off" required>{{ old('alamat', $parkir->alamat) }}</textarea>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-info" id="searchBtn" title="Cari lokasi berdasarkan alamat">
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
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $parkir->latitude) }}">
                        
                        <!-- Longitude (Hidden) -->
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $parkir->longitude) }}">

                        <!-- Koordinat Info (hidden, used by JS) -->
                        <span id="koordinat-info" class="d-none"></span>
                    </div>

                    <!-- Kolom Kanan - Map -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Pilih Lokasi di Peta</label>
                            <div id="map" style="height: 450px; width: 100%; border-radius: 5px; border: 2px solid #dee2e6;"></div>
                            <small class="form-text text-muted">
                                <i class="fas fa-hand-pointer"></i> Klik pada peta untuk mengubah lokasi parkiran
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
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
    // Tunggu sampai halaman selesai loading
    document.addEventListener('DOMContentLoaded', function() {
        // Koordinat dari database
        var initialLat = {{ $parkir->latitude ?? -6.2088 }};
        var initialLng = {{ $parkir->longitude ?? 106.8456 }};
        var initialZoom = {{ ($parkir->latitude && $parkir->longitude) ? 15 : 11 }};

        // Inisialisasi peta
        var map = L.map('map').setView([initialLat, initialLng], initialZoom);
        
        // Tambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Fix untuk ukuran peta yang tidak muncul dengan benar
        setTimeout(function() {
            map.invalidateSize();
        }, 100);

        // Variabel untuk menyimpan marker
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
                .catch(error => console.error('Error searching:', error));
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

        // Tampilkan marker awal jika ada koordinat
        @if($parkir->latitude && $parkir->longitude)
            marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(map);
            marker.bindPopup("<b>{{ $parkir->nama }}</b><br>Lokasi Saat Ini").openPopup();

            // Event ketika marker di-drag
            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat.toFixed(6);
                document.getElementById('longitude').value = position.lng.toFixed(6);
                document.getElementById('koordinat-info').innerHTML = 
                    '<strong>Latitude:</strong> ' + position.lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + position.lng.toFixed(6);
                getAddress(position.lat, position.lng);
            });
        @endif

        // Fungsi untuk mendapatkan alamat dari koordinat (Reverse Geocoding)
        function getAddress(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        document.getElementById('alamat').value = data.display_name;
                    }
                })
                .catch(error => {
                    console.log('Error:', error);
                });
        }

        // Fungsi untuk mencari lokasi berdasarkan alamat (Forward Geocoding)
        function searchLocation(alamat) {
            if (!alamat || alamat.trim() === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Masukkan alamat terlebih dahulu!',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Tampilkan loading
            document.getElementById('searchBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            document.getElementById('searchBtn').disabled = true;

            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(alamat)}&limit=1`)
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    document.getElementById('searchBtn').innerHTML = '<i class="fas fa-search"></i>';
                    document.getElementById('searchBtn').disabled = false;

                    if (data.length > 0) {
                        var lat = parseFloat(data[0].lat);
                        var lng = parseFloat(data[0].lon);

                        // Pindahkan peta ke lokasi baru
                        map.setView([lat, lng], 16);

                        // Hapus marker lama (jika ada)
                        if (marker) {
                            map.removeLayer(marker);
                        }

                        // Tambahkan marker baru
                        marker = L.marker([lat, lng], {
                            draggable: true
                        }).addTo(map);
                        marker.bindPopup("<b>Lokasi Ditemukan</b><br>" + data[0].display_name).openPopup();

                        // Update input latitude & longitude
                        document.getElementById('latitude').value = lat.toFixed(6);
                        document.getElementById('longitude').value = lng.toFixed(6);

                        // Update info koordinat
                        document.getElementById('koordinat-info').innerHTML = 
                            '<strong>Latitude:</strong> ' + lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + lng.toFixed(6);

                        // Update alamat dengan hasil pencarian
                        document.getElementById('alamat').value = data[0].display_name;

                        // SweetAlert sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Lokasi Ditemukan!',
                            text: 'Marker telah diupdate di peta',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Event ketika marker di-drag
                        marker.on('dragend', function(e) {
                            var position = marker.getLatLng();
                            document.getElementById('latitude').value = position.lat.toFixed(6);
                            document.getElementById('longitude').value = position.lng.toFixed(6);
                            document.getElementById('koordinat-info').innerHTML = 
                                '<strong>Latitude:</strong> ' + position.lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + position.lng.toFixed(6);
                            getAddress(position.lat, position.lng);
                        });
                    } else {
                        // SweetAlert jika alamat tidak ditemukan
                        Swal.fire({
                            icon: 'error',
                            title: 'Alamat Tidak Ditemukan!',
                            html: '<p>Coba masukkan alamat yang lebih lengkap atau klik langsung di peta untuk memilih lokasi.</p>',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.log('Error:', error);
                    document.getElementById('searchBtn').innerHTML = '<i class="fas fa-search"></i>';
                    document.getElementById('searchBtn').disabled = false;
                    
                    // SweetAlert jika terjadi error
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: 'Gagal mencari lokasi. Silakan coba lagi.',
                        confirmButtonColor: '#d33'
                    });
                });
        }

        // Event listener untuk tombol Cari
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

        // Event listener untuk klik pada peta
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            
            // Hapus marker lama jika ada
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Tambahkan marker baru (draggable)
            marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);
            marker.bindPopup("<b>Lokasi Dipilih</b><br>Lat: " + lat.toFixed(6) + "<br>Lng: " + lng.toFixed(6)).openPopup();
            
            // Set nilai latitude dan longitude
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            
            // Update koordinat info
            document.getElementById('koordinat-info').innerHTML = 
                '<strong>Latitude:</strong> ' + lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + lng.toFixed(6);
            
            // Dapatkan alamat dari koordinat
            getAddress(lat, lng);

            // Event ketika marker di-drag
            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat.toFixed(6);
                document.getElementById('longitude').value = position.lng.toFixed(6);
                document.getElementById('koordinat-info').innerHTML = 
                    '<strong>Latitude:</strong> ' + position.lat.toFixed(6) + '<br><strong>Longitude:</strong> ' + position.lng.toFixed(6);
                getAddress(position.lat, position.lng);
            });
        });


    });
</script>
@endsection