<?php
require_once __DIR__ . '/../app/init.php';
require_once __DIR__ . '/../app/Models/User.php';

try {
    $db = connectDB();
    
    // 1. Verificar se o papel de admin existe
    $stmt = $db->prepare("SELECT id FROM Roles WHERE name = 'admin'");
    $stmt->execute();
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$role) {
        // Criar papel de admin
        $stmt = $db->prepare("INSERT INTO Roles (name, description) VALUES ('admin', 'Administrador do sistema')");
        $stmt->execute();
        $role_id = $db->lastInsertId();
        echo "Papel de admin criado com ID: " . $role_id . "\n";
    } else {
        $role_id = $role['id'];
        echo "Papel de admin já existe com ID: " . $role_id . "\n";
    }
    
    // 2. Adicionar todas as permissões ao papel de admin
    $stmt = $db->query("SELECT id FROM Permissions");
    $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($permissions as $permission_id) {
        try {
            $stmt = $db->prepare("INSERT INTO RolePermissions (role_id, permission_id) VALUES (?, ?)");
            $stmt->execute([$role_id, $permission_id]);
            echo "Permissão ID " . $permission_id . " adicionada ao admin\n";
        } catch (PDOException $e) {
            if ($e->getCode() != 23000) { // Ignora erro de chave duplicada
                throw $e;
            }
        }
    }
    
    // 3. Criar usuário admin se não existir
    $stmt = $db->prepare("SELECT id FROM Users WHERE username = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        $userModel = new User($db);
        $userData = [
            'username' => 'admin',
            'password' => 'admin123', // Senha inicial
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'role_id' => $role_id
        ];
        
        if ($userModel->create($userData)) {
            echo "\nUsuário admin criado com sucesso!\n";
            echo "Username: admin\n";
            echo "Senha: admin123\n";
        } else {
            echo "\nErro ao criar usuário admin\n";
        }
    } else {
        echo "\nUsuário admin já existe\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
