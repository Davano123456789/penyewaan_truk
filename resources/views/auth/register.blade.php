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
                    <div class="lg:w-1/2 relative bg-cover bg-center"
                        style="background-image: url('https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?q=80&w=2070');">
                        <!-- Dark Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-blue-800/85 to-blue-900/90">
                        </div>

                        <!-- Content -->
                        <div class="relative z-10 h-full flex flex-col justify-center p-12 text-white">

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
                            <div class="mb-8 text-center">
                                <img src="{{ asset('logo-sutra-jaya.png') }}" alt="Logo" class="h-16 w-auto object-contain mx-auto mb-6">
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



                                <!-- Nama -->
                                <div>
                                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Lengkap
                                    </label>
                                    <input type="text" id="nama" name="nama"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="Masukkan nama lengkap" required>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input type="email" id="email" name="email"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="contoh@email.com" required>
                                </div>
                                <!-- Kata Sandi -->
                                <div>
                                    <label for="kata_sandi" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kata Sandi
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="kata_sandi" name="kata_sandi"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition pr-10"
                                            placeholder="Minimal 8 karakter" required>
                                        <button type="button" onclick="togglePassword()"
                                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-blue-600 transition">
                                            <i id="eyeIcon" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <script>
                                    function togglePassword() {
                                        const passwordInput = document.getElementById('kata_sandi');
                                        const eyeIcon = document.getElementById('eyeIcon');

                                        if (passwordInput.type === 'password') {
                                            passwordInput.type = 'text';
                                            eyeIcon.classList.remove('fa-eye');
                                            eyeIcon.classList.add('fa-eye-slash');
                                        } else {
                                            passwordInput.type = 'password';
                                            eyeIcon.classList.remove('fa-eye-slash');
                                            eyeIcon.classList.add('fa-eye');
                                        }
                                    }
                                </script>
                                <!-- Umur -->
                                <div>
                                    <label for="umur" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Umur
                                    </label>
                                    <input type="number" id="umur" name="umur" min="17" max="100"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="Masukkan umur" required>
                                </div>

                                <!-- Telepon -->
                                <div>
                                    <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Telepon
                                    </label>
                                    <input type="tel" id="telepon" name="telepon"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="08xxxxxxxxxx">
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Alamat
                                    </label>
                                    <textarea id="alamat" name="alamat" rows="2"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none"
                                        placeholder="Alamat lengkap"></textarea>
                                </div>





                                <!-- Submit Button -->
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 shadow-md hover:shadow-lg">
                                    DAFTAR SEKARANG
                                </button>

                                <!-- Login Link -->
                                <div class="text-center mt-6">
                                    <p class="text-gray-600 text-sm">
                                        Sudah punya akun?
                                        <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">Login di sini</a>
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