<?php
function get_database_connection() {
    $host = 'localhost';
    $user = 'root';  // Altere conforme suas configurações
    $pass = '';      // Altere conforme suas configurações
    $db   = 'sistema';

    $conn = new mysqli($host, $user, $pass, $db);
    
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}
