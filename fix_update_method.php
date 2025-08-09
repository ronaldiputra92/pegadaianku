<?php
/**
 * Script untuk memperbaiki method update di PawnTransactionController
 */

$controllerPath = __DIR__ . '/app/Http/Controllers/PawnTransactionController.php';

if (!file_exists($controllerPath)) {
    echo "❌ File controller tidak ditemukan: $controllerPath\n";
    exit;
}

$content = file_get_contents($controllerPath);

// Cari dan ganti bagian yang bermasalah di method update
$oldCode = '            // Add new photos
            if ($request->hasFile(\'item_photos\')) {
                // Create directory if it doesn\'t exist
                $uploadPath = public_path(\'images/transactions\');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                foreach ($request->file(\'item_photos\') as $photo) {
                    $photoName = time() . \'_\' . uniqid() . \'.\' . $photo->getClientOriginalExtension();
                    
                    // Move file directly to public/images/transactions
                    $photo->move($uploadPath, $photoName);
                    $currentPhotos[] = $photoName;
                }
            }';

$newCode = '            // Add new photos
            if ($request->hasFile(\'item_photos\')) {
                foreach ($request->file(\'item_photos\') as $photo) {
                    $photoName = time() . \'_\' . uniqid() . \'.\' . $photo->getClientOriginalExtension();
                    
                    // Simpan ke storage/app/public/transaction_photos (recommended Laravel way)
                    $photo->storeAs(\'transaction_photos\', $photoName, \'public\');
                    
                    // Juga copy ke public/images/transactions untuk backward compatibility
                    $uploadPath = public_path(\'images/transactions\');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    copy(storage_path(\'app/public/transaction_photos/\' . $photoName), $uploadPath . \'/\' . $photoName);
                    
                    $currentPhotos[] = $photoName;
                }
            }';

// Coba beberapa variasi pattern untuk mencocokkan kode
$patterns = [
    // Pattern 1: Exact match
    $oldCode,
    
    // Pattern 2: Dengan spasi yang berbeda
    str_replace('            ', '        ', $oldCode),
    
    // Pattern 3: Tanpa komentar
    preg_replace('/\s*\/\/.*?\n/', "\n", $oldCode),
    
    // Pattern 4: Hanya bagian inti
    'if ($request->hasFile(\'item_photos\')) {
                // Create directory if it doesn\'t exist
                $uploadPath = public_path(\'images/transactions\');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                foreach ($request->file(\'item_photos\') as $photo) {
                    $photoName = time() . \'_\' . uniqid() . \'.\' . $photo->getClientOriginalExtension();
                    
                    // Move file directly to public/images/transactions
                    $photo->move($uploadPath, $photoName);
                    $currentPhotos[] = $photoName;
                }
            }'
];

$replaced = false;
foreach ($patterns as $i => $pattern) {
    if (strpos($content, $pattern) !== false) {
        $content = str_replace($pattern, $newCode, $content);
        $replaced = true;
        echo "✅ Pattern " . ($i + 1) . " berhasil ditemukan dan diganti\n";
        break;
    }
}

if (!$replaced) {
    echo "❌ Tidak dapat menemukan pattern yang cocok\n";
    echo "Mari coba dengan pendekatan manual...\n";
    
    // Cari bagian yang mengandung "Add new photos" di method update
    if (preg_match('/\/\/ Add new photos.*?if \(\$request->hasFile\(\'item_photos\'\)\).*?\{.*?\}/s', $content, $matches)) {
        echo "✅ Ditemukan bagian 'Add new photos'\n";
        echo "Bagian yang ditemukan:\n";
        echo $matches[0] . "\n\n";
        
        // Ganti dengan kode yang baru
        $content = str_replace($matches[0], $newCode, $content);
        $replaced = true;
    }
}

if ($replaced) {
    // Backup file asli
    copy($controllerPath, $controllerPath . '.backup');
    echo "✅ Backup dibuat: " . $controllerPath . ".backup\n";
    
    // Tulis file yang sudah diperbaiki
    if (file_put_contents($controllerPath, $content)) {
        echo "✅ File controller berhasil diperbaiki!\n";
        echo "Sekarang upload foto baru di halaman edit seharusnya sudah berfungsi.\n";
    } else {
        echo "❌ Gagal menulis file controller\n";
    }
} else {
    echo "❌ Gagal memperbaiki file controller\n";
    echo "Silakan lakukan perbaikan manual.\n";
}

echo "\n=== SELESAI ===\n";
?>