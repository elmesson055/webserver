<?php

namespace App\Models;

use PDO;
use Exception;

class RoleModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllRoles() {
        $sql = "SELECT * FROM roles WHERE active = 1 ORDER BY name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoleById($id) {
        $sql = "SELECT * FROM roles WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRolePermissions($roleId) {
        $sql = "SELECT p.* 
                FROM permissions p 
                JOIN role_permissions rp ON p.id = rp.permission_id 
                WHERE rp.role_id = ? AND p.active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$roleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPermissions() {
        $sql = "SELECT * FROM permissions WHERE active = 1 ORDER BY name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createRole($data) {
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO roles (name, description, active) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['active'] ?? 1
            ]);
            
            $roleId = $this->db->lastInsertId();
            
            if (!empty($data['permissions'])) {
                $sql = "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)";
                $stmt = $this->db->prepare($sql);
                foreach ($data['permissions'] as $permissionId) {
                    $stmt->execute([$roleId, $permissionId]);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateRole($id, $data) {
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE roles SET name = ?, description = ?, active = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['name'],
                $data['description'],
                $data['active'] ?? 1,
                $id
            ]);
            
            // Update permissions
            $sql = "DELETE FROM role_permissions WHERE role_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            if (!empty($data['permissions'])) {
                $sql = "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)";
                $stmt = $this->db->prepare($sql);
                foreach ($data['permissions'] as $permissionId) {
                    $stmt->execute([$id, $permissionId]);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteRole($id) {
        $this->db->beginTransaction();
        try {
            // Remove role permissions
            $sql = "DELETE FROM role_permissions WHERE role_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            // Remove role
            $sql = "DELETE FROM roles WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
