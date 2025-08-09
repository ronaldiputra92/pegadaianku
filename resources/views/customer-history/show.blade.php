@extends('layouts.app')

@section('title', 'Riwayat Customer - ' . $customer->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Customer</h1>
                <p class="text-gray-600 mt-1">Detail riwayat transaksi dan pembayaran customer</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('customer-history.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <button onclick="exportData()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-download mr-2"></i> Export
                </button>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="mb-6">
        <div class="bg-white shadow rounded-lg">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ $customer->name }}</h2>
                        <div class="space-y-2">
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-id-card mr-2 w-4"></i> {{ $customer->id_number }}
                            </p>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-phone mr-2 w-4"></i> {{ $customer->phone }}
                            </p>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 w-4"></i> {{ $customer->address }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="border-r border-gray-200">
                                <h3 class="text-2xl font-bold text-blue-600">{{ $statistics['total_transactions'] }}</h3>
                                <p class="text-sm text-gray-500">Total Transaksi</p>
                            </div>
                            <div class="border-r border-gray-200">
                                <h3 class="text-2xl font-bold text-green-600">{{ $statistics['active_transactions'] }}</h3>
                                <p class="text-sm text-gray-500">Aktif</p>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-cyan-600">Rp {{ number_format($statistics['total_loan_amount'], 0, ',', '.') }}</h3>
                                <p class="text-sm text-gray-500">Total Pinjaman</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-600 text-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-medium text-blue-100">Transaksi Aktif</h3>
                        <p class="text-2xl font-bold">{{ $statistics['active_transactions'] }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-handshake text-3xl text-blue-200"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-green-600 text-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-medium text-green-100">Transaksi Selesai</h3>
                        <p class="text-2xl font-bold">{{ $statistics['completed_transactions'] }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-3xl text-green-200"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-yellow-600 text-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-medium text-yellow-100">Transaksi Terlambat</h3>
                        <p class="text-2xl font-bold">{{ $statistics['overdue_transactions'] }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-3xl text-yellow-200"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-cyan-600 text-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-medium text-cyan-100">Total Pembayaran</h3>
                        <p class="text-2xl font-bold">Rp {{ number_format($statistics['total_payments'], 0, ',', '.') }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-money-bill-wave text-3xl text-cyan-200"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white shadow rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('transactions')" id="transactions-tab" class="tab-button active border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-handshake mr-2"></i> Transaksi ({{ $transactions->total() }})
                </button>
                <button onclick="showTab('payments')" id="payments-tab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-money-bill-wave mr-2"></i> Pembayaran ({{ $payments->total() }})
                </button>
            </nav>
        </div>
        <div class="p-6">
            <div class="tab-content">
                        <!-- Transactions Tab -->
                <div class="tab-panel" id="transactions">
                    @if($transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Transaksi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pinjaman</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transactions as $transaction)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-semibold text-gray-900">{{ $transaction->transaction_code }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $transaction->item_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $transaction->item_description }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-semibold text-gray-900">Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($transaction->status == 'active')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Aktif</span>
                                            @elseif($transaction->status == 'paid')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                            @elseif($transaction->status == 'overdue')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Terlambat</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($transaction->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaction->officer->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('transactions.show', $transaction->id) }}" class="inline-flex items-center px-3 py-1 border border-blue-300 rounded-md text-sm text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="flex justify-center mt-6">
                            {{ $transactions->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-handshake text-6xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada transaksi</h3>
                            <p class="text-gray-500">Customer ini belum memiliki riwayat transaksi</p>
                        </div>
                    @endif
                </div>

                        <!-- Payments Tab -->
                <div class="tab-panel hidden" id="payments">
                    @if($payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Transaksi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pembayaran</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('transactions.show', $payment->pawnTransaction->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                                {{ $payment->pawnTransaction->transaction_code }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($payment->payment_type == 'interest')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">Bunga</span>
                                            @elseif($payment->payment_type == 'principal')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Pokok</span>
                                            @elseif($payment->payment_type == 'full')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Pelunasan</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($payment->payment_type) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst($payment->payment_method) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $payment->officer->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $payment->notes ?? '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="flex justify-center mt-6">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-money-bill-wave text-6xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pembayaran</h3>
                            <p class="text-gray-500">Customer ini belum memiliki riwayat pembayaran</p>
                        </div>
                    @endif
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tab-button.active {
    border-color: #3B82F6;
    color: #3B82F6;
}
</style>

<script>
function showTab(tabName) {
    // Sembunyikan semua tab panel
    const tabPanels = document.querySelectorAll('.tab-panel');
    tabPanels.forEach(panel => {
        panel.classList.add('hidden');
    });
    
    // Hapus class active dari semua tab button
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active');
        button.classList.add('text-gray-500');
        button.classList.remove('text-blue-600');
    });
    
    // Tampilkan tab panel yang dipilih
    const selectedPanel = document.getElementById(tabName);
    if (selectedPanel) {
        selectedPanel.classList.remove('hidden');
    }
    
    // Tambahkan class active ke tab button yang dipilih
    const selectedButton = document.getElementById(tabName + '-tab');
    if (selectedButton) {
        selectedButton.classList.add('active');
        selectedButton.classList.remove('text-gray-500');
        selectedButton.classList.add('text-blue-600');
    }
}

function exportData() {
    // Implementasi export data
    fetch(`{{ route('customer-history.export', $customer->id) }}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat export data');
    });
}

// Inisialisasi tab pertama sebagai aktif saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    showTab('transactions');
});
</script>
@endsection