<?php
session_start();

// Incluir configurações e funções
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../functions.php';

// Verificar se usuário já está logado
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard/index.php');
    exit();
}

// Variáveis de controle
$error = '';
$success = '';
$email = '';

// Processar recuperação de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar entrada usando métodos modernos
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));

    // Validar email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Por favor, insira um e-mail válido.';
    } else {
        try {
            // Conexão com banco de dados
            $pdo = Database::getConnection();

            // Verificar se o email existe
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Gerar token de redefinição
                $reset_token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Salvar token no banco de dados
                $stmt = $pdo->prepare("INSERT INTO password_reset_tokens (user_id, token, expiry) VALUES (:user_id, :token, :expiry)");
                $stmt->bindParam(':user_id', $user['id']);
                $stmt->bindParam(':token', $reset_token);
                $stmt->bindParam(':expiry', $expiry);
                $stmt->execute();

                // Enviar email de recuperação (implementação simplificada)
                $reset_link = "http://localhost/app/modules/auth/reset-password.php?token=" . $reset_token;
                
                // TODO: Implementar envio de email real
                $success = 'Um link de recuperação de senha foi enviado para seu e-mail.';
            } else {
                $error = 'Nenhuma conta encontrada com este e-mail.';
            }
        } catch (PDOException $e) {
            error_log('Erro de recuperação de senha: ' . $e->getMessage());
            $error = 'Erro no sistema. Tente novamente mais tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - Custos Extras</title>
    <link href="/assets/css/login.css" rel="stylesheet">
    <link href="/assets/css/footer.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Recuperar Senha</h1>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="forgot-password.php">
            <div class="form-group">
                <input type="email" 
                       class="form-control" 
                       name="email" 
                       placeholder="Seu e-mail"
                       value="<?php echo htmlspecialchars($email); ?>"
                       aria-label="E-mail">
            </div>
            
            <button type="submit" class="login-button">
                Recuperar Senha
            </button>
        </form>

        <div class="login-footer">
            <a href="login.php" class="forgot-password">Voltar para Login</a>
        </div>
    </div>

    <?php 
    // Incluir rodapé
    require_once 'C:/webserver/nginx/html/includes/footer.php';
    render_footer(); 
    ?>
</body>
</html>
