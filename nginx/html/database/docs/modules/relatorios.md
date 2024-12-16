# Módulo: Relatórios

## Tabela: relatorios_personalizados

### Descrição
Armazena configurações de relatórios personalizados.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| nome | VARCHAR(100) | Nome do relatório | NOT NULL |
| descricao | TEXT | Descrição do relatório | NULL |
| modulo | VARCHAR(50) | Módulo do relatório | NOT NULL |
| campos | JSON | Campos selecionados | NOT NULL |
| filtros | JSON | Filtros configurados | NULL |
| ordenacao | JSON | Configuração de ordenação | NULL |
| formato_padrao | VARCHAR(20) | Formato padrão de saída | NOT NULL |
| agrupamento | JSON | Configuração de agrupamento | NULL |
| usuario_id | BIGINT UNSIGNED | ID do usuário criador | FK, NOT NULL |
| compartilhado | BOOLEAN | Se é compartilhado | NOT NULL DEFAULT FALSE |
| created_at | TIMESTAMP | Data de criação | NOT NULL |
| updated_at | TIMESTAMP | Data de atualização | NOT NULL |
| deleted_at | TIMESTAMP | Data de exclusão | NULL |

### Índices
- PRIMARY KEY (`id`)
- INDEX `fk_relatorios_usuario` (`usuario_id`)
- INDEX `idx_relatorios_modulo` (`modulo`)
- INDEX `idx_relatorios_compartilhado` (`compartilhado`)

### Relacionamentos
- N:1 com `usuarios`

## Tabela: relatorios_execucoes

### Descrição
Registra o histórico de execuções dos relatórios.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| relatorio_id | BIGINT UNSIGNED | ID do relatório | FK, NOT NULL |
| usuario_id | BIGINT UNSIGNED | ID do usuário executor | FK, NOT NULL |
| data_execucao | TIMESTAMP | Data da execução | NOT NULL |
| parametros | JSON | Parâmetros utilizados | NOT NULL |
| formato_saida | VARCHAR(20) | Formato de saída | NOT NULL |
| tempo_execucao | INT | Tempo em segundos | NOT NULL |
| status | VARCHAR(20) | Status da execução | NOT NULL |
| erro | TEXT | Mensagem de erro | NULL |
| arquivo_gerado | VARCHAR(255) | Caminho do arquivo | NULL |
| tamanho_arquivo | BIGINT | Tamanho em bytes | NULL |
| created_at | TIMESTAMP | Data de criação | NOT NULL |
| updated_at | TIMESTAMP | Data de atualização | NOT NULL |

### Índices
- PRIMARY KEY (`id`)
- INDEX `fk_execucoes_relatorio` (`relatorio_id`)
- INDEX `fk_execucoes_usuario` (`usuario_id`)
- INDEX `idx_execucoes_data` (`data_execucao`)
- INDEX `idx_execucoes_status` (`status`)

### Relacionamentos
- N:1 com `relatorios_personalizados`
- N:1 com `usuarios`

### Observações
- Suporte a múltiplos formatos de saída (PDF, Excel, CSV)
- Sistema de cache para relatórios frequentes
- Agendamento de relatórios automáticos
- Compartilhamento de relatórios entre usuários
- Histórico completo de execuções
- Controle de permissões por relatório
