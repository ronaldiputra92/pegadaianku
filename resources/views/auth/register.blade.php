<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar - {{ config('app.name', 'Pegadaianku') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .slide-in {
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen gradient-bg relative">
        <!-- Background Decorations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <!-- Floating Circles -->
            <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full floating-animation"></div>
            <div class="absolute top-32 right-20 w-16 h-16 bg-white bg-opacity-10 rounded-full floating-animation" style="animation-delay: -2s;"></div>
            <div class="absolute bottom-20 left-20 w-24 h-24 bg-white bg-opacity-10 rounded-full floating-animation" style="animation-delay: -4s;"></div>
            <div class="absolute bottom-32 right-10 w-12 h-12 bg-white bg-opacity-10 rounded-full floating-animation" style="animation-delay: -1s;"></div>
        </div>

        <div class="relative flex items-center justify-center px-4 py-8 sm:py-12 min-h-screen">
            <div class="w-full max-w-4xl">
                <!-- Logo Section -->
                <div class="text-center mb-8 slide-in">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-4 backdrop-blur-sm">
                        <i class="fas fa-coins text-3xl text-white"></i>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-2">Pegadaianku</h1>
                    <p class="text-white text-opacity-80">Bergabunglah dengan Sistem Pegadaian Modern</p>
                </div>

                <!-- Register Card -->
                <div class="glass-effect rounded-2xl shadow-2xl p-8 slide-in" style="animation-delay: 0.2s;">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Akun Baru</h2>
                        <p class="text-gray-600">Daftar sebagai nasabah untuk menggunakan layanan pegadaian</p>
                    </div>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2 mt-0.5"></i>
                                <div>
                                    <div class="font-medium text-red-800 text-sm mb-2">
                                        Terjadi kesalahan dalam pendaftaran
                                    </div>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" x-data="{ showPassword: false, showConfirmPassword: false }">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-blue-500"></i>
                                    Nama Lengkap *
                                </label>
                                <div class="relative">
                                    <input id="name" 
                                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white bg-opacity-50 backdrop-blur-sm" 
                                           type="text" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required 
                                           autofocus 
                                           autocomplete="name"
                                           placeholder="Masukkan nama lengkap" />
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                    Alamat Email *
                                </label>
                                <div class="relative">
                                    <input id="email" 
                                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white bg-opacity-50 backdrop-blur-sm" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autocomplete="username"
                                           placeholder="nama@email.com" />
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-2 text-blue-500"></i>
                                    Nomor Telepon
                                </label>
                                <div class="relative">
                                    <input id="phone" 
                                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white bg-opacity-50 backdrop-blur-sm" 
                                           type="text" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           autocomplete="tel"
                                           placeholder="08xxxxxxxxxx" />
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Identity Number -->
                            <div>
                                <label for="identity_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                    Nomor Identitas (KTP/SIM)
                                </label>
                                <div class="relative">
                                    <input id="identity_number" 
                                           class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white bg-opacity-50 backdrop-blur-sm" 
                                           type="text" 
                                           name="identity_number" 
                                           value="{{ old('identity_number') }}" 
                                           placeholder="16 digit nomor KTP" />
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-id-card text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                    Alamat Lengkap
                                </label>
                                <textarea id="address" 
                                          name="address" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white bg-opacity-50 backdrop-blur-sm"
                                          placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi">{{ old('address') }}</textarea>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-2 text-blue-500"></i>
                                    Kata Sandi *
                                </label>
                                <div class="relative">
                                    <input id="password" 
                                           class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white bg-opacity-50 backdrop-blur-sm" 
                                           :type="showPassword ? 'text' : 'password'"
                                           name="password"
                                           required 
                                           autocomplete="new-password"
                                           placeholder="Minimal 8 karakter" />
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <button type="button" 
                                            @click="showPassword = !showPassword"
                                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition duration-200">
                                        <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-2 text-blue-500"></i>
                                    Konfirmasi Kata Sandi *
                                </label>
                                <div class="relative">
                                    <input id="password_confirmation" 
                                           class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white bg-opacity-50 backdrop-blur-sm" 
                                           :type="showConfirmPassword ? 'text' : 'password'"
                                           name="password_confirmation"
                                           required 
                                           autocomplete="new-password"
                                           placeholder="Ulangi kata sandi" />
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </div>
                                    <button type="button" 
                                            @click="showConfirmPassword = !showConfirmPassword"
                                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition duration-200">
                                        <i :class="showConfirmPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mt-6">
                            <label class="inline-flex items-start cursor-pointer">
                                <input type="checkbox" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 transition duration-200 mt-1" 
                                       required>
                                <span class="ml-3 text-sm text-gray-600">
                                    Saya menyetujui 
                                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Syarat dan Ketentuan</a> 
                                    serta 
                                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Kebijakan Privasi</a> 
                                    yang berlaku.
                                </span>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center justify-between mt-8 space-y-4 sm:space-y-0">
                            <a class="text-sm text-blue-600 hover:text-blue-800 font-medium transition duration-200 flex items-center" 
                               href="{{ route('login') }}">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Sudah punya akun? Masuk di sini
                            </a>

                            <button type="submit" 
                                    class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-200 flex items-center justify-center">
                                <i class="fas fa-user-plus mr-2"></i>
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>

                    <!-- Benefits Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="text-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Keuntungan Bergabung</h3>
                            <p class="text-sm text-gray-600">Nikmati berbagai kemudahan layanan pegadaian digital</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-eye text-white"></i>
                                </div>
                                <h4 class="font-semibold text-blue-700 mb-1">Tracking Real-time</h4>
                                <p class="text-xs text-blue-600">Pantau status transaksi kapan saja</p>
                            </div>

                            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                                <h4 class="font-semibold text-green-700 mb-1">Notifikasi Otomatis</h4>
                                <p class="text-xs text-green-600">Pengingat jatuh tempo pembayaran</p>
                            </div>

                            <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-history text-white"></i>
                                </div>
                                <h4 class="font-semibold text-purple-700 mb-1">Riwayat Lengkap</h4>
                                <p class="text-xs text-purple-600">Akses riwayat transaksi dan pembayaran</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-8 slide-in" style="animation-delay: 0.4s;">
                    <p class="text-white text-opacity-70 text-sm">
                        &copy; {{ date('Y') }} Pegadaianku. Sistem Informasi Pegadaian Modern.
                    </p>
                    <div class="flex items-center justify-center mt-2 space-x-4 text-white text-opacity-60">
                        <span class="flex items-center text-xs">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Aman & Terpercaya
                        </span>
                        <span class="flex items-center text-xs">
                            <i class="fas fa-clock mr-1"></i>
                            24/7 Support
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-gray-700">Sedang mendaftar...</span>
            </div>
        </div>
    </div>

    <script>
        // Show loading overlay on form submit
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[class*="bg-red-50"]');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);
    </script>
</body>
</html>