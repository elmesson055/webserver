<?php

class DriverModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllDrivers() {
        $query = "SELECT * FROM drivers WHERE active = 1 ORDER BY name";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDriverById($id) {
        $query = "SELECT * FROM drivers WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createDriver($data) {
        $query = "INSERT INTO drivers (name, phone, plate) VALUES (:name, :phone, :plate)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'plate' => $data['plate']
        ]);
    }

    public function updateDriver($id, $data) {
        $query = "UPDATE drivers SET name = :name, phone = :phone, plate = :plate WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'plate' => $data['plate']
        ]);
    }

    public function deleteDriver($id) {
        $query = "UPDATE drivers SET active = 0 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}
