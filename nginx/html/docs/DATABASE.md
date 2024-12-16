# Documentação do Banco de Dados

## Configuração de Conexão

O sistema utiliza MySQL como banco de dados principal. As configurações de conexão estão definidas no arquivo `config/database.php`.

### Credenciais

- **Host:** localhost
- **Database:** logistica_transportes
- **User:** logistica_admin
- **Password:** LOg1st1ca2024!
- **Port:** 3306
- **Charset:** utf8mb4

## Estrutura do Banco de Dados

### Tabela: usuarios

Tabela responsável por armazenar os dados dos usuários do sistema.

```sql
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_usuario VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nome_completo VARCHAR(100) NOT NULL,
    tipo_usuario ENUM('admin', 'gerente', 'operador') NOT NULL,
    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
    ultimo_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### Campos
- `id`: Identificador único do usuário
- `nome_usuario`: Nome de usuário para login (único)
- `email`: Email do usuário (único)
- `password_hash`: Senha criptografada usando password_hash()
- `nome_completo`: Nome completo do usuário
- `tipo_usuario`: Tipo/papel do usuário (admin, gerente, operador)
- `status`: Status do usuário (ativo, inativo)
- `ultimo_login`: Data e hora do último login
- `created_at`: Data e hora de criação do registro
- `updated_at`: Data e hora da última atualização

## Conexão com o Banco de Dados

A conexão é gerenciada pela classe `Database` em `app/Core/Database.php`, que implementa o padrão Singleton para garantir uma única instância de conexão.

### Uso da Classe Database

```php
use App\Core\Database;

// Obter instância da conexão
$db = Database::getInstance();

// Executar query
$stmt = $db->prepare("SELECT * FROM usuarios WHERE status = :status");
$stmt->execute(['status' => 'ativo']);
```

## Segurança

1. **Senhas**
   - Todas as senhas são armazenadas usando `password_hash()` com algoritmo padrão
   - Nunca armazenamos senhas em texto plano

2. **Prevenção contra SQL Injection**
   - Uso de prepared statements para todas as queries
   - Parâmetros sempre são vinculados usando PDO

3. **Charset e Collation**
   - Uso de utf8mb4 para suporte completo a Unicode
   - Collation padrão: utf8mb4_unicode_ci

## Manutenção

### Backup
- Realizar backup diário do banco de dados
- Manter backups por pelo menos 30 dias
- Script de backup em: `scripts/backup_database.sh`

### Monitoramento
- Logs de erro em: `/var/log/mysql/error.log`
- Monitorar espaço em disco regularmente
- Verificar performance das queries através do slow query log

## Troubleshooting

### Problemas Comuns

1. **Erro de Conexão**
   ```
   SQLSTATE[HY000] [1045] Access denied for user
   ```
   - Verificar credenciais no arquivo `config/database.php`
   - Confirmar se o usuário tem permissões corretas no MySQL

2. **Timeout de Conexão**
   - Verificar configuração de `max_connections` no MySQL
   - Verificar se há conexões zombies

### Debug

Para debugar problemas de conexão, use o script:
```php
require_once 'app/modules/tests/auth/test_database.php';
```

## Contato

Para problemas com o banco de dados, contatar:
- DBA: administrador@sistema.com
- Suporte: suporte@sistema.com
