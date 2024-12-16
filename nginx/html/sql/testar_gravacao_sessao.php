<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

echo "<h2>Teste de Gravação de Sessão</h2>";

// Informações sobre a configuração do PHP
echo "<h3>Configuração do PHP:</h3>";
echo "<pre>";
echo "session.save_handler: " . ini_get('session.save_handler') . "\n";
echo "session.save_path: " . ini_get('session.save_path') . "\n";
echo "session.gc_maxlifetime: " . ini_get('session.gc_maxlifetime') . "\n";
echo "session.cookie_lifetime: " . ini_get('session.cookie_lifetime') . "\n";
echo "</pre>";

// Tentar gravar dados na sessão
$_SESSION['teste'] = 'teste_' . time();

echo "<h3>Dados gravados na sessão:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Verificar permissões do diretório de sessão
$session_save_path = session_save_path();
if (empty($session_save_path)) {
    $session_save_path = sys_get_temp_dir();
}

echo "<h3>Informações do diretório de sessão:</h3>";
echo "<pre>";
echo "Diretório: " . $session_save_path . "\n";
if (is_dir($session_save_path)) {
    echo "É um diretório: Sim\n";
    echo "Permissão de escrita: " . (is_writable($session_save_path) ? "Sim" : "Não") . "\n";
} else {
    echo "Diretório não existe!\n";
}
echo "</pre>";

// Verificar se o arquivo de sessão foi criado
$session_file = $session_save_path . '/sess_' . session_id();
echo "<h3>Arquivo de sessão:</h3>";
echo "<pre>";
echo "Arquivo: " . $session_file . "\n";
echo "Existe: " . (file_exists($session_file) ? "Sim" : "Não") . "\n";
if (file_exists($session_file)) {
    echo "Permissão de escrita: " . (is_writable($session_file) ? "Sim" : "Não") . "\n";
}
echo "</pre>";
