# Módulo de Fornecedores

## Visão Geral
O módulo de Fornecedores gerencia todo o cadastro e relacionamento com fornecedores, incluindo documentação, contratos e avaliações.

## Tabela: `fornecedores`

Cadastro principal de fornecedores.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| razao_social | VARCHAR(200) | Razão social |
| nome_fantasia | VARCHAR(200) | Nome fantasia |
| cnpj | VARCHAR(14) | CNPJ |
| inscricao_estadual | VARCHAR(20) | Inscrição estadual |
| inscricao_municipal | VARCHAR(20) | Inscrição municipal |
| tipo_empresa | VARCHAR(50) | Tipo de empresa |
| porte | VARCHAR(20) | Porte da empresa |
| ramo_atividade | VARCHAR(100) | Ramo de atividade |
| website | VARCHAR(255) | Website |
| email_comercial | VARCHAR(255) | Email comercial |
| telefone_principal | VARCHAR(20) | Telefone principal |
| telefone_secundario | VARCHAR(20) | Telefone secundário |
| status | VARCHAR(20) | Status do fornecedor |
| rating | DECIMAL(2,1) | Avaliação média |
| observacoes | TEXT | Observações gerais |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: `fornecedores_enderecos`

Endereços dos fornecedores.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| fornecedor_id | INT | ID do fornecedor |
| tipo | VARCHAR(20) | Tipo do endereço |
| cep | VARCHAR(8) | CEP |
| logradouro | VARCHAR(200) | Logradouro |
| numero | VARCHAR(20) | Número |
| complemento | VARCHAR(100) | Complemento |
| bairro | VARCHAR(100) | Bairro |
| cidade | VARCHAR(100) | Cidade |
| estado | VARCHAR(2) | Estado |
| principal | BOOLEAN | Se é endereço principal |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: `fornecedores_contatos`

Contatos dos fornecedores.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| fornecedor_id | INT | ID do fornecedor |
| nome | VARCHAR(100) | Nome do contato |
| cargo | VARCHAR(100) | Cargo |
| departamento | VARCHAR(100) | Departamento |
| email | VARCHAR(255) | Email |
| telefone | VARCHAR(20) | Telefone |
| celular | VARCHAR(20) | Celular |
| principal | BOOLEAN | Se é contato principal |
| observacoes | TEXT | Observações |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: `fornecedores_documentos`

Documentos dos fornecedores.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| fornecedor_id | INT | ID do fornecedor |
| tipo | VARCHAR(50) | Tipo do documento |
| numero | VARCHAR(50) | Número do documento |
| data_emissao | DATE | Data de emissão |
| data_validade | DATE | Data de validade |
| arquivo_path | VARCHAR(255) | Caminho do arquivo |
| status | VARCHAR(20) | Status do documento |
| observacoes | TEXT | Observações |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: `fornecedores_contratos`

Contratos com fornecedores.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| fornecedor_id | INT | ID do fornecedor |
| numero_contrato | VARCHAR(50) | Número do contrato |
| tipo_contrato | VARCHAR(50) | Tipo do contrato |
| objeto | TEXT | Objeto do contrato |
| valor | DECIMAL(15,2) | Valor do contrato |
| data_inicio | DATE | Data de início |
| data_fim | DATE | Data de término |
| renovacao_automatica | BOOLEAN | Renovação automática |
| status | VARCHAR(20) | Status do contrato |
| arquivo_path | VARCHAR(255) | Caminho do arquivo |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: `fornecedores_avaliacoes`

Avaliações de fornecedores.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| fornecedor_id | INT | ID do fornecedor |
| avaliador_id | INT | ID do avaliador |
| periodo | VARCHAR(7) | Período (MM/YYYY) |
| qualidade | INT | Nota de qualidade |
| prazo | INT | Nota de prazo |
| preco | INT | Nota de preço |
| atendimento | INT | Nota de atendimento |
| media | DECIMAL(2,1) | Média geral |
| comentarios | TEXT | Comentários |
| created_at | TIMESTAMP | Data de criação |

## Funcionalidades Principais

### 1. Cadastro
- Dados cadastrais
- Endereços múltiplos
- Contatos
- Documentos

### 2. Contratos
- Gestão de contratos
- Renovações
- Aditivos
- Histórico

### 3. Avaliação
- Critérios múltiplos
- Histórico
- Ranking
- Relatórios

### 4. Documentação
- Upload de arquivos
- Controle de validade
- Notificações
- Histórico

## Integrações

### 1. Financeiro
- Pagamentos
- Notas fiscais
- Impostos
- Relatórios

### 2. Compras
- Cotações
- Pedidos
- Orçamentos
- Aprovações

### 3. Fiscal
- Validação de documentos
- Certidões
- Impostos
- Obrigações

## Boas Práticas

### 1. Cadastro
- Validação de CNPJ
- Consulta Receita
- Padronização
- Duplicidade

### 2. Documentação
- Backup automático
- Versionamento
- OCR
- Classificação

### 3. Avaliação
- Critérios claros
- Periodicidade
- Feedback
- Plano de ação
