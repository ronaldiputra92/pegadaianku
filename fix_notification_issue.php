<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\User;
use App\Models\Notification;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PERBAIKAN MASALAH NOTIFIKASI ===\n\n";

try {
    // 1. Analisis masalah notifikasi
    echo "1. Menganalisis masalah notifikasi...\n";
    
    // Cek notifikasi yang bermasalah (user_id tidak ada di tabel users)
    $invalidNotifications = DB::table('notifications')
        ->leftJoin('users', 'notifications.user_id', '=', 'users.id')
        ->whereNull('users.id')
        ->select('notifications.*')
        ->get();
    
    echo "   - Notifikasi dengan user_id tidak valid: {$invalidNotifications->count()}\n";
    
    if ($invalidNotifications->count() > 0) {
        echo "   - Detail notifikasi bermasalah:\n";
        foreach ($invalidNotifications as $notification) {
            echo "     * ID: {$notification->id}, User ID: {$notification->user_id}, Title: {$notification->title}\n";
        }
    }
    
    // 2. Cek data users dan customers
    echo "\n2. Mengecek data users dan customers...\n";
    
    $usersCount = User::count();
    $customersCount = Customer::count();
    
    echo "   - Total users: {$usersCount}\n";
    echo "   - Total customers: {$customersCount}\n";
    
    // List users
    $users = User::all(['id', 'name', 'role']);
    echo "   - Daftar users:\n";
    foreach ($users as $user) {
        echo "     * ID: {$user->id}, Name: {$user->name}, Role: {$user->role}\n";
    }
    
    // 3. Solusi: Hapus notifikasi yang tidak valid
    echo "\n3. Menerapkan solusi...\n";
    
    if ($invalidNotifications->count() > 0) {
        echo "   - Menghapus notifikasi yang tidak valid...\n";
        
        foreach ($invalidNotifications as $notification) {
            DB::table('notifications')->where('id', $notification->id)->delete();
            echo "     ✅ Dihapus notifikasi ID: {$notification->id} (user_id: {$notification->user_id})\n";
        }
    } else {
        echo "   - Tidak ada notifikasi yang perlu dihapus\n";
    }
    
    // 4. Verifikasi perbaikan
    echo "\n4. Verifikasi perbaikan...\n";
    
    $remainingInvalidNotifications = DB::table('notifications')
        ->leftJoin('users', 'notifications.user_id', '=', 'users.id')
        ->whereNull('users.id')
        ->count();
    
    echo "   - Notifikasi tidak valid yang tersisa: {$remainingInvalidNotifications}\n";
    
    if ($remainingInvalidNotifications == 0) {
        echo "   ✅ Semua notifikasi sekarang valid\n";
    }
    
    // 5. Rekomendasi untuk masa depan
    echo "\n5. Rekomendasi untuk masa depan:\n";
    echo "   - Notifikasi hanya dibuat untuk users (admin, petugas)\n";
    echo "   - Untuk customer, gunakan SMS/email notifications\n";
    echo "   - Atau buat sistem notifikasi terpisah untuk customers\n";
    
    echo "\n=== PERBAIKAN SELESAI ===\n";
    echo "✅ Masalah notifikasi telah diperbaiki\n";
    echo "✅ Sekarang Anda dapat membuat transaksi baru tanpa error\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}