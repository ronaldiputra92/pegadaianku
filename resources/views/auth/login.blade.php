<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'Pegadaianku') }}</title>

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

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
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
            <div class="absolute top-32 right-20 w-16 h-16 bg-white bg-opacity-10 rounded-full floating-animation"
                style="animation-delay: -2s;"></div>
            <div class="absolute bottom-20 left-20 w-24 h-24 bg-white bg-opacity-10 rounded-full floating-animation"
                style="animation-delay: -4s;"></div>
            <div class="absolute bottom-32 right-10 w-12 h-12 bg-white bg-opacity-10 rounded-full floating-animation"
                style="animation-delay: -1s;"></div>

            <!-- Grid Pattern -->
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        </div>

        <div class="relative flex items-center justify-center px-4 py-8 sm:py-12 min-h-screen">
            <div class="w-full max-w-md">
                <!-- Logo Section -->
                <div class="text-center mb-8 slide-in">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-4 backdrop-blur-sm">
                        <i class="fas fa-coins text-3xl text-white"></i>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-2">Pegadaianku</h1>
                    <p class="text-white text-opacity-80">Sistem Informasi Pegadaian Modern</p>
                </div>

                <!-- Login Card -->
                <div class="glass-effect rounded-2xl shadow-2xl p-8 slide-in" style="animation-delay: 0.2s;">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang Kembali</h2>
                        <p class="text-gray-600">Masuk ke akun Anda untuk melanjutkan</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-green-700 text-sm">{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2 mt-0.5"></i>
                                <div>
                                    <div class="font-medium text-red-800 text-sm mb-2">
                                        Terjadi kesalahan saat login
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

                    <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                Alamat Email
                            </label>
                            <div class="relative">
                                <input id="email"
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white bg-opacity-50 backdrop-blur-sm"
                                    type="email" name="email" value="{{ old('email') }}" required autofocus
                                    autocomplete="username" placeholder="nama@email.com" />
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-500"></i>
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <input id="password"
                                    class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white bg-opacity-50 backdrop-blur-sm"
                                    :type="showPassword ? 'text' : 'password'" name="password" required
                                    autocomplete="current-password" placeholder="Masukkan kata sandi" />
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <button type="button" @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition duration-200">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk ke Akun
                        </button>
                    </form>

                    <!-- Demo Accounts -->
                    <div class="mt-8 pt-6 border-t border-gray-200" x-data="{ showDemo: false }">
                        <button @click="showDemo = !showDemo"
                            class="w-full text-center text-sm font-medium text-gray-600 hover:text-gray-800 transition duration-200 flex items-center justify-center">
                            <i class="fas fa-user-cog mr-2"></i>
                            Akun Demo untuk Testing
                            <i :class="showDemo ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"
                                class="ml-2 transition-transform duration-200"></i>
                        </button>

                        <div x-show="showDemo" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95" class="mt-4 space-y-3">

                            <!-- Admin Demo -->
                            <div class="bg-gradient-to-r from-red-50 to-red-100 p-3 rounded-lg border border-red-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center">
                                            <i class="fas fa-crown text-red-500 mr-2"></i>
                                            <span class="font-semibold text-red-700">Administrator</span>
                                        </div>
                                        <div class="text-xs text-red-600 mt-1">Akses penuh ke semua fitur</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-red-600">admin@pegadaianku.com</div>
                                        <div class="text-xs text-red-600">password</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Officer Demo -->
                            <div
                                class="bg-gradient-to-r from-blue-50 to-blue-100 p-3 rounded-lg border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center">
                                            <i class="fas fa-user-tie text-blue-500 mr-2"></i>
                                            <span class="font-semibold text-blue-700">Petugas</span>
                                        </div>
                                        <div class="text-xs text-blue-600 mt-1">Kelola transaksi & pembayaran</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-blue-600">petugas@pegadaianku.com</div>
                                        <div class="text-xs text-blue-600">password</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Demo -->
                            <div
                                class="bg-gradient-to-r from-green-50 to-green-100 p-3 rounded-lg border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center">
                                            <i class="fas fa-user text-green-500 mr-2"></i>
                                            <span class="font-semibold text-green-700">Nasabah</span>
                                        </div>
                                        <div class="text-xs text-green-600 mt-1">Lihat transaksi & pembayaran</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-green-600">nasabah@pegadaianku.com</div>
                                        <div class="text-xs text-green-600">password</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-8 slide-in" style="animation-delay: 0.4s;">
                    <p class="text-white text-opacity-70 text-sm">
                        &copy; {{ date('Y') }} Pegadaianku. Sistem Informasi Pegadaian Modern.
                    </p>

                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-gray-700">Sedang masuk...</span>
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
            const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
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
