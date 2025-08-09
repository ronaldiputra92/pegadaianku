@extends('layouts.app')

@section('title', 'Detail Transaksi - ' . $transaction->transaction_code)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi</h1>
                    <p class="text-gray-600 mt-1">{{ $transaction->transaction_code }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('transactions.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>

                    @if (in_array($transaction->status, ['active', 'extended', 'overdue']))
                        <a href="{{ route('payments.create', ['transaction_id' => $transaction->id]) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-money-bill-wave mr-2"></i> Bayar
                        </a>

                        <a href="{{ route('extensions.create', ['transaction_id' => $transaction->id]) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <i class="fas fa-calendar-plus mr-2"></i> Perpanjang
                        </a>
                    @endif

                    <a href="{{ route('transactions.receipt', $transaction) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-print mr-2"></i> Cetak Bukti
                    </a>

                    @if ($transaction->status === 'active')
                        <a href="{{ route('transactions.edit', $transaction) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="text-lg font-semibold text-gray-900">
                            @if ($transaction->status == 'active')
                                <span class="text-blue-600">Aktif</span>
                            @elseif($transaction->status == 'paid')
                                <span class="text-green-600">Selesai</span>
                            @elseif($transaction->status == 'overdue')
                                <span class="text-red-600">Terlambat</span>
                            @else
                                <span class="text-gray-600">{{ ucfirst($transaction->status) }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-print text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Bukti Cetak</p>
                        <p class="text-lg font-semibold text-gray-900">
                            @if ($transaction->receipt_printed)
                                <span class="text-green-600">Sudah</span>
                            @else
                                <span class="text-gray-600">Belum</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Nasabah</h3>
                    </div>
                    <div class="p-6">
                        @if ($transaction->customer)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Nama</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->customer->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">No. Telepon</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->customer->phone ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">No. Identitas</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->customer->id_number ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Email</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->customer->email ?? '-' }}</p>
                                </div>
                                @if ($transaction->customer->address)
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-600">Alamat</p>
                                        <p class="font-medium text-gray-900">{{ $transaction->customer->address }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="text-red-600 font-medium">Customer tidak ditemukan</div>
                                <div class="text-red-500 text-sm">Customer ID: {{ $transaction->customer_id }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Item Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Barang</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nama Barang</p>
                                <p class="font-medium text-gray-900">{{ $transaction->item_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kategori</p>
                                <p class="font-medium text-gray-900">{{ $transaction->item_category }}</p>
                            </div>
                            @if ($transaction->item_condition)
                                <div>
                                    <p class="text-sm text-gray-600">Kondisi</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->item_condition }}</p>
                                </div>
                            @endif
                            @if ($transaction->item_weight)
                                <div>
                                    <p class="text-sm text-gray-600">Berat</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->item_weight }} gram</p>
                                </div>
                            @endif
                            @if ($transaction->item_description)
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-600">Deskripsi</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->item_description }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Item Photos -->
                        @if ($transaction->item_photos && count($transaction->item_photos) > 0)
                            <div class="mt-6">
                                <p class="text-sm text-gray-600 mb-3">Foto Barang</p>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach ($transaction->item_photos_urls as $photo)
                                        <div class="aspect-w-1 aspect-h-1">
                                            <img src="{{ $photo }}" alt="Foto barang"
                                                class="object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                                                onclick="openImageModal('{{ $photo }}')"
                                                onerror="this.style.border='2px solid red'; this.alt='Gambar tidak dapat dimuat';">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mt-6">
                                <p class="text-sm text-gray-600 mb-3">Foto Barang</p>
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded text-center">
                                    <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500">Tidak ada foto barang</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Appraisal Information -->
                @if ($transaction->isAppraised())
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Penilaian</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($transaction->market_value)
                                    <div>
                                        <p class="text-sm text-gray-600">Nilai Pasar</p>
                                        <p class="font-medium text-gray-900">Rp
                                            {{ number_format($transaction->market_value, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm text-gray-600">Nilai Taksir</p>
                                    <p class="font-medium text-gray-900">Rp
                                        {{ number_format($transaction->appraisal_value, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">LTV Ratio</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->loan_to_value_ratio }}%</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Maksimal Pinjaman</p>
                                    <p class="font-medium text-green-600">Rp
                                        {{ number_format($transaction->calculateMaxLoanAmount(), 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Penilai</p>
                                    <p class="font-medium text-gray-900">{{ $transaction->appraiser->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal Penilaian</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $transaction->appraised_at ? $transaction->appraised_at->format('d/m/Y H:i') : '-' }}
                                    </p>
                                </div>
                                @if ($transaction->appraisal_notes)
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-gray-600">Catatan Penilaian</p>
                                        <p class="font-medium text-gray-900">{{ $transaction->appraisal_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif


                <!-- Extension History -->
                @if ($transaction->extensions && $transaction->extensions->count() > 0)
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Riwayat Perpanjangan</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kode Perpanjangan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Periode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jatuh Tempo Baru</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Biaya</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Petugas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($transaction->extensions->sortByDesc('created_at') as $extension)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $extension->extension_code }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $extension->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $extension->extension_months }} bulan
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $extension->new_due_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                Rp {{ number_format($extension->total_amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $extension->officer->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('extensions.show', $extension) }}" 
                                                       class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('extensions.receipt', $extension) }}" 
                                                       class="text-green-600 hover:text-green-900"
                                                       title="Cetak Bukti">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Payment History -->
                @if ($transaction->payments->count() > 0)
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Riwayat Pembayaran</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jenis</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bunga</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pokok</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Sisa</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Petugas</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($transaction->payments->sortBy('payment_date') as $payment)
                                        <tr class="{{ $payment->is_final_payment ? 'bg-green-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $payment->payment_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $payment->payment_type === 'full'
                                            ? 'bg-green-100 text-green-800'
                                            : ($payment->payment_type === 'partial'
                                                ? 'bg-blue-100 text-blue-800'
                                                : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ $payment->payment_type === 'full'
                                                        ? 'Pelunasan'
                                                        : ($payment->payment_type === 'partial'
                                                            ? 'Sebagian'
                                                            : 'Bunga') }}
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
                                                {{ $payment->officer->name ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="2" class="px-6 py-3 text-sm font-medium text-gray-900">Total
                                            Pembayaran</td>
                                        <td class="px-6 py-3 text-sm font-bold text-green-600">
                                            Rp {{ number_format($totalPaid, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                            Rp {{ number_format($interestPaid, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                            Rp {{ number_format($principalPaid, 0, ',', '.') }}
                                        </td>
                                        <td colspan="2" class="px-6 py-3"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Loan Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Pinjaman</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Jumlah Pinjaman</span>
                            <span class="font-medium text-gray-900">Rp
                                {{ number_format($transaction->loan_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Suku Bunga</span>
                            <span class="font-medium text-gray-900">{{ $transaction->interest_rate }}% /bulan</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Jangka Waktu</span>
                            <span class="font-medium text-gray-900">{{ $transaction->loan_period_months }} Bulan</span>
                        </div>
                        @if ($transaction->admin_fee > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Biaya Admin</span>
                                <span class="font-medium text-gray-900">Rp
                                    {{ number_format($transaction->admin_fee, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if ($transaction->insurance_fee > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Biaya Asuransi</span>
                                <span class="font-medium text-gray-900">Rp
                                    {{ number_format($transaction->insurance_fee, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if ($transaction->calculateTotalFees() > 0)
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-sm text-gray-600">Pinjaman Bersih</span>
                                <span class="font-medium text-green-600">Rp
                                    {{ number_format($transaction->calculateNetLoanAmount(), 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Tanggal Mulai</span>
                            <span class="font-medium text-gray-900">{{ $transaction->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Jatuh Tempo</span>
                            <span class="font-medium text-gray-900">{{ $transaction->due_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="border-t pt-2 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-900">Bunga Saat Ini</span>
                                <span class="font-medium text-orange-600">Rp
                                    {{ number_format($currentInterest, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-900">Total Tagihan</span>
                                <span class="font-medium text-blue-600">Rp
                                    {{ number_format($currentTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-900">Total Dibayar</span>
                                <span class="font-medium text-green-600">Rp
                                    {{ number_format($totalPaid, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="text-sm font-bold text-gray-900">Sisa Tagihan</span>
                                <span
                                    class="font-bold {{ $currentTotal - $totalPaid > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Rp {{ number_format(max(0, $currentTotal - $totalPaid), 0, ',', '.') }}
                                </span>
                            </div>
                            @if ($currentTotal - $totalPaid <= 0)
                                <div class="text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        LUNAS
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Receipt Information -->
                @if ($transaction->receipt_printed)
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Informasi Bukti</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">No. Bukti</span>
                                <span class="font-medium text-gray-900">{{ $transaction->receipt_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Tanggal Cetak</span>
                                <span
                                    class="font-medium text-gray-900">{{ $transaction->receipt_printed_at ? $transaction->receipt_printed_at->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Officer Information -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Petugas</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Nama Petugas</span>
                            <span class="font-medium text-gray-900">{{ $transaction->officer->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Tanggal Dibuat</span>
                            <span
                                class="font-medium text-gray-900">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if ($transaction->updated_at != $transaction->created_at)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Terakhir Diubah</span>
                                <span
                                    class="font-medium text-gray-900">{{ $transaction->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($transaction->notes)
                    <!-- Notes -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Catatan</h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-900">{{ $transaction->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Foto Barang</h3>
                    <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="text-center">
                    <img id="modalImage" src="" alt="Foto barang"
                        class="max-w-full max-h-96 mx-auto rounded-lg">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openImageModal(imageSrc) {
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('imageModal').classList.remove('hidden');
            }

            function closeImageModal() {
                document.getElementById('imageModal').classList.add('hidden');
            }

            // Close modal when clicking outside
            document.getElementById('imageModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeImageModal();
                }
            });
        </script>
    @endpush
@endsection
