<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config/database.php';
require_once dirname(dirname(dirname(__FILE__))) . '/Core/Database.php';

use App\Core\Database;

try {
    $db = new Database();
    
    // Ler o arquivo SQL
    $sql = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/database/sql/setup_auth.sql');
    
    // Executar as queries
    $db->exec($sql);
    
    echo "Banco de dados configurado com sucesso!\n";
    echo "Usuário de teste criado:\n";
    echo "Username: admin\n";
    echo "Senha: admin123\n";
    
} catch (Exception $e) {
    echo "Erro ao configurar banco de dados: " . $e->getMessage() . "\n";
    exit(1);
}
