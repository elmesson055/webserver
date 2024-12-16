<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Debug de Conexão ===\n\n";

// Carregar configurações
require_once dirname(dirname(__FILE__)) . '/config/database.php';

// Informações do ambiente
echo "Informações do Ambiente:\n";
echo "- PHP Version: " . PHP_VERSION . "\n";
echo "- Sistema Operacional: " . PHP_OS . "\n";
echo "- SAPI: " . php_sapi_name() . "\n";
echo "- Timezone: " . date_default_timezone_get() . "\n\n";

// Configurações do MySQL
echo "Configurações do MySQL:\n";
echo "- Host: " . DB_HOST . "\n";
echo "- Database: " . DB_NAME . "\n";
echo "- User: " . DB_USER . "\n";
echo "- Port: " . DB_PORT . "\n";
echo "- Charset: " . DB_CHARSET . "\n\n";

// Verificar extensões
echo "Extensões necessárias:\n";
echo "- PDO instalado: " . (extension_loaded('pdo') ? 'Sim' : 'Não') . "\n";
echo "- PDO MySQL instalado: " . (extension_loaded('pdo_mysql') ? 'Sim' : 'Não') . "\n";
echo "- MySQL Native Driver: " . (extension_loaded('mysqlnd') ? 'Sim' : 'Não') . "\n\n";

// Drivers PDO disponíveis
echo "Drivers PDO:\n";
print_r(PDO::getAvailableDrivers());
echo "\n";

// Tentar conexão com mais detalhes
echo "Tentando conexão...\n";
try {
    // Construir DSN
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        DB_HOST,
        DB_PORT,
        DB_NAME,
        DB_CHARSET
    );
    echo "DSN: " . $dsn . "\n";
    
    // Opções PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
    ];
    echo "Opções PDO: " . print_r($options, true) . "\n";
    
    // Tentar conexão
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "Conexão estabelecida!\n\n";
    
    // Informações do servidor
    echo "Informações do Servidor:\n";
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch();
    echo "- Versão MySQL: " . $version['version'] . "\n";
    
    $stmt = $pdo->query("SELECT CURRENT_USER() as user");
    $user = $stmt->fetch();
    echo "- Usuário atual: " . $user['user'] . "\n";
    
    $stmt = $pdo->query("SHOW VARIABLES LIKE 'character_set%'");
    echo "- Configurações de charset:\n";
    while ($row = $stmt->fetch()) {
        echo "  * " . $row['Variable_name'] . ": " . $row['Value'] . "\n";
    }
    
} catch (PDOException $e) {
    echo "\nERRO DE CONEXÃO:\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "\nTrace completo:\n" . $e->getTraceAsString() . "\n";
    
    // Verificar se o erro é relacionado ao socket
    if (strpos($e->getMessage(), 'sock') !== false) {
        echo "\nPossível problema com socket MySQL.\n";
        echo "Localizações comuns do socket:\n";
        $socket_locations = [
            '/tmp/mysql.sock',
            '/var/run/mysqld/mysqld.sock',
            '/var/lib/mysql/mysql.sock'
        ];
        foreach ($socket_locations as $location) {
            echo "- $location: " . (file_exists($location) ? 'Existe' : 'Não existe') . "\n";
        }
    }
}
?>
