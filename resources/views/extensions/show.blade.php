@extends('layouts.app')

@section('title', 'Detail Perpanjangan Gadai')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Perpanjangan Gadai</h1>
                <p class="text-gray-600 mt-2">{{ $extension->extension_code }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('extensions.receipt', $extension) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-print mr-2"></i>Cetak Bukti
                </a>
                <a href="{{ route('extensions.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Extension Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Perpanjangan</h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kode Perpanjangan</label>
                                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $extension->extension_code }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Perpanjangan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $extension->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Periode Perpanjangan</label>
                                <p class="mt-1 text-sm text-gray-900 font-medium">{{ $extension->extension_months }} Bulan
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Petugas</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $extension->officer->name }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jatuh Tempo Lama</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $extension->original_due_date->format('d/m/Y') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jatuh Tempo Baru</label>
                                <p class="mt-1 text-sm text-gray-900 font-medium">
                                    {{ $extension->new_due_date->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        @if ($extension->notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $extension->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Fee Breakdown -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Rincian Biaya</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-600">Bunga Perpanjangan</span>
                                <span class="text-xs text-gray-400">
                                    {{ $extension->transaction->interest_rate }}% × Rp
                                    {{ number_format($extension->transaction->loan_amount, 0, ',', '.') }} ×
                                    {{ $extension->extension_months }} bulan
                                </span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($extension->interest_amount, 0, ',', '.') }}
                            </span>
                        </div>

                        @if ($extension->penalty_amount > 0)
                            @php
                                $overdueDays = \Carbon\Carbon::now()->diffInDays($extension->original_due_date);
                                $dailyPenaltyRate = config('pawn.penalty_rate_per_day', 0.001) * 100; // Convert to percentage
                            @endphp
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-600">Denda Keterlambatan</span>
                                    <span class="text-xs text-gray-400">
                                        {{ $dailyPenaltyRate }}% per hari × {{ $overdueDays }} hari × Rp
                                        {{ number_format($extension->transaction->loan_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-red-600">
                                    Rp {{ number_format($extension->penalty_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-600">Biaya Administrasi</span>
                                <span class="text-xs text-gray-400">Biaya tetap per perpanjangan</span>
                            </div>
                            <span class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($extension->admin_fee, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center py-3 bg-blue-50 px-4 rounded-md">
                            <span class="text-base font-medium text-gray-900">Total Biaya</span>
                            <span class="text-lg font-bold text-blue-600">
                                Rp {{ number_format($extension->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Calculation Details -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-md">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Perhitungan:</h4>
                        <div class="space-y-2 text-xs text-gray-600">
                            <div class="flex justify-between">
                                <span>• Bunga Perpanjangan:</span>
                                <span>{{ $extension->transaction->interest_rate }}% × Rp
                                    {{ number_format($extension->transaction->loan_amount, 0, ',', '.') }} ×
                                    {{ $extension->extension_months }} = Rp
                                    {{ number_format($extension->interest_amount, 0, ',', '.') }}</span>
                            </div>
                            @if ($extension->penalty_amount > 0)
                                <div class="flex justify-between">
                                    <span>• Denda Keterlambatan:</span>
                                    <span>{{ $dailyPenaltyRate }}% × {{ $overdueDays }} hari × Rp
                                        {{ number_format($extension->transaction->loan_amount, 0, ',', '.') }} = Rp
                                        {{ number_format($extension->penalty_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span>• Biaya Admin:</span>
                                <span>Rp {{ number_format($extension->admin_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-medium border-t pt-2">
                                <span>Total:</span>
                                <span>Rp {{ number_format($extension->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    @if ($extension->receipt_printed)
                        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-md">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                <span class="text-sm text-green-700">
                                    Bukti sudah dicetak pada {{ $extension->receipt_printed_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            @if ($extension->receipt_number)
                                <p class="text-xs text-green-600 mt-1">
                                    No. Bukti: {{ $extension->receipt_number }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Transaction Information -->
        <div class="mt-6 bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Transaksi</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kode Transaksi</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">
                            <a href="{{ route('transactions.show', $extension->transaction) }}"
                                class="text-blue-600 hover:text-blue-800">
                                {{ $extension->transaction->transaction_code }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nasabah</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $extension->transaction->customer->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $extension->transaction->item_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Pinjaman</label>
                        <p class="mt-1 text-sm text-gray-900 font-medium">
                            Rp {{ number_format($extension->transaction->loan_amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Suku Bunga</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $extension->transaction->interest_rate }}% per bulan</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status Transaksi</label>
                        <p class="mt-1">
                            <span
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            {{ $extension->transaction->status === 'extended' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $extension->transaction->status === 'extended' ? 'Diperpanjang' : ucfirst($extension->transaction->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Extension History -->
        @if ($extension->transaction->extensions->count() > 1)
            <div class="mt-6 bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Perpanjangan</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Periode
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jatuh Tempo Baru
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Biaya
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Petugas
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($extension->transaction->extensions->sortByDesc('created_at') as $ext)
                                    <tr class="{{ $ext->id === $extension->id ? 'bg-blue-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $ext->extension_code }}
                                            @if ($ext->id === $extension->id)
                                                <span class="ml-2 text-xs text-blue-600">(Saat ini)</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $ext->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $ext->extension_months }} bulan
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $ext->new_due_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($ext->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $ext->officer->name }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
