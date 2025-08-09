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
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        
        .sidebar-item.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 197, 253, 0.1));
            border: 1px solid rgba(59, 130, 246, 0.2);
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
            background: #cbd5e1;
            border-radius: 50%;
            transform: translateY(-50%);
            transition: all 0.2s ease;
        }
        
        .submenu-item.active::before {
            background: #3b82f6;
            transform: translateY(-50%) scale(1.5);
        }
        
        .submenu-item:hover::before {
            background: #60a5fa;
            transform: translateY(-50%) scale(1.2);
        }
        
        .logo-glow {
            text-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        }
        
        .sidebar-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            margin: 16px 12px;
        }
        
        .mobile-sidebar-gradient {
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
>>>>>>> REPLACE