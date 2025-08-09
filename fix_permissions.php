<?php
/**
 * Script untuk memperbaiki permission dan konfigurasi web server
 */

echo "=== MEMPERBAIKI PERMISSION DAN KONFIGURASI ===\n\n";

// 1. Set permission untuk direktori dan file
$directories = [
    public_path('images'),
    public_path('images/transactions'),
    public_path('storage'),
    public_path('storage/transaction_photos'),
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        // Set permission direktori (755)
        if (chmod($dir, 0755)) {
            echo "✅ Permission set for directory: $dir\n";
        } else {
            echo "❌ Failed to set permission for directory: $dir\n";
        }
        
        // Set permission untuk semua file di dalam direktori (644)
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                if (chmod($file, 0644)) {
                    echo "✅ Permission set for file: " . basename($file) . "\n";
                } else {
                    echo "❌ Failed to set permission for file: " . basename($file) . "\n";
                }
            }
        }
    }
}

// 2. Buat .htaccess untuk direktori images jika belum ada
$imagesHtaccess = public_path('images/.htaccess');
if (!file_exists($imagesHtaccess)) {
    $htaccessContent = '<IfModule mod_rewrite.c>
    RewriteEngine Off
</IfModule>

<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
</IfModule>

# Allow access to image files
<FilesMatch "\.(jpg|jpeg|png|gif|webp)$">
    Order allow,deny
    Allow from all
    Require all granted
</FilesMatch>

# Prevent access to PHP files
<FilesMatch "\.php$">
    Order deny,allow
    Deny from all
</FilesMatch>';

    if (file_put_contents($imagesHtaccess, $htaccessContent)) {
        echo "✅ Created .htaccess for images directory\n";
    } else {
        echo "❌ Failed to create .htaccess for images directory\n";
    }
}

// 3. Buat .htaccess untuk direktori transactions
$transactionsHtaccess = public_path('images/transactions/.htaccess');
if (!file_exists($transactionsHtaccess)) {
    $htaccessContent = '<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
</IfModule>

# Allow access to all files
Order allow,deny
Allow from all
Require all granted

# Set proper headers for images
<IfModule mod_headers.c>
    <FilesMatch "\.(jpg|jpeg|png|gif|webp)$">
        Header set Cache-Control "public, max-age=31536000"
        Header set Access-Control-Allow-Origin "*"
    </FilesMatch>
</IfModule>';

    if (file_put_contents($transactionsHtaccess, $htaccessContent)) {
        echo "✅ Created .htaccess for transactions directory\n";
    } else {
        echo "❌ Failed to create .htaccess for transactions directory\n";
    }
}

// 4. Cek dan perbaiki main .htaccess
$mainHtaccess = public_path('.htaccess');
if (file_exists($mainHtaccess)) {
    $content = file_get_contents($mainHtaccess);
    echo "📄 Main .htaccess exists\n";
    
    // Pastikan ada rule untuk mengizinkan akses ke file statis
    if (strpos($content, 'RewriteCond %{REQUEST_FILENAME} !-f') === false) {
        echo "⚠️  Main .htaccess might be blocking file access\n";
        
        // Backup original
        copy($mainHtaccess, $mainHtaccess . '.backup');
        echo "✅ Backup created: .htaccess.backup\n";
        
        // Add rule to allow static files
        $newContent = $content . "\n\n# Allow direct access to static files\n";
        $newContent .= "<FilesMatch \"\.(jpg|jpeg|png|gif|webp|css|js|ico)$\">\n";
        $newContent .= "    RewriteEngine Off\n";
        $newContent .= "</FilesMatch>\n";
        
        if (file_put_contents($mainHtaccess, $newContent)) {
            echo "✅ Updated main .htaccess to allow static files\n";
        } else {
            echo "❌ Failed to update main .htaccess\n";
        }
    }
} else {
    echo "❌ Main .htaccess not found\n";
}

// 5. Test file dengan PHP built-in server
echo "\n=== TESTING WITH PHP BUILT-IN SERVER ===\n";
$photoFile = '1754251834_688fc23a500e7.png';
$photoPath = public_path('images/transactions/' . $photoFile);

