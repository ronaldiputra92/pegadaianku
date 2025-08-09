# Troubleshooting: Route [customer-documents.index] not defined

## 🔍 **Masalah:**
Error "Route [customer-documents.index] not defined" muncul saat mengakses aplikasi.

## 🛠️ **Solusi:**

### 1. **Clear Route Cache**
Jalankan command berikut untuk membersihkan cache route:

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### 2. **Restart Development Server**
Hentikan server development dan jalankan ulang:

```bash
# Tekan Ctrl+C untuk menghentikan server
php artisan serve
```

### 3. **Verifikasi Route**
Cek apakah route sudah terdaftar:

```bash
php artisan route:list | grep customer-documents
```

### 4. **Test Route Manual**
Akses URL berikut untuk test:

- `http://127.0.0.1:8000/test-routes` - Halaman test route
- `http://127.0.0.1:8000/customer-documents` - Direct access

### 5. **Cek Database**
Pastikan migration sudah dijalankan:

```bash
php artisan migrate
```

### 6. **Cek Model Dependencies**
Pastikan model Customer dan CustomerDocument sudah ada dan benar.

## 🔧 **Langkah Debugging:**

### 1. **Cek File Route**
Pastikan file `routes/web.php` berisi route customer-documents:

```php
Route::get('/customer-documents', [CustomerDocumentController::class, 'index'])->name('customer-documents.index');
```

### 2. **Cek Controller**
Pastikan file `app/Http/Controllers/CustomerDocumentController.php` ada dan memiliki method `index()`.

### 3. **Cek Autoload**
Jalankan composer dump-autoload:

```bash
composer dump-autoload
```

### 4. **Cek Middleware**
Jika masih error, coba akses tanpa middleware dengan menambahkan route test:

```php
Route::get('/test-customer-documents', function() {
    return 'Route is working!';
});
```

## 🚨 **Jika Masih Error:**

### Solusi Darurat:
1. **Hapus route dari sidebar sementara** - Edit `resources/views/layouts/app.blade.php`
2. **Comment route yang bermasalah** di `routes/web.php`
3. **Restart server** dan coba akses dashboard

### Contoh Comment Route:
```php
// Route::get('/customer-documents', [CustomerDocumentController::class, 'index'])->name('customer-documents.index');
```

## 📋 **Checklist Debugging:**

- [ ] Route cache cleared
- [ ] Server restarted  
- [ ] Migration run
- [ ] Controller exists
- [ ] Model exists
- [ ] Autoload dumped
- [ ] Route list checked

## 🔄 **Langkah Pemulihan:**

1. **Backup file route** yang ada
2. **Restore route** dari backup jika diperlukan
3. **Test satu per satu** route yang ditambahkan
4. **Gunakan route sederhana** terlebih dahulu

## 📞 **Bantuan Tambahan:**

Jika semua langkah di atas tidak berhasil:

1. Cek log error di `storage/logs/laravel.log`
2. Pastikan PHP version compatible (8.1+)
3. Cek permission folder storage dan bootstrap/cache
4. Restart web server (Apache/Nginx)

## 🎯 **Quick Fix:**

Untuk fix cepat, ganti route di sidebar dengan URL langsung:

```php
// Dari:
<a href="{{ route('customer-documents.index') }}">

// Ke:
<a href="{{ url('/customer-documents') }}">
```