<?php
$sql = file_get_contents("bengpuskomlekad.sql");
try {
    DB::unprepared($sql);
    echo "SQL Executed Successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
