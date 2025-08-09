<?php
/**
 * Script untuk test akses langsung ke file foto
 */

echo "=== TEST AKSES LANGSUNG FILE FOTO ===\n\n";

$photoFile = '1754251834_688fc23a500e7.png';
$paths = [
    'public/images/transactions/' . $photoFile => public_path('images/transactions/' . $photoFile),
    'public/storage/transaction_photos/' . $photoFile => public_path('storage/transaction_photos/' . $photoFile),
];

foreach ($paths as $desc => $fullPath) {
    echo "Testing: $desc\n";
    echo "Full path: $fullPath\n";
    
    if (file_exists($fullPath)) {
        echo "✅ File exists\n";
        
        // Check file size
        $size = filesize($fullPath);
        echo "📏 File size: " . number_format($size) . " bytes\n";
        
        // Check file permissions
        $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
        echo "🔒 Permissions: $perms\n";
        
        // Check if readable
        if (is_readable($fullPath)) {
            echo "✅ File is readable\n";
        } else {
            echo "❌ File is NOT readable\n";
        }
        
        // Try to read file content
        $content = @file_get_contents($fullPath);
        if ($content !== false) {
            echo "✅ Can read file content (" . strlen($content) . " bytes)\n";
        } else {
            echo "❌ Cannot read file content\n";
        }
        
        // Check MIME type
        $mimeType = mime_content_type($fullPath);
        echo "🎭 MIME type: $mimeType\n";
        
    } else {
        echo "❌ File does not exist\n";
    }
    echo "\n";
}

// Test web server access
echo "=== TEST WEB SERVER ACCESS ===\n";
$testUrl = 'http://127.0.0.1:8000/images/transactions/' . $photoFile;
echo "Testing URL: $testUrl\n";

// Use cURL for better error reporting
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ cURL Error: $error\n";
} else {
    echo "���� HTTP Response Code: $httpCode\n";
    if ($httpCode == 200) {
        echo "✅ URL is accessible via web server\n";
    } else {
        echo "❌ URL is NOT accessible via web server\n";
        echo "Response headers:\n$response\n";
    }
}

// Check .htaccess files
echo "\n=== CHECK .HTACCESS FILES ===\n";
$htaccessPaths = [
    public_path('.htaccess'),
    public_path('images/.htaccess'),
    public_path('images/transactions/.htaccess'),
];

foreach ($htaccessPaths as $htaccessPath) {
    if (file_exists($htaccessPath)) {
        echo "📄 Found .htaccess: $htaccessPath\n";
        $content = file_get_contents($htaccessPath);
        echo "Content:\n" . substr($content, 0, 200) . "...\n\n";
    }
}

// Create test HTML file for direct browser access
echo "=== CREATING TEST HTML FILE ===\n";
$testHtml = public_path('test_image.html');
$htmlContent = '<!DOCTYPE html>
<html>
<head>
    <title>Test Image Access</title>
</head>
<body>
    <h1>Test Image Access</h1>
    <h2>Direct Image Tag</h2>
    <img src="/images/transactions/' . $photoFile . '" alt="Test Image" style="max-width: 300px; border: 2px solid red;">
    
    <h2>With Full URL</h2>
    <img src="http://127.0.0.1:8000/images/transactions/' . $photoFile . '" alt="Test Image Full URL" style="max-width: 300px; border: 2px solid blue;">
    
    <h2>Direct Links</h2>
    <p><a href="/images/transactions/' . $photoFile . '" target="_blank">Direct link (relative)</a></p>
    <p><a href="http://127.0.0.1:8000/images/transactions/' . $photoFile . '" target="_blank">Direct link (absolute)</a></p>
    
    <h2>Debug Info</h2>
    <p>File exists: ' . (file_exists(public_path('images/transactions/' . $photoFile)) ? 'YES' : 'NO') . '</p>
    <p>File size: ' . (file_exists(public_path('images/transactions/' . $photoFile)) ? filesize(public_path('images/transactions/' . $photoFile)) : 'N/A') . ' bytes</p>
</body>
</html>';

file_put_contents($testHtml, $htmlContent);
echo "✅ Test HTML created: http://127.0.0.1:8000/test_image.html\n";

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Buka browser dan akses: http://127.0.0.1:8000/test_image.html\n";
echo "2. Cek apakah gambar muncul di halaman test\n";
echo "3. Jika tidak muncul, coba akses langsung: http://127.0.0.1:8000/images/transactions/$photoFile\n";
echo "4. Periksa console browser untuk error messages\n";

echo "\n=== SELESAI ===\n";
?>