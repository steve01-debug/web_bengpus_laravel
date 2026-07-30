<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $c = file_get_contents($file);
        $c = preg_replace('/<\?php\s*include\s*[\'"]components\/(.*?)\.php[\'"];\s*\?>/', '@include(\'components.$1\')', $c);
        $c = preg_replace('/<\?php\s*require_once.*?db\.php.*?\?>/s', '', $c);
        file_put_contents($file, $c);
    }
}
echo "Done";
