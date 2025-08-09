@extends('layouts.app')

@section('title', 'Perpanjang Gadai')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Perpanjang Gadai</h1>
        <p class="text-gray-600 mt-2">Proses perpanjangan jatuh tempo transaksi gadai</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Transaction Search -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cari Transaksi</h3>
                
                <div class="mb-4">
                    <label for="transaction_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Transaksi
                    </label>
                    <div class="flex">
                        <input type="text" 
                               id="transaction_code" 
                               placeholder="Masukkan kode transaksi..."
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="button" 
                                id="search_transaction"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div id="transaction_details" class="hidden">
                    <div class="border-t pt-4">
                        <h4 class="font-medium text-gray-900 mb-3">Detail Transaksi</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Kode Transaksi:</span>
                                <span id="detail_transaction_code" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nasabah:</span>
                                <span id="detail_customer_name" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Barang:</span>
                                <span id="detail_item_name" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jumlah Pinjaman:</span>
                                <span id="detail_loan_amount" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Suku Bunga:</span>
                                <span id="detail_interest_rate" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Jatuh Tempo:</span>
                                <span id="detail_due_date" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span id="detail_status" class="font-medium"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Perpanjangan:</span>
                                <span id="detail_extensions_count" class="font-medium"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Extension Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Form Perpanjangan</h3>
                
                <form id="extension_form" method="POST" action="{{ route('extensions.store') }}" class="hidden">
                    @csrf
                    <input type="hidden" id="transaction_id" name="transaction_id">
                    
                    <div class="mb-4">
                        <label for="extension_months" class="block text-sm font-medium text-gray-700 mb-2">
                            Periode Perpanjangan (Bulan) <span class="text-red-500">*</span>
                        </label>
                        <select id="extension_months" 
                                name="extension_months" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih periode...</option>
                            <option value="1">1 Bulan</option>
                            <option value="2">2 Bulan</option>
                            <option value="3">3 Bulan</option>
                            <option value="4">4 Bulan</option>
                            <option value="5">5 Bulan</option>
                            <option value="6">6 Bulan</option>
                        </select>
                    </div>

                    <!-- Fee Calculation -->
                    <div id="fee_calculation" class="mb-4 hidden">
                        <h4 class="font-medium text-gray-900 mb-3">Rincian Biaya</h4>
                        <div class="bg-gray-50 p-4 rounded-md space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Bunga Perpanjangan:</span>
                                <span id="fee_interest" class="font-medium">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Denda Keterlambatan:</span>
                                <span id="fee_penalty" class="font-medium">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Admin:</span>
                                <span id="fee_admin" class="font-medium">-</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between font-medium">
                                <span>Total Biaya:</span>
                                <span id="fee_total" class="text-blue-600">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3"
                                  placeholder="Catatan perpanjangan (opsional)..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                            <i class="fas fa-calendar-plus mr-2"></i>Proses Perpanjangan
                        </button>
                        <a href="{{ route('extensions.index') }}" 
                           class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium text-center">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                    </div>
                </form>

                <div id="no_transaction" class="text-center py-8">
                    <i class="fas fa-search text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">Cari transaksi terlebih dahulu untuk melakukan perpanjangan</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const transactionCodeInput = document.getElementById('transaction_code');
    const searchButton = document.getElementById('search_transaction');
    const transactionDetails = document.getElementById('transaction_details');
    const extensionForm = document.getElementById('extension_form');
    const noTransaction = document.getElementById('no_transaction');
    const extensionMonthsSelect = document.getElementById('extension_months');
    const feeCalculation = document.getElementById('fee_calculation');

    // Auto-fill if transaction_id is provided in URL
    const urlParams = new URLSearchParams(window.location.search);
    const transactionId = urlParams.get('transaction_id');
    if (transactionId) {
        // You could add logic here to auto-search by transaction ID
    }

    // Search transaction
    function searchTransaction() {
        const transactionCode = transactionCodeInput.value.trim();
        if (!transactionCode) {
            alert('Masukkan kode transaksi');
            return;
        }

        // Show loading
        searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        searchButton.disabled = true;

        fetch('{{ route("extensions.transaction-details") }}?' + new URLSearchParams({
            transaction_code: transactionCode
        }))
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                showTransactionDetails(data.transaction);
            } else {
                alert(data.message || 'Transaksi tidak ditemukan');
                hideTransactionDetails();
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            alert('Terjadi kesalahan saat mencari transaksi: ' + error.message);
            hideTransactionDetails();
        })
        .finally(() => {
            searchButton.innerHTML = '<i class="fas fa-search"></i>';
            searchButton.disabled = false;
        });
    }

    function showTransactionDetails(transaction) {
        // Fill transaction details
        document.getElementById('detail_transaction_code').textContent = transaction.transaction_code;
        document.getElementById('detail_customer_name').textContent = transaction.customer_name;
        document.getElementById('detail_item_name').textContent = transaction.item_name;
        document.getElementById('detail_loan_amount').textContent = transaction.formatted.loan_amount;
        document.getElementById('detail_interest_rate').textContent = transaction.formatted.interest_rate;
        document.getElementById('detail_due_date').textContent = transaction.due_date;
        document.getElementById('detail_status').textContent = transaction.status;
        document.getElementById('detail_extensions_count').textContent = transaction.extensions_count + ' kali';

        // Set transaction ID in form
        document.getElementById('transaction_id').value = transaction.id;

        // Show elements
        transactionDetails.classList.remove('hidden');
        extensionForm.classList.remove('hidden');
        noTransaction.classList.add('hidden');

        // Reset form
        extensionMonthsSelect.value = '';
        feeCalculation.classList.add('hidden');
    }

    function hideTransactionDetails() {
        transactionDetails.classList.add('hidden');
        extensionForm.classList.add('hidden');
        noTransaction.classList.remove('hidden');
        feeCalculation.classList.add('hidden');
    }

    // Calculate fees when extension months change
    function calculateFees() {
        const transactionId = document.getElementById('transaction_id').value;
        const extensionMonths = extensionMonthsSelect.value;

        if (!transactionId || !extensionMonths) {
            feeCalculation.classList.add('hidden');
            return;
        }

        fetch('{{ route("extensions.calculate-fees") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                transaction_id: transactionId,
                extension_months: parseInt(extensionMonths)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('fee_interest').textContent = data.formatted.interest_amount;
                document.getElementById('fee_penalty').textContent = data.formatted.penalty_amount;
                document.getElementById('fee_admin').textContent = data.formatted.admin_fee;
                document.getElementById('fee_total').textContent = data.formatted.total_amount;
                feeCalculation.classList.remove('hidden');
            } else {
                alert('Gagal menghitung biaya perpanjangan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghitung biaya');
        });
    }

    // Event listeners
    searchButton.addEventListener('click', searchTransaction);
    transactionCodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchTransaction();
        }
    });
    extensionMonthsSelect.addEventListener('change', calculateFees);
});
</script>
@endpush
@endsection