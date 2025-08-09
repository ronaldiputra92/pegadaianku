<?php

// Test script untuk debugging masalah upload foto
echo "=== TESTING PHOTO UPLOAD DIRECTORIES ===\n\n";

// Test direktori yang diperlukan
$directories = [
    'public/images/transactions' => public_path('images/transactions'),
    'storage/app/public/transaction_photos' => storage_path('app/public/transaction_photos'),
    'public/storage/transaction_photos' => public_path('storage/transaction_photos'),
];

foreach ($directories as $name => $path) {
    echo "Testing directory: $name\n";
    echo "Path: $path\n";
    echo "Exists: " . (file_exists($path) ? 'YES' : 'NO') . "\n";
    echo "Writable: " . (is_writable($path) ? 'YES' : 'NO') . "\n";
    
    if (!file_exists($path)) {
        echo "Creating directory...\n";
        if (mkdir($path, 0755, true)) {
            echo "Directory created successfully!\n";
        } else {
            echo "Failed to create directory!\n";
        }
    }
    echo "\n";
}

// Test file permissions
echo "=== TESTING FILE PERMISSIONS ===\n\n";

$testFile = public_path('images/transactions/test.txt');
if (file_put_contents($testFile, 'test content')) {
    echo "Test file created successfully: $testFile\n";
    unlink($testFile);
    echo "Test file deleted successfully\n";
} else {
    echo "Failed to create test file: $testFile\n";
}

echo "\n=== TESTING STORAGE LINK ===\n\n";
$storageLink = public_path('storage');
echo "Storage link exists: " . (file_exists($storageLink) ? 'YES' : 'NO') . "\n";
echo "Storage link is link: " . (is_link($storageLink) ? 'YES' : 'NO') . "\n";

if (is_link($storageLink)) {
    echo "Storage link target: " . readlink($storageLink) . "\n";
}

echo "\n=== TESTING EXISTING PHOTOS ===\n\n";

// Cek foto yang sudah ada
$transactionPhotosDir = public_path('images/transactions');
if (file_exists($transactionPhotosDir)) {
    $files = scandir($transactionPhotosDir);
    $photoFiles = array_filter($files, function($file) {
        return !in_array($file, ['.', '..']) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
    });
    
    echo "Found " . count($photoFiles) . " photo files:\n";
    foreach ($photoFiles as $file) {
        echo "  - $file\n";
    }
} else {
    echo "Transaction photos directory does not exist\n";
}

echo "\nTest completed!\n";