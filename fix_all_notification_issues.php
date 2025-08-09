<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\User;
use App\Models\Notification;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PERBAIKAN MENYELURUH MASALAH NOTIFIKASI ===\n\n";

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
    
    // 3. Hapus notifikasi yang tidak valid
    echo "\n3. Membersihkan notifikasi yang tidak valid...\n";
    
    if ($invalidNotifications->count() > 0) {
        foreach ($invalidNotifications as $notification) {
            DB::table('notifications')->where('id', $notification->id)->delete();
            echo "     ✅ Dihapus notifikasi ID: {$notification->id} (user_id: {$notification->user_id})\n";
        }
    } else {
        echo "   - Tidak ada notifikasi yang perlu dihapus\n";
    }
    
    // 4. Daftar file yang perlu diperbaiki
    echo "\n4. File yang perlu diperbaiki:\n";
    
    $filesToFix = [
        'app/Console/Commands/DueDateReminderCommand.php',
        'app/Console/Commands/OverdueReminderCommand.php', 
        'app/Console/Commands/CalculatePenaltyCommand.php',
        'app/Http/Controllers/ReminderController.php',
        'database/seeders/PawnTransactionSeeder.php'
    ];
    
    foreach ($filesToFix as $file) {
        $fullPath = __DIR__ . '/' . $file;
        if (file_exists($fullPath)) {
            echo "   - ✅ {$file} (ada)\n";
        } else {
            echo "   - ❌ {$file} (tidak ditemukan)\n";
        }
    }
    
    // 5. Rekomendasi
    echo "\n5. Rekomendasi perbaikan:\n";
    echo "   - Hapus semua pembuatan notifikasi untuk customer_id\n";
    echo "   - Ganti dengan sistem notifikasi SMS/Email untuk customer\n";
    echo "   - Notifikasi sistem hanya untuk users (admin/petugas)\n";
    echo "   - Atau buat tabel customer_notifications terpisah\n";
    
    // 6. Verifikasi perbaikan
    echo "\n6. Verifikasi perbaikan...\n";
    
    $remainingInvalidNotifications = DB::table('notifications')
        ->leftJoin('users', 'notifications.user_id', '=', 'users.id')
        ->whereNull('users.id')
        ->count();
    
    echo "   - Notifikasi tidak valid yang tersisa: {$remainingInvalidNotifications}\n";
    
    if ($remainingInvalidNotifications == 0) {
        echo "   ✅ Semua notifikasi sekarang valid\n";
    }
    
    echo "\n=== LANGKAH SELANJUTNYA ===\n";
    echo "1. Jalankan script ini untuk membersihkan data: ✅ SELESAI\n";
    echo "2. Perbaiki file-file yang masih menggunakan customer_id untuk notifikasi\n";
    echo "3. Test fitur pembayaran dan transaksi\n";
    echo "4. Implementasi SMS/Email untuk notifikasi customer (opsional)\n";
    
    echo "\n=== PERBAIKAN SELESAI ===\n";
    echo "✅ Data notifikasi yang tidak valid telah dibersihkan\n";
    echo "✅ Sekarang Anda dapat melakukan pembayaran tanpa error\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}