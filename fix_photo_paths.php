<?php
/**
 * Script untuk memperbaiki masalah path foto transaksi
 * Mengatasi masalah "Image not found" pada halaman detail transaksi
 */

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PawnTransaction;
use Illuminate\Support\Facades\File;

echo "=== MEMPERBAIKI MASALAH PATH FOTO TRANSAKSI ===\n\n";

// 1. Buat direktori yang diperlukan
$directories = [
    public_path('images'),
    public_path('images/transactions'),
    public_path('storage'),
    public_path('storage/transaction_photos'),
];

foreach ($directories as $dir) {
    if (!File::exists($dir)) {
        File::makeDirectory($dir, 0755, true);
        echo "✓ Direktori dibuat: $dir\n";
    } else {
        echo "✓ Direktori sudah ada: $dir\n";
    }
}

// 2. Buat symbolic link jika belum ada
$linkPath = public_path('storage');
$targetPath = storage_path('app/public');

if (!File::exists($linkPath) || !is_link($linkPath)) {
    if (File::exists($linkPath)) {
        File::deleteDirectory($linkPath);
    }
    
    // Untuk Windows, gunakan junction
    if (PHP_OS_FAMILY === 'Windows') {
        $command = 'mklink /J "' . $linkPath . '" "' . $targetPath . '"';
        exec($command, $output, $returnCode);
        if ($returnCode === 0) {
            echo "✓ Symbolic link dibuat (Windows Junction): $linkPath -> $targetPath\n";
        } else {
            echo "✗ Gagal membuat symbolic link: " . implode("\n", $output) . "\n";
        }
    } else {
        // Untuk Linux/Mac
        symlink($targetPath, $linkPath);
        echo "✓ Symbolic link dibuat: $linkPath -> $targetPath\n";
    }
} else {
    echo "✓ Symbolic link sudah ada: $linkPath\n";
}

// 3. Pindahkan foto dari storage/app/public/transaction_photos ke public/images/transactions
$sourceDir = storage_path('app/public/transaction_photos');
$targetDir = public_path('images/transactions');

if (File::exists($sourceDir)) {
    $files = File::files($sourceDir);
    $movedCount = 0;
    
    foreach ($files as $file) {
        $filename = $file->getFilename();
        
        // Skip file sistem
        if (in_array($filename, ['.gitkeep', '.htaccess', 'test.txt'])) {
            continue;
        }
        
        $sourcePath = $file->getPathname();
        $targetPath = $targetDir . '/' . $filename;
        
        if (!File::exists($targetPath)) {
            File::copy($sourcePath, $targetPath);
            echo "✓ Foto dipindahkan: $filename\n";
            $movedCount++;
        } else {
            echo "- Foto sudah ada: $filename\n";
        }
    }
    
    echo "\n✓ Total foto dipindahkan: $movedCount\n";
} else {
    echo "- Direktori source tidak ditemukan: $sourceDir\n";
}

// 4. Cek dan perbaiki data transaksi
echo "\n=== MEMERIKSA DATA TRANSAKSI ===\n";

$transactions = PawnTransaction::whereNotNull('item_photos')->get();
$fixedCount = 0;

foreach ($transactions as $transaction) {
    if (!empty($transaction->item_photos)) {
        echo "\nTransaksi ID: {$transaction->id} - {$transaction->transaction_code}\n";
        echo "Foto yang tersimpan: " . json_encode($transaction->item_photos) . "\n";
        
        $validPhotos = [];
        $hasValidPhoto = false;
        
        foreach ($transaction->item_photos as $photo) {
            // Cek apakah foto ada di lokasi manapun
            $paths = [
                public_path('images/transactions/' . $photo),
                public_path('storage/transaction_photos/' . $photo),
                storage_path('app/public/transaction_photos/' . $photo),
            ];
            
            $found = false;
            foreach ($paths as $path) {
                if (File::exists($path)) {
                    $validPhotos[] = $photo;
                    $hasValidPhoto = true;
                    $found = true;
                    echo "  ✓ Foto ditemukan: $photo di $path\n";
                    break;
                }
            }
            
            if (!$found) {
                echo "  ✗ Foto tidak ditemukan: $photo\n";
            }
        }
        
        if ($hasValidPhoto) {
            $fixedCount++;
        }
    }
}

echo "\n=== RINGKASAN ===\n";
echo "✓ Total transaksi dengan foto: " . $transactions->count() . "\n";
echo "✓ Transaksi dengan foto valid: $fixedCount\n";

// 5. Test URL generation
echo "\n=== TEST URL GENERATION ===\n";
$testTransaction = PawnTransaction::whereNotNull('item_photos')->first();

if ($testTransaction) {
    echo "Test dengan transaksi: {$testTransaction->transaction_code}\n";
    echo "Raw photos: " . json_encode($testTransaction->item_photos) . "\n";
    echo "Generated URLs:\n";
    
    foreach ($testTransaction->item_photos_urls as $url) {
        echo "  - $url\n";
    }
} else {
    echo "Tidak ada transaksi dengan foto untuk di-test.\n";
}

echo "\n=== SELESAI ===\n";
echo "Silakan cek kembali halaman http://127.0.0.1:8000/transactions/2\n";
echo "Jika masih ada masalah, periksa:\n";
echo "1. Apakah file foto benar-benar ada di direktori\n";
echo "2. Apakah permission direktori sudah benar (755)\n";
echo "3. Apakah web server bisa mengakses direktori tersebut\n";
?>