@extends('layouts.app')

@section('title', 'Detail Pembayaran - ' . $payment->payment_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pembayaran</h1>
                <p class="text-gray-600 mt-1">{{ $payment->payment_code }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <a href="{{ route('payments.receipt', $payment) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-print mr-2"></i> Cetak Bukti
                </a>
                <a href="{{ route('transactions.show', $payment->pawnTransaction) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-eye mr-2"></i> Lihat Transaksi
                </a>
            </div>
        </div>
    </div>

    <!-- Payment Status -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Status Pembayaran</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        @if($payment->isFullPayment())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i> Pelunasan Penuh
                            </span>
                        @elseif($payment->payment_type === 'interest')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-percent mr-2"></i> Pembayaran Bunga
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-coins mr-2"></i> Pembayaran Sebagian
                            </span>
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-600">{{ $payment->payment_date->format('d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Information -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Pembayaran</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Kode Pembayaran</span>
                    <span class="font-medium text-gray-900">{{ $payment->payment_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Jenis Pembayaran</span>
                    <span class="font-medium text-gray-900">{{ $payment->payment_type_display }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Metode Pembayaran</span>
                    <span class="font-medium text-gray-900">{{ $payment->payment_method_display }}</span>
                </div>
                @if($payment->payment_method === 'transfer')
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Bank</span>
                    <span class="font-medium text-gray-900">{{ $payment->bank_name ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">No. Referensi</span>
                    <span class="font-medium text-gray-900">{{ $payment->reference_number ?? '-' }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Tanggal Pembayaran</span>
                    <span class="font-medium text-gray-900">{{ $payment->payment_date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Petugas</span>
                    <span class="font-medium text-gray-900">{{ $payment->officer->name }}</span>
                </div>
                @if($payment->notes)
                <div class="pt-2 border-t">
                    <span class="text-sm text-gray-600">Catatan</span>
                    <p class="font-medium text-gray-900 mt-1">{{ $payment->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Transaction Information -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Transaksi</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Kode Transaksi</span>
                    <a href="{{ route('transactions.show', $payment->pawnTransaction) }}" class="font-medium text-blue-600 hover:text-blue-800">
                        {{ $payment->pawnTransaction->transaction_code }}
                    </a>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Nasabah</span>
                    <span class="font-medium text-gray-900">
                        @if($payment->pawnTransaction->customer)
                            {{ $payment->pawnTransaction->customer->name }}
                        @else
                            <span class="text-red-600">Customer tidak ditemukan</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Barang</span>
                    <span class="font-medium text-gray-900">{{ $payment->pawnTransaction->item_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Pinjaman Pokok</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($payment->pawnTransaction->loan_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Status Transaksi</span>
                    <span class="font-medium text-gray-900">
                        @if($payment->pawnTransaction->status == 'active')
                            <span class="text-blue-600">Aktif</span>
                        @elseif($payment->pawnTransaction->status == 'paid')
                            <span class="text-green-600">Selesai</span>
                        @elseif($payment->pawnTransaction->status == 'overdue')
                            <span class="text-red-600">Terlambat</span>
                        @else
                            <span class="text-gray-600">{{ ucfirst($payment->pawnTransaction->status) }}</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Breakdown -->
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Rincian Pembayaran</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-yellow-50 rounded-lg">
                    <div class="text-2xl font-bold text-yellow-600">Rp {{ number_format($payment->interest_amount, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-600 mt-1">Pembayaran Bunga</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">Rp {{ number_format($payment->principal_amount, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-600 mt-1">Pembayaran Pokok</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-gray-600">Rp {{ number_format($payment->remaining_balance, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-600 mt-1">Sisa Tagihan</div>
                </div>
            </div>

            @if($payment->isFullPayment())
            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                    <div>
                        <h4 class="text-green-800 font-medium">Transaksi Lunas</h4>
                        <p class="text-green-700 text-sm">Pembayaran ini melunasi seluruh kewajiban. Barang dapat diambil oleh nasabah.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Receipt Information -->
    @if($payment->receipt_printed)
    <div class="mt-6 bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Bukti</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">No. Bukti</span>
                <span class="font-medium text-gray-900">{{ $payment->receipt_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Tanggal Cetak</span>
                <span class="font-medium text-gray-900">{{ $payment->receipt_printed_at ? $payment->receipt_printed_at->format('d/m/Y H:i') : '-' }}</span>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection