<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <!-- Icon -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-envelope-open-text text-blue-600 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Verifikasi Email Anda</h2>
                <p class="text-gray-600 text-sm">
                    Terima kasih telah mendaftar! Silakan cek email Anda untuk melanjutkan.
                </p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('message'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('message') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Email verifikasi telah dikirim ke: 
                </p>
                <p class="text-sm font-bold text-blue-900 mt-1">
                    {{ Auth::user()->email }}
                </p>
                <p class="text-xs text-blue-600 mt-2">
                    * Cek folder Inbox atau Spam/Junk
                </p>
            </div>

            <!-- Resend Button -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition mb-4">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <!-- Logout Link -->
            <div class="text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm hover:underline">
                        <i class="fas fa-sign-out-alt mr-1"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>