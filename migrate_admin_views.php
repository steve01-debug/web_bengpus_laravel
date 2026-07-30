<?php
$files = ['dashboard.php', 'berita.php', 'video.php', 'struktur.php', 'pimpinan.php'];
@mkdir('resources/views/admin', 0755, true);

foreach ($files as $file) {
    $c = file_get_contents('admin/' . $file);
    // Find <!DOCTYPE html>
    $pos = strpos($c, '<!DOCTYPE html>');
    if ($pos !== false) {
        $c = substr($c, $pos);
    }
    
    // Add @csrf to forms
    $c = preg_replace('/(<form[^>]*>)/i', '$1' . "\n" . '        @csrf', $c);
    
    // Fix action URLs and links
    $c = str_replace('action="berita.php"', 'action="{{ route(\'admin.berita\') }}"', $c);
    $c = str_replace('action="video.php"', 'action="{{ route(\'admin.video\') }}"', $c);
    $c = str_replace('action="struktur.php"', 'action="{{ route(\'admin.struktur\') }}"', $c);
    $c = str_replace('action="pimpinan.php"', 'action="{{ route(\'admin.pimpinan\') }}"', $c);
    
    // Fix link URLs
    $c = str_replace('href="berita.php', 'href="{{ route(\'admin.berita\') }}"', $c);
    $c = str_replace('href="video.php', 'href="{{ route(\'admin.video\') }}"', $c);
    $c = str_replace('href="struktur.php', 'href="{{ route(\'admin.struktur\') }}"', $c);
    $c = str_replace('href="pimpinan.php', 'href="{{ route(\'admin.pimpinan\') }}"', $c);
    $c = str_replace('href="dashboard.php', 'href="{{ route(\'admin.dashboard\') }}"', $c);
    $c = str_replace('href="logout.php"', 'href="#" onclick="event.preventDefault(); document.getElementById(\'logout-form\').submit();"', $c);

    // Logout form
    $logoutForm = '<form id="logout-form" action="{{ route(\'logout\') }}" method="POST" style="display: none;">@csrf</form>';
    $c = str_replace('</body>', $logoutForm . "\n</body>", $c);

    file_put_contents('resources/views/admin/' . str_replace('.php', '.blade.php', $file), $c);
}
echo "Done";
