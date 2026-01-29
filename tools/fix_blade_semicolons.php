<?php

declare(strict_types=1);

/**
 * Bulk-fix Blade "unexpected token ';'" diagnostics by removing semicolons
 * that occur immediately before a Blade raw echo close: "!!}".
 *
 * This targets patterns like: "{!! Form::text(...); !!}" -> "{!! Form::text(...) !!}"
 *
 * Usage:
 *   php tools/fix_blade_semicolons.php [path]
 *
 * Default path:
 *   resources/views
 */

$root = $argv[1] ?? __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';
$root = realpath($root);

if ($root === false || !is_dir($root)) {
    fwrite(STDERR, "Invalid directory: " . ($argv[1] ?? 'resources/views') . PHP_EOL);
    exit(2);
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

$changedFiles = 0;
$changedOccurrences = 0;

foreach ($iterator as $fileInfo) {
    /** @var SplFileInfo $fileInfo */
    if (!$fileInfo->isFile()) {
        continue;
    }

    $path = $fileInfo->getPathname();
    if (strtolower($fileInfo->getExtension()) !== 'php') {
        continue;
    }

    // Only Blade templates.
    if (!str_ends_with(strtolower($path), '.blade.php')) {
        continue;
    }

    $original = file_get_contents($path);
    if ($original === false) {
        fwrite(STDERR, "Failed to read: {$path}" . PHP_EOL);
        continue;
    }

    $count = 0;
    // Remove semicolon only when it is directly before the Blade raw echo close.
    $updated = preg_replace('/;(?=\s*!!\})/m', '', $original, -1, $count);

    if ($updated === null) {
        fwrite(STDERR, "Regex error on: {$path}" . PHP_EOL);
        continue;
    }

    if ($count > 0 && $updated !== $original) {
        $bytes = file_put_contents($path, $updated);
        if ($bytes === false) {
            fwrite(STDERR, "Failed to write: {$path}" . PHP_EOL);
            continue;
        }
        $changedFiles++;
        $changedOccurrences += $count;
        echo "Fixed {$count} occurrence(s): {$path}" . PHP_EOL;
    }
}

echo PHP_EOL;
echo "Done. Updated {$changedOccurrences} occurrence(s) across {$changedFiles} file(s)." . PHP_EOL;
