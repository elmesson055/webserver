# Correções Necessárias

## 1. Autenticação e Autorização

### Login
```sql
-- Adicionar campo para controle de tentativas de login
ALTER TABLE users
ADD COLUMN login_attempts INT DEFAULT 0,
ADD COLUMN last_failed_login TIMESTAMP NULL,
ADD COLUMN locked_until TIMESTAMP NULL;
```

### Formulário de Login
- Adicionar CSRF token
- Padronizar campo de usuário (email)
- Implementar rate limiting
- Adicionar captcha após 3 tentativas falhas

### Rotas
- Padronizar rotas de autenticação:
  - `/auth/login` para POST
  - `/login` para GET
  - `/auth/logout` para POST
  - `/auth/forgot-password` para ambos
  - `/auth/reset-password` para ambos

## 2. Segurança

### Senhas e Tokens
- Remover senhas hardcoded dos scripts SQL
- Aumentar entropia do token de reset:
```php
// Antes
$token = bin2hex(random_bytes(32));

// Depois
$token = bin2hex(random_bytes(64)) . '_' . time();
```

### Sanitização
- Implementar middleware de sanitização
- Usar prepared statements consistentemente
- Escapar output HTML

### SQL Injection
- Revisar e corrigir queries vulneráveis:
```php
// Antes
$sql = "SELECT * FROM users WHERE username = '$username'";

// Depois
$stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
```

## 3. Banco de Dados

### Migrations
```sql
-- Padronizar estrutura da tabela users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    department ENUM('Transportes', 'Custos', 'Financeiro') NOT NULL DEFAULT 'Custos',
    role_id INT NOT NULL,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    login_attempts INT NOT NULL DEFAULT 0,
    last_login TIMESTAMP NULL,
    last_failed_login TIMESTAMP NULL,
    locked_until TIMESTAMP NULL,
    password_reset_token VARCHAR(255) NULL,
    password_reset_expires_at TIMESTAMP NULL,
    created_by INT NULL,
    updated_by INT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id),
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_active (active),
    INDEX idx_department (department),
    INDEX idx_role (role_id),
    INDEX idx_reset_token (password_reset_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Índices
```sql
-- Adicionar índices faltantes
CREATE INDEX idx_last_login ON users(last_login);
CREATE INDEX idx_created_at ON users(created_at);
CREATE INDEX idx_department_active ON users(department, active);
```

## 4. Integrações

### Autenticação
```php
class Auth {
    private static $instance = null;
    private $db;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function attempt($email, $password) {
        // Verificar tentativas de login
        if ($this->isLockedOut($email)) {
            throw new Exception('Conta bloqueada. Tente novamente mais tarde.');
        }
        
        // Buscar usuário
        $user = $this->findByEmail($email);
        
        if (!$user || !password_verify($password, $user->password)) {
            $this->incrementLoginAttempts($email);
            return false;
        }
        
        // Resetar tentativas de login
        $this->resetLoginAttempts($email);
        
        // Registrar login
        $this->logLogin($user->id);
        
        return $user;
    }
    
    private function isLockedOut($email) {
        $stmt = $this->db->prepare("
            SELECT locked_until 
            FROM users 
            WHERE email = ? AND locked_until > NOW()
        ");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }
    
    private function incrementLoginAttempts($email) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET login_attempts = login_attempts + 1,
                last_failed_login = NOW(),
                locked_until = CASE 
                    WHEN login_attempts >= 4 THEN DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                    ELSE NULL 
                END
            WHERE email = ?
        ");
        $stmt->execute([$email]);
    }
}
```

### Roles e Permissions
```php
class Authorization {
    public function hasPermission($userId, $permission) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM users u
            JOIN user_roles ur ON u.id = ur.user_id
            JOIN role_permissions rp ON ur.role_id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            WHERE u.id = ? AND p.name = ? AND u.active = 1
        ");
        $stmt->execute([$userId, $permission]);
        return $stmt->fetchColumn() > 0;
    }
}
```

### Logs de Auditoria
```sql
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(50) NOT NULL,
    table_name VARCHAR(50) NOT NULL,
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## 5. Boas Práticas

### Sessão
- Implementar regeneração de ID de sessão após login
- Adicionar verificação de IP
- Implementar timeout de sessão

### Logs
- Implementar logging estruturado
- Adicionar logs de segurança
- Configurar rotação de logs

### Cache
- Implementar cache de consultas frequentes
- Adicionar cache de sessão
- Configurar invalidação de cache

## 6. Próximos Passos

1. Criar script de migração para aplicar todas as correções
2. Implementar testes automatizados
3. Configurar ambiente de homologação
4. Documentar processo de deploy
5. Treinar equipe nas novas práticas de segurança
