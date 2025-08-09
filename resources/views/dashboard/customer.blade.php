@extends('layouts.app')

@section('title', 'Dashboard Nasabah')
@section('page-title', 'Dashboard Nasabah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-500">Pantau transaksi gadai Anda dengan mudah</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">{{ now()->formatIndonesian('l, d F Y') }}</p>
                <p class="text-sm text-gray-500">{{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Transactions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-handshake text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Transaksi Aktif</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $activeTransactions->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Loan Amount -->
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pinjaman</dt>
                            <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($totalLoanAmount, 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Interest -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-percentage text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Bunga</dt>
                            <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($totalInterest, 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Due Soon -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Jatuh Tempo</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $dueSoonTransactions->count() + $overdueTransactions->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if($overdueTransactions->count() > 0)
    <div class="mb-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span class="block sm:inline">
                    Anda memiliki {{ $overdueTransactions->count() }} transaksi yang sudah jatuh tempo. Segera lakukan pembayaran.
                </span>
            </div>
        </div>
    </div>
    @endif

    @if($dueSoonTransactions->count() > 0)
    <div class="mb-6">
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="block sm:inline">
                    Anda memiliki {{ $dueSoonTransactions->count() }} transaksi yang akan jatuh tempo dalam 7 hari ke depan.
                </span>
            </div>
        </div>
    </div>
    @endif

    <!-- Active Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Current Transactions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Transaksi Aktif</h3>
                @if($activeTransactions->count() > 0)
                    <div class="space-y-4">
                        @foreach($activeTransactions->take(5) as $transaction)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $transaction->transaction_code }}</h4>
                                    <p class="text-sm text-gray-600">{{ $transaction->item_name }}</p>
                                </div>
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'extended' => 'bg-blue-100 text-blue-800',
                                    ];
                                    $statusLabels = [
                                        'active' => 'Aktif',
                                        'extended' => 'Diperpanjang',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Pinjaman:</span>
                                    <div class="font-medium">Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500">Jatuh Tempo:</span>
                                    <div class="font-medium">{{ $transaction->due_date->format('d/m/Y') }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500">Bunga Saat Ini:</span>
                                    <div class="font-medium text-yellow-600">Rp {{ number_format($transaction->calculateInterest(), 0, ',', '.') }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500">Total Tagihan:</span>
                                    <div class="font-medium text-red-600">Rp {{ number_format($transaction->calculateTotalAmount(), 0, ',', '.') }}</div>
                                </div>
                            </div>
                            @php
                                $daysUntilDue = $transaction->getDaysUntilDue();
                            @endphp
                            @if($daysUntilDue < 0)
                                <div class="mt-2 text-sm text-red-600">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Terlambat {{ abs($daysUntilDue) }} hari
                                </div>
                            @elseif($daysUntilDue <= 7)
                                <div class="mt-2 text-sm text-yellow-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $daysUntilDue }} hari lagi
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @if($activeTransactions->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat semua transaksi ({{ $activeTransactions->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-handshake text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500">Tidak ada transaksi aktif</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Pembayaran Terbaru</h3>
                @if($recentPayments->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentPayments as $payment)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $payment->payment_code }}</h4>
                                    <p class="text-sm text-gray-600">{{ $payment->pawnTransaction->transaction_code }}</p>
                                </div>
                                @php
                                    $typeColors = [
                                        'interest' => 'bg-yellow-100 text-yellow-800',
                                        'partial' => 'bg-blue-100 text-blue-800',
                                        'full' => 'bg-green-100 text-green-800',
                                    ];
                                    $typeLabels = [
                                        'interest' => 'Bunga',
                                        'partial' => 'Sebagian',
                                        'full' => 'Pelunasan',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $typeColors[$payment->payment_type] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $typeLabels[$payment->payment_type] ?? ucfirst($payment->payment_type) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Jumlah:</span>
                                    <div class="font-medium text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                </div>
                                <div>
                                    <span class="text-gray-500">Tanggal:</span>
                                    <div class="font-medium">{{ $payment->payment_date->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-money-bill text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500">Belum ada pembayaran</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-eye text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Lihat Semua Transaksi</h4>
                        <p class="text-sm text-gray-500">Riwayat lengkap transaksi Anda</p>
                    </div>
                </a>

                <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Riwayat Pembayaran</h4>
                        <p class="text-sm text-gray-500">Lihat semua pembayaran yang telah dilakukan</p>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">Update Profil</h4>
                        <p class="text-sm text-gray-500">Perbarui informasi pribadi Anda</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection