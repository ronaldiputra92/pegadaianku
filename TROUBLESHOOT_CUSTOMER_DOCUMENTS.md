# Troubleshooting Customer Documents 404 Error

## Masalah
Ketika mengakses `http://127.0.0.1:8000/customer-documents` muncul error 404 Not Found.

## Langkah-langkah Troubleshooting

### 1. Jalankan Fix Script
```bash
# Jalankan file batch untuk fix otomatis
fix_customer_documents.bat
```

### 2. Test Routes Manual
Coba akses URL test berikut untuk mendiagnosis masalah:

1. **Test Basic Route**: `http://127.0.0.1:8000/test-customer-documents`
   - Akan menampilkan hasil test komprehensif
   
2. **Test Auth Route**: `http://127.0.0.1:8000/customer-documents-test`
   - Test route dengan authentication
   
3. **Test Simple Controller**: `http://127.0.0.1:8000/customer-documents-simple`
   - Test controller sederhana

### 3. Manual Commands
Jika script batch tidak bisa dijalankan, jalankan command berikut satu per satu:

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Dump autoload
composer dump-autoload

# Check routes
php artisan route:list | findstr customer-documents
```

### 4. Kemungkinan Penyebab & Solusi

#### A. Migration Belum Dijalankan
**Gejala**: Test menunjukkan table_exists = Fail
**Solusi**: 
```bash
php artisan migrate --force
```

#### B. Model Tidak Ditemukan
**Gejala**: Test menunjukkan model_exists = Fail
**Solusi**: 
```bash
composer dump-autoload
```

#### C. View Tidak Ditemukan
**Gejala**: Test menunjukkan view_exists = Fail
**Solusi**: Pastikan file `resources/views/customer-documents/index.blade.php` ada

#### D. Permission/Role Issue
**Gejala**: Route accessible tapi 403 Forbidden
**Solusi**: Pastikan user login sebagai admin atau petugas

#### E. Route Cache Issue
**Gejala**: Route tidak terdaftar
**Solusi**: 
```bash
php artisan route:clear
php artisan config:clear
```

### 5. Verifikasi User Role
Pastikan user yang login memiliki role yang benar:
- Admin: `role = 'admin'`
- Petugas: `role = 'petugas'`

Menu Dokumen KTP hanya muncul untuk admin dan petugas.

### 6. Check Database Connection
Pastikan database connection berfungsi:
```bash
php artisan tinker
# Kemudian jalankan:
DB::connection()->getPdo();
```

### 7. Check Web Server
Pastikan web server berjalan:
```bash
php artisan serve
```

## Hasil Test yang Diharapkan

Ketika mengakses `http://127.0.0.1:8000/test-customer-documents`, hasil yang diharapkan:

```json
{
  "status": "success",
  "tests": {
    "controller_instantiation": "✓ Pass",
    "model_exists": "✓ Pass", 
    "table_exists": "✓ Pass",
    "view_exists": "✓ Pass"
  },
  "controller": "App\\Http\\Controllers\\CustomerDocumentController"
}
```

## Kontak Support
Jika masalah masih berlanjut, berikan informasi berikut:
1. Hasil dari test URL di atas
2. Error message yang muncul
3. Output dari `php artisan route:list | findstr customer-documents`
4. Role user yang sedang login