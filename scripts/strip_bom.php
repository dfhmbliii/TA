<?php
/**
 * Strip UTF-8 BOM from given files.
 */
if ($argc < 2) {
    fwrite(STDERR, "Usage: php strip_bom.php <file1> [file2 ...]\n");
    exit(1);
}
array_shift($argv);
foreach ($argv as $file) {
    if (!is_file($file)) {
        fwrite(STDERR, "Skip: {$file} (not found)\n");
        continue;
    }
    $contents = file_get_contents($file);
    if ($contents === false) {
        fwrite(STDERR, "Error: cannot read {$file}\n");
        continue;
    }
    $bom = "\xEF\xBB\xBF";
    if (strncmp($contents, $bom, 3) === 0) {
        $contents = substr($contents, 3);
        file_put_contents($file, $contents);
        echo "Stripped BOM: {$file}\n";
    } else {
        echo "No BOM: {$file}\n";
    }
}
