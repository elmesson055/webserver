<?php
session_start();
require_once __DIR__ . '/../app/init.php';

echo "=== Debugging Fornecedor Access ===\n\n";

// 1. Verificar usuário logado
echo "1. Usuário:\n";
if (isset($_SESSION['user_id'])) {
    echo "- ID: " . $_SESSION['user_id'] . "\n";
    echo "- Nome: " . ($_SESSION['user']['name'] ?? 'N/A') . "\n";
    echo "- Role ID: " . ($_SESSION['user']['role_id'] ?? 'N/A') . "\n";
} else {
    echo "Nenhum usuário logado!\n";
}

// 2. Verificar se a permissão existe
echo "\n2. Permissão 'fornecedor_view':\n";
$stmt = $db->prepare("SELECT * FROM Permissions WHERE name = 'fornecedor_view'");
$stmt->execute();
$permission = $stmt->fetch(PDO::FETCH_ASSOC);

if ($permission) {
    echo "- ID: " . $permission['id'] . "\n";
    echo "- Nome: " . $permission['name'] . "\n";
    echo "- Ativa: " . ($permission['active'] ? 'Sim' : 'Não') . "\n";
} else {
    echo "Permissão não encontrada!\n";
}

// 3. Verificar papel do usuário
if (isset($_SESSION['user_id'])) {
    echo "\n3. Papel do usuário:\n";
    $stmt = $db->prepare("SELECT r.* FROM Roles r INNER JOIN Users u ON u.role_id = r.id WHERE u.id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($role) {
        echo "- ID: " . $role['id'] . "\n";
        echo "- Nome: " . $role['name'] . "\n";
    } else {
        echo "Usuário não tem papel definido!\n";
    }
}

// 4. Verificar se o papel tem a permissão
if (isset($role) && isset($permission)) {
    echo "\n4. Verificar permissão no papel:\n";
    $stmt = $db->prepare("
        SELECT * FROM RolePermissions 
        WHERE role_id = ? AND permission_id = ?
    ");
    $stmt->execute([$role['id'], $permission['id']]);
    $rolePermission = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rolePermission) {
        echo "Papel tem a permissão!\n";
    } else {
        echo "Papel NÃO tem a permissão!\n";
    }
}

// 5. Verificar estrutura da tabela RolePermissions
echo "\n5. Estrutura da tabela RolePermissions:\n";
try {
    $stmt = $db->query("SHOW CREATE TABLE RolePermissions");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $result['Create Table'] . "\n";
} catch (PDOException $e) {
    echo "Erro ao verificar tabela: " . $e->getMessage() . "\n";
}

// 6. Verificar todas as permissões do usuário
if (isset($_SESSION['user_id'])) {
    echo "\n6. Todas as permissões do usuário:\n";
    $stmt = $db->prepare("
        SELECT DISTINCT p.name
        FROM Permissions p
        INNER JOIN RolePermissions rp ON p.id = rp.permission_id
        INNER JOIN Users u ON u.role_id = rp.role_id
        WHERE u.id = ? AND p.active = 1
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if ($permissions) {
        foreach ($permissions as $perm) {
            echo "- " . $perm . "\n";
        }
    } else {
        echo "Nenhuma permissão encontrada para o usuário!\n";
    }
}