if (file_exists($photoPath)) {
    echo "✅ Photo file exists: $photoPath\n";
    
    // Create a simple PHP file to serve the image
    $serveImagePhp = public_path('serve_image.php');
    $phpContent = '<?php
$file = __DIR__ . "/images/transactions/' . $photoFile . '";
if (file_exists($file)) {
    $mimeType = mime_content_type($file);
    header("Content-Type: " . $mimeType);
    header("Content-Length: " . filesize($file));
    readfile($file);
} else {
    http_response_code(404);
    echo "File not found";
}
?>';
    
    file_put_contents($serveImagePhp, $phpContent);
    echo "✅ Created serve_image.php for testing\n";
    echo "🔗 Test URL: http://127.0.0.1:8000/serve_image.php\n";
}

// 6. Create comprehensive test page
$testPage = public_path('comprehensive_test.html');
$htmlContent = '<!DOCTYPE html>
<html>
<head>
    <title>Comprehensive Image Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .success { color: green; }
        .error { color: red; }
        img { max-width: 200px; margin: 10px; border: 2px solid #000; }
    </style>
</head>
<body>
    <h1>Comprehensive Image Access Test</h1>
    
    <div class="test-section">
        <h2>Test 1: Direct Image Access</h2>
        <img src="/images/transactions/' . $photoFile . '" alt="Direct access" onload="this.style.borderColor=\'green\'" onerror="this.style.borderColor=\'red\'; this.alt=\'FAILED: \' + this.src">
        <p>Path: /images/transactions/' . $photoFile . '</p>
    </div>
    
    <div class="test-section">
        <h2>Test 2: Full URL Access</h2>
        <img src="http://127.0.0.1:8000/images/transactions/' . $photoFile . '" alt="Full URL access" onload="this.style.borderColor=\'green\'" onerror="this.style.borderColor=\'red\'; this.alt=\'FAILED: \' + this.src">
        <p>Path: http://127.0.0.1:8000/images/transactions/' . $photoFile . '</p>
    </div>
    
    <div class="test-section">
        <h2>Test 3: PHP Served Image</h2>
        <img src="/serve_image.php" alt="PHP served" onload="this.style.borderColor=\'green\'" onerror="this.style.borderColor=\'red\'; this.alt=\'FAILED: \' + this.src">
        <p>Path: /serve_image.php</p>
    </div>
    
    <div class="test-section">
        <h2>Test 4: Storage Path</h2>
        <img src="/storage/transaction_photos/' . $photoFile . '" alt="Storage path" onload="this.style.borderColor=\'green\'" onerror="this.style.borderColor=\'red\'; this.alt=\'FAILED: \' + this.src">
        <p>Path: /storage/transaction_photos/' . $photoFile . '</p>
    </div>
    
    <div class="test-section">
        <h2>Direct Links for Testing</h2>
        <p><a href="/images/transactions/' . $photoFile . '" target="_blank">Open image directly</a></p>
        <p><a href="/serve_image.php" target="_blank">Open via PHP</a></p>
    </div>
    
    <script>
        // JavaScript test
        function testImageLoad(url) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = () => resolve(true);
                img.onerror = () => reject(false);
                img.src = url;
            });
        }
        
        // Test all URLs
        const urls = [
            "/images/transactions/' . $photoFile . '",
            "http://127.0.0.1:8000/images/transactions/' . $photoFile . '",
            "/serve_image.php",
            "/storage/transaction_photos/' . $photoFile . '"
        ];
        
        urls.forEach(async (url, index) => {
            try {
                await testImageLoad(url);
                console.log(`✅ Test ${index + 1} SUCCESS: ${url}`);
            } catch (e) {
                console.log(`❌ Test ${index + 1} FAILED: ${url}`);
            }
        });
    </script>
</body>
</html>';

file_put_contents($testPage, $htmlContent);
echo "✅ Created comprehensive test page: http://127.0.0.1:8000/comprehensive_test.html\n";

echo "\n=== NEXT STEPS ===\n";
echo "1. Jalankan: php test_direct_access.php\n";
echo "2. Buka browser: http://127.0.0.1:8000/comprehensive_test.html\n";
echo "3. Cek console browser untuk error messages\n";
echo "4. Jika masih gagal, restart server: php artisan serve --host=127.0.0.1 --port=8000\n";

echo "\n=== SELESAI ===\n";
?>