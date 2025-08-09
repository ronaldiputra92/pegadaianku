<?php
/**
 * Script untuk test server development Laravel
 */

echo "=== TEST SERVER DEVELOPMENT LARAVEL ===\n\n";

// Test apakah server berjalan
$testUrl = 'http://127.0.0.1:8000';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HEADER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ Server tidak dapat diakses: $error\n";
    echo "💡 Pastikan server berjalan dengan: php artisan serve --host=127.0.0.1 --port=8000\n";
    exit;
} else {
    echo "✅ Server Laravel berjalan (HTTP $httpCode)\n";
}

// Test akses ke file statis
$photoFile = '1754251834_688fc23a500e7.png';
$imageUrl = 'http://127.0.0.1:8000/images/transactions/' . $photoFile;

echo "\nTesting image URL: $imageUrl\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $imageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, false); // Get body too
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$contentLength = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "❌ cURL Error: $error\n";
} else {
    echo "📡 HTTP Code: $httpCode\n";
    echo "📄 Content-Type: $contentType\n";
    echo "📏 Content-Length: $contentLength bytes\n";
    
    if ($httpCode == 200) {
        echo "✅ Image accessible via web server!\n";
        
        // Check if it's actually an image
        if (strpos($contentType, 'image/') === 0) {
            echo "✅ Correct image MIME type\n";
        } else {
            echo "⚠️  Unexpected content type (might be Laravel error page)\n";
            
            // Show first 500 chars of response
            $headers = substr($response, 0, strpos($response, "\r\n\r\n"));
            $body = substr($response, strpos($response, "\r\n\r\n") + 4);
            echo "Response body preview:\n" . substr($body, 0, 500) . "\n";
        }
    } else {
        echo "❌ Image NOT accessible (HTTP $httpCode)\n";
        
        // Show response for debugging
        echo "Response preview:\n" . substr($response, 0, 1000) . "\n";
    }
}

// Test dengan file_get_contents (simpler method)
echo "\n=== TEST DENGAN file_get_contents ===\n";
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'method' => 'GET'
    ]
]);

$result = @file_get_contents($imageUrl, false, $context);
if ($result !== false) {
    echo "✅ file_get_contents berhasil (" . strlen($result) . " bytes)\n";
    
    // Check if it's binary data (image)
    if (strlen($result) > 0 && ord($result[0]) > 127) {
        echo "✅ Data terlihat seperti binary (image)\n";
    } else {
        echo "⚠️  Data terlihat seperti text (mungkin error page)\n";
        echo "Preview: " . substr($result, 0, 200) . "\n";
    }
} else {
    echo "❌ file_get_contents gagal\n";
    
    // Check $http_response_header
    if (isset($http_response_header)) {
        echo "Response headers:\n";
        foreach ($http_response_header as $header) {
            echo "  $header\n";
        }
    }
}

// Test direct file access
echo "\n=== TEST AKSES FILE LANGSUNG ===\n";
$filePath = __DIR__ . '/public/images/transactions/' . $photoFile;
echo "File path: $filePath\n";

if (file_exists($filePath)) {
    echo "✅ File exists\n";
    echo "📏 File size: " . filesize($filePath) . " bytes\n";
    echo "🎭 MIME type: " . mime_content_type($filePath) . "\n";
    echo "🔒 Permissions: " . substr(sprintf('%o', fileperms($filePath)), -4) . "\n";
    
    if (is_readable($filePath)) {
        echo "✅ File is readable\n";
    } else {
        echo "❌ File is NOT readable\n";
    }
} else {
    echo "❌ File does not exist at expected location\n";
}

echo "\n=== DIAGNOSIS ===\n";
if ($httpCode == 200 && strpos($contentType, 'image/') === 0) {
    echo "🎉 MASALAH SUDAH TERATASI! Image dapat diakses dengan benar.\n";
} elseif ($httpCode == 404) {
    echo "🔍 MASALAH: File tidak ditemukan oleh web server\n";
    echo "💡 Solusi: Periksa path file dan routing Laravel\n";
} elseif ($httpCode == 200 && strpos($contentType, 'text/html') === 0) {
    echo "🔍 MASALAH: Laravel menangani request ini sebagai route, bukan file statis\n";
    echo "💡 Solusi: Periksa .htaccess atau routing Laravel\n";
} else {
    echo "🔍 MASALAH: Error tidak dikenal (HTTP $httpCode)\n";
    echo "💡 Solusi: Periksa log server dan konfigurasi\n";
}

echo "\n=== REKOMENDASI ===\n";
echo "1. Restart server Laravel: php artisan serve --host=127.0.0.1 --port=8000\n";
echo "2. Test di browser: $imageUrl\n";
echo "3. Cek browser console untuk error messages\n";
echo "4. Jika masih gagal, coba akses: http://127.0.0.1:8000/comprehensive_test.html\n";

echo "\n=== SELESAI ===\n";
?>