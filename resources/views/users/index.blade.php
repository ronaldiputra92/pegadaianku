@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Daftar Pengguna</h3>
                    <a href="{{ route('users.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Pengguna
                    </a>
                </div>

                <!-- Filter dan Pencarian -->
                <form method="GET" action="{{ route('users.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Cari Pengguna</label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Nama, email, atau telepon..."
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select id="role" name="role"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas
                                </option>
                                <option value="nasabah" {{ request('role') == 'nasabah' ? 'selected' : '' }}>Nasabah
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status" name="status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-search mr-2"></i>
                                Cari
                            </button>
                            <a href="{{ route('users.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-times mr-2"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Tabel Pengguna dengan Scroll Horizontal -->
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full py-2 align-middle">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                No</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                Nama</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                Email</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                Role</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                Telepon</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                Status</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                Terdaftar</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap sticky right-0 bg-gray-50">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($users as $user)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <div
                                                                class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                                                                {{ substr($user->name, 0, 1) }}
                                                            </div>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $user->name }}</div>
                                                            @if ($user->identity_number)
                                                                <div class="text-sm text-gray-500">NIK:
                                                                    {{ $user->identity_number }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $user->email }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($user->role == 'admin')
                                                        <span
                                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Admin</span>
                                                    @elseif($user->role == 'petugas')
                                                        <span
                                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Petugas</span>
                                                    @else
                                                        <span
                                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Nasabah</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $user->phone ?? '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if ($user->is_active)
                                                        <span
                                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                                    @else
                                                        <span
                                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tidak
                                                            Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $user->created_at->format('d/m/Y') }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium sticky right-0 bg-white">
                                                    <div class="flex space-x-3">
                                                        <a href="{{ route('users.show', $user) }}"
                                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                            title="Lihat Detail">
                                                            <i class="fas fa-eye mr-1"></i>
                                                            Lihat
                                                        </a>
                                                        <a href="{{ route('users.edit', $user) }}"
                                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                                                            title="Edit">
                                                            <i class="fas fa-edit mr-1"></i>
                                                            Edit
                                                        </a>
                                                        @if ($user->id !== auth()->id())
                                                            <form action="{{ route('users.destroy', $user) }}"
                                                                method="POST" class="inline"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                                    title="Hapus">
                                                                    <i class="fas fa-trash mr-1"></i>
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                                                        <p class="text-gray-500">Tidak ada data pengguna yang ditemukan.
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="mt-6">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                @endif

                <!-- Info Scroll untuk Mobile -->
                <div class="mt-4 text-center text-sm text-gray-500 md:hidden">
                    <i class="fas fa-arrows-alt-h mr-1"></i>
                    Geser tabel ke kiri atau kanan untuk melihat semua kolom
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Custom scrollbar untuk tabel */
            .overflow-x-auto::-webkit-scrollbar {
                height: 8px;
            }

            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 4px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            /* Sticky column shadow effect */
            .sticky {
                box-shadow: -2px 0 4px rgba(0, 0, 0, 0.1);
            }

            /* Smooth scroll behavior */
            .overflow-x-auto {
                scroll-behavior: smooth;
            }
        </style>
    @endpush
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto submit form when filter changes
            const roleSelect = document.getElementById('role');
            const statusSelect = document.getElementById('status');

            if (roleSelect) {
                roleSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            // Add scroll indicator for table
            const tableContainer = document.querySelector('.overflow-x-auto');
            if (tableContainer) {
                function updateScrollIndicator() {
                    const scrollLeft = tableContainer.scrollLeft;
                    const scrollWidth = tableContainer.scrollWidth;
                    const clientWidth = tableContainer.clientWidth;

                    // Add visual feedback for scrollable content
                    if (scrollWidth > clientWidth) {
                        if (scrollLeft === 0) {
                            tableContainer.classList.add('scroll-start');
                            tableContainer.classList.remove('scroll-end', 'scroll-middle');
                        } else if (scrollLeft + clientWidth >= scrollWidth - 1) {
                            tableContainer.classList.add('scroll-end');
                            tableContainer.classList.remove('scroll-start', 'scroll-middle');
                        } else {
                            tableContainer.classList.add('scroll-middle');
                            tableContainer.classList.remove('scroll-start', 'scroll-end');
                        }
                    }
                }

                tableContainer.addEventListener('scroll', updateScrollIndicator);
                updateScrollIndicator(); // Initial check
            }
        });
    </script>
@endpush
