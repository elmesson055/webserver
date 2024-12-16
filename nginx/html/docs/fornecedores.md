# Documentação do Módulo de Fornecedores

## Status Atual: ⚠️ Parcialmente Implementado

## Índice
1. [Visão Geral](#visão-geral)
2. [Estrutura](#estrutura)
3. [Status de Implementação](#status-de-implementação)
4. [Funcionalidades](#funcionalidades)
5. [Permissões](#permissões)
6. [Logs e Auditoria](#logs-e-auditoria)
7. [Pendências](#pendências)

## Visão Geral

O módulo de Fornecedores é responsável pelo gerenciamento de fornecedores no sistema. Atualmente possui implementação parcial, com funcionalidades básicas operacionais e algumas características avançadas em desenvolvimento.

## Estrutura

### Diretórios e Arquivos
```
app/modules/cadastros/
└── fornecedores/
    ├── controllers/
    │   └── FornecedorController.php
    ├── models/
    │   └── Fornecedor.php
    └── views/
        ├── index.php      # Listagem de fornecedores
        ├── form.php       # Formulário de cadastro/edição
        └── view.php       # Visualização detalhada
```

### Banco de Dados
```sql
CREATE TABLE fornecedores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    razao_social VARCHAR(200) NOT NULL,
    nome_fantasia VARCHAR(200) NOT NULL,
    cnpj VARCHAR(14) NOT NULL UNIQUE,
    inscricao_estadual VARCHAR(50),
    inscricao_municipal VARCHAR(50),
    cep VARCHAR(8) NOT NULL,
    endereco VARCHAR(200) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    complemento VARCHAR(100),
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado CHAR(2) NOT NULL,
    telefone VARCHAR(20),
    celular VARCHAR(20),
    email VARCHAR(200) NOT NULL,
    site VARCHAR(200),
    contato_nome VARCHAR(200),
    contato_email VARCHAR(200),
    contato_telefone VARCHAR(20),
    observacoes TEXT,
    ativo BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Status de Implementação

### ✅ Implementado

1. **Modelo (Model)**
   - Estrutura básica
   - Validações de campos
   - Trait de logging

2. **Controlador (Controller)**
   - CRUD básico
   - Rotas principais
   - Validações

3. **Views**
   - Listagem (index.php)
   - Formulário (form.php)
   - Visualização (view.php)

4. **Funcionalidades Básicas**
   - Cadastro
   - Edição
   - Listagem
   - Exclusão
   - Busca simples

### ⚠️ Parcialmente Implementado

1. **Exportação**
   - Estrutura básica criada
   - Pendente implementação completa

2. **Logs**
   - Sistema básico implementado
   - Pendente expansão de eventos

### ❌ Pendente

1. **Integrações**
   - Receita Federal
   - ViaCEP
   - Serviços de validação

2. **Funcionalidades Avançadas**
   - Dashboard
   - Relatórios complexos
   - Portal do fornecedor

## Funcionalidades

### Implementadas

1. **Listagem de Fornecedores**
   - Rota: `/cadastros/fornecedores`
   - Paginação básica
   - Busca por razão social/CNPJ

2. **Cadastro/Edição**
   - Rota: `/cadastros/fornecedores/create`
   - Rota: `/cadastros/fornecedores/{id}/edit`
   - Validações básicas

3. **Visualização**
   - Rota: `/cadastros/fornecedores/{id}`
   - Exibição de dados básicos

### Em Desenvolvimento

1. **Exportação**
   - Excel (estrutura básica)
   - PDF (estrutura básica)

2. **Integrações**
   - Preparação para ViaCEP
   - Preparação para validação de CNPJ

## Permissões

| Código | Status | Descrição |
|--------|---------|-----------|
| fornecedores.visualizar | ✅ | Visualizar listagem e detalhes |
| fornecedores.criar | ✅ | Criar novos fornecedores |
| fornecedores.editar | ✅ | Editar fornecedores existentes |
| fornecedores.excluir | ✅ | Excluir fornecedores |
| fornecedores.exportar | ⚠️ | Exportar relatórios |

## Logs e Auditoria

### Implementado
- Log de criação
- Log de edição
- Log de exclusão

### Pendente
- Log de exportação
- Log de visualização
- Histórico detalhado de alterações

## Pendências

### Curto Prazo
1. Completar implementação de exportação
2. Melhorar sistema de logs
3. Implementar validações avançadas

### Médio Prazo
1. Integração com Receita Federal
2. Sistema de anexos
3. Dashboard específico

### Longo Prazo
1. Portal do fornecedor
2. API REST
3. Integrações avançadas

## Próximos Passos

1. **Fase 1 - Prioridade Alta**
   - Finalizar exportação de relatórios
   - Implementar validações pendentes
   - Completar documentação técnica

2. **Fase 2 - Prioridade Média**
   - Desenvolver integrações
   - Implementar anexos
   - Criar dashboards

3. **Fase 3 - Prioridade Baixa**
   - Desenvolver portal
   - Criar API
   - Implementar integrações avançadas
