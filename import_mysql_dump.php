<?php
$sqlitePath = __DIR__ . '/database/database.sqlite';
$sqlDumpPath = __DIR__ . '/bengpuskomlekad.sql';

if (!file_exists($sqlDumpPath)) {
    die("SQL dump not found.\n");
}

$pdo = new PDO('sqlite:' . $sqlitePath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = file_get_contents($sqlDumpPath);

// Extract only the INSERT statements
preg_match_all('/INSERT INTO `([^`]+)` \([^)]+\) VALUES\s*(.*?);/s', $sql, $matches, PREG_SET_ORDER);

$count = 0;
foreach ($matches as $match) {
    $table = $match[1];
    
    // Ignore migrations, users, personal_access_tokens etc if they are not in the dump
    // Actually, we just wipe the specific tables first so we don't have duplicate ID errors
    try {
        $pdo->exec("DELETE FROM `$table`");
        $pdo->exec("DELETE FROM sqlite_sequence WHERE name='$table'"); // reset autoincrement
    } catch (Exception $e) {
        // Table might not exist, ignore
    }
    
    $valuesStr = $match[2];
    
    // Split by tuple: (val, val, ...), (val, val, ...)
    preg_match_all('/\((.*?)\)(?:,\s*\(|$)/s', $valuesStr, $tuples);
    
    foreach ($tuples[0] as $tupStr) {
        // Just execute it as a raw INSERT INTO ... VALUES (...)
        // But we need to replace MySQL escapes like \' with ''
        $tupStr = trim($tupStr, ",\n\r\t ");
        if (substr($tupStr, -1) !== ')') {
            $tupStr .= ')'; // fix last paren if lost in regex
        }
        
        $query = "INSERT INTO `$table` VALUES " . $tupStr;
        try {
            $pdo->exec($query);
            $count++;
        } catch (Exception $e) {
            echo "Error inserting into $table: " . $e->getMessage() . "\n";
            echo "Query: $query\n\n";
        }
    }
}

echo "Successfully imported $count rows from $sqlDumpPath.\n";
