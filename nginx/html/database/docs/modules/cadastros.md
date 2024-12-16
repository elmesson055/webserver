# Módulo: Cadastros

## Tabela: fornecedores

### Descrição
Armazena os dados cadastrais dos fornecedores.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| razao_social | VARCHAR(255) | Razão social da empresa | NOT NULL |
| nome_fantasia | VARCHAR(255) | Nome fantasia | NULL |
| cnpj | VARCHAR(14) | CNPJ | NOT NULL, UNIQUE |
| inscricao_estadual | VARCHAR(20) | Inscrição estadual | NULL |
| inscricao_municipal | VARCHAR(20) | Inscrição municipal | NULL |
| email | VARCHAR(255) | Email principal | NOT NULL, UNIQUE |
| telefone | VARCHAR(20) | Telefone fixo | NULL |
| celular | VARCHAR(20) | Telefone celular | NULL |
| cep | VARCHAR(8) | CEP | NOT NULL |
| logradouro | VARCHAR(255) | Endereço | NOT NULL |
| numero | VARCHAR(10) | Número | NOT NULL |
| complemento | VARCHAR(100) | Complemento | NULL |
| bairro | VARCHAR(100) | Bairro | NOT NULL |
| cidade | VARCHAR(100) | Cidade | NOT NULL |
| estado | CHAR(2) | UF | NOT NULL |
| contato | VARCHAR(100) | Nome do contato | NULL |
| observacoes | TEXT | Observações gerais | NULL |
| situacao | ENUM | Status do cadastro | NOT NULL, DEFAULT 'PENDENTE' |
| status | ENUM | Status geral | NOT NULL, DEFAULT 'REGULAR' |
| password | VARCHAR(255) | Senha de acesso | NOT NULL |
| remember_token | VARCHAR(100) | Token de "lembrar-me" | NULL |

### Índices
- PRIMARY KEY (`id`)
- UNIQUE KEY `uk_fornecedores_cnpj` (`cnpj`)
- UNIQUE KEY `uk_fornecedores_email` (`email`)
- INDEX `idx_fornecedores_situacao` (`situacao`)
- INDEX `idx_fornecedores_status` (`status`)

### Relacionamentos
- 1:N com `documentos`
- 1:N com `notificacoes`
- 1:N com `dados_bancarios`
- 1:N com `movimentacoes_financeiras`

### Triggers
1. **before_insert_fornecedor**
   - Valida formato do CNPJ
   - Formata campos de telefone
   - Converte CEP para formato padrão

2. **after_update_fornecedor**
   - Gera notificação se status mudar
   - Atualiza documentos relacionados se situação mudar

### Observações
- Campos sensíveis como senha são armazenados com hash
- Exclusão lógica implementada através do campo `deleted_at`
- Auditoria de alterações através dos timestamps `created_at` e `updated_at`
