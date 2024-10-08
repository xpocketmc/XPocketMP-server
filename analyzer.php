<?php
// analyzer.php

/**
 * PHP Analyzer Script
 * This script scans PHP files in a provided directory recursively,
 * detecting unused variables, functions, syntax errors, and TODO comments.
 */

function check_syntax($file) {
    $output = null;
    $result = null;
    exec("php -l $file", $output, $result);
    return $result === 0;
}

function detect_unused_variables($tokens) {
    $variables = [];
    $errors = [];

    foreach ($tokens as $token) {
        if (is_array($token) && $token[0] == T_VARIABLE) {
            $variables[] = $token[1];
        }
    }

    foreach (array_count_values($variables) as $variable => $count) {
        if ($count == 1) {
            $errors[] = "Warning: Variable '$variable' is declared but never used.";
        }
    }
    return $errors;
}

function detect_unused_functions($tokens) {
    $functions = [];
    $calls = [];
    $errors = [];

    foreach ($tokens as $token) {
        if (is_array($token)) {
            if ($token[0] == T_FUNCTION) {
                $functions[] = next($tokens)[1];
            } elseif ($token[0] == T_STRING) {
                $calls[] = $token[1];
            }
        }
    }

    foreach ($functions as $function) {
        if (!in_array($function, $calls)) {
            $errors[] = "Warning: Function '$function' is declared but never called.";
        }
    }
    return $errors;
}

function detect_comments($content) {
    preg_match_all('/\/\/\s*(TODO|FIXME):.*/', $content, $matches);
    return array_map(fn($match) => "Info: Found comment '$match'", $matches[0]);
}

function detect_global_variables($tokens) {
    $errors = [];
    foreach ($tokens as $token) {
        if (is_array($token) && $token[0] == T_GLOBAL) {
            $errors[] = "Warning: Global variable used.";
        }
    }
    return $errors;
}

function detect_exit_die($tokens) {
    $errors = [];
    foreach ($tokens as $token) {
        if (is_array($token) && in_array($token[1], ['exit', 'die'])) {
            $errors[] = "Warning: Usage of {$token[1]} statement found.";
        }
    }
    return $errors;
}

function analyze_file($file) {
    echo "Analyzing file: $file\n";
    if (!check_syntax($file)) {
        return ["Error: Syntax error found in file $file."];
    }

    $content = file_get_contents($file);
    $tokens = token_get_all($content);
    $errors = array_merge(
        detect_unused_variables($tokens),
        detect_unused_functions($tokens),
        detect_comments($content),
        detect_global_variables($tokens),
        detect_exit_die($tokens)
    );

    return $errors;
}

function analyze_directory($directory) {
    $errors = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() === 'php') {
            $errors = array_merge($errors, analyze_file($file->getPathname()));
            loadingAnimation(); // Menampilkan loading saat menganalisis file
        }
    }

    return $errors;
}

function loadingAnimation() {
    // Menampilkan loading animation
    echo "\033[0;33mLoading...\033[0m\r";
    usleep(500000); // Delay setengah detik
    echo "\033[0;33mLoading...\033[0m\r";
    usleep(500000); // Delay setengah detik
    echo "\033[0;33mLoading...\033[0m\r";
    usleep(500000); // Delay setengah detik
    echo "\033[0;33mLoading...\033[0m\r";
    usleep(500000); // Delay setengah detik
    echo "\033[0;0m\n"; // Reset output
}

// Main script
if ($argc < 2) {
    echo "Usage: php analyzer.php [directory]\n";
    exit(1);
}

$directory = $argv[1];
if (!is_dir($directory)) {
    echo "Directory not found: $directory\n";
    exit(1);
}

// Menganalisis direktori secara rekursif
$all_errors = analyze_directory($directory);

// Menampilkan hasil analisis
if (empty($all_errors)) {
    echo "No issues found.\n";
} else {
    foreach ($all_errors as $error) {
        echo $error . "\n";
    }
}
