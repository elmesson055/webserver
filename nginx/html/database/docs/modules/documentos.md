# Módulo: Documentos

## Tabela: documentos

### Descrição
Gerencia os documentos enviados pelos fornecedores.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| fornecedor_id | BIGINT UNSIGNED | ID do fornecedor | FK, NOT NULL |
| categoria | VARCHAR(50) | Categoria do documento | NOT NULL |
| tipo | VARCHAR(50) | Tipo do documento | NOT NULL |
| nome | VARCHAR(255) | Nome do documento | NOT NULL |
| descricao | TEXT | Descrição do documento | NULL |
| arquivo | VARCHAR(255) | Caminho do arquivo | NOT NULL |
| mime_type | VARCHAR(100) | Tipo MIME do arquivo | NOT NULL |
| tamanho | BIGINT | Tamanho em bytes | NOT NULL |
| data_upload | DATE | Data do upload | NOT NULL |
| data_validade | DATE | Data de validade | NULL |
| status | ENUM | Status do documento | NOT NULL, DEFAULT 'PENDENTE' |
| observacoes | TEXT | Observações | NULL |

### Índices
- PRIMARY KEY (`id`)
- INDEX `fk_documentos_fornecedor_idx` (`fornecedor_id`)
- INDEX `idx_documentos_categoria` (`categoria`)
- INDEX `idx_documentos_status` (`status`)
- INDEX `idx_documentos_data_validade` (`data_validade`)

### Relacionamentos
- N:1 com `fornecedores`

### Triggers
1. **before_insert_documento**
   - Valida tipo de arquivo permitido
   - Verifica tamanho máximo
   - Gera nome único para arquivo

2. **after_insert_documento**
   - Gera notificação de novo documento
   - Atualiza status do fornecedor se necessário

3. **after_update_documento**
   - Gera notificação de mudança de status
   - Atualiza documentos relacionados se necessário

### Categorias de Documentos
1. Documentos Fiscais
   - Certidão Negativa Federal
   - Certidão Negativa Estadual
   - Certidão Negativa Municipal
   - Certidão Negativa FGTS
   - Certidão Negativa Trabalhista

2. Documentos Societários
   - Contrato Social
   - Alterações Contratuais
   - Procurações

3. Documentos Técnicos
   - Certificações
   - Licenças
   - Alvarás

4. Documentos Financeiros
   - Balanço Patrimonial
   - DRE
   - Comprovantes Bancários

### Status Possíveis
- PENDENTE: Aguardando análise
- APROVADO: Documento válido
- REJEITADO: Documento com problemas
- VENCIDO: Documento fora da validade

### Observações
- Arquivos são armazenados em estrutura de diretórios por ano/mês
- Sistema mantém histórico de versões dos documentos
- Validações de segurança para tipos de arquivos permitidos
- Backup automático de documentos importantes
- Notificações automáticas de vencimento
