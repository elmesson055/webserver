# Sistema de Autenticação

Este documento descreve a implementação e uso do sistema de autenticação.

## Estrutura do Módulo

```
/app/modules/auth/
├── controllers/
│   └── AuthController.php    # Controlador principal de autenticação
├── models/
│   └── UserModel.php         # Modelo para operações com usuários
├── middleware/
│   └── AuthMiddleware.php    # Middleware para proteção de rotas
├── views/
│   ├── login.php            # Página de login
│   └── errors/
│       └── 403.php          # Página de erro de acesso negado
└── index.php                # Redirecionamento para login
```

## Componentes Principais

### 1. Model (UserModel.php)

Responsável por operações no banco de dados:
- Autenticação de usuários
- Atualização de último login
- Consultas de usuário

```php
use App\Modules\Auth\Models\UserModel;
$userModel = new UserModel();
$user = $userModel->authenticate($username, $password);
```

### 2. Controller (AuthController.php)

Gerencia a lógica de autenticação:
- Validação de credenciais
- Gerenciamento de sessão
- Controle de login/logout

```php
use App\Modules\Auth\Controllers\AuthController;
$auth = new AuthController();
$result = $auth->login($username, $password);
```

### 3. Middleware (AuthMiddleware.php)

Protege rotas e verifica permissões:
- Verificação de autenticação
- Controle de acesso por papel
- Redirecionamento para login

```php
use App\Modules\Auth\Middleware\AuthMiddleware;

// No início de páginas protegidas:
$auth = new AuthMiddleware();
$auth->handle(); // Verifica apenas autenticação
// ou
$auth->requireRole('admin'); // Verifica papel específico
```

## Uso do Sistema

### 1. Proteger uma Página

Para proteger uma página que requer autenticação:

```php
<?php
require_once dirname(dirname(dirname(__FILE__))) . '/auth/middleware/AuthMiddleware.php';

use App\Modules\Auth\Middleware\AuthMiddleware;

$auth = new AuthMiddleware();
$auth->handle();

// Resto do código da página
?>
```

### 2. Proteger com Papel Específico

Para páginas que requerem um papel específico:

```php
<?php
require_once dirname(dirname(dirname(__FILE__))) . '/auth/middleware/AuthMiddleware.php';

use App\Modules\Auth\Middleware\AuthMiddleware;

$auth = new AuthMiddleware();
$auth->requireRole('admin'); // ou 'gerente', 'operador', etc.

// Resto do código da página
?>
```

### 3. Verificar Estado da Autenticação

Para verificar se um usuário está autenticado:

```php
use App\Modules\Auth\Controllers\AuthController;

$auth = new AuthController();
if ($auth->isAuthenticated()) {
    // Usuário está logado
}
```

## Sessão do Usuário

### Dados Armazenados
```php
$_SESSION['user_id']        // ID do usuário
$_SESSION['username']       // Nome de usuário
$_SESSION['user_type']      // Tipo/papel do usuário
$_SESSION['last_activity']  // Timestamp da última atividade
```

### Configurações de Segurança

- Sessão expira após 30 minutos de inatividade
- Cookie de sessão com httpOnly
- Proteção contra fixação de sessão
- Regeneração de ID após login

## Banco de Dados

### Tabela de Usuários
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

## Segurança

### Práticas Implementadas
1. Senhas hasheadas com `password_hash()`
2. Proteção contra SQL Injection com prepared statements
3. Proteção contra XSS com `htmlspecialchars()`
4. Sessões seguras com configurações apropriadas
5. Validação de inputs
6. Logs de tentativas de login

### Recomendações
1. Usar HTTPS em produção
2. Implementar limite de tentativas de login
3. Implementar autenticação de dois fatores
4. Realizar auditorias regulares
5. Manter bibliotecas atualizadas

## Tratamento de Erros

O sistema fornece mensagens de erro amigáveis para:
- Credenciais inválidas
- Usuário inativo
- Sessão expirada
- Acesso não autorizado

## Logs

Eventos importantes são registrados:
- Tentativas de login (sucesso/falha)
- Logouts
- Alterações de senha
- Acessos não autorizados

## Manutenção

### Tarefas Regulares
1. Limpar sessões antigas
2. Verificar logs de erro
3. Atualizar senhas fracas
4. Revisar permissões de usuários

### Backups
1. Backup diário da tabela de usuários
2. Backup dos logs de acesso
3. Backup das configurações

## Suporte

Para problemas comuns:
1. Verificar logs de erro em `/logs/auth/`
2. Validar configurações de sessão
3. Confirmar conexão com banco de dados
4. Verificar permissões de arquivos

## Desenvolvimento

### Adicionar Novo Tipo de Usuário
1. Adicionar novo tipo na enum da tabela
2. Atualizar middleware de autorização
3. Adicionar novas regras de acesso
4. Atualizar documentação

### Modificar Autenticação
1. Editar `UserModel::authenticate()`
2. Atualizar validações no controller
3. Modificar middleware conforme necessário
4. Testar todas as rotas afetadas
