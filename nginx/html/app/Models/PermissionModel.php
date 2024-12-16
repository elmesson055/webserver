<?php

namespace App\Models;

use PDO;
use Exception;

class PermissionModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllPermissions() {
        $query = "SELECT * FROM permissions ORDER BY name";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPermissionById($id) {
        $query = "SELECT * FROM permissions WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createPermission($data) {
        $query = "INSERT INTO permissions (name, description) VALUES (:name, :description)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }

    public function updatePermission($id, $data) {
        $query = "UPDATE permissions SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description']
        ]);
    }

    public function deletePermission($id) {
        $query = "DELETE FROM permissions WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}
