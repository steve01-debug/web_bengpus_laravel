<?php
$files = glob("resources/views/admin/*.blade.php");
foreach ($files as $file) {
    $c = file_get_contents($file);
    $c = str_replace('}}""?add=1"', '}}?add=1"', $c);
    $c = preg_replace('/\}\}""\?edit=(.+?)"/', '}}?edit=$1"', $c);
    
    // file_exists for pimpinan
    if (basename($file) == 'pimpinan.blade.php') {
        $c = str_replace('<?php if ($p[\'gambar\']): ?>', '<?php if ($p[\'gambar\'] && file_exists(public_path($p[\'gambar\']))): ?>', $c);
    }
    
    file_put_contents($file, $c);
}
echo "Fixed quotes and file_exists.";
