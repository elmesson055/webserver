<?php
require_once __DIR__ . '/../app/init.php';

try {
    // 1. Criar tabela Roles se não existir
    $db->exec("
        CREATE TABLE IF NOT EXISTS Roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            description VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "Tabela Roles criada ou já existe\n";

    // 2. Criar tabela RolePermissions se não existir
    $db->exec("
        CREATE TABLE IF NOT EXISTS RolePermissions (
            role_id INT,
            permission_id INT,
            PRIMARY KEY (role_id, permission_id),
            FOREIGN KEY (role_id) REFERENCES Roles(id) ON DELETE CASCADE,
            FOREIGN KEY (permission_id) REFERENCES Permissions(id) ON DELETE CASCADE
        )
    ");
    echo "Tabela RolePermissions criada ou já existe\n";

    // 3. Inserir papel de administrador se não existir
    $stmt = $db->prepare("SELECT id FROM Roles WHERE name = 'admin'");
    $stmt->execute();
    $adminRole = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$adminRole) {
        $stmt = $db->prepare("INSERT INTO Roles (name, description) VALUES ('admin', 'Administrador do sistema')");
        $stmt->execute();
        $adminRoleId = $db->lastInsertId();
        echo "Papel de administrador criado com ID: $adminRoleId\n";
    } else {
        $adminRoleId = $adminRole['id'];
        echo "Papel de administrador já existe com ID: $adminRoleId\n";
    }

    // 4. Atribuir todas as permissões ao papel de administrador
    $stmt = $db->query("SELECT id FROM Permissions WHERE active = 1");
    $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($permissions as $permissionId) {
        try {
            $stmt = $db->prepare("INSERT IGNORE INTO RolePermissions (role_id, permission_id) VALUES (?, ?)");
            $stmt->execute([$adminRoleId, $permissionId]);
        } catch (PDOException $e) {
            // Ignora erro de duplicidade
            if ($e->getCode() !== '23000') {
                throw $e;
            }
        }
    }
    echo "Permissões atribuídas ao papel de administrador\n";

    // 5. Atualizar usuários sem papel para terem o papel de admin
    $db->exec("UPDATE Users SET role_id = $adminRoleId WHERE role_id IS NULL");
    echo "Usuários atualizados com o papel de administrador\n";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
