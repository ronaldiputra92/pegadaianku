@extends('layouts.app')

@section('title', 'Detail Dokumen')
@section('page-title', 'Detail Dokumen')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('customer-documents.index') }}" 
                   class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Detail Dokumen</h2>
                    <p class="mt-1 text-sm text-gray-600">{{ $customerDocument->document_type_name }} - {{ $customerDocument->customer->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('customer-documents.download', $customerDocument) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>
                    Download
                </a>
                @if(!$customerDocument->is_verified)
                    <button onclick="verifyDocument()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        <i class="fas fa-check mr-2"></i>
                        Verifikasi
                    </button>
                @endif
                <a href="{{ route('customer-documents.edit', $customerDocument) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Document Preview -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Preview Dokumen</h3>
                    
                    @if($customerDocument->is_image)
                        <div class="text-center">
                            <img src="{{ $customerDocument->document_url }}" 
                                 alt="{{ $customerDocument->original_filename }}"
                                 class="max-w-md max-h-96 w-auto h-auto rounded-lg shadow-lg mx-auto object-contain cursor-pointer hover:opacity-90 transition-opacity"
                                 onerror="handleImageError(this)"
                                 onload="console.log('Image loaded successfully')"
                                 onclick="openImageModal(this.src, '{{ $customerDocument->original_filename }}')">
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-search-plus mr-1"></i>
                                Klik gambar untuk memperbesar
                            </p>
                        </div>
                    @elseif($customerDocument->is_pdf)
                        <div class="text-center">
                            <div class="bg-gray-100 rounded-lg p-8">
                                <i class="fas fa-file-pdf text-red-500 text-6xl mb-4"></i>
                                <p class="text-gray-600 mb-4">File PDF tidak dapat ditampilkan di browser ini.</p>
                                <a href="{{ $customerDocument->document_url }}" target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Buka di Tab Baru
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <div class="bg-gray-100 rounded-lg p-8">
                                <i class="fas fa-file text-gray-500 text-6xl mb-4"></i>
                                <p class="text-gray-600 mb-4">File tidak dapat ditampilkan.</p>
                                <a href="{{ route('customer-documents.download', $customerDocument) }}"
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    <i class="fas fa-download mr-2"></i>
                                    Download File
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Document Information -->
        <div class="space-y-6">
            <!-- Basic Info -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Dokumen</h3>
                    
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis Dokumen</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $customerDocument->document_type_name }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nomor Dokumen</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customerDocument->document_number }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama File</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customerDocument->original_filename }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ukuran File</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customerDocument->file_size_formatted }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status Verifikasi</dt>
                            <dd class="mt-1">
                                @if($customerDocument->is_verified)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Menunggu Verifikasi
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tanggal Upload</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customerDocument->created_at->format('d F Y, H:i') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Diupload oleh</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customerDocument->uploadedBy->name }}</dd>
                        </div>
                        
                        @if($customerDocument->is_verified)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Diverifikasi oleh</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customerDocument->verifiedBy->name }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Verifikasi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customerDocument->verified_at->format('d F Y, H:i') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Nasabah</h3>
                    
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                {{ substr($customerDocument->customer->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $customerDocument->customer->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $customerDocument->customer->phone }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $customerDocument->customer->email }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('customers.show', $customerDocument->customer) }}" 
                           class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            Lihat Detail Nasabah →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($customerDocument->notes)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Catatan</h3>
                        <p class="text-sm text-gray-700">{{ $customerDocument->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-full max-h-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
        <div class="absolute bottom-4 left-4 right-4 text-white text-center">
            <p id="modalImageName" class="text-sm bg-black bg-opacity-50 rounded px-2 py-1"></p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function verifyDocument() {
    if (confirm('Apakah Anda yakin ingin memverifikasi dokumen ini?')) {
        fetch(`/customer-documents/{{ $customerDocument->id }}/verify`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal memverifikasi dokumen');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function handleImageError(img) {
    console.error('Image failed to load:', img.src);
    
    // Try alternative URL
    const originalSrc = img.src;
    const alternativeUrl = '{{ asset("storage/" . $customerDocument->document_path) }}';
    
    if (img.src !== alternativeUrl) {
        console.log('Trying alternative URL:', alternativeUrl);
        img.src = alternativeUrl;
        return;
    }
    
    // If both URLs fail, show error message
    const container = img.parentElement;
    container.innerHTML = `
        <div class="bg-red-100 rounded-lg p-8">
            <i class="fas fa-exclamation-triangle text-red-500 text-6xl mb-4"></i>
            <p class="text-red-600 mb-4">Gambar tidak dapat dimuat</p>
            <p class="text-sm text-gray-600 mb-4">
                URL yang dicoba:<br>
                1. ${originalSrc}<br>
                2. ${alternativeUrl}
            </p>
            <a href="{{ route('customer-documents.download', $customerDocument) }}"
               class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                <i class="fas fa-download mr-2"></i>
                Download File
            </a>
        </div>
    `;
}

// Image Modal Functions
function openImageModal(imageSrc, imageName) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalImageName = document.getElementById('modalImageName');
    
    modalImage.src = imageSrc;
    modalImage.alt = imageName;
    modalImageName.textContent = imageName;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto'; // Restore scrolling
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Test URLs on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Testing image URLs...');
    console.log('Primary URL: {{ $customerDocument->document_url }}');
    console.log('Alternative URL: {{ asset("storage/" . $customerDocument->document_path) }}');
    
    // Test if URLs are accessible
    fetch('{{ $customerDocument->document_url }}', { method: 'HEAD' })
        .then(response => {
            console.log('Primary URL status:', response.status);
        })
        .catch(error => {
            console.error('Primary URL failed:', error);
        });
        
    fetch('{{ asset("storage/" . $customerDocument->document_path) }}', { method: 'HEAD' })
        .then(response => {
            console.log('Alternative URL status:', response.status);
        })
        .catch(error => {
            console.error('Alternative URL failed:', error);
        });
});
</script>
@endpush
@endsection