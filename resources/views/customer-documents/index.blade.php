@extends('layouts.app')

@section('title', 'Dokumen Nasabah')
@section('page-title', 'Dokumen Nasabah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Dokumen Nasabah</h2>
            <p class="mt-1 text-sm text-gray-600">Kelola dokumen identitas dan berkas nasabah</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('customer-documents.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i>
                Upload Dokumen
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('customer-documents.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="document_type" class="block text-sm font-medium text-gray-700">Jenis Dokumen</label>
                    <select name="document_type" id="document_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Jenis</option>
                        <option value="ktp" {{ request('document_type') == 'ktp' ? 'selected' : '' }}>KTP</option>
                        <option value="sim" {{ request('document_type') == 'sim' ? 'selected' : '' }}>SIM</option>
                        <option value="passport" {{ request('document_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                        <option value="kk" {{ request('document_type') == 'kk' ? 'selected' : '' }}>Kartu Keluarga</option>
                        <option value="npwp" {{ request('document_type') == 'npwp' ? 'selected' : '' }}>NPWP</option>
                    </select>
                </div>
                
                <div>
                    <label for="verified" class="block text-sm font-medium text-gray-700">Status Verifikasi</label>
                    <select name="verified" id="verified" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Belum Verifikasi</option>
                    </select>
                </div>

                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Cari Nasabah</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Nama atau nomor dokumen..."
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            @if($documents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nasabah
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jenis Dokumen
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nomor Dokumen
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    File
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Upload
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($documents as $document)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                                                {{ substr($document->customer->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $document->customer->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $document->customer->phone }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $document->document_type_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $document->document_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($document->is_image)
                                            <i class="fas fa-image text-green-500 mr-2"></i>
                                        @elseif($document->is_pdf)
                                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                        @else
                                            <i class="fas fa-file text-gray-500 mr-2"></i>
                                        @endif
                                        <div>
                                            <div class="text-sm text-gray-900">{{ $document->original_filename }}</div>
                                            <div class="text-xs text-gray-500">{{ $document->file_size_formatted }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($document->is_verified)
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $document->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('customer-documents.show', $document) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customer-documents.download', $document) }}" 
                                           class="text-green-600 hover:text-green-900" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="{{ route('customer-documents.edit', $document) }}" 
                                           class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$document->is_verified)
                                            <button onclick="verifyDocument({{ $document->id }})" 
                                                    class="text-green-600 hover:text-green-900" title="Verifikasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button onclick="deleteDocument({{ $document->id }})" 
                                                class="text-red-600 hover:text-red-900" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $documents->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada dokumen</h3>
                    <p class="text-gray-500 mb-6">Mulai dengan mengupload dokumen nasabah pertama.</p>
                    <a href="{{ route('customer-documents.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>
                        Upload Dokumen
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