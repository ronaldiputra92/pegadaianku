@extends('layouts.app')

@section('title', 'Edit Dokumen')
@section('page-title', 'Edit Dokumen')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Dokumen</h2>
                <p class="mt-1 text-sm text-gray-600">Perbarui informasi dokumen nasabah</p>
            </div>
            <a href="{{ route('customer-documents.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('customer-documents.update', $customerDocument) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Selection -->
                <div class="md:col-span-2">
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Nasabah</label>
                    <select name="customer_id" id="customer_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('customer_id') border-red-300 @enderror">
                        <option value="">Pilih Nasabah</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $customerDocument->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Document Type -->
                <div>
                    <label for="document_type" class="block text-sm font-medium text-gray-700">Jenis Dokumen</label>
                    <select name="document_type" id="document_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('document_type') border-red-300 @enderror">
                        <option value="">Pilih Jenis Dokumen</option>
                        <option value="ktp" {{ old('document_type', $customerDocument->document_type) == 'ktp' ? 'selected' : '' }}>KTP</option>
                        <option value="sim" {{ old('document_type', $customerDocument->document_type) == 'sim' ? 'selected' : '' }}>SIM</option>
                        <option value="passport" {{ old('document_type', $customerDocument->document_type) == 'passport' ? 'selected' : '' }}>Passport</option>
                        <option value="kk" {{ old('document_type', $customerDocument->document_type) == 'kk' ? 'selected' : '' }}>Kartu Keluarga</option>
                        <option value="npwp" {{ old('document_type', $customerDocument->document_type) == 'npwp' ? 'selected' : '' }}>NPWP</option>
                    </select>
                    @error('document_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Document Number -->
                <div>
                    <label for="document_number" class="block text-sm font-medium text-gray-700">Nomor Dokumen</label>
                    <input type="text" name="document_number" id="document_number" 
                           value="{{ old('document_number', $customerDocument->document_number) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('document_number') border-red-300 @enderror"
                           placeholder="Masukkan nomor dokumen">
                    @error('document_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current File Info -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">File Saat Ini</label>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        @if($customerDocument->is_image)
                            <i class="fas fa-image text-green-500 text-2xl mr-3"></i>
                        @elseif($customerDocument->is_pdf)
                            <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                        @else
                            <i class="fas fa-file text-gray-500 text-2xl mr-3"></i>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $customerDocument->original_filename }}</p>
                            <p class="text-xs text-gray-500">{{ $customerDocument->file_size_formatted }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('customer-documents.download', $customerDocument) }}" 
                               class="text-blue-600 hover:text-blue-800" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            @if($customerDocument->is_image)
                                <button type="button" onclick="previewImage('{{ $customerDocument->document_url }}')" 
                                        class="text-green-600 hover:text-green-800" title="Preview">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Document File (Optional for update) -->
                <div class="md:col-span-2">
                    <label for="document_file" class="block text-sm font-medium text-gray-700">File Dokumen Baru (Opsional)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="document_file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload file baru</span>
                                    <input id="document_file" name="document_file" type="file" class="sr-only" 
                                           accept=".jpg,.jpeg,.png,.pdf"
                                           onchange="updateFileName(this)">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF hingga 5MB</p>
                            <p id="file-name" class="text-sm text-gray-900 font-medium hidden"></p>
                        </div>
                    </div>
                    @error('document_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-300 @enderror"
                              placeholder="Catatan tambahan (opsional)">{{ old('notes', $customerDocument->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('customer-documents.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>
                    Perbarui Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-medium">Preview Dokumen</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4">
                <img id="previewImg" src="" alt="Preview" class="max-w-full h-auto">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateFileName(input) {
    const fileName = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        fileName.textContent = input.files[0].name;
        fileName.classList.remove('hidden');
    } else {
        fileName.classList.add('hidden');
    }
}

function previewImage(url) {
    document.getElementById('previewImg').src = url;
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