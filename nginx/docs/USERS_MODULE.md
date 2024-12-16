# Documentação Técnica - Módulo de Usuários

## Visão Geral
O módulo de Usuários é responsável pelo gerenciamento de usuários do sistema, incluindo criação, edição, listagem e controle de status. Este módulo é fundamental para o controle de acesso e segurança do sistema.

## Estrutura do Módulo

### 1. Modelo (app/Models/User.php)
```php
namespace App\Models;
class User extends Model
```

#### Atributos
- `protected $table = 'users'`: Nome da tabela no banco de dados
- `protected $primaryKey = 'id'`: Chave primária da tabela
- `protected $fillable`: Lista de campos que podem ser preenchidos em massa

#### Métodos Principais
- `getAll($filters)`: Busca usuários com filtros
- `create($data)`: Cria novo usuário
- `update($id, $data)`: Atualiza usuário existente
- `toggleStatus($id)`: Alterna status do usuário
- `getPermissions($userId)`: Busca permissões do usuário
- `validate($data, $id)`: Valida dados do usuário

### 2. Controller (app/Controllers/UsersController.php)
```php
namespace App\Controllers;
class UsersController extends Controller
```

#### Endpoints
- `GET /config/usuarios`: Lista de usuários
- `GET /config/usuarios/novo`: Formulário de criação
- `GET /config/usuarios/editar/{id}`: Formulário de edição
- `POST /api/users`: Cria usuário
- `PUT /api/users/{id}`: Atualiza usuário
- `POST /api/users/{id}/toggle-status`: Alterna status

#### Permissões Necessárias
- `view_users`: Visualizar lista de usuários
- `manage_users`: Criar, editar e alterar status

### 3. Views

#### Lista de Usuários (views/config/usuarios/index.php)
- DataTables para listagem paginada
- Filtros por:
  - Busca textual
  - Departamento
  - Perfil
  - Status
- Ações disponíveis:
  - Criar novo usuário
  - Editar usuário
  - Ativar/desativar usuário

#### Formulário (views/config/usuarios/form.php)
- Campos:
  - Nome (obrigatório)
  - Email (obrigatório, único)
  - Nome de usuário (obrigatório, único)
  - Departamento (obrigatório)
  - Perfil (obrigatório)
  - Status
  - Senha (obrigatório na criação)
- Validações client-side com jQuery Validate
- Integração com API via AJAX

## Banco de Dados

### Tabela: users
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    department VARCHAR(50) NOT NULL,
    role_id INT NOT NULL,
    active TINYINT(1) DEFAULT 1,
    created_by INT,
    updated_by INT,
    created_at DATETIME,
    updated_at DATETIME,
    last_login DATETIME,
    password_reset_token VARCHAR(100),
    password_reset_expires_at DATETIME,
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);
```

### Tabela: roles
```sql
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at DATETIME,
    updated_at DATETIME
);
```

### Tabela: role_permissions
```sql
CREATE TABLE role_permissions (
    role_id INT,
    permission_id INT,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id)
);
```

## JavaScript e AJAX

### DataTables
```javascript
const table = $('#users-table').DataTable({
    ajax: {
        url: '/api/users',
        data: function(d) {
            d.search = $('#search').val();
            d.department = $('#filter-department').val();
            d.role = $('#filter-role').val();
            d.status = $('#filter-status').val();
        }
    },
    columns: [
        { data: 'name' },
        { data: 'username' },
        { data: 'email' },
        { data: 'department' },
        { data: 'role_name' },
        { data: 'status_badge' },
        { data: 'actions' }
    ]
});
```

### Validação de Formulário
```javascript
$('#userForm').validate({
    rules: {
        name: 'required',
        email: {
            required: true,
            email: true
        },
        username: 'required',
        department: 'required',
        role_id: 'required',
        password: {
            required: !isEdit,
            minlength: 8
        }
    }
});
```

## Segurança

### Validações
1. **Server-side**:
   - Campos obrigatórios
   - Email único
   - Username único
   - Força da senha
   - Permissões do usuário

2. **Client-side**:
   - Validação de formulário
   - Confirmação de ações críticas
   - Feedback visual

### Proteção
- Uso de prepared statements
- Hash de senha com `password_hash()`
- Verificação de permissões
- Proteção contra CSRF
- Sanitização de inputs

## Tratamento de Erros

### Backend
```php
try {
    // Operação
} catch (\Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
```

### Frontend
```javascript
$.ajax({
    // ...
    error: function(xhr) {
        toastr.error(xhr.responseJSON?.message || 'Erro ao processar requisição');
    }
});
```

## Dependências

### Backend
- PHP 7.4+
- MySQL 5.7+
- PDO Extension
- PHP Password Hashing

### Frontend
- jQuery 3.6+
- DataTables 1.10+
- jQuery Validate
- Bootstrap 4.6+
- Font Awesome 5+
- Toastr

## Manutenção

### Logs
- Erros são registrados em `/logs/[data].log`
- Formato: `[data][nível] mensagem`
- Níveis: info, warning, error

### Backups
- Backup diário do banco às 00:00
- Retenção de 30 dias
- Localização: `/backups/[data]/`

### Cache
- Cache de consultas: 1 hora
- Cache de permissões: 24 horas
- Limpo automaticamente após updates

## Troubleshooting

### Problemas Comuns

1. **Erro de Login**
   - Verificar credenciais
   - Confirmar status do usuário
   - Checar logs de erro

2. **Permissões Negadas**
   - Verificar role do usuário
   - Confirmar permissões da role
   - Limpar cache de permissões

3. **Erro ao Salvar**
   - Validar dados únicos
   - Checar conexão com banco
   - Verificar logs de erro

### Soluções Rápidas
1. Limpar cache: `php artisan cache:clear`
2. Recarregar permissões: `php artisan permissions:reload`
3. Verificar status: `php artisan user:check {id}`

## Futuras Implementações

### Planejadas
1. Autenticação 2FA
2. Integração com LDAP
3. Auditoria de ações
4. Recuperação de senha por SMS

### Em Consideração
1. SSO (Single Sign-On)
2. Perfis personalizados
3. Importação em massa
4. API completa RESTful
