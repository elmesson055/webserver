<?php
require_once __DIR__ . '/../app/init.php';

try {
    // 1. Verificar se a permissão fornecedor_view existe
    $stmt = $db->prepare("SELECT id FROM Permissions WHERE name = 'fornecedor_view'");
    $stmt->execute();
    $permission = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$permission) {
        // Criar a permissão se não existir
        $stmt = $db->prepare("INSERT INTO Permissions (name, description, active) VALUES ('fornecedor_view', 'Visualizar Fornecedores', 1)");
        $stmt->execute();
        $permission_id = $db->lastInsertId();
        echo "Permissão fornecedor_view criada com ID: $permission_id\n";
    } else {
        $permission_id = $permission['id'];
        echo "Permissão fornecedor_view já existe com ID: $permission_id\n";
    }

    // 2. Verificar se a permissão está atribuída ao papel de administrador
    $stmt = $db->prepare("
        SELECT rp.* 
        FROM RolePermissions rp 
        INNER JOIN Roles r ON r.id = rp.role_id 
        WHERE r.name = 'admin' AND rp.permission_id = ?
    ");
    $stmt->execute([$permission_id]);
    $rolePermission = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rolePermission) {
        // Atribuir a permissão ao papel de administrador
        $stmt = $db->prepare("INSERT INTO RolePermissions (role_id, permission_id) VALUES (1, ?)");
        $stmt->execute([$permission_id]);
        echo "Permissão fornecedor_view atribuída ao papel de administrador\n";
    } else {
        echo "Permissão fornecedor_view já está atribuída ao papel de administrador\n";
    }

    // 3. Verificar se o usuário atual tem a permissão
    if (isset($_SESSION['user_id'])) {
        $stmt = $db->prepare("
            SELECT p.name
            FROM Permissions p
            INNER JOIN RolePermissions rp ON p.id = rp.permission_id
            INNER JOIN Users u ON u.role_id = rp.role_id
            WHERE u.id = ? AND p.name = 'fornecedor_view'
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $userPermission = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userPermission) {
            echo "Usuário tem a permissão fornecedor_view\n";
        } else {
            echo "Usuário NÃO tem a permissão fornecedor_view\n";
            
            // Verificar o papel do usuário
            $stmt = $db->prepare("SELECT role_id FROM Users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Papel do usuário: " . $user['role_id'] . "\n";
        }
    } else {
        echo "Nenhum usuário logado\n";
    }

    // 4. Verificar status da permissão
    $stmt = $db->prepare("SELECT active FROM Permissions WHERE name = 'fornecedor_view'");
    $stmt->execute();
    $status = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Status da permissão fornecedor_view: " . ($status['active'] ? 'Ativa' : 'Inativa') . "\n";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
