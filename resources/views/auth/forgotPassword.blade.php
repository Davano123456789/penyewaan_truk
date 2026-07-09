<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Penyewaan Truk</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-8 px-4">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <i class="fas fa-key text-blue-600 text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Lupa Kata Sandi?</h2>
                    <p class="text-gray-600">Masukkan email Anda dan kami akan mengirimkan link untuk reset kata sandi</p>
                </div>

                <!-- Success Message -->
                @if (session('status'))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                            <p class="text-green-700 text-sm">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
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

                <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="contoh@email.com"
                            required
                            autofocus
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 shadow-md hover:shadow-lg"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        KIRIM LINK RESET
                    </button>

                    <!-- Back to Login -->
                    <div class="text-center mt-6">
                        <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Login
                        </a>
                    </div>
                </form>

                <!-- Info Box -->
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi
                    </h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Link reset akan dikirim ke email Anda</li>
                        <li>• Link berlaku selama 60 menit</li>
                        <li>• Periksa folder spam jika tidak menerima email</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>