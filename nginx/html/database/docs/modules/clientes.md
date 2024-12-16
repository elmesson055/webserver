# Módulo de Clientes

## Tabela: `clientes`

Armazena informações cadastrais dos clientes do sistema.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único do cliente |
| razao_social | VARCHAR(255) | Razão social do cliente |
| nome_fantasia | VARCHAR(255) | Nome fantasia do cliente |
| cnpj | VARCHAR(14) | CNPJ do cliente |
| inscricao_estadual | VARCHAR(20) | Inscrição estadual |
| cep | VARCHAR(8) | CEP do endereço |
| logradouro | VARCHAR(255) | Logradouro do endereço |
| numero | VARCHAR(10) | Número do endereço |
| complemento | VARCHAR(100) | Complemento do endereço |
| bairro | VARCHAR(100) | Bairro |
| cidade | VARCHAR(100) | Cidade |
| estado | CHAR(2) | Estado (UF) |
| telefone | VARCHAR(20) | Telefone principal |
| email | VARCHAR(255) | Email principal |
| contato | VARCHAR(100) | Nome do contato principal |
| status | ENUM | Status do cliente (Ativo, Inativo, Bloqueado) |
| created_at | TIMESTAMP | Data de criação do registro |
| updated_at | TIMESTAMP | Data da última atualização |
| created_by | INT | ID do usuário que criou o registro |
| updated_by | INT | ID do usuário que atualizou o registro |

### Relacionamentos

- **Documentos**: Um cliente pode ter vários documentos (`documentos`)
- **Custos Extras**: Um cliente pode ter vários registros de custos extras (`custos_extras_registros_iniciais`)
- **Dados Bancários**: Um cliente pode ter vários dados bancários cadastrados (`dados_bancarios`)

### Índices

- PRIMARY KEY (`id`)
- UNIQUE KEY (`cnpj`)
- INDEX (`status`)
- INDEX (`created_by`)
- INDEX (`updated_by`)

### Triggers

1. **before_insert_cliente**
   - Valida o formato do CNPJ
   - Formata o CEP e telefone
   - Define status inicial como 'Ativo'

2. **after_update_cliente**
   - Registra alterações na tabela de auditoria
   - Atualiza status baseado em regras de negócio

### Regras de Negócio

1. **Validação de CNPJ**
   - CNPJ deve ser único no sistema
   - Formato deve ser válido (14 dígitos)
   - Verificação de dígitos verificadores

2. **Status do Cliente**
   - Alterações de status são registradas em auditoria
   - Bloqueio automático por inadimplência
   - Reativação mediante aprovação

3. **Documentação**
   - Documentos obrigatórios devem ser anexados
   - Controle de vencimento de documentos
   - Notificações automáticas de vencimento

4. **Integrações**
   - Validação de CNPJ na Receita Federal
   - Consulta de endereço via CEP
   - Verificação de situação cadastral
