<?php

class SystemSetting {
    private $db;
    private $defaultSettings = [
        'login_bg_image' => '/assets/images/login-bg.jpg',
        'login_logo' => '/assets/images/logo.png',
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => '587',
        'smtp_username' => '',
        'smtp_password' => '',
        'smtp_encryption' => 'tls',
        'smtp_from_email' => 'noreply@custoextras.com.br',
        'smtp_from_name' => 'Sistema Custo Extras'
    ];

    public function __construct($db) {
        $this->db = $db;
        $this->ensureDefaultSettings();
    }

    private function ensureDefaultSettings() {
        try {
            foreach ($this->defaultSettings as $key => $value) {
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM system_settings WHERE setting_key = ?");
                $stmt->execute([$key]);
                if ($stmt->fetchColumn() == 0) {
                    $this->set($key, $value);
                }
            }
        } catch (PDOException $e) {
            error_log("Error ensuring default settings: " . $e->getMessage());
        }
    }

    public function get($key) {
        try {
            $stmt = $this->db->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['setting_value'] : ($this->defaultSettings[$key] ?? null);
        } catch (PDOException $e) {
            error_log("Error getting setting {$key}: " . $e->getMessage());
            return $this->defaultSettings[$key] ?? null;
        }
    }

    public function update($key, $value) {
        try {
            // Se for uma senha, criptografa antes de salvar
            if (strpos($key, 'password') !== false && !empty($value)) {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }

            $stmt = $this->db->prepare("
                INSERT INTO system_settings (setting_key, setting_value, updated_at, updated_by) 
                VALUES (?, ?, NOW(), ?) 
                ON DUPLICATE KEY UPDATE 
                    setting_value = ?,
                    updated_at = NOW(),
                    updated_by = ?
            ");
            
            $userId = $_SESSION['user']['id'] ?? null;
            return $stmt->execute([$key, $value, $userId, $value, $userId]);
        } catch (PDOException $e) {
            error_log("Error updating {$key}: " . $e->getMessage());
            return false;
        }
    }

    public function getAll() {
        try {
            $stmt = $this->db->query("
                SELECT s.*, u.name as updated_by_name 
                FROM system_settings s 
                LEFT JOIN users u ON s.updated_by = u.id 
                ORDER BY s.setting_key
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all settings: " . $e->getMessage());
            return [];
        }
    }

    public function uploadImage($file, $key) {
        try {
            $uploadDir = __DIR__ . '/../../public/assets/images/';
            
            // Criar diretório se não existir
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Validar tipo MIME
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception('Tipo de arquivo não permitido. Use apenas: JPG, PNG ou GIF');
            }

            // Validar tamanho (máximo 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                throw new Exception('Arquivo muito grande. Tamanho máximo: 5MB');
            }

            // Gerar nome único
            $extension = $this->getExtensionFromMimeType($mimeType);
            $filename = $key . '_' . time() . '.' . $extension;
            $filepath = $uploadDir . $filename;

            // Fazer upload
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new Exception('Erro ao fazer upload do arquivo');
            }

            // Atualizar no banco
            $publicPath = '/assets/images/' . $filename;
            $this->update($key, $publicPath);

            return $publicPath;
        } catch (Exception $e) {
            error_log("Error uploading image for {$key}: " . $e->getMessage());
            throw $e;
        }
    }

    private function getExtensionFromMimeType($mimeType) {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif'
        ];
        return $extensions[$mimeType] ?? 'jpg';
    }

    public function getSmtpSettings() {
        return [
            'host' => $this->get('smtp_host'),
            'port' => $this->get('smtp_port'),
            'username' => $this->get('smtp_username'),
            'password' => $this->get('smtp_password'),
            'encryption' => $this->get('smtp_encryption'),
            'from_email' => $this->get('smtp_from_email'),
            'from_name' => $this->get('smtp_from_name')
        ];
    }
}
