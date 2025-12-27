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
                            <div class="input-group">
                                <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                          id="alamat" name="alamat" rows="3" 
                                          placeholder="Ketik alamat lengkap atau klik di peta">{{ old('alamat') }}</textarea>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info" id="searchBtn" title="Cari lokasi">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-lightbulb"></i> Ketik alamat lalu klik <strong>Cari</strong>, atau klik di peta
                            </small>
                            @error('alamat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <!-- Latitude (Hidden) -->
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                        
                        <!-- Longitude (Hidden) -->
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                        <!-- Koordinat Info -->
                        <div class="alert alert-info">
                            <small>
                                <strong><i class="fas fa-map-pin"></i> Koordinat:</strong><br>
                                <span id="koordinat-info">Belum ada lokasi yang dipilih</span>
                            </small>
                        </div>

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
                        <button type="reset" class="btn btn-warning" id="resetBtn">
                            <i class="fas fa-redo"></i> Reset
                        </button>
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

        // Event Enter di Textarea
        document.getElementById('alamat').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                searchLocation(this.value.trim());
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

        // Reset Button
        document.getElementById('resetBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Yakin ingin reset?',
                text: "Semua input akan dikosongkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form').reset();
                    
                    if (marker) {
                        map.removeLayer(marker);
                        marker = null;
                    }
                    
                    document.getElementById('koordinat-info').innerHTML = 'Belum ada lokasi yang dipilih';
                    map.setView([-6.2088, 106.8456], 11);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Form telah direset',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
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