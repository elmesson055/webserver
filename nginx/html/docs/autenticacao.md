# Sistema de Autenticação

## Descrição
Este documento descreve o sistema de autenticação, incluindo login, logout e gerenciamento de sessões.

## Componentes

### 1. Login
- Arquivo: `/login.php`
- Estilo: `/assets/css/login.css`
- Template: `/includes/login_template.php`

### 2. Sessão
- Arquivo: `/includes/session.php`
- Tempo de expiração: 30 minutos
- Regeneração de ID: A cada 15 minutos

## Fluxo de Autenticação

### 1. Login
```php
// Exemplo de uso
require_once 'includes/session.php';

if ($auth->login($username, $password)) {
    redirect('dashboard.php');
} else {
    show_error('Credenciais inválidas');
}
```

### 2. Verificação de Sessão
```php
// No início de cada página protegida
require_once 'includes/session.php';

if (!$auth->is_authenticated()) {
    redirect('login.php');
}
```

### 3. Logout
```php
// Processo de logout
$auth->logout();
redirect('login.php');
```

## Segurança

### Proteções Implementadas
1. **Contra Força Bruta**
   - Limite de 5 tentativas por 15 minutos
   - Bloqueio temporário após exceder limite

2. **Contra Session Hijacking**
   - Regeneração de ID de sessão
   - Validação de IP e User Agent
   - Uso de tokens CSRF

3. **Contra SQL Injection**
   - Uso de Prepared Statements
   - Sanitização de inputs

## Customização

### Mensagens de Erro
```php
// Arquivo: /includes/messages.php
$auth_messages = [
    'invalid_credentials' => 'Usuário ou senha inválidos',
    'account_locked' => 'Conta bloqueada temporariamente',
    'session_expired' => 'Sessão expirada'
];
```

### Tempo de Sessão
```php
// Arquivo: /config/config.php
define('SESSION_LIFETIME', 1800); // 30 minutos
define('SESSION_REGENERATE', 900); // 15 minutos
```

## Logs e Monitoramento

### Eventos Registrados
1. Tentativas de login (sucesso/falha)
2. Logouts
3. Bloqueios de conta
4. Regenerações de sessão

### Formato do Log
```
[DATA] [NÍVEL] [IP] [USUÁRIO] MENSAGEM
```

## Manutenção

### Rotinas Periódicas
1. Limpeza de sessões expiradas
2. Rotação de logs
3. Verificação de tentativas de invasão

### Backup
1. Dados de usuário
2. Logs de autenticação
3. Configurações de segurança

## Troubleshooting

### Problemas Comuns

1. **Sessão Expirando Rapidamente**
   - Verificar SESSION_LIFETIME
   - Confirmar configurações do PHP
   - Checar problemas de rede

2. **Bloqueio de Conta**
   - Verificar logs de tentativas
   - Confirmar IP do usuário
   - Resetar contador de tentativas

## Suporte
Para dúvidas ou problemas:
- Email: elmesson@outlook.com
- Tel: (38) 98824-9631
