<?php
require_once __DIR__ . '/../app/init.php';

try {
    // Adicionar permissões do fornecedor
    $permissions = [
        ['fornecedor_view', 'Visualizar Fornecedores'],
        ['fornecedor_create', 'Criar Fornecedores'],
        ['fornecedor_edit', 'Editar Fornecedores'],
        ['fornecedor_delete', 'Excluir Fornecedores']
    ];

    foreach ($permissions as $permission) {
        $stmt = $db->prepare("INSERT INTO Permissions (name, description) VALUES (?, ?)");
        $stmt->execute($permission);
        echo "Permissão {$permission[0]} adicionada com sucesso!\n";
    }

    // Adicionar permissões ao papel de administrador (role_id = 1)
    $role_id = 1; // ID do papel de administrador
    
    $stmt = $db->prepare("SELECT id FROM Permissions WHERE name LIKE 'fornecedor_%'");
    $stmt->execute();
    $permission_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($permission_ids as $permission_id) {
        $stmt = $db->prepare("INSERT INTO RolePermissions (role_id, permission_id) VALUES (?, ?)");
        $stmt->execute([$role_id, $permission_id]);
        echo "Permissão ID {$permission_id} adicionada ao papel de administrador!\n";
    }

    echo "Todas as permissões foram configuradas com sucesso!\n";

} catch (PDOException $e) {
    echo "Erro ao configurar permissões: " . $e->getMessage() . "\n";
}
