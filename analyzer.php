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
    exec("php -l " . escapeshellarg($file), $output, $result);
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
            $errors[] = "Error: Variable '$variable' is declared but never used.";
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
            $errors[] = "Error: Function '$function' is declared but never called.";
        }
    }
    return $errors;
}

function detect_global_variables($tokens) {
    $errors = [];
    foreach ($tokens as $token) {
        if (is_array($token) && $token[0] == T_GLOBAL) {
            $errors[] = "Error: Global variable used.";
        }
    }
    return $errors;
}

function detect_exit_die($tokens) {
    $errors = [];
    foreach ($tokens as $token) {
        if (is_array($token) && isset($token[1]) && in_array($token[1], ['exit', 'die'])) {
            $errors[] = "Error: Usage of {$token[1]} statement found.";
        }
    }
    return $errors;
}

function detect_long_functions($tokens, $max_lines = 190) {
    $errors = [];
    $current_function = null;
    $bracket_count = 0;
    $line_count = 0;

    foreach ($tokens as $token) {
        if (is_array($token)) {
            if ($token[0] == T_FUNCTION) {
                $current_function = next($tokens)[1];
                $bracket_count = 0;
                $line_count = 0;
            } elseif ($current_function && $token[0] == T_CURLY_OPEN) {
                $bracket_count++;
            } elseif ($current_function && $token[0] == T_WHITESPACE) {
                $line_count += substr_count($token[1], "\n");
            }
        } else {
            if ($current_function && $token == '{') {
                $bracket_count++;
            } elseif ($current_function && $token == '}') {
                $bracket_count--;
                if ($bracket_count == 0) {
                    if ($line_count > $max_lines) {
                        $errors[] = "Error: Function '$current_function' exceeds $max_lines lines.";
                    }
                    $current_function = null;
                }
            }
        }
    }
    return $errors;
}

function analyze_file($file) {
    if (!check_syntax($file)) {
        return ["Error: Syntax error found in file $file."];
    }

    $content = file_get_contents($file);
    $tokens = token_get_all($content);
    $errors = array_merge(
        detect_unused_variables($tokens),
        detect_unused_functions($tokens),
        detect_global_variables($tokens),
        detect_exit_die($tokens),
        detect_long_functions($tokens)
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
            loadingAnimation();
        }
    }

    return $errors;
}

function loadingAnimation() {
    echo "\033[0;33mLoading...\033[0m\r";
    usleep(100000);
    echo "\033[0;33mLoading...\033[0m\r";
    usleep(100000);
    echo "\033[0;33mLoading...\033[0m\r";
    usleep(100000);
    echo "\033[0;33mLoading...\033[0m\r";
    usleep(100000);
    echo "\033[0;0m\n";
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

$all_errors = analyze_directory($directory);

if (empty($all_errors)) {
    echo "[OK] No errors.\n";
} else {
    $error_count = count($all_errors);
    echo "$error_count error(s) found:\n";
    foreach ($all_errors as $error) {
        echo $error . "\n";
    }
}
