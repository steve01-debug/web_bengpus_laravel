<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $c = file_get_contents($file);
        $c = str_replace('href="index.php"', 'href="/"', $c);
        $c = str_replace("href='index.php'", "href='/'", $c);
        $c = str_replace('href="berita.php"', 'href="/berita"', $c);
        $c = str_replace('href="video.php"', 'href="/video"', $c);
        $c = str_replace('href="piket.php"', 'href="/piket"', $c);
        $c = str_replace('href="kode-piket.php"', 'href="/kode-piket"', $c);
        $c = str_replace('href="entering.php"', 'href="/entering"', $c);
        $c = str_replace('href="berita-detail.php', 'href="/berita-detail', $c);
        file_put_contents($file, $c);
    }
}
echo "Done replacing links";
