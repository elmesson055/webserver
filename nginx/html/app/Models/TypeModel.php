<?php

class TypeModel {
    private $db;
    private $table;
    private $hasActiveColumn = false;
    private $hasColorColumn = false;
    private $hasDescriptionColumn = false;

    public function __construct($db) {
        $this->db = $db;
    }

    private function setTable($table) {
        $tableName = $this->getTableName($table);
        $this->table = $tableName;
        $this->checkColumns();
        return $this;
    }

    private function checkColumns() {
        try {
            error_log("Verificando colunas para tabela: {$this->table}");

            // Verifica coluna active
            $sql = "SHOW COLUMNS FROM {$this->table} LIKE 'active'";
            error_log("SQL para verificar coluna active: " . $sql);
            $stmt = $this->db->query($sql);
            if ($stmt === false) {
                error_log("Erro ao verificar coluna active: " . print_r($this->db->errorInfo(), true));
                return;
            }
            $this->hasActiveColumn = $stmt->rowCount() > 0;
            error_log("hasActiveColumn: " . ($this->hasActiveColumn ? "sim" : "não"));

            // Verifica coluna color
            $sql = "SHOW COLUMNS FROM {$this->table} LIKE 'color'";
            error_log("SQL para verificar coluna color: " . $sql);
            $stmt = $this->db->query($sql);
            if ($stmt === false) {
                error_log("Erro ao verificar coluna color: " . print_r($this->db->errorInfo(), true));
                return;
            }
            $this->hasColorColumn = $stmt->rowCount() > 0;
            error_log("hasColorColumn: " . ($this->hasColorColumn ? "sim" : "não"));

            // Verifica coluna description
            $sql = "SHOW COLUMNS FROM {$this->table} LIKE 'description'";
            error_log("SQL para verificar coluna description: " . $sql);
            $stmt = $this->db->query($sql);
            if ($stmt === false) {
                error_log("Erro ao verificar coluna description: " . print_r($this->db->errorInfo(), true));
                return;
            }
            $this->hasDescriptionColumn = $stmt->rowCount() > 0;
            error_log("hasDescriptionColumn: " . ($this->hasDescriptionColumn ? "sim" : "não"));
        } catch (Exception $e) {
            error_log("Erro em checkColumns: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
        }
    }

    private function getTableName($type) {
        $tables = [
            'cargo_types' => 'cargo_types',
            'cost_types' => 'cost_types',
            'operational_status' => 'operational_status',
            'status_types' => 'status_types',
            'document_types' => 'document_types',
            'follow_up_types' => 'follow_up_types',
            'contact_types' => 'contact_types',
            'billing_status' => 'billing_status',
        ];
        
        if (!isset($tables[$type])) {
            throw new Exception("Tipo de tabela inválido: $type");
        }
        
        return $tables[$type];
    }

    public function create($data, $type) {
        try {
            error_log("Iniciando criação de tipo: " . $type);
            error_log("Dados recebidos: " . print_r($data, true));

            $this->setTable($type);
            error_log("Tabela definida como: " . $this->table);
            
            $fields = ['name'];
            $values = [$data['name']];
            $placeholders = ['?'];
            
            if ($this->hasDescriptionColumn) {
                error_log("Adicionando coluna description");
                $fields[] = 'description';
                $values[] = $data['description'] ?? null;
                $placeholders[] = '?';
            }
            
            if ($this->hasColorColumn) {
                error_log("Adicionando coluna color");
                $fields[] = 'color';
                $values[] = $data['color'] ?? '#000000';
                $placeholders[] = '?';
            }
            
            $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
            error_log("SQL Query: " . $sql);
            error_log("Valores: " . print_r($values, true));
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Erro ao preparar query: " . print_r($this->db->errorInfo(), true));
                throw new Exception("Erro ao preparar query: " . implode(" - ", $this->db->errorInfo()));
            }
            
            $result = $stmt->execute($values);
            if (!$result) {
                error_log("Erro ao executar query: " . print_r($stmt->errorInfo(), true));
                throw new Exception("Erro ao executar query: " . implode(" - ", $stmt->errorInfo()));
            }
            
            error_log("Tipo criado com sucesso!");
            return true;
        } catch (Exception $e) {
            error_log("Erro em TypeModel::create: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public function getAll($table) {
        $this->setTable($table);
        $sql = "SELECT * FROM {$this->table}";
        if ($this->hasActiveColumn) {
            $sql .= " WHERE active = 1";
        }
        $sql .= " ORDER BY name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllByType($type) {
        return $this->getAll($type);
    }

    public function getById($id, $table) {
        $this->setTable($table);
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        if ($this->hasActiveColumn) {
            $sql .= " AND active = 1";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data, $type) {
        try {
            $this->setTable($type);
            
            $fields = ['name = ?'];
            $values = [$data['name']];
            
            if ($this->hasDescriptionColumn) {
                $fields[] = 'description = ?';
                $values[] = $data['description'] ?? null;
            }
            
            if ($this->hasColorColumn) {
                $fields[] = 'color = ?';
                $values[] = $data['color'] ?? '#000000';
            }
            
            $values[] = $id; // Para o WHERE id = ?
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
            error_log("SQL Query: " . $sql);
            error_log("Values: " . print_r($values, true));
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . print_r($this->db->errorInfo(), true));
                return false;
            }
            
            $result = $stmt->execute($values);
            if (!$result) {
                error_log("Execute failed: " . print_r($stmt->errorInfo(), true));
                return false;
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error in TypeModel::update: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function delete($id, $type) {
        try {
            $this->setTable($type);
            
            $sql = $this->hasActiveColumn 
                ? "UPDATE {$this->table} SET active = 0 WHERE id = ?"
                : "DELETE FROM {$this->table} WHERE id = ?";
                
            error_log("SQL Query: " . $sql);
            error_log("ID: " . $id);
            
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                error_log("Prepare failed: " . print_r($this->db->errorInfo(), true));
                return false;
            }
            
            $result = $stmt->execute([$id]);
            if (!$result) {
                error_log("Execute failed: " . print_r($stmt->errorInfo(), true));
                return false;
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error in TypeModel::delete: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }
}
