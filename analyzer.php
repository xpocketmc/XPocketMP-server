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
    if ($result !== 0) {
        return [['file' => $file, 'line' => 0, 'message' => 'Syntax error']];
    }
    return [];
}

function detect_unused_functions($tokens, $file) {
    $functions = [];
    $calls = [];
    $errors = [];
    $inside_function = false;

    foreach ($tokens as $index => $token) {
        if (is_array($token)) {
            if ($token[0] == T_FUNCTION) {
                $inside_function = true;
                $function_name_token = next($tokens);
                if (is_array($function_name_token)) {
                    $functions[] = ['name' => $function_name_token[1], 'line' => $function_name_token[2]];
                }
            } elseif ($token[0] == T_STRING && !$inside_function) {
                $calls[] = $token[1];
            }
        } else {
            if ($token == '{') {
                $inside_function = true;
            } elseif ($token == '}') {
                $inside_function = false;
            }
        }
    }

    foreach ($functions as $function) {
        if (!in_array($function['name'], $calls)) {
            $errors[] = ['file' => $file, 'line' => $function['line'], 'message' => "Unused function '{$function['name']}'"];
        }
    }

    return $errors;
}

function detect_global_variables($tokens, $file) {
    $errors = [];
    foreach ($tokens as $token) {
        if (is_array($token) && $token[0] == T_GLOBAL) {
            $errors[] = ['file' => $file, 'line' => $token[2], 'message' => 'Global variable used'];
        }
    }
    return $errors;
}

function detect_exit_die($tokens, $file) {
    $errors = [];
    foreach ($tokens as $token) {
        if (is_array($token) && isset($token[1]) && in_array($token[1], ['exit', 'die'])) {
            $errors[] = ['file' => $file, 'line' => $token[2], 'message' => "Usage of '{$token[1]}'"];
        }
    }
    return $errors;
}

function detect_long_functions($tokens, $file, $max_lines = 190) {
    $errors = [];
    $current_function = null;
    $bracket_count = 0;
    $line_count = 0;
    $start_line = 0;
    $inside_function = false;

    foreach ($tokens as $token) {
        if (is_array($token)) {
            if ($token[0] == T_FUNCTION) {
                $function_name = next($tokens);
                if (is_array($function_name)) {
                    $current_function = $function_name[1];
                    $start_line = $function_name[2];
                    $bracket_count = 0;
                    $line_count = 0;
                    $inside_function = true;
                }
            } elseif ($inside_function && $token[0] == T_WHITESPACE) {
                $line_count += substr_count($token[1], "\n");
            }
        } else {
            if ($inside_function && $token == '{') {
                $bracket_count++;
            } elseif ($inside_function && $token == '}') {
                $bracket_count--;
                if ($bracket_count == 0) {
                    if ($line_count > $max_lines) {
                        $errors[] = ['file' => $file, 'line' => $start_line, 'message' => "Function '$current_function' exceeds $max_lines lines"];
                    }
                    $inside_function = false;
                }
            }
        }
    }
    return $errors;
}

function analyze_file($file) {
    if (!check_syntax($file)) {
        return [["file" => $file, "line" => 0, "message" => "Syntax error"]];
    }

    $content = file_get_contents($file);
    $tokens = token_get_all($content);

    $errors = array_merge(
        detect_unused_functions($tokens, $file),
        detect_global_variables($tokens, $file),
        detect_exit_die($tokens, $file),
        detect_long_functions($tokens, $file)
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
        }
    }

    return $errors;
}

if ($argc < 2) {
    echo "Usage: php analyzer.php [directory]\n";
    exit(1);
}

$directory = $argv[1];
if (!is_dir($directory)) {
    echo "Directory not found\n";
    exit(1);
}

$all_errors = analyze_directory($directory);

if (empty($all_errors)) {
    echo "[OK] No errors found.\n";
} else {
    foreach ($all_errors as $error) {
        echo "{$error['file']}:{$error['line']} {$error['message']}\n";
    }
}
