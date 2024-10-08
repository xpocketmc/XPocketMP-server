<?php
// analyzer.php

// Fungsi untuk melakukan pemeriksaan sintaks
function check_syntax($file) {
    $output = null;
    $result = null;
    exec("php -l $file", $output, $result);
    return $result === 0;
}

// Fungsi untuk melakukan deteksi variabel tak terpakai
function detect_unused_variables($tokens) {
    $variables = [];
    foreach ($tokens as $token) {
        if (is_array($token) && $token[0] == T_VARIABLE) {
            $variables[] = $token[1];
        }
    }
    $errors = [];
    foreach (array_count_values($variables) as $variable => $count) {
        if ($count == 1) {
            $errors[] = "Warning: Variable '$variable' is declared but never used.";
        }
    }
    return $errors;
}

// Fungsi untuk mendeteksi fungsi yang tidak digunakan
function detect_unused_functions($tokens) {
    $functions = [];
    $calls = [];
    foreach ($tokens as $token) {
        if (is_array($token)) {
            if ($token[0] == T_FUNCTION) {
                // Tangkap nama fungsi
                $functions[] = next($tokens)[1];
            } elseif ($token[0] == T_STRING) {
                // Tangkap semua panggilan fungsi
                $calls[] = $token[1];
            }
        }
    }
    $errors = [];
    foreach ($functions as $function) {
        if (!in_array($function, $calls)) {
            $errors[] = "Warning: Function '$function' is declared but never called.";
        }
    }
    return $errors;
}

// Fungsi untuk mendeteksi komentar TODO/FIXME
function detect_comments($content) {
    preg_match_all('/\/\/\s*(TODO|FIXME):.*/', $content, $matches);
    return array_map(fn($match) => "Info: Found comment '$match'", $matches[0]);
}

if ($argc < 2) {
    echo "Usage: php analyzer.php [directory]\n";
    exit(1);
}

$directory = $argv[1];
if (!is_dir($directory)) {
    echo "Directory not found: $directory\n";
    exit(1);
}

$files = glob($directory . '/*.php');
$all_errors = [];

foreach ($files as $file) {
    echo "Analyzing file: $file\n";

    // 1. Pemeriksaan sintaks
    if (!check_syntax($file)) {
        $all_errors[] = "Error: Syntax error found in file $file.";
        continue;
    }

    $content = file_get_contents($file);
    $tokens = token_get_all($content);

    // 2. Pemeriksaan variabel tak terpakai
    $all_errors = array_merge($all_errors, detect_unused_variables($tokens));

    // 3. Pemeriksaan fungsi tak terpakai
    $all_errors = array_merge($all_errors, detect_unused_functions($tokens));

    // 4. Pemeriksaan komentar TODO/FIXME
    $all_errors = array_merge($all_errors, detect_comments($content));
}

if (empty($all_errors)) {
    echo "No issues found.\n";
} else {
    foreach ($all_errors as $error) {
        echo $error . "\n";
    }
}
