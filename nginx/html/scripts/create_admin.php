<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/functions.php';

// Configurações do admin master
$admin_username = 'admin_master';
$admin_email = 'admin@elmessonlogistica.com';
$admin_password = 'M@sterAdmin2024!';

try {
    // Conectar ao banco de dados
    $pdo = Database::getConnection();

    // Verificar se o usuário já existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$admin_username, $admin_email]);
    
    if ($stmt->rowCount() > 0) {
        die("Usuário admin master já existe!\n");
    }

    // Criar hash da senha
    $password_hash = password_hash($admin_password, PASSWORD_DEFAULT);

    // Iniciar transação
    $pdo->beginTransaction();

    // Criar role de admin master se não existir
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO roles (name, description) 
        VALUES ('admin_master', 'Administrador com acesso total ao sistema')
    ");
    $stmt->execute();

    // Obter o ID da role de admin master
    $stmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'admin_master'");
    $stmt->execute();
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
    $role_id = $role['id'];

    // Inserir usuário admin master
    $stmt = $pdo->prepare("
        INSERT INTO users (
            username, 
            email, 
            password_hash, 
            role_id, 
            is_active, 
            first_name, 
            last_name
        ) VALUES (?, ?, ?, ?, 1, 'Admin', 'Master')
    ");
    $stmt->execute([
        $admin_username, 
        $admin_email, 
        $password_hash, 
        $role_id
    ]);

    // Criar permissões para admin master
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO permissions (
            role_id, 
            module, 
            can_view, 
            can_create, 
            can_update, 
            can_delete, 
            can_approve
        ) 
        SELECT 
            ?, 
            module, 
            1, 
            1, 
            1, 
            1, 
            1 
        FROM modules
    ");
    $stmt->execute([$role_id]);

    // Confirmar transação
    $pdo->commit();

    echo "Usuário admin master criado com sucesso!\n";
    echo "Username: $admin_username\n";
    echo "Email: $admin_email\n";
    echo "Senha: $admin_password\n";

} catch (PDOException $e) {
    // Reverter transação em caso de erro
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    echo "Erro ao criar usuário admin master: " . $e->getMessage() . "\n";
}
?>
