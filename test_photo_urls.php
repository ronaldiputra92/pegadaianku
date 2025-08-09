<?php
/**
 * Script untuk menguji dan memperbaiki URL foto transaksi
 */

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PawnTransaction;

echo "=== TEST URL FOTO TRANSAKSI ===\n\n";

// Test konfigurasi Laravel
echo "APP_URL: " . config('app.url') . "\n";
echo "Asset URL: " . asset('test') . "\n\n";

// Test transaksi ID 2
$transaction = PawnTransaction::find(2);

if (!$transaction) {
    echo "❌ Transaksi ID 2 tidak ditemukan!\n";
    exit;
}

echo "Transaksi: {$transaction->transaction_code}\n";
echo "Raw photos: " . json_encode($transaction->item_photos) . "\n\n";

if (empty($transaction->item_photos)) {
    echo "❌ Tidak ada foto pada transaksi ini!\n";
    exit;
}

echo "=== TESTING FOTO PATHS ===\n";

foreach ($transaction->item_photos as $index => $photo) {
    echo "\nFoto #{$index}: {$photo}\n";
    
    // Test berbagai path
    $paths = [
        'public/images/transactions/' . $photo => public_path('images/transactions/' . $photo),
        'public/storage/transaction_photos/' . $photo => public_path('storage/transaction_photos/' . $photo),
        'storage/app/public/transaction_photos/' . $photo => storage_path('app/public/transaction_photos/' . $photo),
    ];
    
    $foundPath = null;
    foreach ($paths as $desc => $fullPath) {
        if (file_exists($fullPath)) {
            echo "  ✅ Ditemukan di: {$desc}\n";
            echo "     Full path: {$fullPath}\n";
            $foundPath = $fullPath;
        } else {
            echo "  ❌ Tidak ada di: {$desc}\n";
        }
    }
    
    if ($foundPath) {
        // Test URL generation
        if (strpos($foundPath, 'public/images/transactions/') !== false) {
            $url = asset('images/transactions/' . $photo);
        } elseif (strpos($foundPath, 'public/storage/transaction_photos/') !== false) {
            $url = asset('storage/transaction_photos/' . $photo);
        } else {
            $url = asset('storage/transaction_photos/' . $photo);
        }
        
        echo "  🔗 Generated URL: {$url}\n";
        
        // Test HTTP access
        $headers = @get_headers($url);
        if ($headers && strpos($headers[0], '200') !== false) {
            echo "  ✅ URL dapat diakses via HTTP\n";
        } else {
            echo "  ❌ URL tidak dapat diakses via HTTP\n";
            echo "     Headers: " . ($headers ? $headers[0] : 'No response') . "\n";
        }
    }
}

echo "\n=== TESTING METHOD item_photos_urls ===\n";
$urls = $transaction->item_photos_urls;
foreach ($urls as $index => $url) {
    echo "URL #{$index}: {$url}\n";
    
    // Test HTTP access
    $headers = @get_headers($url);
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "  ✅ Dapat diakses\n";
    } else {
        echo "  ❌ Tidak dapat diakses\n";
        echo "     Headers: " . ($headers ? $headers[0] : 'No response') . "\n";
    }
}

echo "\n=== SOLUSI YANG DISARANKAN ===\n";

// Cek apakah foto ada di storage tapi tidak di public
$needsCopy = false;
foreach ($transaction->item_photos as $photo) {
    $storagePath = storage_path('app/public/transaction_photos/' . $photo);
    $publicPath = public_path('images/transactions/' . $photo);
    
    if (file_exists($storagePath) && !file_exists($publicPath)) {
        echo "📋 Perlu copy: {$photo} dari storage ke public\n";
        $needsCopy = true;
        
        // Copy file
        $publicDir = dirname($publicPath);
        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0755, true);
        }
        
        if (copy($storagePath, $publicPath)) {
            echo "  ✅ Berhasil dicopy ke: {$publicPath}\n";
        } else {
            echo "  ❌ Gagal copy ke: {$publicPath}\n";
        }
    }
}

if (!$needsCopy) {
    echo "✅ Semua foto sudah ada di lokasi yang benar\n";
}

echo "\n=== FINAL TEST ===\n";
// Test ulang setelah copy
$urls = $transaction->fresh()->item_photos_urls;
foreach ($urls as $index => $url) {
    echo "Final URL #{$index}: {$url}\n";
    
    $headers = @get_headers($url);
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "  ✅ BERHASIL - Dapat diakses!\n";
    } else {
        echo "  ❌ GAGAL - Masih tidak dapat diakses\n";
    }
}

echo "\n=== SELESAI ===\n";
?>