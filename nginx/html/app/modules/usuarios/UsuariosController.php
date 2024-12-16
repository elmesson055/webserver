<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/database.php';

class UsuariosController {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function index() {
        require_once __DIR__ . '/index.php';
    }

    public function getUsers($page = 1, $filters = []) {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['search'])) {
            $where[] = "(name LIKE ? OR email LIKE ?)";
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }

        if (!empty($filters['status'])) {
            $where[] = "status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['role'])) {
            $where[] = "role = ?";
            $params[] = $filters['role'];
        }

        if (!empty($filters['department'])) {
            $where[] = "department = ?";
            $params[] = $filters['department'];
        }

        $whereClause = implode(' AND ', $where);

        // Get total count for pagination
        $countQuery = "SELECT COUNT(*) as total FROM users WHERE $whereClause";
        $stmt = $this->db->prepare($countQuery);
        $stmt->execute($params);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Get users for current page
        $query = "SELECT * FROM users WHERE $whereClause ORDER BY name LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($query);
        $params[] = $limit;
        $params[] = $offset;
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'success' => true,
            'users' => $users,
            'total' => $total,
            'totalPages' => ceil($total / $limit),
            'currentPage' => $page
        ];
    }

    public function createUser($data) {
        try {
            $query = "INSERT INTO users (name, email, role, department, status, password_hash, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['name'],
                $data['email'],
                $data['role'],
                $data['department'],
                $data['status'],
                password_hash($data['password'], PASSWORD_DEFAULT)
            ]);

            return ['success' => true, 'id' => $this->db->lastInsertId()];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao criar usuário: ' . $e->getMessage()];
        }
    }

    public function updateUser($id, $data) {
        try {
            $updates = [];
            $params = [];

            foreach (['name', 'email', 'role', 'department', 'status'] as $field) {
                if (isset($data[$field])) {
                    $updates[] = "$field = ?";
                    $params[] = $data[$field];
                }
            }

            if (!empty($data['password'])) {
                $updates[] = "password_hash = ?";
                $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            if (empty($updates)) {
                return ['success' => false, 'message' => 'Nenhum dado para atualizar'];
            }

            $params[] = $id;
            $query = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao atualizar usuário: ' . $e->getMessage()];
        }
    }

    public function toggleStatus($id) {
        try {
            $query = "UPDATE users SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);

            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao alterar status: ' . $e->getMessage()];
        }
    }

    public function resetPassword($id) {
        try {
            $tempPassword = bin2hex(random_bytes(8));
            $passwordHash = password_hash($tempPassword, PASSWORD_DEFAULT);

            $query = "UPDATE users SET password_hash = ?, password_reset_required = 1 WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$passwordHash, $id]);

            // Here you would typically send an email with the temporary password
            // For now, we'll just return it (not recommended in production)
            return [
                'success' => true,
                'temporaryPassword' => $tempPassword
            ];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao redefinir senha: ' . $e->getMessage()];
        }
    }
}
