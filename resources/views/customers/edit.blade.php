@extends('layouts.app')

@section('title', 'Edit Nasabah')
@section('page-title', 'Edit Nasabah')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('customers.show', $customer) }}" 
               class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Nasabah</h2>
                <p class="mt-1 text-sm text-gray-600">Perbarui informasi nasabah {{ $customer->name }}</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('customers.update', $customer) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}"
                           placeholder="08xxxxxxxxxx"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('phone') border-red-300 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Identity Number -->
                <div>
                    <label for="id_number" class="block text-sm font-medium text-gray-700">NIK/Nomor Identitas *</label>
                    <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $customer->id_number) }}" required
                           placeholder="16 digit NIK"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('id_number') border-red-300 @enderror">
                    @error('id_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ID Type -->
                <div>
                    <label for="id_type" class="block text-sm font-medium text-gray-700">Jenis Identitas</label>
                    <select name="id_type" id="id_type" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="ktp" {{ old('id_type', $customer->id_type) == 'ktp' ? 'selected' : '' }}>KTP</option>
                        <option value="sim" {{ old('id_type', $customer->id_type) == 'sim' ? 'selected' : '' }}>SIM</option>
                        <option value="passport" {{ old('id_type', $customer->id_type) == 'passport' ? 'selected' : '' }}>Passport</option>
                    </select>
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin *</label>
                    <select name="gender" id="gender" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('gender') border-red-300 @enderror">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male" {{ old('gender', $customer->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender', $customer->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="active" {{ old('status', $customer->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $customer->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="blocked" {{ old('status', $customer->status) == 'blocked' ? 'selected' : '' }}>Diblokir</option>
                    </select>
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth ? $customer->date_of_birth->format('Y-m-d') : '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('date_of_birth') border-red-300 @enderror">
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Place of Birth -->
                <div>
                    <label for="place_of_birth" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input type="text" name="place_of_birth" id="place_of_birth" value="{{ old('place_of_birth', $customer->place_of_birth) }}"
                           placeholder="Kota tempat lahir"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('place_of_birth') border-red-300 @enderror">
                    @error('place_of_birth')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Occupation -->
                <div>
                    <label for="occupation" class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                    <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $customer->occupation) }}"
                           placeholder="Pekerjaan/profesi"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('occupation') border-red-300 @enderror">
                    @error('occupation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Monthly Income -->
                <div>
                    <label for="monthly_income" class="block text-sm font-medium text-gray-700">Penghasilan Bulanan</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="monthly_income" id="monthly_income" value="{{ old('monthly_income', $customer->monthly_income) }}"
                               placeholder="0"
                               class="block w-full pl-12 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('monthly_income') border-red-300 @enderror">
                    </div>
                    @error('monthly_income')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Emergency Contact Name -->
                <div>
                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Nama Kontak Darurat</label>
                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $customer->emergency_contact_name) }}"
                           placeholder="Nama kontak darurat"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('emergency_contact_name') border-red-300 @enderror">
                    @error('emergency_contact_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Emergency Contact Phone -->
                <div>
                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">Telepon Kontak Darurat</label>
                    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $customer->emergency_contact_phone) }}"
                           placeholder="08xxxxxxxxxx"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('emergency_contact_phone') border-red-300 @enderror">
                    @error('emergency_contact_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="notes" id="notes" rows="3" 
                              placeholder="Catatan tambahan tentang nasabah"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('notes') border-red-300 @enderror">{{ old('notes', $customer->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="address" id="address" rows="3" 
                              placeholder="Alamat lengkap nasabah"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('address') border-red-300 @enderror">{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Account Info -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Akun</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Terdaftar</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Terakhir Diperbarui</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $customer->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Untuk mengubah password, nasabah dapat melakukannya sendiri melalui halaman profile atau hubungi administrator.
                    </p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('customers.show', $customer) }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Transaction Warning -->
    @if($customer->pawnTransactions->count() > 0)
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Nasabah ini memiliki {{ $customer->pawnTransactions->count() }} transaksi. Perubahan data dapat mempengaruhi transaksi yang sedang berjalan.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
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

// Auto format identity number
document.getElementById('id_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 16) {
        value = value.substring(0, 16);
    }
    e.target.value = value;
});

// Format monthly income
document.getElementById('monthly_income').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    e.target.value = value;
});
</script>
@endpush
@endsection