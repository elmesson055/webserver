# Módulo de Segurança

## Visão Geral
O módulo de Segurança gerencia todos os aspectos de segurança do sistema, incluindo autenticação, autorização, auditoria e conformidade.

## Tabela: `seguranca_usuarios`

Gerencia usuários e suas credenciais.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único do usuário |
| username | VARCHAR(50) | Nome de usuário |
| email | VARCHAR(255) | Email do usuário |
| senha_hash | VARCHAR(255) | Hash da senha |
| salt | VARCHAR(32) | Salt para senha |
| status | VARCHAR(20) | Status da conta |
| ultimo_login | TIMESTAMP | Último login |
| tentativas_login | INT | Tentativas de login |
| bloqueado_ate | TIMESTAMP | Bloqueio temporário |
| mfa_ativo | BOOLEAN | MFA ativado |
| mfa_secret | VARCHAR(32) | Segredo MFA |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

### Índices
- PRIMARY KEY (`id`)
- UNIQUE KEY (`username`)
- UNIQUE KEY (`email`)
- INDEX (`status`)

## Tabela: `seguranca_permissoes`

Define permissões do sistema.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| nome | VARCHAR(100) | Nome da permissão |
| descricao | TEXT | Descrição da permissão |
| modulo | VARCHAR(50) | Módulo relacionado |
| acoes | JSON | Ações permitidas |
| recursos | JSON | Recursos afetados |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

### Índices
- PRIMARY KEY (`id`)
- UNIQUE KEY (`nome`)
- INDEX (`modulo`)

## Tabela: `seguranca_grupos`

Grupos de usuários e suas permissões.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| nome | VARCHAR(100) | Nome do grupo |
| descricao | TEXT | Descrição do grupo |
| permissoes | JSON | Lista de permissões |
| hierarquia | INT | Nível hierárquico |
| ativo | BOOLEAN | Status do grupo |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: `seguranca_sessoes`

Gerencia sessões ativas.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | VARCHAR(64) | ID da sessão |
| usuario_id | INT | ID do usuário |
| token | VARCHAR(255) | Token de acesso |
| refresh_token | VARCHAR(255) | Token de refresh |
| ip | VARCHAR(45) | IP do cliente |
| user_agent | TEXT | User agent |
| dados | JSON | Dados da sessão |
| expira_em | TIMESTAMP | Data de expiração |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: `seguranca_auditoria`

Registra eventos de auditoria.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | BIGINT | Identificador único |
| usuario_id | INT | ID do usuário |
| acao | VARCHAR(50) | Ação realizada |
| modulo | VARCHAR(50) | Módulo afetado |
| recurso_tipo | VARCHAR(50) | Tipo do recurso |
| recurso_id | VARCHAR(50) | ID do recurso |
| dados_antigos | JSON | Estado anterior |
| dados_novos | JSON | Novo estado |
| ip | VARCHAR(45) | IP do cliente |
| created_at | TIMESTAMP | Data do evento |

## Funcionalidades Principais

### 1. Autenticação
- Multi-fator (MFA)
- Single Sign-On (SSO)
- OAuth/OIDC
- LDAP/AD

### 2. Autorização
- RBAC (Role-Based)
- ABAC (Attribute-Based)
- Políticas dinâmicas
- Hierarquia de grupos

### 3. Auditoria
- Logging completo
- Trilha de auditoria
- Alertas de segurança
- Relatórios

## Recursos Técnicos

### 1. Criptografia
- AES-256
- RSA
- Hashing (bcrypt)
- Salt único

### 2. Tokens
- JWT
- Refresh tokens
- Access tokens
- API keys

### 3. Segurança
- Rate limiting
- Bloqueio de conta
- Validação de senha
- Lista negra de tokens

## Conformidade

### 1. Padrões
- LGPD
- GDPR
- PCI DSS
- ISO 27001

### 2. Privacidade
- Mascaramento
- Criptografia
- Anonimização
- Retenção

### 3. Controles
- Logs
- Backups
- Monitoramento
- Incidentes

## Boas Práticas

### 1. Senhas
- Política forte
- Rotação periódica
- Hash + Salt
- Histórico

### 2. Sessões
- Timeout
- Renovação
- Single session
- Device tracking

### 3. Auditoria
- Logs detalhados
- Não-repúdio
- Integridade
- Retenção
