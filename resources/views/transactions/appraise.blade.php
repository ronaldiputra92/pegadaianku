@extends('layouts.app')

@section('title', 'Penilaian Barang - ' . $transaction->transaction_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('transactions.show', $transaction) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Penilaian Barang</h1>
                <p class="text-gray-600">Transaksi: {{ $transaction->transaction_code }}</p>
            </div>
        </div>
    </div>

    <!-- Transaction Info -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Barang</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Nama Barang</p>
                    <p class="font-medium text-gray-900">{{ $transaction->item_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kategori</p>
                    <p class="font-medium text-gray-900">{{ $transaction->item_category }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kondisi</p>
                    <p class="font-medium text-gray-900">{{ $transaction->item_condition }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Berat</p>
                    <p class="font-medium text-gray-900">{{ $transaction->item_weight ? $transaction->item_weight . ' gram' : '-' }}</p>
                </div>
                @if($transaction->item_description)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600">Deskripsi</p>
                    <p class="font-medium text-gray-900">{{ $transaction->item_description }}</p>
                </div>
                @endif
            </div>

            <!-- Item Photos -->
            @if($transaction->item_photos && count($transaction->item_photos) > 0)
            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Foto Barang</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($transaction->item_photos_urls as $photo)
                    <div class="aspect-w-1 aspect-h-1">
                        <img src="{{ $photo }}" alt="Foto barang" class="object-cover rounded-lg border border-gray-200">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Appraisal Form -->
    <div class="bg-white shadow rounded-lg">
        <form method="POST" action="{{ route('transactions.store-appraisal', $transaction) }}" class="space-y-6 p-6">
            @csrf

            <h3 class="text-lg font-medium text-gray-900 mb-4">Form Penilaian</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="market_value" class="block text-sm font-medium text-gray-700">Nilai Pasar *</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" 
                               name="market_value" 
                               id="market_value" 
                               value="{{ old('market_value', $transaction->estimated_value) }}"
                               required
                               min="0"
                               class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="0">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Nilai pasar saat ini berdasarkan kondisi barang</p>
                    @error('market_value')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="appraisal_value" class="block text-sm font-medium text-gray-700">Nilai Taksir *</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" 
                               name="appraisal_value" 
                               id="appraisal_value" 
                               value="{{ old('appraisal_value') }}"
                               required
                               min="0"
                               class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="0">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Nilai taksir untuk keperluan gadai</p>
                    @error('appraisal_value')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="loan_to_value_ratio" class="block text-sm font-medium text-gray-700">LTV Ratio (%) *</label>
                    <input type="number" 
                           name="loan_to_value_ratio" 
                           id="loan_to_value_ratio" 
                           value="{{ old('loan_to_value_ratio', '80') }}"
                           required
                           step="0.01"
                           min="0"
                           max="100"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="80.00">
                    <p class="mt-1 text-xs text-gray-500">Persentase maksimal pinjaman dari nilai taksir</p>
                    @error('loan_to_value_ratio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Maksimal Pinjaman</label>
                    <div class="mt-1 p-3 bg-gray-50 rounded-md">
                        <span id="max_loan_amount" class="text-lg font-semibold text-blue-600">Rp 0</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Dihitung otomatis berdasarkan nilai taksir × LTV ratio</p>
                </div>

                <div class="md:col-span-2">
                    <label for="appraisal_notes" class="block text-sm font-medium text-gray-700">Catatan Penilaian *</label>
                    <textarea name="appraisal_notes" 
                              id="appraisal_notes" 
                              rows="4"
                              required
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                              placeholder="Jelaskan detail penilaian, kondisi barang, faktor yang mempengaruhi nilai, dll...">{{ old('appraisal_notes') }}</textarea>
                    @error('appraisal_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Calculation Preview -->
            <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Ringkasan Penilaian</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Nilai Pasar:</span>
                        <div id="display_market_value" class="font-medium text-gray-900">Rp 0</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Nilai Taksir:</span>
                        <div id="display_appraisal_value" class="font-medium text-gray-900">Rp 0</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Maksimal Pinjaman:</span>
                        <div id="display_max_loan" class="font-medium text-blue-600">Rp 0</div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6">
                <a href="{{ route('transactions.show', $transaction) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-check mr-2"></i>
                    Simpan Penilaian
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function calculateMaxLoan() {
        const appraisalValue = parseFloat(document.getElementById('appraisal_value').value) || 0;
        const ltvRatio = parseFloat(document.getElementById('loan_to_value_ratio').value) || 0;
        const marketValue = parseFloat(document.getElementById('market_value').value) || 0;

        const maxLoanAmount = appraisalValue * (ltvRatio / 100);

        // Update displays
        document.getElementById('max_loan_amount').textContent = 'Rp ' + maxLoanAmount.toLocaleString('id-ID');
        document.getElementById('display_market_value').textContent = 'Rp ' + marketValue.toLocaleString('id-ID');
        document.getElementById('display_appraisal_value').textContent = 'Rp ' + appraisalValue.toLocaleString('id-ID');
        document.getElementById('display_max_loan').textContent = 'Rp ' + maxLoanAmount.toLocaleString('id-ID');
    }

    // Add event listeners
    document.getElementById('market_value').addEventListener('input', calculateMaxLoan);
    document.getElementById('appraisal_value').addEventListener('input', calculateMaxLoan);
    document.getElementById('loan_to_value_ratio').addEventListener('input', calculateMaxLoan);

    // Calculate on page load
    calculateMaxLoan();
</script>
@endpush
@endsection