<?php
session_start();
require_once __DIR__ . '/../app/init.php';

echo "=== Verificando Permissão de Fornecedor ===\n\n";

// 1. Verificar se a permissão existe
$stmt = $db->prepare("SELECT * FROM Permissions WHERE name = 'fornecedor_view'");
$stmt->execute();
$permission = $stmt->fetch(PDO::FETCH_ASSOC);

echo "1. Permissão 'fornecedor_view':\n";
if ($permission) {
    echo "- ID: " . $permission['id'] . "\n";
    echo "- Nome: " . $permission['name'] . "\n";
    echo "- Ativa: " . ($permission['active'] ? 'Sim' : 'Não') . "\n\n";
} else {
    echo "Permissão não encontrada!\n\n";
}

// 2. Verificar usuário logado
if (isset($_SESSION['user_id'])) {
    echo "2. Usuário Logado:\n";
    echo "- ID: " . $_SESSION['user_id'] . "\n";
    echo "- Nome: " . ($_SESSION['user']['name'] ?? 'N/A') . "\n";
    echo "- Role ID: " . ($_SESSION['user']['role_id'] ?? 'N/A') . "\n\n";

    // 3. Verificar papel do usuário
    $stmt = $db->prepare("
        SELECT r.* 
        FROM Roles r 
        INNER JOIN Users u ON u.role_id = r.id 
        WHERE u.id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "3. Papel do Usuário:\n";
    if ($role) {
        echo "- ID: " . $role['id'] . "\n";
        echo "- Nome: " . $role['name'] . "\n\n";

        // 4. Verificar se o papel tem a permissão
        if ($permission) {
            $stmt = $db->prepare("
                SELECT * FROM RolePermissions 
                WHERE role_id = ? AND permission_id = ?
            ");
            $stmt->execute([$role['id'], $permission['id']]);
            $rolePermission = $stmt->fetch(PDO::FETCH_ASSOC);

            echo "4. Permissão no Papel:\n";
            if ($rolePermission) {
                echo "O papel tem a permissão 'fornecedor_view'\n\n";
            } else {
                echo "O papel NÃO tem a permissão 'fornecedor_view'\n\n";
            }
        }

        // 5. Listar todas as permissões do papel
        $stmt = $db->prepare("
            SELECT p.name 
            FROM Permissions p
            INNER JOIN RolePermissions rp ON p.id = rp.permission_id
            WHERE rp.role_id = ?
        ");
        $stmt->execute([$role['id']]);
        $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo "5. Todas as Permissões do Papel:\n";
        foreach ($permissions as $perm) {
            echo "- " . $perm . "\n";
        }
    } else {
        echo "Papel não encontrado!\n";
    }
} else {
    echo "2. Nenhum usuário logado!\n";
}

// 6. Verificar função hasPermission
echo "\n6. Teste da função hasPermission:\n";
if (function_exists('hasPermission')) {
    $result = hasPermission('fornecedor_view');
    echo "hasPermission('fornecedor_view') retornou: " . ($result ? 'true' : 'false') . "\n";
} else {
    echo "Função hasPermission não existe!\n";
}
