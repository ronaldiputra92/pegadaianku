@extends('layouts.app')

@section('title', 'Dokumen ' . $customer->name)
@section('page-title', 'Dokumen ' . $customer->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Dokumen {{ $customer->name }}</h2>
                <p class="mt-1 text-sm text-gray-600">Daftar dokumen yang dimiliki nasabah</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('customer-documents.create') }}?customer_id={{ $customer->id }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Upload Dokumen
                </a>
                <a href="{{ route('customer-documents.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16">
                    <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl">
                        {{ substr($customer->name, 0, 1) }}
                    </div>
                </div>
                <div class="ml-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ $customer->name }}</h3>
                    <div class="mt-1 text-sm text-gray-500">
                        <p><i class="fas fa-phone mr-2"></i>{{ $customer->phone }}</p>
                        @if($customer->email)
                            <p><i class="fas fa-envelope mr-2"></i>{{ $customer->email }}</p>
                        @endif
                        <p><i class="fas fa-id-card mr-2"></i>{{ $customer->id_type_name }}: {{ $customer->id_number }}</p>
                    </div>
                </div>
                <div class="ml-auto">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Total Dokumen</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $documents->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            @if($documents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($documents as $document)
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center">
                                    @if($document->is_image)
                                        <i class="fas fa-image text-green-500 text-2xl mr-3"></i>
                                    @elseif($document->is_pdf)
                                        <i class="fas fa-file-pdf text-red-500 text-2xl mr-3"></i>
                                    @else
                                        <i class="fas fa-file text-gray-500 text-2xl mr-3"></i>
                                    @endif
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $document->document_type_name }}</h4>
                                        <p class="text-xs text-gray-500">{{ $document->document_number }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    @if($document->is_verified)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <p class="text-xs text-gray-500">{{ $document->original_filename }}</p>
                                <p class="text-xs text-gray-400">{{ $document->file_size_formatted }} • {{ $document->created_at->format('d/m/Y H:i') }}</p>
                                @if($document->notes)
                                    <p class="text-xs text-gray-600 mt-1">{{ Str::limit($document->notes, 50) }}</p>
                                @endif
                            </div>

                            <div class="mt-4 flex justify-between items-center">
                                <div class="flex space-x-2">
                                    <a href="{{ route('customer-documents.show', $document) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('customer-documents.download', $document) }}" 
                                       class="text-green-600 hover:text-green-800 text-sm" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <a href="{{ route('customer-documents.edit', $document) }}" 
                                       class="text-yellow-600 hover:text-yellow-800 text-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(!$document->is_verified)
                                        <button onclick="verifyDocument({{ $document->id }})" 
                                                class="text-green-600 hover:text-green-800 text-sm" title="Verifikasi">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button onclick="deleteDocument({{ $document->id }})" 
                                            class="text-red-600 hover:text-red-800 text-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada dokumen</h3>
                    <p class="text-gray-500 mb-6">Nasabah ini belum memiliki dokumen yang diupload.</p>
                    <a href="{{ route('customer-documents.create') }}?customer_id={{ $customer->id }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Upload Dokumen Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function verifyDocument(id) {
    if (confirm('Apakah Anda yakin ingin memverifikasi dokumen ini?')) {
        fetch(`/customer-documents/${id}/verify`, {
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

function deleteDocument(id) {
    if (confirm('Apakah Anda yakin ingin menghapus dokumen ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/customer-documents/${id}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection