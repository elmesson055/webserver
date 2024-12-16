<?php

class FollowUpModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function update($data) {
        try {
            $this->db->beginTransaction();

            // Atualizar a tabela costs
            $sql = "UPDATE costs SET
                date = :date,
                cargo_type_id = :cargo_type_id,
                cost_type_id = :cost_type_id,
                operational_status_id = :operational_status_id,
                romaneio = :romaneio,
                reference_number = :reference_number,
                nfe_number = :nfe_number,
                quantity = :quantity,
                total_value = :total_value,
                approved_value = :approved_value,
                approval_date = :approval_date,
                occurrence_number = :occurrence_number,
                status_id = :status_id,
                document_type_id = :document_type_id,
                cte_number = :cte_number,
                cte_emission_date = :cte_emission_date,
                payment_date = :payment_date,
                updated_by = :updated_by,
                updated_at = NOW()
                WHERE id = :cost_id";

            try {
                $stmt = $this->db->prepare($sql);
                $updateResult = $stmt->execute([
                    'date' => $data['date'],
                    'cargo_type_id' => $data['cargo_type_id'],
                    'cost_type_id' => $data['cost_type_id'],
                    'operational_status_id' => $data['operational_status_id'],
                    'romaneio' => $data['romaneio'],
                    'reference_number' => $data['reference_number'],
                    'nfe_number' => $data['nfe_number'],
                    'quantity' => $data['quantity'],
                    'total_value' => $data['total_value'],
                    'approved_value' => $data['approved_value'],
                    'approval_date' => $data['approval_date'],
                    'occurrence_number' => $data['occurrence_number'],
                    'status_id' => $data['status_id'],
                    'document_type_id' => $data['document_type_id'],
                    'cte_number' => $data['cte_number'],
                    'cte_emission_date' => $data['cte_emission_date'],
                    'payment_date' => $data['payment_date'],
                    'updated_by' => $data['updated_by'],
                    'cost_id' => $data['cost_id']
                ]);

                if (!$updateResult) {
                    throw new Exception("Erro ao atualizar a tabela costs: " . implode(", ", $stmt->errorInfo()));
                }
            } catch (PDOException $e) {
                throw new Exception("Erro na atualização da tabela costs: " . $e->getMessage());
            }

            // Inserir no histórico
            $sql = "INSERT INTO follow_up_history (
                cost_id, date, cargo_type_id, cost_type_id, operational_status_id,
                romaneio, reference_number, nfe_number, quantity, total_value,
                approved_value, approval_date, occurrence_number, status_id,
                document_type_id, cte_number, cte_emission_date, payment_date,
                follow_up_type_id, contact_type_id, driver_id, observation,
                created_by, created_at
            ) VALUES (
                :cost_id, :date, :cargo_type_id, :cost_type_id, :operational_status_id,
                :romaneio, :reference_number, :nfe_number, :quantity, :total_value,
                :approved_value, :approval_date, :occurrence_number, :status_id,
                :document_type_id, :cte_number, :cte_emission_date, :payment_date,
                :follow_up_type_id, :contact_type_id, :driver_id, :observation,
                :created_by, NOW()
            )";

            try {
                $stmt = $this->db->prepare($sql);
                $historyResult = $stmt->execute([
                    'cost_id' => $data['cost_id'],
                    'date' => $data['date'],
                    'cargo_type_id' => $data['cargo_type_id'],
                    'cost_type_id' => $data['cost_type_id'],
                    'operational_status_id' => $data['operational_status_id'],
                    'romaneio' => $data['romaneio'],
                    'reference_number' => $data['reference_number'],
                    'nfe_number' => $data['nfe_number'],
                    'quantity' => $data['quantity'],
                    'total_value' => $data['total_value'],
                    'approved_value' => $data['approved_value'],
                    'approval_date' => $data['approval_date'],
                    'occurrence_number' => $data['occurrence_number'],
                    'status_id' => $data['status_id'],
                    'document_type_id' => $data['document_type_id'],
                    'cte_number' => $data['cte_number'],
                    'cte_emission_date' => $data['cte_emission_date'],
                    'payment_date' => $data['payment_date'],
                    'follow_up_type_id' => $data['follow_up_type_id'],
                    'contact_type_id' => $data['contact_type_id'],
                    'driver_id' => $data['driver_id'],
                    'observation' => $data['observation'],
                    'created_by' => $data['updated_by']
                ]);

                if (!$historyResult) {
                    throw new Exception("Erro ao inserir no histórico: " . implode(", ", $stmt->errorInfo()));
                }
            } catch (PDOException $e) {
                throw new Exception("Erro na inserção do histórico: " . $e->getMessage());
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erro completo na atualização: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public function getById($id) {
        $sql = "SELECT c.*, 
                       cl.name as client_name,
                       ft.name as follow_up_type_name,
                       ct.name as contact_type_name,
                       d.name as driver_name,
                       d.plate as driver_plate
                FROM costs c
                LEFT JOIN clients cl ON c.client_id = cl.id
                LEFT JOIN follow_up_types ft ON c.follow_up_type_id = ft.id
                LEFT JOIN contact_types ct ON c.contact_type_id = ct.id
                LEFT JOIN drivers d ON c.driver_id = d.id
                WHERE c.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
