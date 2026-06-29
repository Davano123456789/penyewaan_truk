@extends('layouts.masterHome')

@section('title', 'Beranda - Penyewaan Truk')

@section('content')
    <!-- Hero Section -->
    <!-- Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center pt-28 pb-24 overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=1600" alt="Hero Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/60 to-transparent"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-3xl" data-aos="fade-up">
                <span class="inline-block px-4 py-1 rounded-full bg-blue-600/20 text-blue-400 font-semibold text-sm mb-6 border border-blue-600/30">
                    Logistik Terpercaya di Indonesia tes vps
                </span>
                <h1 class="text-5xl md:text-7xl font-extrabold text-white mb-6 leading-tight">
                    PT Sutera Jaya <br>
                    <span class="text-blue-500">Logistik & Distribusi</span>
                </h1>
                <p class="text-gray-300 text-xl mb-10 max-w-xl leading-relaxed font-light">
                    Solusi transportasi logistik terpadu dengan armada modern untuk menjangkau seluruh pelosok negeri. Kami mengutamakan keamanan dan ketepatan waktu.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('pemesanan') }}" class="bg-blue-600 text-white px-10 py-4 rounded-xl font-bold hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-blue-600/30 flex items-center group">
                        Mulai Menyewa
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#tentang" class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-10 py-4 rounded-xl font-bold hover:bg-white/20 transition-all duration-300">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Nilai Unggulan Perusahaan -->
    <section id="keunggulan" class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">NILAI UNGGULAN PERUSAHAAN</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Komitmen kami dalam memberikan pelayanan terbaik dengan standar profesional yang tinggi untuk kepuasan pelanggan
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                @forelse($keunggulans as $keunggulan)
                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 overflow-hidden group relative hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                    <!-- Background Filler Animation (Smooth Circle) -->
                    <div class="absolute -top-10 -right-10 w-20 h-20 bg-blue-600 rounded-full transition-all duration-700 ease-in-out group-hover:scale-[15] opacity-0 group-hover:opacity-100 z-0"></div>
                    
                    <div class="p-8 relative z-10">
                        <div class="w-14 h-14 flex items-center justify-center mb-6 transition-all duration-300">
                            @if($keunggulan->gambar)
                                <img src="{{ $keunggulan->gambar }}" alt="{{ $keunggulan->judul }}" class="w-full h-full object-contain group-hover:brightness-0 group-hover:invert transition-all duration-300">
                            @else
                                <i class="fas fa-star text-blue-600 text-3xl group-hover:text-white transition-all duration-300"></i>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-white transition-all duration-300">{{ $keunggulan->judul }}</h3>
                        <p class="text-gray-600 group-hover:text-blue-50 transition-all duration-300 leading-relaxed">{{ $keunggulan->deskripsi }}</p>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">Belum ada data keunggulan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="tentang" class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <!-- Left Side - Images -->
                <div class="md:w-1/2 space-y-6" data-aos="fade-up">
                    <div class="bg-gray-200 h-72 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800" alt="Truk 1" class="w-full h-full object-cover">
                    </div>
                    <div class="bg-gray-200 h-72 rounded-lg overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=800" alt="Truk 2" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Right Side - Content -->
                <div class="md:w-1/2" data-aos="fade-up">
                    <h2 class="text-4xl font-bold text-gray-800 mb-6">TENTANG KAMI</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Kami adalah perusahaan penyewaan truk yang telah berpengalaman melayani berbagai kebutuhan transportasi logistik. Dengan komitmen tinggi terhadap kepuasan pelanggan, kami menyediakan layanan yang handal dan efisien.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Tim profesional kami siap membantu Anda dalam setiap tahap pengiriman, mulai dari konsultasi hingga pengiriman barang sampai tujuan dengan aman dan tepat waktu.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-8">
                        Dengan armada modern dan sistem tracking real-time, kami memastikan setiap pengiriman berjalan lancar dan transparan. Kepercayaan Anda adalah prioritas utama kami.
                    </p>

                    <!-- Social Media Icons -->
                    <div class="flex gap-4">
                        <a href="https://facebook.com" target="_blank" class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all duration-300 text-blue-600">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank" class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all duration-300 text-blue-600">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="https://wa.me/6282211167667" target="_blank" class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all duration-300 text-blue-600">
                            <i class="fab fa-whatsapp text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi Misi Section -->
    <section id="visi-misi" class="py-24 relative bg-cover bg-center bg-fixed" style="background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1600');">
        <!-- Overlay gelap -->
        <div class="absolute inset-0 bg-black bg-opacity-70"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-5xl mx-auto" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-center mb-12 text-white">VISI DAN MISI KAMI</h2>
                <div class="grid md:grid-cols-2 gap-12">
                    <div class="bg-blue-600 bg-opacity-90 backdrop-blur-sm p-8 rounded-lg shadow-xl hover:bg-opacity-100 transition-all duration-300">
                        <div class="flex items-center mb-4">
                            <div class="bg-white w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-eye text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-white">VISI</h3>
                        </div>
                        <p class="leading-relaxed text-white">
                            Menjadi penyedia layanan penyewaan truk terdepan di Indonesia yang terpercaya dan profesional, dengan fokus pada kepuasan pelanggan dan efisiensi operasional.
                        </p>
                    </div>
                    <div class="bg-blue-600 bg-opacity-90 backdrop-blur-sm p-8 rounded-lg shadow-xl hover:bg-opacity-100 transition-all duration-300">
                        <div class="flex items-center mb-4">
                            <div class="bg-white w-12 h-12 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-bullseye text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-white">MISI</h3>
                        </div>
                        <ul class="space-y-3 text-white">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-3 text-white"></i>
                                <span>Menyediakan armada truk berkualitas dan terawat</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-3 text-white"></i>
                                <span>Memberikan pelayanan profesional dan responsif</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-3 text-white"></i>
                                <span>Mengutamakan keamanan dan ketepatan waktu</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Riwayat Penyewaan Client Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">RIWAYAT PENYEWAAN CLIENT</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Testimoni dan pengalaman dari berbagai klien yang telah mempercayakan kebutuhan logistik mereka kepada kami
                </p>
            </div>

            <!-- Testimonial Slider Container -->
            <div class="relative max-w-7xl mx-auto">
                <!-- Slider Wrapper -->
                <div class="overflow-hidden">
                    <div id="testimonialSlider" class="flex transition-transform duration-500 ease-in-out">
                        @forelse($riwayatPenyewaan as $penyewaan)
                        <!-- Rental Card -->
                        <div class="flex-shrink-0 w-full md:w-1/3 px-4" data-aos="fade-up">
                            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden h-full flex flex-col group relative">
                                <!-- Background Filler Animation -->
                                <div class="absolute -top-20 -right-20 w-32 h-32 bg-blue-600 rounded-full transition-all duration-700 ease-in-out group-hover:scale-[15] opacity-0 group-hover:opacity-100 z-0"></div>

                                <!-- Image Section -->
                                <div class="relative h-48 overflow-hidden z-10">
                                    @php
                                        $firstKeranjang = $penyewaan->keranjangs->first();
                                        $buktiSelesai = $firstKeranjang->penugasan->bukti_selesai ?? $firstKeranjang->bukti_selesai ?? null;
                                    @endphp
                                    
                                    @if($buktiSelesai)
                                        <img src="{{ $buktiSelesai }}" alt="Bukti" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600" alt="Truck" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @endif
                                    
                                    <div class="absolute top-4 right-4">
                                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-green-600 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm border border-green-100 group-hover:bg-green-500 group-hover:text-white group-hover:border-transparent transition-colors duration-300">
                                            Selesai
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6 flex-1 flex flex-col relative z-10">
                                    <!-- Client Header -->
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="relative">
                                            <div class="bg-gradient-to-br from-blue-600 to-blue-400 w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200 group-hover:shadow-none group-hover:from-white/20 group-hover:to-white/10 transition-all duration-300">
                                                <i class="fas fa-user-tie text-xl"></i>
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 bg-green-500 w-4 h-4 rounded-full border-2 border-white group-hover:border-blue-600 transition-all duration-300"></div>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold text-gray-800 leading-tight group-hover:text-white transition-colors duration-300">{{ $penyewaan->client->nama ?? 'Client' }}</h3>
                                            <div class="flex items-center gap-1">
                                                <i class="fas fa-star text-amber-400 text-[10px]"></i>
                                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter group-hover:text-blue-100 transition-colors duration-300">Verified Client</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Route & Details Box -->
                                    <div class="bg-blue-50/50 rounded-2xl p-4 mb-6 border border-blue-50 group-hover:bg-white/10 group-hover:border-white/20 transition-all duration-300">
                                        <div class="flex flex-col gap-4">
                                            <!-- Delivery Route -->
                                            <div class="relative pl-6">
                                                <div class="absolute left-1.5 top-1.5 bottom-1.5 w-0.5 bg-blue-200 border-l border-dashed group-hover:bg-white/30"></div>
                                                <div class="absolute left-0 top-0 w-3 h-3 rounded-full bg-blue-600 border-2 border-white group-hover:bg-white group-hover:border-blue-600"></div>
                                                <div class="absolute left-0 bottom-0 w-3 h-3 rounded-full bg-red-500 border-2 border-white"></div>
                                                
                                                <div class="mb-4">
                                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-0.5 group-hover:text-blue-200 transition-colors duration-300">Asal</p>
                                                    <p class="text-xs text-gray-700 font-bold line-clamp-1 group-hover:text-white transition-colors duration-300">{{ $firstKeranjang->rute->tempat_jemput ?? $firstKeranjang->tempat_jemput ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-0.5 group-hover:text-blue-200 transition-colors duration-300">Tujuan</p>
                                                    <p class="text-xs text-gray-700 font-bold line-clamp-1 group-hover:text-white transition-colors duration-300">{{ $firstKeranjang->rute->tempat_antar ?? $firstKeranjang->tempat_antar ?? '-' }}</p>
                                                </div>
                                            </div>

                                            <!-- Cargo Info -->
                                            <div class="flex justify-between items-center pt-3 border-t border-blue-100/50 group-hover:border-white/10 transition-colors duration-300">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-7 h-7 bg-white rounded-lg flex items-center justify-center shadow-sm group-hover:bg-white/20">
                                                        <i class="fas fa-box-open text-blue-500 text-xs group-hover:text-white"></i>
                                                    </div>
                                                    <span class="text-xs text-gray-600 font-bold group-hover:text-white">{{ $firstKeranjang->barang_muatan ?? 'Logistik' }}</span>
                                                </div>
                                                <span class="text-[10px] bg-blue-600 text-white px-2 py-0.5 rounded-md font-bold tracking-tight group-hover:bg-white group-hover:text-blue-600 transition-all duration-300">{{ $firstKeranjang->armada->merek ?? 'Unit' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer Meta -->
                                    <div class="mt-auto flex justify-between items-center">
                                        <div class="flex items-center gap-1.5 text-gray-400 group-hover:text-blue-200 transition-colors duration-300">
                                            <i class="fas fa-fingerprint text-xs"></i>
                                            <span class="text-[9px] font-bold uppercase tracking-widest">TRX-{{ substr($penyewaan->kode_transaksi, -6) }}</span>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest group-hover:text-blue-200 transition-colors duration-300">Tgl Selesai</p>
                                            <p class="text-[10px] text-gray-800 font-black group-hover:text-white transition-colors duration-300">{{ $penyewaan->updated_at->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="w-full text-center py-12">
                            <p class="text-gray-500">Belum ada riwayat penyewaan yang selesai.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <button id="prevTestiBtn" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-blue-600 hover:text-white transition-all duration-300 z-10">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="nextTestiBtn" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-blue-600 hover:text-white transition-all duration-300 z-10">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Dots Indicator -->
                <div id="testiDotsContainer" class="flex justify-center gap-
                2 mt-8">
                    <!-- Dots will be generated by JavaScript -->
                </div>
            </div>
        </div>
    </section>

    <!-- Bekerja Sama Dengan Section -->
<!-- Bekerja Sama Dengan Section -->
<section id="client" class="py-24 bg-blue-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">BEKERJA SAMA DENGAN</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Dipercaya oleh berbagai perusahaan ternama untuk menangani kebutuhan logistik dan distribusi mereka
            </p>
        </div>

        <!-- Slider Container -->
        <div class="relative max-w-6xl mx-auto">
            <!-- Slider Wrapper -->
            <div class="overflow-hidden">
                <div id="logoSlider" class="flex transition-transform duration-500 ease-in-out">
                    @forelse($mitras as $mitra)
                    <!-- Logo Card -->
                    <div class="flex-shrink-0 w-full md:w-1/4 px-4">
                        <div class="bg-white border border-blue-100 p-8 rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center h-40">
                            <div class="text-center">
                                @if($mitra->logo)
                                    <div class="w-32 h-20 mx-auto rounded flex items-center justify-center mb-2 overflow-hidden">
                                        <img src="{{ $mitra->logo }}" 
                                             alt="Logo {{ $mitra->nama }}" 
                                             class="w-full h-full object-contain">
                                    </div>
                                @else
                                    <div class="bg-gray-200 w-32 h-20 mx-auto rounded flex items-center justify-center mb-2">
                                        <i class="fas fa-building text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                                <p class="text-gray-700 font-semibold">{{ $mitra->nama }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Default jika tidak ada data -->
                    <div class="flex-shrink-0 w-full md:w-1/4 px-4">
                        <div class="bg-white border border-blue-100 p-8 rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center h-40">
                            <div class="text-center">
                                <div class="bg-gray-200 w-32 h-20 mx-auto rounded flex items-center justify-center mb-2">
                                    <i class="fas fa-building text-4xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-700 font-semibold">Belum Ada Mitra</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Navigation Buttons -->
            @if($mitras->count() > 4)
            <button id="prevBtn" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-blue-600 hover:text-white transition-all duration-300 z-10">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="nextBtn" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:bg-blue-600 hover:text-white transition-all duration-300 z-10">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Dots Indicator -->
            <div id="dotsContainer" class="flex justify-center gap-2 mt-8">
                <!-- Dots will be generated by JavaScript -->
            </div>
            @endif
        </div>
    </div>
</section>
    <!-- Informasi Kami Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <!-- Left Side - Content -->
                <div class="md:w-1/2">
                    <div class="border-l-4 border-blue-600 pl-6 mb-8">
                        <h2 class="text-4xl font-bold text-gray-800 mb-4">INFORMASI KAMI</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Hubungi kami untuk informasi lebih lanjut mengenai layanan penyewaan truk dan solusi logistik yang kami tawarkan.
                        </p>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Tim customer service kami siap melayani Anda 24/7 untuk memberikan konsultasi dan bantuan terbaik sesuai kebutuhan bisnis Anda.
                        </p>
                        <p class="text-gray-600 leading-relaxed">
                            Dapatkan penawaran harga terbaik dan pengalaman layanan yang profesional dari kami.
                        </p>
                    </div>

                    <!-- Contact Icons -->
                    <div class="space-y-4">
                        <!-- WhatsApp -->
                        <div class="flex items-center gap-4 group">
                            <div class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center group-hover:bg-blue-600 transition-all duration-300">
                                <i class="fab fa-whatsapp text-blue-600 text-2xl group-hover:text-white transition-all duration-300"></i>
                            </div>
                            <a href="https://wa.me/6282211167667" class="text-gray-700 text-lg hover:text-blue-600 transition-all duration-300">0822-1116-7667 (Adang)</a>
                        </div>

                        <!-- Instagram -->
                        <div class="flex items-center gap-4 group">
                            <div class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center group-hover:bg-blue-600 transition-all duration-300">
                                <i class="fab fa-instagram text-blue-600 text-2xl group-hover:text-white transition-all duration-300"></i>
                            </div>
                            <a href="https://instagram.com/suterajaya" class="text-gray-700 text-lg hover:text-blue-600 transition-all duration-300">@suterajaya</a>
                        </div>

                        <!-- Facebook -->
                        <div class="flex items-center gap-4 group">
                            <div class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center group-hover:bg-blue-600 transition-all duration-300">
                                <i class="fab fa-facebook-f text-blue-600 text-2xl group-hover:text-white transition-all duration-300"></i>
                            </div>
                            <a href="https://facebook.com/suterajaya" class="text-gray-700 text-lg hover:text-blue-600 transition-all duration-300">PT Sutera Jaya</a>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Image -->
                <div class="md:w-1/2" data-aos="fade-up">
                    <div class="rounded-lg overflow-hidden shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=800" alt="Truk Kami" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Layanan Kami Section -->
    <section class="py-24 relative bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=1600');">
        <!-- Overlay gelap -->
        <div class="absolute inset-0 bg-black bg-opacity-75"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <!-- Left Side - Image -->
                <div class="md:w-1/2 flex justify-center" data-aos="fade-up">
                    <div class="relative w-full max-w-md">
                        <img src="{{ asset('images/gambar truk.png') }}" alt="Truk Layanan" class="w-full h-auto object-contain drop-shadow-2xl">
                    </div>
                </div>

                <!-- Right Side - Content -->
                <div class="md:w-1/2" data-aos="fade-up">
                    <div class="border-l-4 border-blue-500 pl-6">
                        <h2 class="text-4xl font-bold text-white mb-4">Layanan Kami</h2>
                        <p class="text-gray-300 leading-relaxed mb-6">
                            Jangkauan luas ke seluruh Indonesia dengan berbagai jenis armada truk untuk memenuhi kebutuhan logistik Anda. Layanan profesional dan terpercaya untuk pengiriman yang aman dan tepat waktu.
                        </p>

                        <!-- Coverage Info -->
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-white mb-4">Pengiriman produk dengan menggunakan:</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center gap-3 text-gray-300">
                                    <i class="fas fa-check-circle text-blue-500"></i>
                                    <span>JAWA</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-300">
                                    <i class="fas fa-check-circle text-blue-500"></i>
                                    <span>BALI</span>
                                </div>
                            </div>
                        </div>

                        <!-- Truck Types -->
                        <div>
                            <h3 class="text-xl font-bold text-white mb-4">Jenis Truk Tersedia:</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center gap-3 text-gray-300">
                                    <i class="fas fa-trailer text-blue-500"></i>
                                    <span>CDD</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-300">
                                    <i class="fas fa-cube text-blue-500"></i>
                                    <span>BOX</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-300">
                                    <i class="fas fa-truck-moving text-blue-500"></i>
                                    <span>WINGBOX</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Logo Slider (Dynamic)
    const slider = document.getElementById('logoSlider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const dotsContainer = document.getElementById('dotsContainer');

    const totalSlides = {{ $mitras->count() }};

    if (totalSlides > 0) {
        let currentIndex = 0;
        const slidesPerView = window.innerWidth >= 768 ? 4 : 1;
        const maxIndex = Math.max(0, totalSlides - slidesPerView);
        
        if (totalSlides > slidesPerView && dotsContainer) {
            for (let i = 0; i <= maxIndex; i++) {
                const dot = document.createElement('button');
                dot.className = 'w-3 h-3 rounded-full transition-all duration-300';
                dot.style.backgroundColor = i === 0 ? '#2563eb' : '#d1d5db';
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }
        }
        
        const dots = dotsContainer ? dotsContainer.querySelectorAll('button') : [];
        
        function updateSlider() {
            const slideWidth = 100 / slidesPerView;
            slider.style.transform = `translateX(-${currentIndex * slideWidth}%)`;
            
            dots.forEach((dot, index) => {
                dot.style.backgroundColor = index === currentIndex ? '#2563eb' : '#d1d5db';
            });
        }
        
        function goToSlide(index) {
            currentIndex = index;
            updateSlider();
        }
        
        function nextSlide() {
            currentIndex = currentIndex >= maxIndex ? 0 : currentIndex + 1;
            updateSlider();
        }
        
        function prevSlide() {
            currentIndex = currentIndex <= 0 ? maxIndex : currentIndex - 1;
            updateSlider();
        }
        
        if (prevBtn && nextBtn) {
            nextBtn.addEventListener('click', nextSlide);
            prevBtn.addEventListener('click', prevSlide);
            
            let autoSlide = setInterval(nextSlide, 3000);
            
            slider.addEventListener('mouseenter', () => {
                clearInterval(autoSlide);
            });
            
            slider.addEventListener('mouseleave', () => {
                autoSlide = setInterval(nextSlide, 3000);
            });
        }
    }

    // Testimonial Slider (tetap seperti sebelumnya)
    const testiSlider = document.getElementById('testimonialSlider');
    const prevTestiBtn = document.getElementById('prevTestiBtn');
    const nextTestiBtn = document.getElementById('nextTestiBtn');
    const testiDotsContainer = document.getElementById('testiDotsContainer');
    
    let currentTestiIndex = 0;
    const totalTestiSlides = {{ $riwayatPenyewaan->count() }};
    const testiSlidesPerView = window.innerWidth >= 768 ? 3 : 1;
    const maxTestiIndex = totalTestiSlides - testiSlidesPerView;
    
    for (let i = 0; i <= maxTestiIndex; i++) {
        const dot = document.createElement('button');
        dot.className = 'w-3 h-3 rounded-full transition-all duration-300';
        dot.style.backgroundColor = i === 0 ? '#2563eb' : '#d1d5db';
        dot.addEventListener('click', () => goToTestiSlide(i));
        testiDotsContainer.appendChild(dot);
    }
    
    const testiDots = testiDotsContainer.querySelectorAll('button');
    
    function updateTestiSlider() {
        const slideWidth = 100 / testiSlidesPerView;
        testiSlider.style.transform = `translateX(-${currentTestiIndex * slideWidth}%)`;
        
        testiDots.forEach((dot, index) => {
            dot.style.backgroundColor = index === currentTestiIndex ? '#2563eb' : '#d1d5db';
        });
    }
    
    function goToTestiSlide(index) {
        currentTestiIndex = index;
        updateTestiSlider();
    }
    
    function nextTestiSlide() {
        currentTestiIndex = currentTestiIndex >= maxTestiIndex ? 0 : currentTestiIndex + 1;
        updateTestiSlider();
    }
    
    function prevTestiSlide() {
        currentTestiIndex = currentTestiIndex <= 0 ? maxTestiIndex : currentTestiIndex - 1;
        updateTestiSlider();
    }
    
    nextTestiBtn.addEventListener('click', nextTestiSlide);
    prevTestiBtn.addEventListener('click', prevTestiSlide);
    
    let autoTestiSlide = setInterval(nextTestiSlide, 4000);
    
    testiSlider.addEventListener('mouseenter', () => {
        clearInterval(autoTestiSlide);
    });
    
    testiSlider.addEventListener('mouseleave', () => {
        autoTestiSlide = setInterval(nextTestiSlide, 4000);
    });
</script>
@endsection