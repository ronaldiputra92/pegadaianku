@extends('layouts.app')

@section('title', 'Edit Pengguna - ' . $user->name)

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
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Pengguna</h3>
                    <div class="flex space-x-3">
                        <a href="{{ route('users.show', $user) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>
                        <a href="{{ route('users.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Informasi Dasar -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-4">Informasi Dasar</h4>

                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="name" name="name"
                                            value="{{ old('name', $user->name) }}" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3
                                            @error('name') @enderror">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" id="email" name="email"
                                            value="{{ old('email', $user->email) }}" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3 @error('email') @enderror">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="role" class="block text-sm font-medium text-gray-700">
                                            Role <span class="text-red-500">*</span>
                                        </label>
                                        <select id="role" name="role" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3 @error('role') @enderror">
                                            <option value="">Pilih Role</option>
                                            <option value="admin"
                                                {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="petugas"
                                                {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas
                                            </option>
                                            <option value="nasabah"
                                                {{ old('role', $user->role) == 'nasabah' ? 'selected' : '' }}>Nasabah
                                            </option>
                                        </select>
                                        @error('role')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <div id="role-description" class="mt-1 text-sm text-gray-500"></div>
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor
                                            Telepon</label>
                                        <input type="text" id="phone" name="phone"
                                            value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3 @error('phone') @enderror">
                                        @error('phone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-md font-medium text-gray-900 mb-4">Informasi Tambahan</h4>

                                <div class="space-y-4">
                                    <div>
                                        <label for="identity_number" class="block text-sm font-medium text-gray-700">Nomor
                                            Identitas (NIK/KTP)</label>
                                        <input type="text" id="identity_number" name="identity_number"
                                            value="{{ old('identity_number', $user->identity_number) }}"
                                            placeholder="Contoh: 1234567890123456"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3 @error('identity_number') @enderror">
                                        @error('identity_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                                        <textarea id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3 @error('address') @enderror">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status Akun</label>
                                        <div class="mt-2">
                                            <label class="inline-flex items-center">
                                                <input type="hidden" name="is_active" value="0">
                                                <input type="checkbox" name="is_active" value="1"
                                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 custom-input-focus pb-3">
                                                <span class="ml-2 text-sm text-gray-900">Akun Aktif</span>
                                            </label>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">Jika tidak dicentang, pengguna tidak dapat
                                            login ke sistem</p>
                                        @error('is_active')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Informasi Akun -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h5 class="text-sm font-medium text-gray-900 mb-2">Informasi Akun</h5>
                                        <div class="text-sm text-gray-600 space-y-1">
                                            <div><strong>Terdaftar:</strong> {{ $user->created_at->format('d F Y, H:i') }}
                                            </div>
                                            <div><strong>Terakhir Update:</strong>
                                                {{ $user->updated_at->format('d F Y, H:i') }}</div>
                                            <div>
                                                <strong>Email Verified:</strong>
                                                @if ($user->email_verified_at)
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Terverifikasi</span>
                                                @else
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum
                                                        Terverifikasi</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Peringatan untuk perubahan role -->
                    @if ($user->role !== 'nasabah')
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Peringatan!</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Mengubah role pengguna dapat mempengaruhi akses mereka ke sistem. Pastikan Anda
                                            memahami konsekuensi dari perubahan ini.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Informasi Password -->
                    <div class="rounded-md bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Informasi Password</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Password tidak dapat diubah melalui form ini. Jika pengguna lupa password, mereka
                                        dapat menggunakan fitur "Lupa Password" di halaman login, atau hubungi administrator
                                        untuk reset password.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <div>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-trash mr-2"></i>
                                        Hapus Pengguna
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="flex space-x-3">
                            <a href="{{ route('users.show', $user) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const roleDescription = document.getElementById('role-description');
            const originalRole = '{{ $user->role }}';

            // Role description
            roleSelect.addEventListener('change', function() {
                const role = this.value;
                let description = '';

                switch (role) {
                    case 'admin':
                        description = 'Admin memiliki akses penuh ke semua fitur sistem';
                        break;
                    case 'petugas':
                        description = 'Petugas dapat mengelola transaksi, nasabah, dan laporan';
                        break;
                    case 'nasabah':
                        description = 'Nasabah hanya dapat melihat transaksi dan data pribadi';
                        break;
                }

                roleDescription.textContent = description;
            });

            // Confirm role change if user has transactions
            roleSelect.addEventListener('change', function() {
                const newRole = this.value;
                if (originalRole !== newRole && (originalRole === 'nasabah' || newRole === 'nasabah')) {
                    if (!confirm(
                            'Mengubah role dari/ke nasabah dapat mempengaruhi akses ke transaksi. Apakah Anda yakin?'
                        )) {
                        this.value = originalRole;
                        roleSelect.dispatchEvent(new Event('change'));
                    }
                }
            });

            // Trigger role change on page load
            roleSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endpush
