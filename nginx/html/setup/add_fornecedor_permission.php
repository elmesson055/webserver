<?php
require_once __DIR__ . '/../app/init.php';

try {
    $db->beginTransaction();

    // 1. Verificar se a permissão existe
    $stmt = $db->prepare("SELECT id FROM Permissions WHERE name = 'fornecedor_view'");
    $stmt->execute();
    $permission = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$permission) {
        // Criar a permissão se não existir
        $stmt = $db->prepare("INSERT INTO Permissions (name, description, active) VALUES (?, ?, 1)");
        $stmt->execute(['fornecedor_view', 'Visualizar Fornecedores']);
        $permission_id = $db->lastInsertId();
        echo "Permissão 'fornecedor_view' criada com ID: $permission_id\n";
    } else {
        $permission_id = $permission['id'];
        echo "Permissão 'fornecedor_view' já existe com ID: $permission_id\n";
    }

    // 2. Adicionar permissão a todos os papéis de administrador
    $stmt = $db->prepare("
        INSERT IGNORE INTO RolePermissions (role_id, permission_id)
        SELECT r.id, :permission_id
        FROM Roles r
        WHERE r.name = 'admin'
    ");
    $stmt->execute([':permission_id' => $permission_id]);
    
    echo "Permissão adicionada aos papéis de administrador\n";

    // 3. Verificar se há outros papéis que precisam da permissão
    $stmt = $db->query("SELECT id, name FROM Roles WHERE name != 'admin'");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($roles as $role) {
        echo "\nDeseja adicionar a permissão 'fornecedor_view' ao papel '{$role['name']}'? (s/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        if (trim($line) == 's') {
            $stmt = $db->prepare("INSERT IGNORE INTO RolePermissions (role_id, permission_id) VALUES (?, ?)");
            $stmt->execute([$role['id'], $permission_id]);
            echo "Permissão adicionada ao papel '{$role['name']}'\n";
        }
        fclose($handle);
    }

    $db->commit();
    echo "\nProcesso concluído com sucesso!\n";

} catch (Exception $e) {
    $db->rollBack();
    echo "Erro: " . $e->getMessage() . "\n";
}
