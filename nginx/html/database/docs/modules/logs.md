# Módulo de Logs

O módulo de Logs é responsável pelo registro e monitoramento de todas as atividades e eventos importantes do sistema. Ele fornece recursos para rastreamento, auditoria e análise de eventos.

## Tabelas Principais

### logs_sistema

Registra eventos gerais do sistema.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | Identificador único do log |
| tipo | VARCHAR(50) | Tipo do evento (error, warning, info, debug) |
| origem | VARCHAR(100) | Origem/módulo que gerou o evento |
| mensagem | TEXT | Descrição detalhada do evento |
| dados | JSON | Dados adicionais relacionados ao evento |
| created_at | TIMESTAMP | Data/hora do registro |
| ip_address | VARCHAR(45) | Endereço IP de origem |
| user_agent | VARCHAR(255) | Informações do navegador/cliente |

### logs_acesso

Registra acessos e tentativas de acesso ao sistema.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | Identificador único do log |
| usuario_id | INT | ID do usuário (se autenticado) |
| tipo | VARCHAR(50) | Tipo de acesso (login, logout, failed_login) |
| ip_address | VARCHAR(45) | Endereço IP de origem |
| user_agent | VARCHAR(255) | Informações do navegador/cliente |
| created_at | TIMESTAMP | Data/hora do registro |
| detalhes | JSON | Informações adicionais sobre o acesso |

### logs_auditoria

Registra alterações em registros do banco de dados.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | Identificador único do log |
| tabela | VARCHAR(100) | Nome da tabela alterada |
| registro_id | INT | ID do registro alterado |
| acao | VARCHAR(20) | Tipo de ação (insert, update, delete) |
| dados_anteriores | JSON | Estado anterior do registro |
| dados_novos | JSON | Novo estado do registro |
| usuario_id | INT | ID do usuário que realizou a alteração |
| created_at | TIMESTAMP | Data/hora do registro |

### logs_api

Registra chamadas à API do sistema.

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | Identificador único do log |
| metodo | VARCHAR(10) | Método HTTP (GET, POST, etc) |
| endpoint | VARCHAR(255) | Endpoint da API acessado |
| parametros | JSON | Parâmetros da requisição |
| resposta | JSON | Resposta da API |
| status_code | INT | Código de status HTTP |
| tempo_resposta | INT | Tempo de resposta em milissegundos |
| ip_address | VARCHAR(45) | Endereço IP de origem |
| created_at | TIMESTAMP | Data/hora do registro |

## Funcionalidades Principais

### Registro de Eventos

- Captura automática de erros e exceções do sistema
- Registro de ações importantes dos usuários
- Monitoramento de performance e tempos de resposta
- Rastreamento de alterações em dados sensíveis

### Consulta e Análise

- Interface para pesquisa e filtro de logs
- Exportação de logs para análise externa
- Dashboards com métricas e indicadores
- Alertas para eventos críticos

### Retenção e Arquivamento

- Políticas de retenção por tipo de log
- Arquivamento automático de logs antigos
- Backup periódico dos registros
- Limpeza automática conforme regras definidas

### Segurança

- Proteção contra manipulação de logs
- Criptografia de dados sensíveis
- Controle de acesso baseado em perfis
- Auditoria de consultas aos logs

## Integrações

- Integração com sistema de monitoramento
- Exportação para ferramentas de análise
- Notificações via email/SMS para eventos críticos
- Webhooks para sistemas externos

## Configurações

### Níveis de Log

- ERROR: Erros que afetam o funcionamento do sistema
- WARNING: Alertas sobre possíveis problemas
- INFO: Informações sobre operações normais
- DEBUG: Informações detalhadas para desenvolvimento

### Retenção de Dados

- Logs de sistema: 30 dias
- Logs de acesso: 90 dias
- Logs de auditoria: 5 anos
- Logs de API: 15 dias

### Monitoramento

- Verificação periódica de espaço em disco
- Alertas de volume anormal de erros
- Monitoramento de performance
- Verificação de integridade dos logs
