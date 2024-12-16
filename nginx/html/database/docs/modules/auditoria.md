# Módulo: Auditoria

## Tabela: auditoria_alteracoes

### Descrição
Registra todas as alterações realizadas no sistema.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| usuario_id | BIGINT UNSIGNED | ID do usuário | FK, NOT NULL |
| acao | VARCHAR(50) | Tipo da ação realizada | NOT NULL |
| descricao | TEXT | Descrição da alteração | NOT NULL |
| entidade | VARCHAR(100) | Nome da entidade alterada | NOT NULL |
| entidade_id | BIGINT UNSIGNED | ID do registro alterado | NOT NULL |
| dados_antigos | JSON | Dados antes da alteração | NULL |
| dados_novos | JSON | Dados após a alteração | NULL |
| ip_address | VARCHAR(45) | IP do usuário | NOT NULL |
| user_agent | VARCHAR(255) | Navegador/dispositivo | NOT NULL |
| data_hora | TIMESTAMP | Data e hora da alteração | NOT NULL |
| created_at | TIMESTAMP | Data de criação | NOT NULL |
| updated_at | TIMESTAMP | Data de atualização | NOT NULL |

### Índices
- PRIMARY KEY (`id`)
- INDEX `idx_auditoria_alteracoes_usuario` (`usuario_id`)
- INDEX `idx_auditoria_alteracoes_entidade` (`entidade`, `entidade_id`)
- INDEX `idx_auditoria_alteracoes_data` (`data_hora`)

### Relacionamentos
- N:1 com `usuarios`

## Tabela: auditoria_versoes

### Descrição
Mantém o histórico de versões de documentos e registros importantes.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| entidade | VARCHAR(100) | Nome da entidade | NOT NULL |
| entidade_id | BIGINT UNSIGNED | ID do registro | NOT NULL |
| versao | INT | Número da versão | NOT NULL |
| dados | JSON | Conteúdo da versão | NOT NULL |
| usuario_id | BIGINT UNSIGNED | ID do usuário | FK, NOT NULL |
| motivo | TEXT | Motivo da alteração | NULL |
| data_hora | TIMESTAMP | Data e hora da versão | NOT NULL |
| created_at | TIMESTAMP | Data de criação | NOT NULL |
| updated_at | TIMESTAMP | Data de atualização | NOT NULL |

### Índices
- PRIMARY KEY (`id`)
- UNIQUE KEY `uk_auditoria_versoes_entidade` (`entidade`, `entidade_id`, `versao`)
- INDEX `idx_auditoria_versoes_usuario` (`usuario_id`)
- INDEX `idx_auditoria_versoes_data` (`data_hora`)

### Relacionamentos
- N:1 com `usuarios`

### Triggers
1. **before_insert_versao**
   - Incrementa número da versão
   - Valida dados JSON

2. **after_insert_versao**
   - Registra alteração na auditoria
   - Notifica administradores se necessário

### Observações
- Sistema mantém histórico completo de alterações
- Permite rollback para versões anteriores
- Suporte a auditoria de múltiplas entidades
- Rastreamento completo de alterações por usuário
