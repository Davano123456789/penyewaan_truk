<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Penyewaan Truk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center py-8 px-4">
        <div class="max-w-6xl w-full">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex flex-col lg:flex-row min-h-[600px]">
                    <!-- Left Side - Background Image with Overlay -->
                    <div class="lg:w-1/2 relative bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?q=80&w=2070');">
                        <!-- Dark Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-blue-800/85 to-blue-900/90"></div>
                        
                        <!-- Content -->
                        <div class="relative z-10 h-full flex flex-col justify-center p-12 text-white">
                            <!-- Logo/Brand -->
                            <div class="mb-8">
                                <div class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-md px-6 py-3 rounded-full">
                                    <i class="fas fa-truck-moving text-2xl"></i>
                                    <span class="text-xl font-bold">TruckRental</span>
                                </div>
                            </div>

                            <!-- Welcome Text -->
                            <div>
                                <h1 class="text-5xl font-bold mb-6 leading-tight">
                                    Selamat<br>Bergabung!
                                </h1>
                                <p class="text-xl mb-8 text-blue-100 leading-relaxed">
                                    Daftar sekarang dan nikmati kemudahan<br>
                                    dalam menyewa truk untuk kebutuhan Anda
                                </p>

                                <!-- Features -->
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="bg-green-500 rounded-full p-2 flex-shrink-0">
                                            <i class="fas fa-check text-white text-sm"></i>
                                        </div>
                                        <span class="text-lg">Armada Lengkap & Terawat</span>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="bg-green-500 rounded-full p-2 flex-shrink-0">
                                            <i class="fas fa-check text-white text-sm"></i>
                                        </div>
                                        <span class="text-lg">Proses Cepat & Mudah</span>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="bg-green-500 rounded-full p-2 flex-shrink-0">
                                            <i class="fas fa-check text-white text-sm"></i>
                                        </div>
                                        <span class="text-lg">Pelayanan 24/7</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Quote -->
                            <div class="mt-12 pt-8 border-t border-white/20">
                                <p class="text-blue-100 italic">
                                    "Solusi terpercaya untuk kebutuhan transportasi Anda"
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Registration Form -->
                    <div class="lg:w-1/2 p-8 lg:p-12 flex items-center overflow-y-auto">
                        <div class="w-full max-w-md mx-auto">
                            <!-- Header -->
                            <div class="mb-8">
                                <h2 class="text-3xl font-bold text-gray-800 mb-2">Daftar Akun</h2>
                                <p class="text-gray-600">Isi form di bawah untuk membuat akun</p>
                            </div>

                            <!-- Form -->
                            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                                @csrf

                                <!-- Error Messages -->
                                @if ($errors->any())
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                                            <div>
                                                <h3 class="text-red-800 font-semibold text-sm mb-2">Terjadi kesalahan:</h3>
                                                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Pilihan Peran -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                                        Daftar Sebagai
                                    </label>
                                    <div class="space-y-3">
                                        <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                            <input 
                                                type="radio" 
                                                name="peran_id" 
                                                value="2" 
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500"
                                                required
                                                checked
                                            >
                                            <div class="ml-3">
                                                <span class="font-semibold text-gray-800">Client</span>
                                                <p class="text-sm text-gray-600">Saya ingin menyewa truk</p>
                                            </div>
                                        </label>
                                        
                                        <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                            <input 
                                                type="radio" 
                                                name="peran_id" 
                                                value="3" 
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500"
                                                required
                                            >
                                            <div class="ml-3">
                                                <span class="font-semibold text-gray-800">Sopir</span>
                                                <p class="text-sm text-gray-600">Saya ingin bekerja sebagai sopir</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Nama -->
                                <div>
                                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Lengkap
                                    </label>
                                    <input 
                                        type="text" 
                                        id="nama" 
                                        name="nama" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="Masukkan nama lengkap"
                                        required
                                    >
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="contoh@email.com"
                                        required
                                    >
                                </div>
  <!-- Kata Sandi -->
                                <div>
                                    <label for="kata_sandi" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kata Sandi
                                    </label>
                                    <input 
                                        type="password" 
                                        id="kata_sandi" 
                                        name="kata_sandi" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="Minimal 8 karakter"
                                        required
                                    >
                                </div>
                                <!-- Umur -->
                                <div>
                                    <label for="umur" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Umur
                                    </label>
                                    <input 
                                        type="number" 
                                        id="umur" 
                                        name="umur" 
                                        min="17"
                                        max="100"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="Masukkan umur"
                                        required
                                    >
                                </div>

                                <!-- Telepon -->
                                <div>
                                    <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Telepon
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="telepon" 
                                        name="telepon" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="08xxxxxxxxxx"
                                    >
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Alamat
                                    </label>
                                    <textarea 
                                        id="alamat" 
                                        name="alamat" 
                                        rows="2"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                                        placeholder="Alamat lengkap"
                                    ></textarea>
                                </div>

                              

                                <!-- Terms -->
                                <div class="flex items-start">
                                    <input 
                                        type="checkbox" 
                                        name="terms" 
                                        id="terms"
                                        class="mt-1 mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        required
                                    >
                                    <label for="terms" class="text-sm text-gray-600">
                                        Saya setuju dengan <a href="#" class="text-blue-600 hover:underline font-semibold">Syarat & Ketentuan</a>
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <button 
                                    type="submit" 
                                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 shadow-md hover:shadow-lg"
                                >
                                    DAFTAR SEKARANG
                                </button>

                                <!-- Login Link -->
                                <div class="text-center mt-6">
                                    <p class="text-gray-600 text-sm">
                                        Sudah punya akun? 
                                        <a href="#" class="text-blue-600 font-semibold hover:underline">Login di sini</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>