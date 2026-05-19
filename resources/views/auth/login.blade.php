<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Penyewaan Truk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-8 px-4">
        <div class="max-w-6xl w-full">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex flex-col lg:flex-row min-h-[600px]">
                    <!-- Left Side -->
                    <div class="lg:w-1/2 relative bg-cover bg-center"
                        style="background-image: url('https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?q=80&w=2070');">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-blue-800/85 to-blue-900/90">
                        </div>

                        <div class="relative z-10 h-full flex flex-col justify-center p-12 text-white">
                            <div class="mb-8">
                                <div
                                    class="inline-flex items-center space-x-3 bg-white/10 backdrop-blur-md px-6 py-3 rounded-full">
                                    <i class="fas fa-truck-moving text-2xl"></i>
                                    <span class="text-xl font-bold">sutera jaya</span>
                                </div>
                            </div>

                            <h1 class="text-5xl font-bold mb-6 leading-tight">
                                Selamat Datang<br>Kembali!
                            </h1>
                            <p class="text-xl mb-8 text-blue-100 leading-relaxed">
                                Masuk untuk melanjutkan penyewaan<br>
                                truk dengan mudah dan cepat.
                            </p>

                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-green-500 rounded-full p-2">
                                        <i class="fas fa-truck text-white text-sm"></i>
                                    </div>
                                    <span class="text-lg">Truk Siap Jalan</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="bg-green-500 rounded-full p-2">
                                        <i class="fas fa-clock text-white text-sm"></i>
                                    </div>
                                    <span class="text-lg">Pesan Kapan Saja</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="bg-green-500 rounded-full p-2">
                                        <i class="fas fa-user-shield text-white text-sm"></i>
                                    </div>
                                    <span class="text-lg">Data Anda Aman</span>
                                </div>
                            </div>

                            <div class="mt-12 pt-8 border-t border-white/20">
                                <p class="text-blue-100 italic">
                                    "Truk andalan untuk setiap perjalanan Anda"
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side -->
                    <div class="lg:w-1/2 p-8 lg:p-12 flex items-center">
                        <div class="w-full max-w-md mx-auto">
                            <div class="mb-8">
                                <h2 class="text-3xl font-bold text-gray-800 mb-2">Masuk Akun</h2>
                                <p class="text-gray-600">Gunakan email dan kata sandi Anda</p>
                            </div>

                            <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                                @csrf

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

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input type="email" id="email" name="email"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="contoh@email.com" required>
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="kata_sandi" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kata Sandi
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="kata_sandi" name="kata_sandi"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition pr-10"
                                            placeholder="Masukkan kata sandi" required>
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

                                <!-- Submit -->
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 shadow-md hover:shadow-lg">
                                    MASUK
                                </button>

                                <!-- Link ke register -->
                                <div class="text-center mt-6">
                                    <p class="text-gray-600 text-sm">
                                        Belum punya akun?
                                        <a href="{{ route('register') }}"
                                            class="text-blue-600 font-semibold hover:underline">Daftar di sini</a>
                                    </p>
                                </div>
                                <!-- Tambahkan setelah input password -->
                                <div class="flex justify-end mb-4">
                                    <a href="{{ route('password.request') }}"
                                        class="text-sm text-blue-600 hover:underline">
                                        Lupa kata sandi?
                                    </a>
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