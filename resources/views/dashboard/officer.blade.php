@extends('layouts.app')

@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard Petugas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-500">Kelola transaksi dan pembayaran dengan efisien</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">{{ now()->formatIndonesian('l, d F Y') }}</p>
                <p class="text-sm text-gray-500">{{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today's Transactions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-plus-circle text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Transaksi Hari Ini</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($todayTransactions) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Payments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pembayaran Hari Ini</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($todayPayments) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Transactions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Transaksi Aktif</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($activeTransactions) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Transactions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Jatuh Tempo</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($overdueTransactions) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('transactions.create') }}" class="flex items-center p-4 border-2 border-dashed border-blue-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-plus text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Transaksi Baru</h4>
                            <p class="text-sm text-gray-500">Buat transaksi gadai baru</p>
                        </div>
                    </a>

                    <a href="{{ route('payments.create') }}" class="flex items-center p-4 border-2 border-dashed border-green-300 rounded-lg hover:border-green-400 hover:bg-green-50 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-money-bill text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Proses Pembayaran</h4>
                            <p class="text-sm text-gray-500">Terima pembayaran nasabah</p>
                        </div>
                    </a>

                    <a href="{{ route('customers.create') }}" class="flex items-center p-4 border-2 border-dashed border-purple-300 rounded-lg hover:border-purple-400 hover:bg-purple-50 transition-colors">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-gray-900">Nasabah Baru</h4>
                            <p class="text-sm text-gray-500">Daftarkan nasabah baru</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if($overdueTransactions > 0)
    <div class="mb-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="block sm:inline">
                    Terdapat {{ $overdueTransactions }} transaksi yang sudah jatuh tempo. 
                    <a href="{{ route('transactions.index', ['status' => 'overdue']) }}" class="underline font-medium">Lihat detail</a>
                </span>
            </div>
        </div>
    </div>
    @endif

    @if($dueSoonTransactions->count() > 0)
    <div class="mb-6">
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
            <div class="flex items-center">
                <i class="fas fa-clock mr-2"></i>
                <span class="block sm:inline">
                    {{ $dueSoonTransactions->count() }} transaksi akan jatuh tempo dalam 7 hari ke depan.
                </span>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Transactions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Transaksi Terbaru</h3>
                    <a href="{{ route('transactions.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat semua</a>
                </div>
                @if($recentTransactions->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentTransactions->take(5) as $transaction)
                        <div class="flex items-center space-x-4 p-3 border border-gray-200 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-handshake text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $transaction->transaction_code }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $transaction->customer->name }} - {{ $transaction->item_name }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $transaction->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}
                                </p>
                                @php
                                    $statusColors = [
                                        'active' => 'text-green-600',
                                        'extended' => 'text-blue-600',
                                        'paid' => 'text-gray-600',
                                        'overdue' => 'text-red-600',
                                    ];
                                @endphp
                                <p class="text-xs {{ $statusColors[$transaction->status] ?? 'text-gray-600' }}">
                                    {{ ucfirst($transaction->status) }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-exchange-alt text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500">Belum ada transaksi hari ini</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Due Soon Transactions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Akan Jatuh Tempo</h3>
                    <span class="text-sm text-gray-500">7 hari ke depan</span>
                </div>
                @if($dueSoonTransactions->count() > 0)
                    <div class="space-y-4">
                        @foreach($dueSoonTransactions->take(5) as $transaction)
                        <div class="flex items-center space-x-4 p-3 border border-yellow-200 bg-yellow-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $transaction->transaction_code }}
                                </p>
                                <p class="text-sm text-gray-600 truncate">
                                    {{ $transaction->customer->name }}
                                </p>
                                <p class="text-xs text-yellow-600">
                                    Jatuh tempo: {{ $transaction->due_date->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-yellow-600">
                                    {{ $transaction->getDaysUntilDue() }} hari lagi
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-check-circle text-green-400 text-3xl mb-2"></i>
                        <p class="text-gray-500">Tidak ada transaksi yang akan jatuh tempo</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Ringkasan Kinerja</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $todayTransactions }}</div>
                    <div class="text-sm text-gray-500">Transaksi Hari Ini</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $todayPayments }}</div>
                    <div class="text-sm text-gray-500">Pembayaran Hari Ini</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $activeTransactions }}</div>
                    <div class="text-sm text-gray-500">Total Transaksi Aktif</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection