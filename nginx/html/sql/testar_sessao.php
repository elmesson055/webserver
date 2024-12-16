<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Teste de Sessão</h2>";
echo "<pre>";
echo "Conteúdo da sessão:\n";
print_r($_SESSION);
echo "\n\nStatus da sessão:\n";
echo "Session ID: " . session_id() . "\n";
echo "Session Status: " . session_status() . "\n";
echo "Session Save Path: " . session_save_path() . "\n";
echo "</pre>";
