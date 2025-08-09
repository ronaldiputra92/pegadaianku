<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pegadaianku') }} - @yield('title', 'Sistem Informasi Pegadaian')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        @auth
            <div class="flex h-screen bg-gray-100">
                <!-- Sidebar -->
                <div class="hidden md:flex md:w-64 md:flex-col">
                    <div class="flex flex-col flex-grow pt-5 overflow-y-auto bg-white border-r border-gray-200">
                        <!-- Logo -->
                        <div class="flex items-center flex-shrink-0 px-4">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">
                                <i class="fas fa-coins mr-2"></i>
                                Pegadaianku
                            </a>
                        </div>

                        <!-- Navigation -->
                        <div class="mt-8 flex-grow flex flex-col">
                            <nav class="flex-1 px-2 space-y-1">
                                <!-- Dashboard -->
                                <a href="{{ route('dashboard') }}" 
                                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-tachometer-alt mr-3 text-lg {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                    Dashboard
                                </a>

                                @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
                                    <!-- Transaksi -->
                                    <a href="{{ route('transactions.index') }}" 
                                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('transactions.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-exchange-alt mr-3 text-lg {{ request()->routeIs('transactions.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                        Transaksi
                                    </a>

                                    <!-- Pembayaran -->
                                    <a href="{{ route('payments.index') }}" 
                                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('payments.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-money-bill-wave mr-3 text-lg {{ request()->routeIs('payments.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                        Pembayaran
                                    </a>

                                    <!-- Nasabah -->
                                    <div x-data="{ open: {{ request()->is('customers*') || request()->is('customer-documents*') || request()->is('customer-history*') ? 'true' : 'false' }} }">
                                        <button @click="open = !open" 
                                                class="group w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md {{ request()->is('customers*') || request()->is('customer-documents*') || request()->is('customer-history*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <div class="flex items-center">
                                                <i class="fas fa-users mr-3 text-lg {{ request()->is('customers*') || request()->is('customer-documents*') || request()->is('customer-history*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                                Nasabah
                                            </div>
                                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                                        </button>
                                        
                                        <div x-show="open" x-transition class="ml-6 mt-1 space-y-1">
                                            <a href="{{ route('customers.index') }}" 
                                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('customers.index') || request()->routeIs('customers.show') || request()->routeIs('customers.edit') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                <i class="fas fa-list mr-3 text-sm {{ request()->routeIs('customers.index') || request()->routeIs('customers.show') || request()->routeIs('customers.edit') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                                Data Nasabah
                                            </a>
                                            
                                            <a href="{{ route('customers.create') }}" 
                                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('customers.create') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                <i class="fas fa-user-plus mr-3 text-sm {{ request()->routeIs('customers.create') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                                Registrasi Nasabah
                                            </a>
                                            
                                            <a href="{{ url('/customer-documents') }}" 
                                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->is('customer-documents*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                <i class="fas fa-id-card mr-3 text-sm {{ request()->is('customer-documents*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                                Dokumen KTP
                                            </a>
                                            
                                            <a href="{{ url('/customer-history') }}" 
                                               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->is('customer-history*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                <i class="fas fa-history mr-3 text-sm {{ request()->is('customer-history*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                                Riwayat Transaksi
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Laporan -->
                                    <a href="{{ route('reports.index') }}" 
                                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-chart-bar mr-3 text-lg {{ request()->routeIs('reports.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                        Laporan
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <!-- Pengguna -->
                                    <a href="{{ route('users.index') }}" 
                                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-user-cog mr-3 text-lg {{ request()->routeIs('users.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                        Pengguna
                                    </a>
                                @endif
                            </nav>
                        </div>

                        <!-- User Profile Section -->
                        <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                            <div class="flex-shrink-0 w-full group block" x-data="{ open: false }">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 bg-blue-500 rounded-full flex items-center justify-center text-white font-medium">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                                            {{ auth()->user()->name }}
                                        </p>
                                        <p class="text-xs font-medium text-gray-500 group-hover:text-gray-700 capitalize">
                                            {{ auth()->user()->role }}
                                        </p>
                                    </div>
                                    <button @click="open = !open" class="ml-3 flex-shrink-0">
                                        <i class="fas fa-ellipsis-v text-gray-400 hover:text-gray-600"></i>
                                    </button>
                                </div>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.away="open = false" 
                                     class="absolute bottom-16 left-4 right-4 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2"></i>
                                        Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile sidebar -->
                <div class="md:hidden" x-data="{ sidebarOpen: false }">
                    <!-- Mobile menu button -->
                    <div class="fixed top-0 left-0 right-0 z-40 flex items-center justify-between h-16 bg-white border-b border-gray-200 px-4">
                        <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">
                            <i class="fas fa-coins mr-2"></i>
                            Pegadaianku
                        </a>
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-bell text-lg"></i>
                                @if(auth()->user()->notifications()->unread()->count() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ auth()->user()->notifications()->unread()->count() }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-900">Notifikasi</h3>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                                            <div class="font-medium">{{ $notification->title }}</div>
                                            <div class="text-gray-500">{{ Str::limit($notification->message, 50) }}</div>
                                            <div class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                                        </a>
                                    @empty
                                        <div class="px-4 py-2 text-sm text-gray-500">Tidak ada notifikasi</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile sidebar overlay -->
                    <div x-show="sidebarOpen" class="fixed inset-0 z-50 flex">
                        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
                             class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
                        
                        <div x-show="sidebarOpen" 
                             class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button @click="sidebarOpen = false" 
                                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                                    <i class="fas fa-times text-white"></i>
                                </button>
                            </div>
                            
                            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                                <div class="flex-shrink-0 flex items-center px-4">
                                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">
                                        <i class="fas fa-coins mr-2"></i>
                                        Pegadaianku
                                    </a>
                                </div>
                                <nav class="mt-5 px-2 space-y-1">
                                    <!-- Mobile Navigation Items -->
                                    <a href="{{ route('dashboard') }}" 
                                       class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-tachometer-alt mr-4 text-lg {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                        Dashboard
                                    </a>

                                    @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
                                        <a href="{{ route('transactions.index') }}" 
                                           class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('transactions.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-exchange-alt mr-4 text-lg {{ request()->routeIs('transactions.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                            Transaksi
                                        </a>

                                        <a href="{{ route('payments.index') }}" 
                                           class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('payments.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-money-bill-wave mr-4 text-lg {{ request()->routeIs('payments.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                            Pembayaran
                                        </a>

                                        <a href="{{ route('customers.index') }}" 
                                           class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('customers.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-users mr-4 text-lg {{ request()->routeIs('customers.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                            Nasabah
                                        </a>

                                        <a href="{{ route('reports.index') }}" 
                                           class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('reports.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-chart-bar mr-4 text-lg {{ request()->routeIs('reports.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                            Laporan
                                        </a>
                                    @endif

                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('users.index') }}" 
                                           class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            <i class="fas fa-user-cog mr-4 text-lg {{ request()->routeIs('users.*') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                                            Pengguna
                                        </a>
                                    @endif
                                </nav>
                            </div>
                            
                            <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-medium">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-base font-medium text-gray-700">{{ auth()->user()->name }}</p>
                                        <p class="text-sm font-medium text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main content -->
                <div class="flex flex-col w-0 flex-1 overflow-hidden">
                    <!-- Top bar for desktop -->
                    <div class="hidden md:block bg-white shadow-sm border-b border-gray-200">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="flex justify-between h-16">
                                <div class="flex items-center">
                                    <h1 class="text-2xl font-semibold text-gray-900">
                                        @yield('page-title', 'Dashboard')
                                    </h1>
                                </div>
                                
                                <!-- Notifications for desktop -->
                                <div class="flex items-center space-x-4">
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-bell text-lg"></i>
                                            @if(auth()->user()->notifications()->unread()->count() > 0)
                                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                                    {{ auth()->user()->notifications()->unread()->count() }}
                                                </span>
                                            @endif
                                        </button>

                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50">
                                            <div class="px-4 py-2 border-b border-gray-200">
                                                <h3 class="text-sm font-medium text-gray-900">Notifikasi</h3>
                                            </div>
                                            <div class="max-h-64 overflow-y-auto">
                                                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                                                        <div class="font-medium">{{ $notification->title }}</div>
                                                        <div class="text-gray-500">{{ Str::limit($notification->message, 50) }}</div>
                                                        <div class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                                                    </a>
                                                @empty
                                                    <div class="px-4 py-2 text-sm text-gray-500">Tidak ada notifikasi</div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Page Content -->
                    <main class="flex-1 relative overflow-y-auto focus:outline-none">
                        <div class="py-6 {{ auth()->check() ? 'md:pt-6 pt-20' : '' }}">
                            @if(session('success'))
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                        <span class="block sm:inline">{{ session('success') }}</span>
                                    </div>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                        <span class="block sm:inline">{{ session('error') }}</span>
                                    </div>
                                </div>
                            @endif

                            @yield('content')
                        </div>
                    </main>
                </div>
            </div>
        @else
            <!-- Guest Layout -->
            <main class="py-6">
                @yield('content')
            </main>
        @endauth
    </div>

    @stack('scripts')
</body>
</html>