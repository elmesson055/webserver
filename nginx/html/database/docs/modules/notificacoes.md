# Módulo: Notificações

## Tabela: notificacoes

### Descrição
Sistema de notificações para fornecedores.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| fornecedor_id | BIGINT UNSIGNED | ID do fornecedor | FK, NOT NULL |
| tipo | VARCHAR(50) | Tipo da notificação | NOT NULL |
| titulo | VARCHAR(255) | Título da notificação | NOT NULL |
| mensagem | TEXT | Conteúdo da notificação | NOT NULL |
| lida | BOOLEAN | Status de leitura | NOT NULL, DEFAULT FALSE |
| data_leitura | TIMESTAMP | Data de leitura | NULL |

### Índices
- PRIMARY KEY (`id`)
- INDEX `fk_notificacoes_fornecedor_idx` (`fornecedor_id`)
- INDEX `idx_notificacoes_lida` (`lida`)
- INDEX `idx_notificacoes_tipo` (`tipo`)

### Relacionamentos
- N:1 com `fornecedores`

### Tipos de Notificações
1. Documentos
   - DOCUMENTO_PENDENTE
   - DOCUMENTO_APROVADO
   - DOCUMENTO_REJEITADO
   - DOCUMENTO_VENCENDO
   - DOCUMENTO_VENCIDO

2. Financeiro
   - PAGAMENTO_RECEBIDO
   - PAGAMENTO_PENDENTE
   - PAGAMENTO_ATRASADO
   - LIMITE_CREDITO_ATUALIZADO
   - STATUS_FINANCEIRO_ALTERADO

3. Cadastro
   - CADASTRO_ATUALIZADO
   - CADASTRO_PENDENTE
   - CADASTRO_APROVADO
   - CADASTRO_BLOQUEADO

4. Sistema
   - MANUTENCAO_PROGRAMADA
   - NOVA_FUNCIONALIDADE
   - ALERTA_SEGURANCA

### Triggers
1. **after_insert_notificacao**
   - Envia email se configurado
   - Atualiza contadores de notificações

2. **after_update_notificacao**
   - Atualiza estatísticas de leitura
   - Registra tempo de resposta

### Observações
- Sistema suporta notificações por email além do portal
- Notificações podem ser configuradas por tipo
- Histórico completo de notificações mantido
- Suporte a notificações em massa
- Priorização de notificações críticas
