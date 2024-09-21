<?php

require 'vendor/autoload.php';

use nurazlib\phpstat\analyzer\PhpStatAnalyzer;

// Fungsi untuk membaca semua file PHP di dalam direktori
function getPhpFiles($dir) {
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    foreach ($iterator as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $files[] = $file->getPathname();
        }
    }

    return $files;
}

// Direktori tempat file PHP yang ingin dianalisis
$directory = 'src/'; // Ubah sesuai dengan direktori yang diinginkan

// Ambil semua file PHP dari direktori yang ditentukan
$phpFiles = getPhpFiles($directory);

// Inisialisasi analyzer
$analyzer = new PhpStatAnalyzer();

// Loop melalui setiap file PHP dan analisis kodenya
foreach ($phpFiles as $file) {
    echo "Analyzing file: $file\n";
    $code = file_get_contents($file);
    $analyzer->analyze($code);
    echo "\n";
}
