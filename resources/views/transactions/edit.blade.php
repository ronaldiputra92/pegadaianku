@extends('layouts.app')

@section('title', 'Edit Transaksi - ' . $transaction->transaction_code)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('transactions.show', $transaction) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Transaksi</h1>
                <p class="text-gray-600">{{ $transaction->transaction_code }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form method="POST" action="{{ route('transactions.update', $transaction) }}" enctype="multipart/form-data" class="space-y-6 p-6">
            @csrf
            @method('PUT')

            <!-- Customer Information -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Nasabah</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Nasabah *</label>
                        <select name="customer_id" 
                                id="customer_id" 
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Pilih Nasabah</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $transaction->customer_id) == $customer->id ? 'selected' : '' }}>
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
                        <input type="text" 
                               name="item_name" 
                               id="item_name" 
                               value="{{ old('item_name', $transaction->item_name) }}"
                               required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Contoh: Emas Kalung 24K">
                        @error('item_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="item_category" class="block text-sm font-medium text-gray-700">Kategori Barang *</label>
                        <select name="item_category" 
                                id="item_category" 
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Pilih Kategori</option>
                            <option value="Perhiasan" {{ old('item_category', $transaction->item_category) === 'Perhiasan' ? 'selected' : '' }}>Perhiasan</option>
                            <option value="Elektronik" {{ old('item_category', $transaction->item_category) === 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="Kendaraan" {{ old('item_category', $transaction->item_category) === 'Kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                            <option value="Lainnya" {{ old('item_category', $transaction->item_category) === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('item_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="item_condition" class="block text-sm font-medium text-gray-700">Kondisi Barang *</label>
                        <select name="item_condition" 
                                id="item_condition" 
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Pilih Kondisi</option>
                            <option value="Baik" {{ old('item_condition', $transaction->item_condition) === 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('item_condition', $transaction->item_condition) === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('item_condition', $transaction->item_condition) === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        @error('item_condition')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="item_weight" class="block text-sm font-medium text-gray-700">Berat (gram)</label>
                        <input type="number" 
                               name="item_weight" 
                               id="item_weight" 
                               value="{{ old('item_weight', $transaction->item_weight) }}"
                               step="0.01"
                               min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="0.00">
                        @error('item_weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Photos Display -->
                    @if($transaction->item_photos && count($transaction->item_photos) > 0)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Barang Saat Ini</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            @foreach($transaction->item_photos_urls as $index => $photo)
                            <div class="relative">
                                <img src="{{ $photo }}" alt="Foto barang" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                                <button type="button" onclick="removePhoto({{ $index }})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="md:col-span-2">
                        <label for="item_photos" class="block text-sm font-medium text-gray-700">Tambah Foto Barang Baru</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="item_photos" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Pilih foto baru</span>
                                        <input id="item_photos" name="item_photos[]" type="file" class="sr-only" multiple accept="image/jpeg,image/png,image/jpg" onchange="previewNewPhotos(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB (maksimal 5 foto)</p>
                                <div id="new_photos_preview" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-2 hidden"></div>
                            </div>
                        </div>
                        @error('item_photos.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="item_description" class="block text-sm font-medium text-gray-700">Deskripsi Barang</label>
                        <textarea name="item_description" 
                                  id="item_description" 
                                  rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Deskripsi detail barang...">{{ old('item_description', $transaction->item_description) }}</textarea>
                        @error('item_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Appraisal Information (Read Only if already appraised) -->
            @if($transaction->isAppraised())
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Penilaian (Tidak dapat diubah)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nilai Pasar</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <span class="text-gray-900">Rp {{ number_format($transaction->market_value, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nilai Taksir</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <span class="text-gray-900">Rp {{ number_format($transaction->appraisal_value, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">LTV Ratio</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <span class="text-gray-900">{{ $transaction->loan_to_value_ratio }}%</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Maksimal Pinjaman</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <span class="text-green-600 font-semibold">Rp {{ number_format($transaction->calculateMaxLoanAmount(), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($transaction->appraisal_notes)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Catatan Penilaian</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <span class="text-gray-900">{{ $transaction->appraisal_notes }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Loan Information -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pinjaman</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="estimated_value" class="block text-sm font-medium text-gray-700">Nilai Taksir Awal *</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" 
                                   name="estimated_value" 
                                   id="estimated_value" 
                                   value="{{ old('estimated_value', $transaction->estimated_value) }}"
                                   required
                                   min="0"
                                   class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="0">
                        </div>
                        @error('estimated_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="loan_amount" class="block text-sm font-medium text-gray-700">Jumlah Pinjaman *</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" 
                                   name="loan_amount" 
                                   id="loan_amount" 
                                   value="{{ old('loan_amount', $transaction->loan_amount) }}"
                                   required
                                   min="0"
                                   @if($transaction->isAppraised()) max="{{ $transaction->calculateMaxLoanAmount() }}" @endif
                                   class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="0">
                        </div>
                        @if($transaction->isAppraised())
                        <p class="mt-1 text-xs text-gray-500">Maksimal: Rp {{ number_format($transaction->calculateMaxLoanAmount(), 0, ',', '.') }}</p>
                        @endif
                        @error('loan_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="interest_rate" class="block text-sm font-medium text-gray-700">Suku Bunga (% per bulan) *</label>
                        <input type="number" 
                               name="interest_rate" 
                               id="interest_rate" 
                               value="{{ old('interest_rate', $transaction->interest_rate) }}"
                               required
                               step="0.01"
                               min="0"
                               max="100"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="1.25">
                        @error('interest_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="loan_period_months" class="block text-sm font-medium text-gray-700">Jangka Waktu (bulan) *</label>
                        <select name="loan_period_months" 
                                id="loan_period_months" 
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Pilih Jangka Waktu</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ old('loan_period_months', $transaction->loan_period_months) == $i ? 'selected' : '' }}>
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
                            <input type="number" 
                                   name="admin_fee" 
                                   id="admin_fee" 
                                   value="{{ old('admin_fee', $transaction->admin_fee ?? 0) }}"
                                   min="0"
                                   class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="0">
                        </div>
                        @error('admin_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="insurance_fee" class="block text-sm font-medium text-gray-700">Biaya Asuransi</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" 
                                   name="insurance_fee" 
                                   id="insurance_fee" 
                                   value="{{ old('insurance_fee', $transaction->insurance_fee ?? 0) }}"
                                   min="0"
                                   class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="0">
                        </div>
                        @error('insurance_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Catatan tambahan...">{{ old('notes', $transaction->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Calculation Preview -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Perhitungan Estimasi</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Bunga per bulan:</span>
                        <div id="monthly_interest" class="font-medium text-gray-900">Rp 0</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Total bunga:</span>
                        <div id="total_interest" class="font-medium text-gray-900">Rp 0</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Total biaya:</span>
                        <div id="total_fees" class="font-medium text-gray-900">Rp 0</div>
                    </div>
                    <div>
                        <span class="text-gray-600">Total yang harus dibayar:</span>
                        <div id="total_amount" class="font-medium text-blue-600">Rp 0</div>
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
                    <i class="fas fa-save mr-2"></i>
                    Update Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let removedPhotos = [];

    function removePhoto(index) {
        // Add to removed photos array
        removedPhotos.push(index);
        
        // Hide the photo element
        event.target.closest('.relative').style.display = 'none';
        
        // Create hidden input to track removed photos
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'removed_photos[]';
        hiddenInput.value = index;
        document.querySelector('form').appendChild(hiddenInput);
    }

    function previewNewPhotos(input) {
        const previewContainer = document.getElementById('new_photos_preview');
        previewContainer.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            previewContainer.classList.remove('hidden');
            
            // Limit to 5 photos maximum
            const maxFiles = Math.min(input.files.length, 5);
            
            for (let i = 0; i < maxFiles; i++) {
                const file = input.files[i];
                
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview" class="w-full h-16 object-cover rounded border border-gray-200">
                            <div class="absolute -top-1 -right-1 bg-green-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs">
                                <i class="fas fa-plus"></i>
                            </div>
                        `;
                        previewContainer.appendChild(div);
                    };
                    
                    reader.readAsDataURL(file);
                }
            }
            
            if (input.files.length > 5) {
                alert('Maksimal 5 foto yang dapat diunggah. Hanya 5 foto pertama yang akan diproses.');
            }
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    function calculateLoan() {
        const loanAmount = parseFloat(document.getElementById('loan_amount').value) || 0;
        const interestRate = parseFloat(document.getElementById('interest_rate').value) || 0;
        const loanPeriod = parseInt(document.getElementById('loan_period_months').value) || 0;
        const adminFee = parseFloat(document.getElementById('admin_fee').value) || 0;
        const insuranceFee = parseFloat(document.getElementById('insurance_fee').value) || 0;

        const monthlyInterest = loanAmount * (interestRate / 100);
        const totalInterest = monthlyInterest * loanPeriod;
        const totalFees = adminFee + insuranceFee;
        const totalAmount = loanAmount + totalInterest;

        document.getElementById('monthly_interest').textContent = 'Rp ' + monthlyInterest.toLocaleString('id-ID');
        document.getElementById('total_interest').textContent = 'Rp ' + totalInterest.toLocaleString('id-ID');
        document.getElementById('total_fees').textContent = 'Rp ' + totalFees.toLocaleString('id-ID');
        document.getElementById('total_amount').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
    }

    // Add event listeners
    document.getElementById('loan_amount').addEventListener('input', calculateLoan);
    document.getElementById('interest_rate').addEventListener('input', calculateLoan);
    document.getElementById('loan_period_months').addEventListener('change', calculateLoan);
    document.getElementById('admin_fee').addEventListener('input', calculateLoan);
    document.getElementById('insurance_fee').addEventListener('input', calculateLoan);

    // Calculate on page load
    calculateLoan();
</script>
@endpush
@endsection