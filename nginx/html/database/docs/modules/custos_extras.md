# Módulo: Custos Extras

## Tabela: custos_extras_registros_iniciais

### Descrição
Registra os lançamentos iniciais de custos extras.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| tipo_custo_id | BIGINT UNSIGNED | ID do tipo de custo | FK, NOT NULL |
| cliente_id | BIGINT UNSIGNED | ID do cliente | FK, NOT NULL |
| documento_id | BIGINT UNSIGNED | ID do documento | FK, NULL |
| descricao | VARCHAR(255) | Descrição do custo | NOT NULL |
| valor | DECIMAL(15,2) | Valor do custo | NOT NULL |
| data_registro | DATE | Data do registro | NOT NULL |
| data_vencimento | DATE | Data de vencimento | NOT NULL |
| status_id | BIGINT UNSIGNED | ID do status | FK, NOT NULL |
| observacoes | TEXT | Observações adicionais | NULL |
| usuario_id | BIGINT UNSIGNED | ID do usuário que registrou | FK, NOT NULL |
| created_at | TIMESTAMP | Data de criação | NOT NULL |
| updated_at | TIMESTAMP | Data de atualização | NOT NULL |
| deleted_at | TIMESTAMP | Data de exclusão | NULL |

### Índices
- PRIMARY KEY (`id`)
- INDEX `fk_registros_tipo_custo` (`tipo_custo_id`)
- INDEX `fk_registros_cliente` (`cliente_id`)
- INDEX `fk_registros_documento` (`documento_id`)
- INDEX `fk_registros_status` (`status_id`)
- INDEX `fk_registros_usuario` (`usuario_id`)
- INDEX `idx_registros_data` (`data_registro`)
- INDEX `idx_registros_vencimento` (`data_vencimento`)

### Relacionamentos
- N:1 com `tipos_custos`
- N:1 com `clientes`
- N:1 com `documentos`
- N:1 com `status`
- N:1 com `usuarios`

## Tabela: custos_extras_cobrancas

### Descrição
Gerencia as cobranças dos custos extras.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| registro_id | BIGINT UNSIGNED | ID do registro inicial | FK, NOT NULL |
| valor_cobrado | DECIMAL(15,2) | Valor da cobrança | NOT NULL |
| data_cobranca | DATE | Data da cobrança | NOT NULL |
| data_vencimento | DATE | Data de vencimento | NOT NULL |
| status_id | BIGINT UNSIGNED | ID do status | FK, NOT NULL |
| forma_pagamento | VARCHAR(50) | Forma de pagamento | NULL |
| comprovante | VARCHAR(255) | Caminho do comprovante | NULL |
| observacoes | TEXT | Observações | NULL |
| usuario_id | BIGINT UNSIGNED | ID do usuário | FK, NOT NULL |
| created_at | TIMESTAMP | Data de criação | NOT NULL |
| updated_at | TIMESTAMP | Data de atualização | NOT NULL |
| deleted_at | TIMESTAMP | Data de exclusão | NULL |

### Índices
- PRIMARY KEY (`id`)
- INDEX `fk_cobrancas_registro` (`registro_id`)
- INDEX `fk_cobrancas_status` (`status_id`)
- INDEX `fk_cobrancas_usuario` (`usuario_id`)
- INDEX `idx_cobrancas_data` (`data_cobranca`)
- INDEX `idx_cobrancas_vencimento` (`data_vencimento`)

### Relacionamentos
- N:1 com `custos_extras_registros_iniciais`
- N:1 com `status`
- N:1 com `usuarios`

### Triggers
1. **after_insert_cobranca**
   - Atualiza status do registro inicial
   - Gera notificação para o cliente

2. **after_update_cobranca**
   - Atualiza saldo do registro
   - Registra alteração na auditoria

### Observações
- Sistema mantém histórico completo de cobranças
- Suporte a múltiplas formas de pagamento
- Integração com sistema de notificações
- Rastreamento completo de alterações
