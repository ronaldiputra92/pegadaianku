@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@push('styles')
    <style>
        /* Custom style untuk input focus - hanya border bottom */
        .custom-input-focus:focus {
            outline: none !important;
            border-bottom: 2px solid #000000 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        /* Style default untuk input */
        .custom-input-focus {
            border-bottom: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: all 0.15s ease-in-out;
        }

        /* Hover effect */
        .custom-input-focus:hover {
            border-color: #9ca3af;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Keuangan</h1>
                    <p class="text-gray-600 mt-1">Analisis keuangan mendalam</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('reports.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    {{-- <button onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button> --}}
                </div>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Periode Laporan</h3>
            <form method="GET" action="{{ route('reports.financial') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 custom-input-focus pb-3">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 custom-input-focus pb-3">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-filter mr-2"></i> Update
                    </button>
                </div>
            </form>
        </div>

        <!-- Financial Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-hand-holding-usd text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Pinjaman Disalurkan</p>
                        <p class="text-lg font-semibold text-gray-900">Rp
                            {{ number_format($metrics['total_loans_disbursed'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-line text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Pendapatan Bunga</p>
                        <p class="text-lg font-semibold text-gray-900">Rp
                            {{ number_format($metrics['total_interest_earned'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-coins text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Pokok Terkumpul</p>
                        <p class="text-lg font-semibold text-gray-900">Rp
                            {{ number_format($metrics['total_principal_collected'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-wallet text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Portofolio Aktif</p>
                        <p class="text-lg font-semibold text-gray-900">Rp
                            {{ number_format($metrics['active_loan_portfolio'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Tunggakan</p>
                        <p class="text-lg font-semibold text-gray-900">Rp
                            {{ number_format($metrics['overdue_amount'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Daily Revenue Chart -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Pendapatan Harian</h3>
                <canvas id="dailyRevenueChart" width="400" height="200"></canvas>
            </div>

            <!-- Status Breakdown Chart -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Breakdown Status Transaksi</h3>
                <canvas id="statusChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Status Breakdown Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Detail Status Transaksi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                                Nilai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $totalCount = $statusBreakdown->sum('count');
                            $totalAmount = $statusBreakdown->sum('amount');
                        @endphp
                        @foreach ($statusBreakdown as $status)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $status->status === 'active'
                                    ? 'bg-blue-100 text-blue-800'
                                    : ($status->status === 'paid'
                                        ? 'bg-green-100 text-green-800'
                                        : ($status->status === 'extended'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : 'bg-red-100 text-red-800')) }}">
                                        @if ($status->status === 'active')
                                            Aktif
                                        @elseif($status->status === 'paid')
                                            Lunas
                                        @elseif($status->status === 'extended')
                                            Diperpanjang
                                        @elseif($status->status === 'overdue')
                                            Terlambat
                                        @else
                                            {{ ucfirst($status->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($status->count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($status->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $totalCount > 0 ? number_format(($status->count / $totalCount) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">Total</td>
                            <td class="px-6 py-3 text-sm font-bold text-gray-900">{{ number_format($totalCount) }}</td>
                            <td class="px-6 py-3 text-sm font-bold text-gray-900">Rp
                                {{ number_format($totalAmount, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-sm font-bold text-gray-900">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Daily Revenue Chart
            const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
            const dailyRevenueData = @json($dailyRevenue);

            const labels = dailyRevenueData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit'
                });
            });
            const revenues = dailyRevenueData.map(item => item.revenue);

            new Chart(dailyRevenueCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan Harian (Rp)',
                        data: revenues,
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
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

            // Status Breakdown Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            const statusData = @json($statusBreakdown);

            const statusLabels = statusData.map(item => {
                switch (item.status) {
                    case 'active':
                        return 'Aktif';
                    case 'paid':
                        return 'Lunas';
                    case 'extended':
                        return 'Diperpanjang';
                    case 'overdue':
                        return 'Terlambat';
                    default:
                        return item.status;
                }
            });
            const statusCounts = statusData.map(item => item.count);
            const statusColors = statusData.map(item => {
                switch (item.status) {
                    case 'active':
                        return 'rgba(59, 130, 246, 0.8)';
                    case 'paid':
                        return 'rgba(16, 185, 129, 0.8)';
                    case 'extended':
                        return 'rgba(245, 158, 11, 0.8)';
                    case 'overdue':
                        return 'rgba(239, 68, 68, 0.8)';
                    default:
                        return 'rgba(107, 114, 128, 0.8)';
                }
            });

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusCounts,
                        backgroundColor: statusColors,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            @media print {
                .no-print {
                    display: none !important;
                }

                body {
                    font-size: 12px;
                }

                .shadow {
                    box-shadow: none !important;
                }

                .rounded-lg {
                    border-radius: 0 !important;
                }
            }
        </style>
    @endpush
@endsection
