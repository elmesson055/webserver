<?php
// Garantir que a sessão não foi iniciada
if (session_status() === PHP_SESSION_ACTIVE) {
    return;
}

// Configurações de sessão devem ser definidas antes de iniciar a sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Mudado para 0 para funcionar sem HTTPS
ini_set('session.cookie_samesite', 'Lax'); // Mudado para Lax para ser menos restritivo
ini_set('session.gc_maxlifetime', 1440);
ini_set('session.cookie_lifetime', 0); // 0 para durar até o navegador fechar
ini_set('session.use_strict_mode', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.cache_limiter', 'nocache');
ini_set('session.sid_length', 48);
ini_set('session.sid_bits_per_character', 6);

// Usar o diretório temporário do sistema para as sessões
$temp_dir = sys_get_temp_dir();
if (is_dir($temp_dir) && is_writable($temp_dir)) {
    session_save_path($temp_dir);
}

// Iniciar a sessão
session_start();
