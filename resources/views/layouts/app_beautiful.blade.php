<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pegadaianku') }} - @yield('title', 'Sistem Informasi Pegadaian')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
    
    <style>
        .sidebar-gradient {
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 50%, #3b82f6 100%);
        }
        
        .sidebar-item {
            position: relative;
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 2px 8px;
        }
        
        .sidebar-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-item.active {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #fbbf24, #f59e0b);
            border-radius: 0 4px 4px 0;
        }
        
        .nav-icon {
            transition: all 0.3s ease;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-item:hover .nav-icon {
            transform: scale(1.1);
        }
        
        .submenu-item {
            position: relative;
            transition: all 0.2s ease;
            border-radius: 8px;
            margin: 1px 4px;
        }
        
        .submenu-item::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 50%;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            transform: translateY(-50%);
            transition: all 0.2s ease;
        }
        
        .submenu-item.active::before {
            background: #fbbf24;
            transform: translateY(-50%) scale(1.5);
        }
        
        .submenu-item:hover::before {
            background: rgba(255, 255, 255, 0.8);
            transform: translateY(-50%) scale(1.2);
        }
        
        .submenu-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .submenu-item.active {
            background: rgba(255, 255, 255, 0.15);
        }
        
        .logo-glow {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }
        
        .sidebar-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            margin: 16px 12px;
        }
        
        .mobile-sidebar-gradient {
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
        }
        
        .chevron-icon {
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        @auth
            <div class="flex h-screen bg-gray-50">
                <!-- Sidebar -->
                <div class="hidden md:flex md:w-72 md:flex-col">
                    <div class="flex flex-col flex-grow sidebar-gradient shadow-xl overflow-y-auto">
                        <!-- Logo -->
                        <div class="flex items-center flex-shrink-0 px-6 py-6">
                            <a href="{{ route('dashboard') }}" class="flex items-center text-2xl font-bold text-white logo-glow hover:scale-105 transition-transform duration-300">
                                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-3 backdrop-blur-sm">
                                    <i class="fas fa-coins text-yellow-400"></i>
                                </div>
                                <span>Pegadaianku</span>
                            </a>
                        </div>

                        <!-- User Welcome -->
                        <div class="px-6 pb-4">
                            <div class="bg-white bg-opacity-10 rounded-xl p-4 backdrop-blur-sm">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-white font-semibold">{{ auth()->user()->name }}</p>
                                        <p class="text-blue-200 text-sm capitalize">{{ auth()->user()->role }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="flex-grow flex flex-col px-4 pb-6">
                            <nav class="flex-1 space-y-2">
                                <!-- Dashboard -->
                                <a href="{{ route('dashboard') }}" 
                                   class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} group flex items-center px-4 py-3 text-sm font-medium text-white hover:text-white">
                                    <i class="nav-icon fas fa-tachometer-alt mr-3 text-lg {{ request()->routeIs('dashboard') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                    <span>Dashboard</span>
                                </a>

                                @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
                                    <!-- Divider -->
                                    <div class="sidebar-divider"></div>
                                    
                                    <!-- Transaksi -->
                                    <a href="{{ route('transactions.index') }}" 
                                       class="sidebar-item {{ request()->routeIs('transactions.*') ? 'active' : '' }} group flex items-center px-4 py-3 text-sm font-medium text-white hover:text-white">
                                        <i class="nav-icon fas fa-exchange-alt mr-3 text-lg {{ request()->routeIs('transactions.*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                        <span>Transaksi</span>
                                    </a>

                                    <!-- Pembayaran -->
                                    <a href="{{ route('payments.index') }}" 
                                       class="sidebar-item {{ request()->routeIs('payments.*') ? 'active' : '' }} group flex items-center px-4 py-3 text-sm font-medium text-white hover:text-white">
                                        <i class="nav-icon fas fa-money-bill-wave mr-3 text-lg {{ request()->routeIs('payments.*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                        <span>Pembayaran</span>
                                    </a>

                                    <!-- Nasabah -->
                                    <div x-data="{ open: {{ request()->is('customers*') || request()->is('customer-documents*') || request()->is('customer-history*') ? 'true' : 'false' }} }">
                                        <button @click="open = !open" 
                                                class="sidebar-item {{ request()->is('customers*') || request()->is('customer-documents*') || request()->is('customer-history*') ? 'active' : '' }} group w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-white hover:text-white">
                                            <div class="flex items-center">
                                                <i class="nav-icon fas fa-users mr-3 text-lg {{ request()->is('customers*') || request()->is('customer-documents*') || request()->is('customer-history*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                                <span>Nasabah</span>
                                            </div>
                                            <i class="chevron-icon fas fa-chevron-down text-xs text-blue-200" :class="{ 'rotate-180': open }"></i>
                                        </button>
                                        
                                        <div x-show="open" x-transition class="ml-8 mt-1 space-y-1">
                                            <a href="{{ route('customers.index') }}" 
                                               class="submenu-item {{ request()->routeIs('customers.index') || request()->routeIs('customers.show') || request()->routeIs('customers.edit') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                <i class="fas fa-list mr-3 text-sm"></i>
                                                <span>Data Nasabah</span>
                                            </a>
                                            
                                            <a href="{{ route('customers.create') }}" 
                                               class="submenu-item {{ request()->routeIs('customers.create') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                <i class="fas fa-user-plus mr-3 text-sm"></i>
                                                <span>Registrasi Nasabah</span>
                                            </a>
                                            
                                            <a href="{{ url('/customer-documents') }}" 
                                               class="submenu-item {{ request()->is('customer-documents*') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                <i class="fas fa-id-card mr-3 text-sm"></i>
                                                <span>Dokumen KTP</span>
                                            </a>
                                            
                                            <a href="{{ url('/customer-history') }}" 
                                               class="submenu-item {{ request()->is('customer-history*') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                <i class="fas fa-history mr-3 text-sm"></i>
                                                <span>Riwayat Transaksi</span>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Pengingat -->
                                    <a href="{{ route('reminders.index') }}" 
                                       class="sidebar-item {{ request()->routeIs('reminders.*') ? 'active' : '' }} group flex items-center px-4 py-3 text-sm font-medium text-white hover:text-white">
                                        <i class="nav-icon fas fa-bell mr-3 text-lg {{ request()->routeIs('reminders.*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                        <span>Pengingat</span>
                                    </a>

                                    <!-- Laporan -->
                                    <div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
                                        <button @click="open = !open" 
                                                class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }} group w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-white hover:text-white">
                                            <div class="flex items-center">
                                                <i class="nav-icon fas fa-chart-bar mr-3 text-lg {{ request()->routeIs('reports.*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                                <span>Laporan</span>
                                            </div>
                                            <i class="chevron-icon fas fa-chevron-down text-xs text-blue-200" :class="{ 'rotate-180': open }"></i>
                                        </button>
                                        
                                        <div x-show="open" x-transition class="ml-8 mt-1 space-y-1">
                                            <a href="{{ route('reports.index') }}" 
                                               class="submenu-item {{ request()->routeIs('reports.index') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                <i class="fas fa-tachometer-alt mr-3 text-sm"></i>
                                                <span>Dashboard Laporan</span>
                                            </a>
                                            
                                            <a href="{{ route('reports.transactions') }}" 
                                               class="submenu-item {{ request()->routeIs('reports.transactions') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                <i class="fas fa-file-alt mr-3 text-sm"></i>
                                                <span>Laporan Transaksi</span>
                                            </a>
                                            
                                            <a href="{{ route('reports.payments') }}" 
                                               class="submenu-item {{ request()->routeIs('reports.payments') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                <i class="fas fa-receipt mr-3 text-sm"></i>
                                                <span>Laporan Pembayaran</span>
                                            </a>
                                            
                                            <a href="{{ route('reports.financial') }}" 
                                               class="submenu-item {{ request()->routeIs('reports.financial') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                <i class="fas fa-chart-pie mr-3 text-sm"></i>
                                                <span>Laporan Keuangan</span>
                                            </a>
                                            
                                            <a href="{{ route('reports.export') }}" 
                                               class="submenu-item {{ request()->routeIs('reports.export') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                <i class="fas fa-download mr-3 text-sm"></i>
                                                <span>Export Data</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <!-- Divider -->
                                    <div class="sidebar-divider"></div>
                                    
                                    <!-- Pengguna -->
                                    <a href="{{ route('users.index') }}" 
                                       class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }} group flex items-center px-4 py-3 text-sm font-medium text-white hover:text-white">
                                        <i class="nav-icon fas fa-user-cog mr-3 text-lg {{ request()->routeIs('users.*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                        <span>Pengguna</span>
                                    </a>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Mobile sidebar -->
                <div class="md:hidden" x-data="{ sidebarOpen: false }">
                    <!-- Mobile menu button -->
                    <div class="fixed top-0 left-0 right-0 z-40 flex items-center justify-between h-16 bg-white shadow-sm border-b border-gray-200 px-4">
                        <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-bold text-blue-600 logo-glow">
                            <i class="fas fa-coins mr-2 text-yellow-500"></i>
                            <span>Pegadaianku</span>
                        </a>
                        <!-- Notifications and User Profile for mobile -->
                        <div class="flex items-center space-x-2">
                            <!-- Notifications -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-bell text-lg"></i>
                                    @if(auth()->user()->notifications()->unread()->count() > 0)
                                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                            {{ auth()->user()->notifications()->unread()->count() }}
                                        </span>
                                    @endif
                                </button>

                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100">
                                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                        <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                                        <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                            Lihat Semua
                                        </a>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                            <a href="{{ $notification->pawn_transaction_id ? route('transactions.show', $notification->pawn_transaction_id) : '#' }}" 
                                               onclick="markAsRead({{ $notification->id }})"
                                               class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200 {{ !$notification->is_read ? 'bg-blue-25 border-l-4 border-blue-400' : '' }}">
                                                <div class="font-medium text-gray-900">{{ $notification->title }}</div>
                                                <div class="text-gray-600 mt-1">{{ Str::limit($notification->message, 50) }}</div>
                                                <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumansIndonesian() }}</div>
                                            </a>
                                        @empty
                                            <div class="px-4 py-6 text-center">
                                                <i class="fas fa-bell-slash text-gray-300 text-2xl mb-2"></i>
                                                <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- User Profile -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 p-1 hover:bg-gray-100 transition-colors duration-200">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-medium shadow-lg">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                </button>

                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100">
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                                        <i class="fas fa-user mr-3 text-blue-500"></i>
                                        <span>Profile</span>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 transition-colors duration-200">
                                            <i class="fas fa-sign-out-alt mr-3 text-red-500"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile sidebar overlay -->
                    <div x-show="sidebarOpen" class="fixed inset-0 z-50 flex">
                        <div x-show="sidebarOpen" 
                             x-transition:enter="transition-opacity ease-linear duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity ease-linear duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click="sidebarOpen = false" 
                             class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
                        
                        <div x-show="sidebarOpen" 
                             x-transition:enter="transition ease-in-out duration-300 transform"
                             x-transition:enter-start="-translate-x-full"
                             x-transition:enter-end="translate-x-0"
                             x-transition:leave="transition ease-in-out duration-300 transform"
                             x-transition:leave-start="translate-x-0"
                             x-transition:leave-end="-translate-x-full"
                             class="relative flex-1 flex flex-col max-w-xs w-full mobile-sidebar-gradient shadow-2xl">
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button @click="sidebarOpen = false" 
                                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full bg-white bg-opacity-20 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white hover:bg-opacity-30 transition-all duration-200">
                                    <i class="fas fa-times text-white"></i>
                                </button>
                            </div>
                            
                            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                                <!-- Mobile Logo -->
                                <div class="flex-shrink-0 flex items-center px-6 pb-4">
                                    <a href="{{ route('dashboard') }}" class="flex items-center text-xl font-bold text-white logo-glow">
                                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-coins text-yellow-400"></i>
                                        </div>
                                        <span>Pegadaianku</span>
                                    </a>
                                </div>
                                
                                <!-- Mobile User Info -->
                                <div class="px-6 pb-4">
                                    <div class="bg-white bg-opacity-10 rounded-xl p-3 backdrop-blur-sm">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-white font-semibold text-sm">{{ auth()->user()->name }}</p>
                                                <p class="text-blue-200 text-xs capitalize">{{ auth()->user()->role }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <nav class="mt-2 px-4 space-y-2">
                                    <!-- Mobile Navigation Items -->
                                    <a href="{{ route('dashboard') }}" 
                                       class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }} group flex items-center px-4 py-3 text-base font-medium text-white hover:text-white">
                                        <i class="nav-icon fas fa-tachometer-alt mr-4 text-lg {{ request()->routeIs('dashboard') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                        <span>Dashboard</span>
                                    </a>

                                    @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
                                        <div class="sidebar-divider"></div>
                                        
                                        <a href="{{ route('transactions.index') }}" 
                                           class="sidebar-item {{ request()->routeIs('transactions.*') ? 'active' : '' }} group flex items-center px-4 py-3 text-base font-medium text-white hover:text-white">
                                            <i class="nav-icon fas fa-exchange-alt mr-4 text-lg {{ request()->routeIs('transactions.*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                            <span>Transaksi</span>
                                        </a>

                                        <a href="{{ route('payments.index') }}" 
                                           class="sidebar-item {{ request()->routeIs('payments.*') ? 'active' : '' }} group flex items-center px-4 py-3 text-base font-medium text-white hover:text-white">
                                            <i class="nav-icon fas fa-money-bill-wave mr-4 text-lg {{ request()->routeIs('payments.*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                            <span>Pembayaran</span>
                                        </a>

                                        <!-- Nasabah Mobile -->
                                        <div x-data="{ open: {{ request()->is('customers*') || request()->is('customer-documents*') || request()->is('customer-history*') ? 'true' : 'false' }} }">
                                            <button @click="open = !open" 
                                                    class="sidebar-item {{ request()->is('customers*') || request()->is('customer-documents*') || request()->is('customer-history*') ? 'active' : '' }} group w-full flex items-center justify-between px-4 py-3 text-base font-medium text-white hover:text-white">
                                                <div class="flex items-center">
                                                    <i class="nav-icon fas fa-users mr-4 text-lg {{ request()->is('customers*') || request()->is('customer-documents*') || request()->is('customer-history*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                                    <span>Nasabah</span>
                                                </div>
                                                <i class="chevron-icon fas fa-chevron-down text-xs text-blue-200" :class="{ 'rotate-180': open }"></i>
                                            </button>
                                            
                                            <div x-show="open" x-transition class="ml-8 mt-1 space-y-1">
                                                <a href="{{ route('customers.index') }}" 
                                                   class="submenu-item {{ request()->routeIs('customers.index') || request()->routeIs('customers.show') || request()->routeIs('customers.edit') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                    <i class="fas fa-list mr-3 text-sm"></i>
                                                    <span>Data Nasabah</span>
                                                </a>
                                                
                                                <a href="{{ route('customers.create') }}" 
                                                   class="submenu-item {{ request()->routeIs('customers.create') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                    <i class="fas fa-user-plus mr-3 text-sm"></i>
                                                    <span>Registrasi Nasabah</span>
                                                </a>
                                                
                                                <a href="{{ url('/customer-documents') }}" 
                                                   class="submenu-item {{ request()->is('customer-documents*') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                    <i class="fas fa-id-card mr-3 text-sm"></i>
                                                    <span>Dokumen KTP</span>
                                                </a>
                                                
                                                <a href="{{ url('/customer-history') }}" 
                                                   class="submenu-item {{ request()->is('customer-history*') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                    <i class="fas fa-history mr-3 text-sm"></i>
                                                    <span>Riwayat Transaksi</span>
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Laporan Mobile -->
                                        <div x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
                                            <button @click="open = !open" 
                                                    class="sidebar-item {{ request()->routeIs('reports.*') ? 'active' : '' }} group w-full flex items-center justify-between px-4 py-3 text-base font-medium text-white hover:text-white">
                                                <div class="flex items-center">
                                                    <i class="nav-icon fas fa-chart-bar mr-4 text-lg {{ request()->routeIs('reports.*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                                    <span>Laporan</span>
                                                </div>
                                                <i class="chevron-icon fas fa-chevron-down text-xs text-blue-200" :class="{ 'rotate-180': open }"></i>
                                            </button>
                                            
                                            <div x-show="open" x-transition class="ml-8 mt-1 space-y-1">
                                                <a href="{{ route('reports.index') }}" 
                                                   class="submenu-item {{ request()->routeIs('reports.index') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                    <i class="fas fa-tachometer-alt mr-3 text-sm"></i>
                                                    <span>Dashboard Laporan</span>
                                                </a>
                                                
                                                <a href="{{ route('reports.transactions') }}" 
                                                   class="submenu-item {{ request()->routeIs('reports.transactions') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                    <i class="fas fa-file-alt mr-3 text-sm"></i>
                                                    <span>Laporan Transaksi</span>
                                                </a>
                                                
                                                <a href="{{ route('reports.payments') }}" 
                                                   class="submenu-item {{ request()->routeIs('reports.payments') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                    <i class="fas fa-receipt mr-3 text-sm"></i>
                                                    <span>Laporan Pembayaran</span>
                                                </a>
                                                
                                                <a href="{{ route('reports.financial') }}" 
                                                   class="submenu-item {{ request()->routeIs('reports.financial') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                    <i class="fas fa-chart-pie mr-3 text-sm"></i>
                                                    <span>Laporan Keuangan</span>
                                                </a>
                                                
                                                <a href="{{ route('reports.export') }}" 
                                                   class="submenu-item {{ request()->routeIs('reports.export') ? 'active' : '' }} group flex items-center px-4 py-2 text-sm font-medium text-blue-100 hover:text-white">
                                                    <i class="fas fa-download mr-3 text-sm"></i>
                                                    <span>Export Data</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if(auth()->user()->isAdmin())
                                        <div class="sidebar-divider"></div>
                                        
                                        <a href="{{ route('users.index') }}" 
                                           class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }} group flex items-center px-4 py-3 text-base font-medium text-white hover:text-white">
                                            <i class="nav-icon fas fa-user-cog mr-4 text-lg {{ request()->routeIs('users.*') ? 'text-yellow-400' : 'text-blue-200' }}"></i>
                                            <span>Pengguna</span>
                                        </a>
                                    @endif
                                </nav>
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
                                
                                <!-- Notifications and User Profile for desktop -->
                                <div class="flex items-center space-x-4">
                                    <!-- Notifications -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="relative p-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                            <i class="fas fa-bell text-lg"></i>
                                            @if(auth()->user()->notifications()->unread()->count() > 0)
                                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse">
                                                    {{ auth()->user()->notifications()->unread()->count() }}
                                                </span>
                                            @endif
                                        </button>

                                        <div x-show="open" @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 transform scale-95"
                                             x-transition:enter-end="opacity-100 transform scale-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 transform scale-100"
                                             x-transition:leave-end="opacity-0 transform scale-95"
                                             class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100">
                                            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                                <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                                                <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                    Lihat Semua
                                                </a>
                                            </div>
                                            <div class="max-h-64 overflow-y-auto">
                                                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                                    <a href="{{ $notification->pawn_transaction_id ? route('transactions.show', $notification->pawn_transaction_id) : '#' }}" 
                                                       onclick="markAsRead({{ $notification->id }})"
                                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200 {{ !$notification->is_read ? 'bg-blue-25 border-l-4 border-blue-400' : '' }}">
                                                        <div class="font-medium text-gray-900">{{ $notification->title }}</div>
                                                        <div class="text-gray-600 mt-1">{{ Str::limit($notification->message, 50) }}</div>
                                                        <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumansIndonesian() }}</div>
                                                    </a>
                                                @empty
                                                    <div class="px-4 py-6 text-center">
                                                        <i class="fas fa-bell-slash text-gray-300 text-2xl mb-2"></i>
                                                        <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>

                                    <!-- User Profile -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hover:bg-gray-100 p-2 transition-colors duration-200">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-medium shadow-lg">
                                                    {{ substr(auth()->user()->name, 0, 1) }}
                                                </div>
                                                <div class="hidden lg:block text-left">
                                                    <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                                                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                                                </div>
                                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                                            </div>
                                        </button>

                                        <div x-show="open" @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 transform scale-95"
                                             x-transition:enter-end="opacity-100 transform scale-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 transform scale-100"
                                             x-transition:leave-end="opacity-0 transform scale-95"
                                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 z-50 border border-gray-100">
                                            <div class="px-4 py-3 border-b border-gray-100">
                                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                                <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                                            </div>
                                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 transition-colors duration-200">
                                                <i class="fas fa-user mr-3 text-blue-500"></i>
                                                <span>Profile</span>
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 transition-colors duration-200">
                                                    <i class="fas fa-sign-out-alt mr-3 text-red-500"></i>
                                                    <span>Logout</span>
                                                </button>
                                            </form>
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
                                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm" role="alert">
                                        <div class="flex items-center">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            <span class="block sm:inline">{{ session('success') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm" role="alert">
                                        <div class="flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            <span class="block sm:inline">{{ session('error') }}</span>
                                        </div>
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
    
    <script>
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    // Refresh the page to update notification count
                    setTimeout(() => {
                        location.reload();
                    }, 100);
                }
            }).catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }
    </script>
</body>
</html>