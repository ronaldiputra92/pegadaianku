@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-500">Kelola sistem pegadaian dengan mudah dan efisien</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">{{ now()->formatIndonesian('l, d F Y') }}</p>
                <p class="text-sm text-gray-500">{{ now()->format('H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Customers -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-users text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Nasabah</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($totalCustomers) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Officers -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <i class="fas fa-user-tie text-white"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Petugas</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($totalOfficers) }}</dd>
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

        <!-- Total Loan Amount -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
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

    <!-- Charts and Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Monthly Transactions Chart -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Transaksi Bulanan</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="transactionsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Pendapatan Bulanan</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Transactions -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Transaksi Terbaru</h3>
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @forelse($recentTransactions as $transaction)
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
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
                                        @if($transaction->customer)
                                            {{ $transaction->customer->name }} - {{ $transaction->item_name }}
                                        @else
                                            Customer tidak ditemukan - {{ $transaction->item_name }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex-shrink-0 text-sm text-gray-500">
                                    Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="py-4 text-center text-gray-500">
                            Belum ada transaksi
                        </li>
                        @endforelse
                    </ul>
                </div>
                <div class="mt-6">
                    <a href="{{ route('transactions.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Lihat Semua Transaksi
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Pembayaran Terbaru</h3>
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @forelse($recentPayments as $payment)
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-money-bill text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $payment->payment_code }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate">
                                        @if($payment->pawnTransaction->customer)
                                            {{ $payment->pawnTransaction->customer->name }} - {{ ucfirst($payment->payment_type) }}
                                        @else
                                            Customer tidak ditemukan - {{ ucfirst($payment->payment_type) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex-shrink-0 text-sm text-gray-500">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="py-4 text-center text-gray-500">
                            Belum ada pembayaran
                        </li>
                        @endforelse
                    </ul>
                </div>
                <div class="mt-6">
                    <a href="{{ route('payments.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Lihat Semua Pembayaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Transactions Chart
    const transactionsCtx = document.getElementById('transactionsChart').getContext('2d');
    const transactionsData = @json($monthlyTransactions);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    new Chart(transactionsCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Transaksi',
                data: months.map((month, index) => transactionsData[index + 1] || 0),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Monthly Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($monthlyRevenue);
    
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: months.map((month, index) => revenueData[index + 1] || 0),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection