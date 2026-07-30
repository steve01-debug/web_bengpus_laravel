<?php
$logPath = $argv[1];
$lines = file($logPath);
$fileContents = [];
foreach ($lines as $line) {
    $data = json_decode($line, true);
    if (!$data) continue;
    if ($data['source'] === 'SYSTEM' && $data['type'] === 'TOOL_RESPONSE') {
        if (strpos($data['content'], 'File Path: `file:///d:/PROJECT/bengpus_puskomlekad/resources/views/admin/') !== false) {
            preg_match('/File Path: `file:\/\/\/[^`]+resources\/views\/admin\/([a-zA-Z0-9_\-\.]+)\`/', $data['content'], $m);
            if ($m) {
                $filename = $m[1];
                $content = $data['content'];
                $content = preg_replace('/^.*Showing lines \d+ to \d+\nThe following code has been modified.*?\n/s', '', $content);
                $content = preg_replace('/\nThe above content (shows the entire|does NOT show the entire).*$/s', '', $content);
                $contentLines = explode("\n", $content);
                $cleanLines = [];
                foreach ($contentLines as $cl) {
                    $cl = preg_replace('/^\d+:\s/', '', $cl);
                    $cleanLines[] = $cl;
                }
                $fileContents[$filename][] = implode("\n", $cleanLines);
            }
        }
    }
}
print_r(array_keys($fileContents));
