<?php

class Cost {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function generateTrackingNumber() {
        $year = date('Y');
        $month = date('m');
        
        // Get the last tracking number for the current year/month
        $sql = "SELECT tracking_number FROM costs 
                WHERE tracking_number LIKE ? 
                ORDER BY tracking_number DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["CE{$year}{$month}%"]);
        $lastNumber = $stmt->fetch(PDO::FETCH_COLUMN);
        
        if ($lastNumber) {
            // Extract the sequence number and increment it
            $sequence = intval(substr($lastNumber, -4)) + 1;
        } else {
            // Start with 1 if no previous number exists
            $sequence = 1;
        }
        
        // Format: CE + YYYY + MM + 0001
        return sprintf("CE%s%s%04d", $year, $month, $sequence);
    }

    public function create($data) {
        try {
            // Generate tracking number
            $data['tracking_number'] = $this->generateTrackingNumber();
            
            // Add created_by field
            $data['created_by'] = $_SESSION['user_id'] ?? 1;

            $sql = "INSERT INTO costs (
                tracking_number, date, client_id, driver_id, cargo_type_id, cost_type_id, operational_status_id,
                romaneio, reference_number, nfe_number, quantity, total_value,
                approved_value, approval_date, occurrence_number, status_id,
                document_type_id, cte_number, cte_emission_date, payment_date, created_by
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $data['tracking_number'],
                $data['date'],
                $data['client_id'],
                $data['driver_id'] ?? null,
                $data['cargo_type_id'],
                $data['cost_type_id'],
                $data['operational_status_id'],
                $data['romaneio'],
                $data['reference_number'],
                $data['nfe_number'],
                $data['quantity'],
                $data['total_value'],
                $data['approved_value'],
                $data['approval_date'],
                $data['occurrence_number'],
                $data['status_id'],
                $data['document_type_id'],
                $data['cte_number'],
                $data['cte_emission_date'],
                $data['payment_date'],
                $data['created_by']
            ]);

            if (!$result) {
                error_log("Error creating cost: " . implode(", ", $stmt->errorInfo()));
                return false;
            }

            return true;
        } catch (Exception $e) {
            error_log("Exception creating cost: " . $e->getMessage());
            return false;
        }
    }

    public function getFilteredCosts($filters = []) {
        $query = "
            SELECT c.*, 
                   cl.name as client_name,
                   ct.name as cargo_type_name,
                   cot.name as cost_type_name,
                   os.name as operational_status_name,
                   st.name as status_name,
                   dt.name as document_type_name,
                   d.name as driver_name,
                   d.plate as driver_plate
            FROM costs c
            LEFT JOIN clients cl ON c.client_id = cl.id
            LEFT JOIN cargo_types ct ON c.cargo_type_id = ct.id
            LEFT JOIN cost_types cot ON c.cost_type_id = cot.id
            LEFT JOIN operational_status os ON c.operational_status_id = os.id
            LEFT JOIN status_types st ON c.status_id = st.id
            LEFT JOIN document_types dt ON c.document_type_id = dt.id
            LEFT JOIN drivers d ON c.driver_id = d.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filters['start_date'])) {
            $query .= " AND c.date >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $query .= " AND c.date <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        if (!empty($filters['client_id'])) {
            $query .= " AND c.client_id = :client_id";
            $params['client_id'] = $filters['client_id'];
        }

        if (!empty($filters['driver_id'])) {
            $query .= " AND c.driver_id = :driver_id";
            $params['driver_id'] = $filters['driver_id'];
        }

        if (!empty($filters['cargo_type_id'])) {
            $query .= " AND c.cargo_type_id = :cargo_type_id";
            $params['cargo_type_id'] = $filters['cargo_type_id'];
        }

        if (!empty($filters['cost_type_id'])) {
            $query .= " AND c.cost_type_id = :cost_type_id";
            $params['cost_type_id'] = $filters['cost_type_id'];
        }

        if (!empty($filters['operational_status_id'])) {
            $query .= " AND c.operational_status_id = :operational_status_id";
            $params['operational_status_id'] = $filters['operational_status_id'];
        }

        $query .= " ORDER BY c.date DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($filters) {
        $sql = "SELECT DISTINCT c.*, 
                cl.name as client_name,
                st.name as status_name,
                ft.color as status_color,
                ft.name as follow_up_type_name,
                ft.color as follow_up_type_color,
                u.name as user_name,
                fh.follow_up_type_id,
                ft.color as row_color
                FROM costs c
                LEFT JOIN clients cl ON c.client_id = cl.id
                LEFT JOIN status_types st ON c.status_id = st.id
                LEFT JOIN follow_up_history fh ON c.id = fh.cost_id
                LEFT JOIN follow_up_types ft ON fh.follow_up_type_id = ft.id
                LEFT JOIN users u ON c.created_by = u.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['cost_id'])) {
            $sql .= " AND c.id = :cost_id";
            $params['cost_id'] = $filters['cost_id'];
        }

        if (!empty($filters['nfe_number'])) {
            $sql .= " AND c.nfe_number LIKE :nfe_number";
            $params['nfe_number'] = '%' . $filters['nfe_number'] . '%';
        }

        if (!empty($filters['cte_number'])) {
            $sql .= " AND c.cte_number LIKE :cte_number";
            $params['cte_number'] = '%' . $filters['cte_number'] . '%';
        }

        if (!empty($filters['romaneio'])) {
            $sql .= " AND c.romaneio LIKE :romaneio";
            $params['romaneio'] = '%' . $filters['romaneio'] . '%';
        }

        if (!empty($filters['client_id'])) {
            $sql .= " AND c.client_id = :client_id";
            $params['client_id'] = $filters['client_id'];
        }

        if (!empty($filters['status_id'])) {
            $sql .= " AND c.status_id = :status_id";
            $params['status_id'] = $filters['status_id'];
        }

        if (!empty($filters['tracking_number'])) {
            $sql .= " AND c.tracking_number LIKE :tracking_number";
            $params['tracking_number'] = '%' . $filters['tracking_number'] . '%';
        }

        if (!empty($filters['driver_id'])) {
            $sql .= " AND c.driver_id = :driver_id";
            $params['driver_id'] = $filters['driver_id'];
        }

        if (!empty($filters['start_date'])) {
            $sql .= " AND c.date >= :start_date";
            $params['start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND c.date <= :end_date";
            $params['end_date'] = $filters['end_date'];
        }

        // Pega o último follow-up para cada custo
        $sql .= " AND (fh.id IS NULL OR fh.id = (
            SELECT MAX(id)
            FROM follow_up_history
            WHERE cost_id = c.id
        ))";

        $sql .= " ORDER BY c.date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE costs SET
                date = :date,
                client_id = :client_id,
                driver_id = :driver_id,
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
                updated_at = NOW()
            WHERE id = :id";
            
            // Ajustar o nome do campo antes de fazer o bind
            if (isset($data['cte_issue_date'])) {
                $data['cte_emission_date'] = $data['cte_issue_date'];
                unset($data['cte_issue_date']);
            }
            
            $stmt = $this->db->prepare($sql);
            
            // Bind all parameters
            $params = array_merge($data, ['id' => $id]);
            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            
            $result = $stmt->execute();
            
            if (!$result) {
                throw new Exception("Erro ao atualizar o custo: " . implode(", ", $stmt->errorInfo()));
            }
            
            return $result;
        } catch (Exception $e) {
            error_log("Error updating cost: " . $e->getMessage());
            throw $e;
        }
    }

    public function getById($id) {
        try {
            $sql = "SELECT c.*, 
                          cl.name as client_name,
                          ct.name as cargo_type_name,
                          cot.name as cost_type_name,
                          os.name as operational_status_name,
                          st.name as status_name,
                          dt.name as document_type_name,
                          d.name as driver_name,
                          d.plate as driver_plate
                   FROM costs c
                   LEFT JOIN clients cl ON c.client_id = cl.id
                   LEFT JOIN cargo_types ct ON c.cargo_type_id = ct.id
                   LEFT JOIN cost_types cot ON c.cost_type_id = cot.id
                   LEFT JOIN operational_status os ON c.operational_status_id = os.id
                   LEFT JOIN status_types st ON c.status_id = st.id
                   LEFT JOIN document_types dt ON c.document_type_id = dt.id
                   LEFT JOIN drivers d ON c.driver_id = d.id
                   WHERE c.id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Exception getting cost by ID: " . $e->getMessage());
            return null;
        }
    }

    public function getAll() {
        $sql = "SELECT c.*,
                cl.name as client_name,
                ct.name as cargo_type_name,
                cot.name as cost_type_name,
                os.name as operational_status_name,
                st.name as status_name,
                dt.name as document_type_name,
                d.name as driver_name,
                d.plate as driver_plate,
                CASE 
                    WHEN st.name LIKE '%Pendente%' THEN 'yellow'
                    WHEN st.name LIKE '%Aprovado%' THEN 'green'
                    WHEN st.name LIKE '%Rejeitado%' THEN 'red'
                    ELSE 'gray'
                END as status_color
                FROM costs c
                LEFT JOIN clients cl ON c.client_id = cl.id
                LEFT JOIN cargo_types ct ON c.cargo_type_id = ct.id
                LEFT JOIN cost_types cot ON c.cost_type_id = cot.id
                LEFT JOIN operational_status os ON c.operational_status_id = os.id
                LEFT JOIN status_types st ON c.status_id = st.id
                LEFT JOIN document_types dt ON c.document_type_id = dt.id
                LEFT JOIN drivers d ON c.driver_id = d.id
                ORDER BY c.date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailedById($id) {
        $sql = "SELECT c.*,
                cl.name as client_name,
                ct.name as cargo_type_name,
                cot.name as cost_type_name,
                os.name as operational_status_name,
                st.name as status_name,
                dt.name as document_type_name,
                d.name as driver_name,
                d.plate as driver_plate,
                uc.name as created_by_name,
                uu.name as updated_by_name
                FROM costs c
                LEFT JOIN clients cl ON c.client_id = cl.id
                LEFT JOIN cargo_types ct ON c.cargo_type_id = ct.id
                LEFT JOIN cost_types cot ON c.cost_type_id = cot.id
                LEFT JOIN operational_status os ON c.operational_status_id = os.id
                LEFT JOIN status_types st ON c.status_id = st.id
                LEFT JOIN document_types dt ON c.document_type_id = dt.id
                LEFT JOIN drivers d ON c.driver_id = d.id
                LEFT JOIN users uc ON c.created_by = uc.id
                LEFT JOIN users uu ON c.updated_by = uu.id
                WHERE c.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTotalCostsByClient($clientId) {
        try {
            $sql = "SELECT COALESCE(SUM(total_value), 0) as total FROM costs WHERE client_id = :client_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['client_id' => $clientId]);
            return $stmt->fetch(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("Error getting total costs by client: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalCosts() {
        try {
            $sql = "SELECT COALESCE(SUM(total_value), 0) as total FROM costs";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("Error getting total costs: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalPendingCosts() {
        try {
            $sql = "SELECT COALESCE(SUM(total_value), 0) as total FROM costs WHERE status_id = 1"; // Assumindo que status_id 1 é pendente
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("Error getting total pending costs: " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalApprovedCosts() {
        try {
            $sql = "SELECT COALESCE(SUM(total_value), 0) as total FROM costs WHERE status_id = 2"; // Assumindo que status_id 2 é aprovado
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_COLUMN);
        } catch (Exception $e) {
            error_log("Error getting total approved costs: " . $e->getMessage());
            return 0;
        }
    }

    public function getCostsByType() {
        try {
            $sql = "SELECT ct.name, COALESCE(SUM(c.total_value), 0) as total
                    FROM cost_types ct
                    LEFT JOIN costs c ON ct.id = c.cost_type_id
                    GROUP BY ct.id, ct.name
                    ORDER BY total DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting costs by type: " . $e->getMessage());
            return [];
        }
    }

    public function getCostsByCargoType() {
        try {
            $sql = "SELECT ct.name, COALESCE(SUM(c.total_value), 0) as total
                    FROM cargo_types ct
                    LEFT JOIN costs c ON ct.id = c.cargo_type_id
                    GROUP BY ct.id, ct.name
                    ORDER BY total DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting costs by cargo type: " . $e->getMessage());
            return [];
        }
    }

    public function exists($id) {
        try {
            $sql = "SELECT COUNT(*) FROM costs WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Exception checking if cost exists: " . $e->getMessage());
            return false;
        }
    }
}
