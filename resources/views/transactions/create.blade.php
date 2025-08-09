@extends('layouts.app')

@section('title', 'Buat Transaksi Baru')
@section('page-title', 'Buat Transaksi Baru')

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
                <a href="{{ route('transactions.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <p class="text-gray-600">Isi form di bawah untuk membuat transaksi gadai baru</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data"
                class="space-y-6 p-6">
                @csrf

                <!-- Customer Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Nasabah</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700">Nasabah *</label>
                            <select name="customer_id" id="customer_id" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                                <option value="">Pilih Nasabah</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->phone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Item Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Barang</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="item_name" class="block text-sm font-medium text-gray-700">Nama Barang *</label>
                            <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3"
                                placeholder="Contoh: Emas Kalung 24K">
                            @error('item_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="item_category" class="block text-sm font-medium text-gray-700">Kategori Barang
                                *</label>
                            <select name="item_category" id="item_category" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                                <option value="">Pilih Kategori</option>
                                <option value="Perhiasan" {{ old('item_category') === 'Perhiasan' ? 'selected' : '' }}>
                                    Perhiasan</option>
                                <option value="Elektronik" {{ old('item_category') === 'Elektronik' ? 'selected' : '' }}>
                                    Elektronik</option>
                                <option value="Kendaraan" {{ old('item_category') === 'Kendaraan' ? 'selected' : '' }}>
                                    Kendaraan</option>
                                <option value="Lainnya" {{ old('item_category') === 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                            @error('item_category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="item_condition" class="block text-sm font-medium text-gray-700">Kondisi Barang
                                *</label>
                            <select name="item_condition" id="item_condition" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                                <option value="">Pilih Kondisi</option>
                                <option value="Baik" {{ old('item_condition') === 'Baik' ? 'selected' : '' }}>Baik
                                </option>
                                <option value="Rusak Ringan"
                                    {{ old('item_condition') === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="Rusak Berat"
                                    {{ old('item_condition') === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                            @error('item_condition')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="item_weight" class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                            <input type="number" name="item_weight" id="item_weight" value="{{ old('item_weight') }}"
                                step="0.01" min="0"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3"
                                placeholder="0.00">
                            @error('item_weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="item_photos" class="block text-sm font-medium text-gray-700">Foto Barang</label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="item_photos"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload foto</span>
                                            <input id="item_photos" name="item_photos[]" type="file" class="sr-only"
                                                multiple accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB (maksimal 5 foto)</p>
                                </div>
                            </div>
                            @error('item_photos.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="item_description" class="block text-sm font-medium text-gray-700">Deskripsi
                                Barang</label>
                            <textarea name="item_description" id="item_description" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3"
                                placeholder="Deskripsi detail barang...">{{ old('item_description') }}</textarea>
                            @error('item_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Appraisal Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Penilaian Barang</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="market_value" class="block text-sm font-medium text-gray-700">Nilai Pasar</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="market_value" id="market_value"
                                    value="{{ old('market_value') }}" min="0"
                                    class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus"
                                    placeholder="0">
                            </div>
                            @error('market_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        <div>
                            <label for="appraisal_value" class="block text-sm font-medium text-gray-700">Nilai Taksir
                                Petugas</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="appraisal_value" id="appraisal_value"
                                    value="{{ old('appraisal_value') }}" min="0"
                                    class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus"
                                    placeholder="0">
                            </div>
                            @error('appraisal_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="loan_to_value_ratio" class="block text-sm font-medium text-gray-700">LTV Ratio (%)
                                - Auto Calculate</label>
                            <input type="number" name="loan_to_value_ratio" id="loan_to_value_ratio"
                                value="{{ old('loan_to_value_ratio', '0') }}" step="0.01" min="0"
                                max="100" readonly
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-600 cursor-not-allowed sm:text-sm custom-input-focus"
                                placeholder="Auto calculated">
                            <p class="mt-1 text-xs text-gray-500">Otomatis dihitung: (Jumlah Pinjaman ÷ Nilai Taksir
                                Petugas) × 100%</p>
                            @error('loan_to_value_ratio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="appraisal_notes" class="block text-sm font-medium text-gray-700">Catatan
                                Penilaian</label>
                            <textarea name="appraisal_notes" id="appraisal_notes" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus"
                                placeholder="Catatan detail penilaian barang...">{{ old('appraisal_notes') }}</textarea>
                            @error('appraisal_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Loan Information -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pinjaman</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="loan_amount" class="block text-sm font-medium text-gray-700">Jumlah Pinjaman
                                *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="loan_amount" id="loan_amount"
                                    value="{{ old('loan_amount') }}" required min="0"
                                    class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus"
                                    placeholder="0">
                            </div>
                            @error('loan_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="interest_rate" class="block text-sm font-medium text-gray-700">Suku Bunga (% per
                                bulan) *</label>
                            <input type="number" name="interest_rate" id="interest_rate"
                                value="{{ old('interest_rate', '1.25') }}" required step="0.01" min="0"
                                max="100"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3"
                                placeholder="1.25">
                            @error('interest_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="loan_period_months" class="block text-sm font-medium text-gray-700 mb-2">Jangka
                                Waktu
                                (bulan) *</label>
                            <select name="loan_period_months" id="loan_period_months" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                                <option value="">Pilih Jangka Waktu</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('loan_period_months', 4) == $i ? 'selected' : '' }}>
                                        {{ $i }} Bulan
                                    </option>
                                @endfor
                            </select>
                            @error('loan_period_months')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="admin_fee" class="block text-sm font-medium text-gray-700">Biaya Admin</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="admin_fee" id="admin_fee"
                                    value="{{ old('admin_fee', '0') }}" min="0"
                                    class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus"
                                    placeholder="0">
                            </div>
                            @error('admin_fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="insurance_fee" class="block text-sm font-medium text-gray-700">Biaya
                                Asuransi</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="insurance_fee" id="insurance_fee"
                                    value="{{ old('insurance_fee', '0') }}" min="0"
                                    class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus"
                                    placeholder="0">
                            </div>
                            @error('insurance_fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai
                                *</label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', date('Y-m-d')) }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus"
                                placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Calculation Preview -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Perhitungan Estimasi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Bunga per bulan:</span>
                            <div id="monthly_interest" class="font-medium text-gray-900">Rp 0</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Total bunga:</span>
                            <div id="total_interest" class="font-medium text-gray-900">Rp 0</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Total yang harus dibayar:</span>
                            <div id="total_amount" class="font-medium text-blue-600">Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6">
                    <a href="{{ route('transactions.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function calculateLoan() {
                const loanAmount = parseFloat(document.getElementById('loan_amount').value) || 0;
                const interestRate = parseFloat(document.getElementById('interest_rate').value) || 0;
                const loanPeriod = parseInt(document.getElementById('loan_period_months').value) || 0;

                const monthlyInterest = loanAmount * (interestRate / 100);
                const totalInterest = monthlyInterest * loanPeriod;
                const totalAmount = loanAmount + totalInterest;

                document.getElementById('monthly_interest').textContent = 'Rp ' + monthlyInterest.toLocaleString('id-ID');
                document.getElementById('total_interest').textContent = 'Rp ' + totalInterest.toLocaleString('id-ID');
                document.getElementById('total_amount').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
            }

            // Calculate LTV Ratio automatically
            function calculateLTVRatio() {
                const loanAmount = parseFloat(document.getElementById('loan_amount').value) || 0;
                const appraisalValue = parseFloat(document.getElementById('appraisal_value').value) || 0;

                let ltvRatio = 0;
                if (appraisalValue > 0) {
                    ltvRatio = (loanAmount / appraisalValue) * 100;
                }

                // Round to 2 decimal places
                ltvRatio = Math.round(ltvRatio * 100) / 100;

                document.getElementById('loan_to_value_ratio').value = ltvRatio;
            }

            // File upload feedback
            function handleFileSelect() {
                const fileInput = document.getElementById('item_photos');
                const fileCount = fileInput.files.length;

                if (fileCount > 0) {
                    const fileNames = Array.from(fileInput.files).map(file => file.name).join(', ');
                    const feedback = document.createElement('div');
                    feedback.className = 'mt-2 text-sm text-green-600';
                    feedback.textContent = `${fileCount} file(s) dipilih: ${fileNames}`;

                    // Remove existing feedback
                    const existingFeedback = fileInput.parentNode.parentNode.parentNode.querySelector('.file-feedback');
                    if (existingFeedback) {
                        existingFeedback.remove();
                    }

                    // Add new feedback
                    feedback.className += ' file-feedback';
                    fileInput.parentNode.parentNode.parentNode.appendChild(feedback);
                }
            }

            // Combined calculation function
            function performCalculations() {
                calculateLoan();
                calculateLTVRatio();
            }

            // Add event listeners
            document.getElementById('loan_amount').addEventListener('input', performCalculations);
            document.getElementById('appraisal_value').addEventListener('input', performCalculations);
            document.getElementById('interest_rate').addEventListener('input', calculateLoan);
            document.getElementById('loan_period_months').addEventListener('change', calculateLoan);
            document.getElementById('item_photos').addEventListener('change', handleFileSelect);

            // Calculate on page load
            performCalculations();
        </script>
    @endpush
@endsection
