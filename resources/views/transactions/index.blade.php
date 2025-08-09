@extends('layouts.app')

@section('title', 'Daftar Transaksi')
@section('page-title', 'Daftar Transaksi')

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
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600">Kelola semua transaksi gadai</p>
                </div>
                <a href="{{ route('transactions.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    Transaksi Baru
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4">
                <form method="GET" action="{{ route('transactions.index') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Kode transaksi atau nama nasabah"
                            class="mt-1 pb-3 block w-full custom-input-focus sm:text-sm">
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 pb-3 block w-full custom-input-focus sm:text-sm">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="extended" {{ request('status') === 'extended' ? 'selected' : '' }}>Diperpanjang
                            </option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Jatuh Tempo
                            </option>
                            <option value="auction" {{ request('status') === 'auction' ? 'selected' : '' }}>Lelang</option>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                            class="mt-1 pb-3 block w-full custom-input-focus sm:text-sm">
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                            class="mt-1 pb-3 block w-full custom-input-focus sm:text-sm">
                    </div>

                    <!-- Filter Buttons -->
                    <div class="md:col-span-4 flex space-x-3">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-search mr-2"></i>
                            Filter
                        </button>
                        <a href="{{ route('transactions.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-times mr-2"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:p-6">
                @if ($transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode Transaksi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nasabah
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Barang
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pinjaman
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jatuh Tempo
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($transactions as $transaction)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $transaction->transaction_code }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->created_at->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($transaction->customer)
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $transaction->customer->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $transaction->customer->phone }}
                                                </div>
                                            @else
                                                <div class="text-sm font-medium text-red-600">
                                                    Customer tidak ditemukan
                                                </div>
                                                <div class="text-sm text-red-500">
                                                    ID: {{ $transaction->customer_id }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $transaction->item_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->item_category }}
                                                @if ($transaction->item_weight)
                                                    - {{ $transaction->item_weight }}g
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $transaction->interest_rate }}% / bulan
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $transaction->due_date->format('d/m/Y') }}
                                            </div>
                                            @php
                                                $daysUntilDue = $transaction->getDaysUntilDue();
                                            @endphp
                                            @if ($daysUntilDue < 0)
                                                <div class="text-sm text-red-600">
                                                    Terlambat {{ abs($daysUntilDue) }} hari
                                                </div>
                                            @elseif($daysUntilDue <= 7)
                                                <div class="text-sm text-yellow-600">
                                                    {{ $daysUntilDue }} hari lagi
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-500">
                                                    {{ $daysUntilDue }} hari lagi
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'active' => 'bg-green-100 text-green-800',
                                                    'extended' => 'bg-blue-100 text-blue-800',
                                                    'paid' => 'bg-gray-100 text-gray-800',
                                                    'overdue' => 'bg-red-100 text-red-800',
                                                    'auction' => 'bg-purple-100 text-purple-800',
                                                ];
                                                $statusLabels = [
                                                    'active' => 'Aktif',
                                                    'extended' => 'Diperpanjang',
                                                    'paid' => 'Lunas',
                                                    'overdue' => 'Jatuh Tempo',
                                                    'auction' => 'Lelang',
                                                ];
                                            @endphp
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('transactions.show', $transaction) }}"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if (in_array($transaction->status, ['active', 'extended']))
                                                    <a href="{{ route('transactions.edit', $transaction) }}"
                                                        class="text-yellow-600 hover:text-yellow-900">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('payments.create', ['transaction_id' => $transaction->id]) }}"
                                                        class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-money-bill"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-exchange-alt text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada transaksi</h3>
                        <p class="text-gray-500 mb-6">Belum ada transaksi yang sesuai dengan filter yang dipilih.</p>
                        <a href="{{ route('transactions.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Transaksi Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
