<?php
// Código para logout

// Iniciar sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destruir a sessão
session_unset();
session_destroy();

// Redirecionar para a página de login
header('Location: /app/modules/auth/views/login.php');
exit();
?>
