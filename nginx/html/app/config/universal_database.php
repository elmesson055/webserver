<?php
// Configurações universais de banco de dados

// Detectar ambiente
$is_windows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
$is_docker = file_exists('/.dockerenv');

// Configurações de host
$hosts = [
    'windows_local' => 'localhost',
    'windows_docker' => 'custos_db',
    'linux_local' => 'localhost',
    'linux_docker' => 'custos_db'
];

// Configurações de usuário
$users = [
    'windows' => ['root', ''],
    'linux' => ['root', ''],
    'docker' => ['custos', 'custos']
];

// Selecionar configurações
if ($is_docker) {
    $host = $is_windows ? $hosts['windows_docker'] : $hosts['linux_docker'];
    $user = $users['docker'][0];
    $pass = $users['docker'][1];
} else {
    $host = $is_windows ? $hosts['windows_local'] : $hosts['linux_local'];
    $user = $users[$is_windows ? 'windows' : 'linux'][0];
    $pass = $users[$is_windows ? 'windows' : 'linux'][1];
}

define('DB_HOST', $host);
define('DB_PORT', '3306');
define('DB_NAME', 'custos');
define('DB_USER', $user);
define('DB_PASS', $pass);
define('DB_CHARSET', 'utf8mb4');

// Configurações PDO
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

return [
    'host' => DB_HOST,
    'port' => DB_PORT,
    'dbname' => DB_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => DB_CHARSET,
    'options' => DB_OPTIONS
];
