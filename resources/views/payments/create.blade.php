@extends('layouts.app')

@section('title', 'Tambah Pembayaran')

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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('payments.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Tambah Pembayaran</h1>
                    <p class="text-gray-600">Proses pembayaran bunga, sebagian, atau pelunasan penuh</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('payments.store') }}" class="space-y-6 p-6">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <!-- Transaction Selection -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Transaksi</h3>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="pawn_transaction_id" class="block text-sm font-medium text-gray-700">Transaksi Gadai
                                *</label>
                            <select name="pawn_transaction_id" id="pawn_transaction_id" required
                                onchange="loadTransactionDetails()"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                                <option value="">Pilih Transaksi</option>
                                @foreach ($activeTransactions as $trans)
                                    <option value="{{ $trans->id }}"
                                        {{ old('pawn_transaction_id', $transaction?->id) == $trans->id ? 'selected' : '' }}
                                        data-customer="{{ $trans->customer->name }}" data-item="{{ $trans->item_name }}"
                                        data-loan="{{ $trans->loan_amount }}" data-code="{{ $trans->transaction_code }}">
                                        {{ $trans->transaction_code }} - {{ $trans->customer->name }} -
                                        {{ $trans->item_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pawn_transaction_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div id="transaction-details" class="border-b border-gray-200 pb-6"
                    style="{{ $transaction ? '' : 'display: none;' }}">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Transaksi</h3>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Nasabah:</span>
                                <div id="customer-name" class="font-medium text-gray-900">-</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Barang:</span>
                                <div id="item-name" class="font-medium text-gray-900">-</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Pinjaman Pokok:</span>
                                <div id="loan-amount" class="font-medium text-gray-900">Rp 0</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Bunga Saat Ini:</span>
                                <div id="current-interest" class="font-medium text-yellow-600">Rp 0</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Total Kewajiban:</span>
                                <div id="total-obligation" class="font-medium text-blue-600">Rp 0</div>
                            </div>
                            <div>
                                <span class="text-gray-600">Sudah Dibayar:</span>
                                <div id="total-paid" class="font-medium text-green-600">Rp 0</div>
                            </div>
                            <div class="md:col-span-2">
                                <span class="text-gray-600">Sisa Tagihan:</span>
                                <div id="remaining-amount" class="font-bold text-red-600 text-lg">Rp 0</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="payment_type" class="block text-sm font-medium text-gray-700">Jenis Pembayaran
                                *</label>
                            <select name="payment_type" id="payment_type" required onchange="updatePaymentAmount()"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                                <option value="">Pilih Jenis Pembayaran</option>
                                <option value="interest" {{ old('payment_type') === 'interest' ? 'selected' : '' }}>Bayar
                                    Bunga Saja</option>
                                <option value="partial" {{ old('payment_type') === 'partial' ? 'selected' : '' }}>
                                    Pembayaran Sebagian</option>
                                <option value="full" {{ old('payment_type') === 'full' ? 'selected' : '' }}>Pelunasan
                                    Penuh</option>
                            </select>
                            @error('payment_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran
                                *</label>
                            <select name="payment_method" id="payment_method" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                                <option value="">Pilih Metode</option>
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Tunai
                                </option>
                                <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>
                                    Transfer Bank</option>
                                <option value="debit" {{ old('payment_method') === 'debit' ? 'selected' : '' }}>Kartu
                                    Debit</option>
                                <option value="credit" {{ old('payment_method') === 'credit' ? 'selected' : '' }}>Kartu
                                    Kredit</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran
                                *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required
                                    min="0" step="1"
                                    class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus"
                                    placeholder="0">
                            </div>
                            <div id="amount-suggestions" class="mt-2 space-y-1"></div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700">Tanggal Pembayaran
                                *</label>
                            <input type="date" name="payment_date" id="payment_date"
                                value="{{ old('payment_date', date('Y-m-d')) }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Transfer Details (conditional) -->
                        <div id="transfer-details" class="md:col-span-2" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium text-gray-700">Nama
                                        Bank</label>
                                    <input type="text" name="bank_name" id="bank_name"
                                        value="{{ old('bank_name') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3"
                                        placeholder="Contoh: BCA, Mandiri, BRI">
                                </div>
                                <div>
                                    <label for="reference_number" class="block text-sm font-medium text-gray-700">Nomor
                                        Referensi</label>
                                    <input type="text" name="reference_number" id="reference_number"
                                        value="{{ old('reference_number') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3"
                                        placeholder="Nomor referensi transfer">
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3"
                                placeholder="Catatan tambahan pembayaran...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div id="payment-summary" class="bg-blue-50 p-4 rounded-lg" style="display: none;">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Ringkasan Pembayaran</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Pembayaran Bunga:</span>
                            <div id="interest-payment" class="font-medium text-gray-900">Rp 0</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Pembayaran Pokok:</span>
                            <div id="principal-payment" class="font-medium text-gray-900">Rp 0</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Sisa Setelah Bayar:</span>
                            <div id="remaining-after-payment" class="font-medium text-blue-600">Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6">
                    <a href="{{ route('payments.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Proses Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            let transactionData = {};

            // Load transaction details when selection changes
            function loadTransactionDetails() {
                const select = document.getElementById('pawn_transaction_id');
                const transactionId = select.value;

                if (!transactionId) {
                    document.getElementById('transaction-details').style.display = 'none';
                    document.getElementById('payment-summary').style.display = 'none';
                    return;
                }

                // Show loading state
                document.getElementById('transaction-details').style.display = 'block';

                // Fetch transaction details
                fetch(`{{ route('payments.transaction-details') }}?transaction_id=${transactionId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            throw new Error(data.error);
                        }
                        transactionData = data;
                        updateTransactionDisplay();
                        updatePaymentAmount();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memuat detail transaksi: ' + error.message);
                        document.getElementById('transaction-details').style.display = 'none';
                    });
            }

            function updateTransactionDisplay() {
                document.getElementById('customer-name').textContent = transactionData.transaction.customer.name;
                document.getElementById('item-name').textContent = transactionData.transaction.item_name;
                document.getElementById('loan-amount').textContent = 'Rp ' + transactionData.transaction.loan_amount
                    .toLocaleString('id-ID');
                document.getElementById('current-interest').textContent = 'Rp ' + transactionData.current_interest
                    .toLocaleString('id-ID');
                document.getElementById('total-obligation').textContent = 'Rp ' + transactionData.current_total.toLocaleString(
                    'id-ID');
                document.getElementById('total-paid').textContent = 'Rp ' + transactionData.total_paid.toLocaleString('id-ID');
                document.getElementById('remaining-amount').textContent = 'Rp ' + transactionData.remaining_amount
                    .toLocaleString('id-ID');
            }

            function updatePaymentAmount() {
                const paymentType = document.getElementById('payment_type').value;
                const amountInput = document.getElementById('amount');
                const suggestionsDiv = document.getElementById('amount-suggestions');

                if (!transactionData.remaining_amount) return;

                suggestionsDiv.innerHTML = '';

                if (paymentType === 'interest') {
                    // Interest only payment
                    const interestOnly = Math.max(0, transactionData.current_interest - (transactionData.total_paid > 0 ? Math
                        .min(transactionData.total_paid, transactionData.current_interest) : 0));
                    amountInput.value = interestOnly;
                    suggestionsDiv.innerHTML =
                        `<button type="button" onclick="setAmount(${interestOnly})" class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Bunga: Rp ${interestOnly.toLocaleString('id-ID')}</button>`;
                } else if (paymentType === 'full') {
                    // Full payment
                    amountInput.value = transactionData.remaining_amount;
                    suggestionsDiv.innerHTML =
                        `<button type="button" onclick="setAmount(${transactionData.remaining_amount})" class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">Pelunasan: Rp ${transactionData.remaining_amount.toLocaleString('id-ID')}</button>`;
                } else if (paymentType === 'partial') {
                    // Partial payment suggestions
                    const quarter = Math.round(transactionData.remaining_amount / 4);
                    const half = Math.round(transactionData.remaining_amount / 2);
                    const threeQuarter = Math.round(transactionData.remaining_amount * 3 / 4);

                    suggestionsDiv.innerHTML = `
                <div class="flex gap-2 flex-wrap">
                    <button type="button" onclick="setAmount(${quarter})" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">25%: Rp ${quarter.toLocaleString('id-ID')}</button>
                    <button type="button" onclick="setAmount(${half})" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">50%: Rp ${half.toLocaleString('id-ID')}</button>
                    <button type="button" onclick="setAmount(${threeQuarter})" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">75%: Rp ${threeQuarter.toLocaleString('id-ID')}</button>
                </div>
            `;
                }

                updatePaymentSummary();
            }

            function setAmount(amount) {
                document.getElementById('amount').value = amount;
                updatePaymentSummary();
            }

            function updatePaymentSummary() {
                const amount = parseFloat(document.getElementById('amount').value) || 0;

                if (!transactionData.remaining_amount || amount === 0) {
                    document.getElementById('payment-summary').style.display = 'none';
                    return;
                }

                document.getElementById('payment-summary').style.display = 'block';

                // Calculate interest and principal portions
                const totalPaid = transactionData.total_paid;
                const currentInterest = transactionData.current_interest;
                const interestPaid = Math.min(totalPaid, currentInterest);
                const remainingInterest = currentInterest - interestPaid;

                const interestPayment = Math.min(amount, remainingInterest);
                const principalPayment = amount - interestPayment;
                const remainingAfterPayment = transactionData.remaining_amount - amount;

                document.getElementById('interest-payment').textContent = 'Rp ' + interestPayment.toLocaleString('id-ID');
                document.getElementById('principal-payment').textContent = 'Rp ' + principalPayment.toLocaleString('id-ID');
                document.getElementById('remaining-after-payment').textContent = 'Rp ' + Math.max(0, remainingAfterPayment)
                    .toLocaleString('id-ID');
            }

            // Show/hide transfer details based on payment method
            document.getElementById('payment_method').addEventListener('change', function() {
                const transferDetails = document.getElementById('transfer-details');
                if (this.value === 'transfer') {
                    transferDetails.style.display = 'block';
                    document.getElementById('bank_name').required = true;
                    document.getElementById('reference_number').required = true;
                } else {
                    transferDetails.style.display = 'none';
                    document.getElementById('bank_name').required = false;
                    document.getElementById('reference_number').required = false;
                }
            });

            // Update payment summary when amount changes
            document.getElementById('amount').addEventListener('input', updatePaymentSummary);

            // Load transaction details on page load if transaction is pre-selected
            @if ($transaction)
                document.addEventListener('DOMContentLoaded', function() {
                    loadTransactionDetails();
                });
            @endif
        </script>
    @endpush
@endsection
