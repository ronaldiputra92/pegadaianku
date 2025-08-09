@extends('layouts.app')

@section('title', 'Laporan Pembayaran')

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
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Pembayaran</h1>
                    <p class="text-gray-600 mt-1">Detail semua pembayaran yang telah dilakukan</p>
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

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Laporan</h3>
            <form method="GET" action="{{ route('reports.payments') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 custom-input-focus pb-3">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 custom-input-focus pb-3">
                </div>
                <div>
                    <label for="payment_type" class="block text-sm font-medium text-gray-700">Jenis Pembayaran</label>
                    <select name="payment_type" id="payment_type"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 custom-input-focus pb-3">
                        <option value="">Semua Jenis</option>
                        <option value="full" {{ request('payment_type') == 'full' ? 'selected' : '' }}>Pelunasan</option>
                        <option value="partial" {{ request('payment_type') == 'partial' ? 'selected' : '' }}>Sebagian
                        </option>
                        <option value="interest" {{ request('payment_type') == 'interest' ? 'selected' : '' }}>Bunga
                        </option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-list text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Total Pembayaran</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($summary['total_count']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Total Jumlah</p>
                        <p class="text-lg font-semibold text-gray-900">Rp
                            {{ number_format($summary['total_amount'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-percentage text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Total Bunga</p>
                        <p class="text-lg font-semibold text-gray-900">Rp
                            {{ number_format($summary['total_interest'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-coins text-2xl text-purple-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Total Pokok</p>
                        <p class="text-lg font-semibold text-gray-900">Rp
                            {{ number_format($summary['total_principal'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Daftar Pembayaran</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                                Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nasabah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bunga
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pokok
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->payment_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('transactions.show', $payment->pawnTransaction) }}"
                                        class="text-blue-600 hover:text-blue-900 font-medium">
                                        {{ $payment->pawnTransaction->transaction_code }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $payment->pawnTransaction->customer->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $payment->pawnTransaction->customer->phone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $payment->payment_type === 'full'
                                    ? 'bg-green-100 text-green-800'
                                    : ($payment->payment_type === 'partial'
                                        ? 'bg-blue-100 text-blue-800'
                                        : 'bg-yellow-100 text-yellow-800') }}">
                                        @if ($payment->payment_type === 'full')
                                            Pelunasan
                                        @elseif($payment->payment_type === 'partial')
                                            Sebagian
                                        @else
                                            Bunga
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($payment->interest_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($payment->principal_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($payment->remaining_balance, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->officer->name ?? 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada data pembayaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-sm font-medium text-gray-900">Total</td>
                            <td class="px-6 py-3 text-sm font-bold text-green-600">
                                Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                Rp {{ number_format($summary['total_interest'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                Rp {{ number_format($summary['total_principal'], 0, ',', '.') }}
                            </td>
                            <td colspan="2" class="px-6 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if ($payments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $payments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

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
