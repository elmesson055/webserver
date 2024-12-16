<?php
class ShipperModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO shippers (name, cnpj, phone, email, observation)
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $data['name'],
                $data['cnpj'],
                $data['phone'],
                $data['email'],
                $data['observation']
            ]);

            $shipperId = $this->db->lastInsertId();

            // Inserir operadores relacionados
            if (!empty($data['operators'])) {
                $stmtOp = $this->db->prepare("
                    INSERT INTO shipper_operators (shipper_id, operator_id)
                    VALUES (?, ?)
                ");

                foreach ($data['operators'] as $operatorId) {
                    $stmtOp->execute([$shipperId, $operatorId]);
                }
            }

            return $shipperId;
        } catch (PDOException $e) {
            error_log("Erro ao criar embarcador: " . $e->getMessage());
            throw new Exception("Erro ao criar embarcador");
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare("
                UPDATE shippers 
                SET name = ?, cnpj = ?, phone = ?, email = ?, observation = ?
                WHERE id = ?
            ");
            
            $stmt->execute([
                $data['name'],
                $data['cnpj'],
                $data['phone'],
                $data['email'],
                $data['observation'],
                $id
            ]);

            // Atualizar operadores
            if (isset($data['operators'])) {
                // Remover operadores existentes
                $stmtDelete = $this->db->prepare("DELETE FROM shipper_operators WHERE shipper_id = ?");
                $stmtDelete->execute([$id]);

                // Inserir novos operadores
                $stmtOp = $this->db->prepare("
                    INSERT INTO shipper_operators (shipper_id, operator_id)
                    VALUES (?, ?)
                ");

                foreach ($data['operators'] as $operatorId) {
                    $stmtOp->execute([$id, $operatorId]);
                }
            }

            return true;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar embarcador: " . $e->getMessage());
            throw new Exception("Erro ao atualizar embarcador");
        }
    }

    public function delete($id) {
        try {
            // Desativar em vez de deletar
            $stmt = $this->db->prepare("UPDATE shippers SET active = 0 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao excluir embarcador: " . $e->getMessage());
            throw new Exception("Erro ao excluir embarcador");
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT s.*, GROUP_CONCAT(so.operator_id) as operator_ids
                FROM shippers s
                LEFT JOIN shipper_operators so ON s.id = so.shipper_id
                WHERE s.id = ?
                GROUP BY s.id
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar embarcador: " . $e->getMessage());
            throw new Exception("Erro ao buscar embarcador");
        }
    }

    public function getAll() {
        try {
            $stmt = $this->db->prepare("
                SELECT s.*, GROUP_CONCAT(u.name) as operator_names
                FROM shippers s
                LEFT JOIN shipper_operators so ON s.id = so.shipper_id
                LEFT JOIN users u ON so.operator_id = u.id
                WHERE s.active = 1
                GROUP BY s.id
                ORDER BY s.name
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar embarcadores: " . $e->getMessage());
            throw new Exception("Erro ao listar embarcadores");
        }
    }
}
