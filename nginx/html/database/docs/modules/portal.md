# Módulo de Portal

## Visão Geral
O módulo Portal gerencia a interface externa do sistema, permitindo acesso a fornecedores, clientes e parceiros.

## Tabela: `portal_usuarios`

Gerencia os usuários externos que acessam o portal.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único do usuário do portal |
| tipo_usuario | ENUM | Tipo do usuário (Fornecedor, Cliente, Parceiro) |
| entidade_id | INT | ID da entidade relacionada (fornecedor_id, cliente_id, etc) |
| nome | VARCHAR(255) | Nome completo do usuário |
| email | VARCHAR(255) | Email para acesso |
| senha_hash | VARCHAR(255) | Hash da senha |
| ultimo_acesso | TIMESTAMP | Data/hora do último acesso |
| token_acesso | VARCHAR(100) | Token de acesso atual |
| token_expiracao | TIMESTAMP | Data/hora de expiração do token |
| status | ENUM | Status do usuário (Ativo, Inativo, Bloqueado) |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da última atualização |

### Índices
- PRIMARY KEY (`id`)
- UNIQUE KEY (`email`)
- INDEX (`tipo_usuario`, `entidade_id`)
- INDEX (`status`)

## Tabela: `portal_acessos`

Registra os acessos e ações realizadas no portal.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único do acesso |
| usuario_id | INT | ID do usuário do portal |
| data_hora | TIMESTAMP | Data/hora do acesso |
| ip | VARCHAR(45) | Endereço IP do acesso |
| user_agent | TEXT | Informações do navegador |
| acao | VARCHAR(50) | Ação realizada |
| modulo | VARCHAR(50) | Módulo acessado |
| detalhes | JSON | Detalhes adicionais da ação |

### Índices
- PRIMARY KEY (`id`)
- INDEX (`usuario_id`)
- INDEX (`data_hora`)
- INDEX (`acao`)

## Tabela: `portal_documentos`

Gerencia documentos disponibilizados no portal.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único do documento |
| tipo_usuario | ENUM | Tipo de usuário que pode acessar |
| titulo | VARCHAR(255) | Título do documento |
| descricao | TEXT | Descrição do documento |
| arquivo_path | VARCHAR(255) | Caminho do arquivo |
| data_disponibilizacao | TIMESTAMP | Data de disponibilização |
| data_expiracao | TIMESTAMP | Data de expiração (opcional) |
| downloads | INT | Contador de downloads |
| status | ENUM | Status do documento |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da última atualização |

### Índices
- PRIMARY KEY (`id`)
- INDEX (`tipo_usuario`)
- INDEX (`status`)
- INDEX (`data_disponibilizacao`, `data_expiracao`)

## Funcionalidades Principais

### 1. Autenticação e Autorização
- Login com email e senha
- Recuperação de senha
- Controle de sessão
- Níveis de acesso por tipo de usuário

### 2. Gestão de Documentos
- Upload e download de documentos
- Controle de versões
- Histórico de acessos
- Notificações de novos documentos

### 3. Comunicação
- Sistema de mensagens
- Notificações automáticas
- Avisos e comunicados
- Chat de suporte

### 4. Dashboards
- Visão geral personalizada
- Indicadores relevantes
- Gráficos e relatórios
- Status de processos

## Integrações

### 1. Sistema Principal
- Sincronização de dados
- Compartilhamento de documentos
- Atualização de status

### 2. Notificações
- Emails automáticos
- Alertas SMS
- Notificações push

### 3. APIs Externas
- Validação de documentos
- Consultas em tempo real
- Integrações bancárias

## Segurança

### 1. Controle de Acesso
- Autenticação em duas etapas
- Políticas de senha
- Bloqueio por tentativas inválidas

### 2. Proteção de Dados
- Criptografia de dados sensíveis
- Logs de auditoria
- Backup automático

### 3. Conformidade
- LGPD
- Termos de uso
- Políticas de privacidade
