@extends('layouts.app')

@section('title', 'Upload Dokumen Nasabah')
@section('page-title', 'Upload Dokumen Nasabah')

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
                <a href="{{ route('customer-documents.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Upload Dokumen Nasabah</h2>
                    <p class="mt-1 text-sm text-gray-600">Upload dokumen identitas dan berkas nasabah</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('customer-documents.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Selection -->
                    <div class="md:col-span-2">
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Nasabah *</label>
                        <select name="customer_id" id="customer_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('customer_id') @enderror">
                            <option value="">Pilih Nasabah</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}"
                                    {{ (old('customer_id') ?? request('customer_id')) == $customer->id ? 'selected' : '' }}>
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
                        <label for="document_type" class="block text-sm font-medium text-gray-700">Jenis Dokumen *</label>
                        <select name="document_type" id="document_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('document_type') @enderror">
                            <option value="">Pilih Jenis Dokumen</option>
                            <option value="ktp" {{ old('document_type') == 'ktp' ? 'selected' : '' }}>KTP</option>
                            <option value="sim" {{ old('document_type') == 'sim' ? 'selected' : '' }}>SIM</option>
                            <option value="passport" {{ old('document_type') == 'passport' ? 'selected' : '' }}>Passport
                            </option>
                            <option value="kk" {{ old('document_type') == 'kk' ? 'selected' : '' }}>Kartu Keluarga
                            </option>
                            <option value="npwp" {{ old('document_type') == 'npwp' ? 'selected' : '' }}>NPWP</option>
                        </select>
                        @error('document_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Number -->
                    <div>
                        <label for="document_number" class="block text-sm font-medium text-gray-700">Nomor Dokumen *</label>
                        <input type="text" name="document_number" id="document_number"
                            value="{{ old('document_number') }}" required placeholder="Masukkan nomor dokumen"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('document_number') @enderror">
                        @error('document_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div class="md:col-span-2">
                        <label for="document_file" class="block text-sm font-medium text-gray-700">File Dokumen *</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="document_file"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file</span>
                                        <input id="document_file" name="document_file" type="file" class="sr-only"
                                            required accept=".jpg,.jpeg,.png,.pdf" onchange="previewFile(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    JPG, JPEG, PNG, PDF hingga 5MB
                                </p>
                            </div>
                        </div>
                        @error('document_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- File Preview -->
                        <div id="file-preview" class="mt-4 hidden">
                            <div class="flex items-center p-3 bg-gray-50 rounded-md">
                                <div id="file-icon" class="flex-shrink-0 mr-3"></div>
                                <div class="flex-1">
                                    <p id="file-name" class="text-sm font-medium text-gray-900"></p>
                                    <p id="file-size" class="text-xs text-gray-500"></p>
                                </div>
                                <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Catatan tambahan tentang dokumen (opsional)"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('notes') @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('customer-documents.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-upload mr-2"></i>
                        Upload Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewFile(input) {
                const file = input.files[0];
                const preview = document.getElementById('file-preview');
                const fileName = document.getElementById('file-name');
                const fileSize = document.getElementById('file-size');
                const fileIcon = document.getElementById('file-icon');

                if (file) {
                    preview.classList.remove('hidden');
                    fileName.textContent = file.name;
                    fileSize.textContent = formatFileSize(file.size);

                    // Set icon based on file type
                    if (file.type.startsWith('image/')) {
                        fileIcon.innerHTML = '<i class="fas fa-image text-green-500 text-xl"></i>';
                    } else if (file.type === 'application/pdf') {
                        fileIcon.innerHTML = '<i class="fas fa-file-pdf text-red-500 text-xl"></i>';
                    } else {
                        fileIcon.innerHTML = '<i class="fas fa-file text-gray-500 text-xl"></i>';
                    }
                }
            }

            function removeFile() {
                document.getElementById('document_file').value = '';
                document.getElementById('file-preview').classList.add('hidden');
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Drag and drop functionality
            const dropZone = document.querySelector('.border-dashed');
            const fileInput = document.getElementById('document_file');

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-blue-500', 'bg-blue-50');
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-500', 'bg-blue-50');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    previewFile(fileInput);
                }
            });
        </script>
    @endpush
@endsection
