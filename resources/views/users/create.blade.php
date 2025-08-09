@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

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
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Tambah Pengguna Baru</h3>
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>

                <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                    @csrf
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
                                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                                            required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3 @error('name') @enderror">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                                            required
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
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
                                            </option>
                                            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas
                                            </option>
                                            <option value="nasabah" {{ old('role') == 'nasabah' ? 'selected' : '' }}>Nasabah
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
                                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                            placeholder="Contoh: 08123456789"
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
                                            value="{{ old('identity_number') }}" placeholder="Contoh: 1234567890123456"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3 @error('identity_number') @enderror">
                                        @error('identity_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                                        <textarea id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3 @error('address') @enderror">{{ old('address') }}</textarea>
                                        @error('address')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">
                                            Password <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="password" name="password" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3 @error('password') @enderror">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-sm text-gray-500">Minimal 8 karakter</p>
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                            Konfirmasi Password <span class="text-red-500">*</span>
                                        </label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm custom-input-focus pb-3">
                                        <div id="password-match-message" class="mt-1 text-sm"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('users.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Pengguna
                        </button>
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
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');
            const roleDescription = document.getElementById('role-description');
            const passwordMatchMessage = document.getElementById('password-match-message');

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

            // Password confirmation validation
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = passwordConfirmInput.value;

                if (confirmPassword === '') {
                    passwordMatchMessage.textContent = '';
                    passwordConfirmInput.classList.remove('border-red-300', 'border-green-300');
                    return;
                }

                if (password !== confirmPassword) {
                    passwordMatchMessage.textContent = 'Password tidak cocok';
                    passwordMatchMessage.className = 'mt-1 text-sm text-red-600';
                    passwordConfirmInput.classList.add('border-red-300');
                    passwordConfirmInput.classList.remove('border-green-300');
                } else {
                    passwordMatchMessage.textContent = 'Password cocok';
                    passwordMatchMessage.className = 'mt-1 text-sm text-green-600';
                    passwordConfirmInput.classList.add('border-green-300');
                    passwordConfirmInput.classList.remove('border-red-300');
                }
            }

            passwordInput.addEventListener('input', checkPasswordMatch);
            passwordConfirmInput.addEventListener('input', checkPasswordMatch);

            // Trigger role change on page load
            roleSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endpush
