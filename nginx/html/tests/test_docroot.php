<?php
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "Current directory: " . __DIR__ . "\n";
echo "File exists test:\n";
echo "/config/database.php exists? " . (file_exists($_SERVER['DOCUMENT_ROOT'] . '/config/database.php') ? "Yes" : "No") . "\n";
echo "Direct path exists? " . (file_exists("c:/webserver/nginx/html/config/database.php") ? "Yes" : "No") . "\n";
