@extends('layouts.app')

@section('title', 'Tambah Nasabah')
@section('page-title', 'Tambah Nasabah')

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
                <a href="{{ route('customers.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Nasabah Baru</h2>
                    <p class="mt-1 text-sm text-gray-600">Daftarkan nasabah baru ke sistem pegadaian</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('customers.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('name') @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('email') @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            placeholder="08xxxxxxxxxx"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('phone') @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ID Number -->
                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700">NIK/Nomor Identitas *</label>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" required
                            placeholder="16 digit NIK"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('id_number') @enderror">
                        @error('id_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ID Type -->
                    <div>
                        <label for="id_type" class="block text-sm font-medium text-gray-700">Jenis Identitas</label>
                        <select name="id_type" id="id_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 custom-input-focus pb-3">
                            <option value="ktp" {{ old('id_type') == 'ktp' ? 'selected' : '' }}>KTP</option>
                            <option value="sim" {{ old('id_type') == 'sim' ? 'selected' : '' }}>SIM</option>
                            <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                        </select>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin *</label>
                        <select name="gender" id="gender" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('gender') @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('date_of_birth') @enderror">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Place of Birth -->
                    <div>
                        <label for="place_of_birth" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input type="text" name="place_of_birth" id="place_of_birth" value="{{ old('place_of_birth') }}"
                            placeholder="Kota tempat lahir"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('place_of_birth') @enderror">
                        @error('place_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Occupation -->
                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                        <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}"
                            placeholder="Pekerjaan/profesi"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('occupation') @enderror">
                        @error('occupation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Monthly Income -->
                    <div>
                        <label for="monthly_income" class="block text-sm font-medium text-gray-700">Penghasilan
                            Bulanan</label>
                        <input type="number" name="monthly_income" id="monthly_income" value="{{ old('monthly_income') }}"
                            placeholder="0" min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('monthly_income') @enderror">
                        @error('monthly_income')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" id="address" rows="3" placeholder="Alamat lengkap nasabah"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('address') @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Emergency Contact -->
                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Kontak Darurat
                            (Nama)</label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name"
                            value="{{ old('emergency_contact_name') }}" placeholder="Nama kontak darurat"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('emergency_contact_name') @enderror">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">Kontak
                            Darurat (Telepon)</label>
                        <input type="text" name="emergency_contact_phone" id="emergency_contact_phone"
                            value="{{ old('emergency_contact_phone') }}" placeholder="08xxxxxxxxxx"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('emergency_contact_phone') @enderror">
                        @error('emergency_contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" id="notes" rows="2" placeholder="Catatan tambahan tentang nasabah"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm custom-input-focus pb-3 focus:border-blue-500 focus:ring-blue-500 @error('notes') @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('customers.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Nasabah
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto format phone number
            document.getElementById('phone').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.startsWith('0')) {
                    e.target.value = value;
                } else if (value.startsWith('62')) {
                    e.target.value = '0' + value.substring(2);
                }
            });

            // Auto format emergency contact phone
            document.getElementById('emergency_contact_phone').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.startsWith('0')) {
                    e.target.value = value;
                } else if (value.startsWith('62')) {
                    e.target.value = '0' + value.substring(2);
                }
            });

            // Auto format ID number
            document.getElementById('id_number').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 16) {
                    value = value.substring(0, 16);
                }
                e.target.value = value;
            });

            // Format currency input
            document.getElementById('monthly_income').addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value;
            });
        </script>
    @endpush
@endsection
