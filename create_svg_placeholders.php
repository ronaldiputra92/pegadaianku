<?php
// Script untuk membuat placeholder SVG untuk foto yang hilang

$images = [
    '1754250604_688fbd6c6ce4b.jpg',
    '1754251515_688fc0fb259da.png'
];

$svgTemplate = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg">
    <rect width="300" height="200" fill="#f0f0f0" stroke="#ccc" stroke-width="2"/>
    <text x="150" y="80" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#666">
        Placeholder Image
    </text>
    <text x="150" y="110" font-family="Arial, sans-serif" font-size="14" text-anchor="middle" fill="#999">
        %s
    </text>
    <text x="150" y="140" font-family="Arial, sans-serif" font-size="12" text-anchor="middle" fill="#aaa">
        300 x 200 pixels
    </text>
</svg>';

$directories = [
    'storage/app/public/transaction_photos/',
    'public/storage/transaction_photos/'
];

foreach ($images as $image) {
    $svg = sprintf($svgTemplate, $image);
    
    foreach ($directories as $dir) {
        $filepath = $dir . $image;
        file_put_contents($filepath, $svg);
        echo "Created: $filepath\n";
    }
}

echo "SVG placeholders created successfully!\n";
?>