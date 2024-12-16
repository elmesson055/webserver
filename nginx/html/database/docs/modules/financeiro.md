# Módulo: Financeiro

## Visão Geral
O módulo Financeiro gerencia todas as operações financeiras do sistema, incluindo contas a pagar, contas a receber, movimentações bancárias e fluxo de caixa.

## Tabela: dados_bancarios

### Descrição
Armazena as informações bancárias dos fornecedores.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| fornecedor_id | BIGINT UNSIGNED | ID do fornecedor | FK, NOT NULL |
| banco | VARCHAR(3) | Código do banco | NOT NULL |
| agencia | VARCHAR(10) | Número da agência | NOT NULL |
| conta | VARCHAR(20) | Número da conta | NOT NULL |
| tipo_conta | ENUM | Tipo da conta | NOT NULL |
| titular | VARCHAR(255) | Nome do titular | NOT NULL |
| cpf_cnpj_titular | VARCHAR(14) | CPF/CNPJ do titular | NOT NULL |
| pix_tipo | ENUM | Tipo da chave PIX | NULL |
| pix_chave | VARCHAR(255) | Chave PIX | NULL |
| principal | BOOLEAN | Conta principal | NOT NULL, DEFAULT FALSE |
| status | ENUM | Status da conta | NOT NULL, DEFAULT 'ATIVO' |

### Índices
- PRIMARY KEY (`id`)
- INDEX `fk_dados_bancarios_fornecedor_idx` (`fornecedor_id`)
- INDEX `idx_dados_bancarios_principal` (`principal`)
- INDEX `idx_dados_bancarios_status` (`status`)

### Relacionamentos
- N:1 com `fornecedores`
- 1:N com `movimentacoes_financeiras`

## Tabela: movimentacoes_financeiras

### Descrição
Registra todas as movimentações financeiras dos fornecedores.

### Estrutura
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| id | BIGINT UNSIGNED | Identificador único | PK, AUTO_INCREMENT |
| fornecedor_id | BIGINT UNSIGNED | ID do fornecedor | FK, NOT NULL |
| dados_bancarios_id | BIGINT UNSIGNED | ID da conta bancária | FK, NULL |
| tipo | ENUM | Tipo da movimentação | NOT NULL |
| valor | DECIMAL(15,2) | Valor da movimentação | NOT NULL |
| data_vencimento | DATE | Data de vencimento | NOT NULL |
| data_pagamento | DATE | Data do pagamento | NULL |
| status | ENUM | Status da movimentação | NOT NULL, DEFAULT 'PENDENTE' |
| forma_pagamento | ENUM | Forma de pagamento | NULL |
| numero_documento | VARCHAR(50) | Número do documento | NULL |
| descricao | VARCHAR(255) | Descrição | NOT NULL |
| observacoes | TEXT | Observações | NULL |
| comprovante | VARCHAR(255) | Caminho do comprovante | NULL |
| nota_fiscal | VARCHAR(255) | Caminho da nota fiscal | NULL |

### Índices
- PRIMARY KEY (`id`)
- INDEX `fk_movimentacoes_fornecedor_idx` (`fornecedor_id`)
- INDEX `fk_movimentacoes_dados_bancarios_idx` (`dados_bancarios_id`)
- INDEX `idx_movimentacoes_tipo` (`tipo`)
- INDEX `idx_movimentacoes_status` (`status`)
- INDEX `idx_movimentacoes_data_vencimento` (`data_vencimento`)
- INDEX `idx_movimentacoes_data_pagamento` (`data_pagamento`)

### Relacionamentos
- N:1 com `fornecedores`
- N:1 com `dados_bancarios`

### Triggers
1. **trg_after_insert_movimentacao**
   - Atualiza saldo do fornecedor
   - Gera notificação de nova movimentação

2. **trg_after_insert_conta_pagar**
   - Cria notificações automáticas para contas a pagar.

3. **trg_before_update_conta_pagar**
   - Calcula automaticamente juros e multas para pagamentos em atraso.

4. **trg_after_update_conta_pagar**
   - Atualiza o status financeiro do fornecedor.

### Regras de Negócio dos Triggers

#### Cálculo de Juros e Multas
- Multa fixa de 2% após vencimento
- Juros de 1% ao mês, calculado proporcionalmente aos dias de atraso
- Valor total atualizado = Principal + Multa + Juros

#### Status Financeiro
| Status | Condição |
|--------|-----------|
| Inadimplente | Atraso > 90 dias |
| Irregular | Atraso > 30 dias |
| Regular com Restrição | Atraso > 0 dias |
| Regular | Sem atrasos |

#### Notificações
- Primeira notificação: 5 dias antes do vencimento
- Segunda notificação: No dia do vencimento
- Notificações enviadas para:
  - Responsável pelo lançamento
  - Setor financeiro

### Campos Adicionados em fornecedores
| Coluna | Tipo | Descrição | Restrições |
|--------|------|-----------|------------|
| saldo | DECIMAL(15,2) | Saldo atual | NOT NULL, DEFAULT 0.00 |
| limite_credito | DECIMAL(15,2) | Limite de crédito | NOT NULL, DEFAULT 0.00 |
| status_financeiro | ENUM | Status financeiro | NOT NULL, DEFAULT 'REGULAR' |
| ultima_movimentacao | DATE | Data da última movimentação | NULL |
| dias_atraso | INT | Dias em atraso | NOT NULL, DEFAULT 0 |
| valor_em_aberto | DECIMAL(15,2) | Valor total em aberto | NOT NULL, DEFAULT 0.00 |

### Observações
- Todas as operações financeiras são registradas com timestamps
- Comprovantes e notas fiscais são armazenados em diretório específico
- Sistema mantém histórico completo de movimentações mesmo após exclusão lógica
- Validações de segurança implementadas para todas as operações financeiras

## Stored Procedures Implementadas

### 1. sp_fechamento_mensal
**Descrição**: Realiza o fechamento mensal do período especificado.

**Parâmetros**:
- `p_ano`: Ano do fechamento
- `p_mes`: Mês do fechamento
- `p_usuario_id`: ID do usuário realizando o fechamento

**Funcionalidades**:
- Verifica se já existe fechamento para o período
- Calcula saldo anterior do último fechamento
- Totaliza receitas e despesas do período
- Calcula resultado final
- Registra fechamento e log da operação

### 2. sp_conciliacao_bancaria
**Descrição**: Realiza a conciliação bancária de uma conta específica.

**Parâmetros**:
- `p_conta_id`: ID da conta bancária
- `p_data_inicio`: Data inicial do período
- `p_data_fim`: Data final do período
- `p_usuario_id`: ID do usuário realizando a conciliação

**Funcionalidades**:
- Calcula saldo do sistema
- Registra processo de conciliação
- Marca movimentações como conciliadas
- Registra log da operação

### 3. sp_fluxo_caixa_projetado
**Descrição**: Gera relatório de fluxo de caixa projetado.

**Parâmetros**:
- `p_data_inicio`: Data inicial da projeção
- `p_data_fim`: Data final da projeção
- `p_conta_id`: ID da conta (opcional)

**Funcionalidades**:
- Calcula saldo inicial
- Lista receitas previstas
- Lista despesas previstas
- Gera projeção diária de saldo

## Regras de Negócio das Procedures

### Fechamento Mensal
- Não permite fechamento duplicado
- Considera saldo anterior do último fechamento
- Totaliza movimentações do período
- Status: FECHADO após conclusão

### Conciliação Bancária
- Marca movimentações como conciliadas
- Calcula diferença entre saldo banco e sistema
- Permite conciliação por período
- Status inicial: PENDENTE

### Fluxo de Caixa Projetado
- Considera apenas movimentações pendentes
- Permite filtro por conta específica
- Calcula saldo projetado diário
- Separa receitas e despesas

## Exemplos de Uso

### Fechamento Mensal
```sql
CALL sp_fechamento_mensal(2024, 1, 1);
```

### Conciliação Bancária
```sql
CALL sp_conciliacao_bancaria(1, '2024-01-01', '2024-01-31', 1);
```

### Fluxo de Caixa Projetado
```sql
CALL sp_fluxo_caixa_projetado('2024-01-01', '2024-12-31', NULL);
```

## Procedures de Relatórios

### 1. sp_relatorio_posicao_financeira
**Descrição**: Gera relatório detalhado da posição financeira por conta.

**Parâmetros**:
- `p_data`: Data base do relatório
- `p_conta_id`: ID da conta (opcional)

**Funcionalidades**:
- Utiliza cache em tabela temporária
- Calcula saldos anteriores
- Totaliza movimentações do dia
- Indica pendências de conciliação

### 2. sp_relatorio_aging_list
**Descrição**: Gera aging list de contas a pagar por fornecedor.

**Parâmetros**:
- `p_data_base`: Data base para cálculo
- `p_fornecedor_id`: ID do fornecedor (opcional)

**Faixas de Atraso**:
- A vencer
- Até 30 dias
- 31 a 60 dias
- 61 a 90 dias
- Acima de 90 dias

## Jobs de Manutenção

### 1. Atualização de Estatísticas
**Procedure**: `sp_atualizar_estatisticas`
- Atualiza estatísticas das tabelas principais
- Execução diária automática
- Registra log de execução

### 2. Limpeza de Cache
**Procedure**: `sp_limpar_cache_financeiro`
- Remove tabelas temporárias antigas
- Execução a cada 6 horas
- Registra log de limpeza

### Events Scheduler
```sql
-- Atualização de estatísticas (diário)
evt_atualizar_estatisticas
Execução: 01:00 AM

-- Limpeza de cache (6/6 horas)
evt_limpar_cache
Execução: +1h, +7h, +13h, +19h
```

## Exemplos de Uso

```sql
-- Posição financeira do dia
CALL sp_relatorio_posicao_financeira(CURRENT_DATE, NULL);

-- Aging list específico
CALL sp_relatorio_aging_list(CURRENT_DATE, 1);

-- Atualização manual de estatísticas
CALL sp_atualizar_estatisticas();

-- Limpeza manual de cache
CALL sp_limpar_cache_financeiro();
```

## Views para Relatórios

### 1. vw_posicao_financeira
Fornece uma visão geral da posição financeira por conta:
- Saldo atual
- Quantidade de movimentos pendentes de conciliação
- Total de créditos e débitos do dia
- Status da conta

### 2. vw_contas_pagar_vencimento
Análise de contas a pagar por data de vencimento:
- Quantidade de títulos
- Valor total por vencimento
- Valor e quantidade de títulos vencidos
- Agrupamento por data

### 3. vw_analise_credito_fornecedor
Consolidação da análise de crédito por fornecedor:
- Limite de crédito e disponível
- Quantidade de títulos pendentes
- Último score de crédito
- Resultado da última análise

### 4. vw_fluxo_caixa_realizado
Fluxo de caixa dos últimos 30 dias:
- Créditos e débitos por dia
- Resultado diário
- Saldo acumulado por conta
- Quantidade de movimentos

## Índices de Otimização

### Movimentações
- `idx_movimentacoes_data`: Busca por data
- `idx_movimentacoes_conta_data`: Filtros por conta e data
- `idx_movimentacoes_conciliacao`: Conciliação bancária

### Contas a Pagar
- `idx_contas_pagar_vencimento`: Análise de vencimentos
- `idx_contas_pagar_fornecedor`: Filtros por fornecedor
- `idx_contas_pagar_emissao`: Busca por data de emissão

### Análise de Crédito
- `idx_analise_credito_data`: Histórico por fornecedor
- `idx_analise_credito_score`: Análise de scores

### Categorias
- `idx_categorias_pai`: Hierarquia de categorias
- `idx_categorias_tipo`: Filtros por tipo

## Exemplos de Consultas

```sql
-- Posição atual das contas
SELECT * FROM vw_posicao_financeira;

-- Contas a pagar próximos 30 dias
SELECT * FROM vw_contas_pagar_vencimento
WHERE data_vencimento <= DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY);

-- Análise de crédito fornecedores
SELECT * FROM vw_analise_credito_fornecedor
WHERE limite_disponivel < 1000;

-- Fluxo de caixa últimos 7 dias
SELECT * FROM vw_fluxo_caixa_realizado
WHERE data >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY);
```

## Sistema de Crédito

### Validação de Limite de Crédito
O sistema implementa validações automáticas de limite de crédito através do trigger `trg_before_insert_conta_pagar`, que:
- Verifica o limite disponível antes de cada lançamento
- Impede lançamentos que ultrapassem o limite
- Registra tentativas de ultrapassar limite
- Atualiza valor em aberto do fornecedor

### Análise de Crédito
A procedure `sp_analise_credito` realiza análise automática considerando:

#### Fatores Analisados
1. **Score de Crédito (0-100)**
   - Dias de atraso atual
   - Quantidade de atrasos nos últimos 6 meses
   - Percentual de utilização do limite

2. **Critérios de Aprovação**
   | Score | Resultado | Descrição |
   |-------|-----------|-----------|
   | < 30 | REPROVADO | Score muito baixo |
   | 30-60 | PENDENTE | Necessita análise manual |
   | > 60 | APROVADO | Aprovação automática |

3. **Histórico**
   - Todas as análises são registradas
   - Mantém histórico completo por fornecedor
   - Permite acompanhamento de evolução

### Tabelas do Sistema de Crédito

#### financeiro_analise_credito
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | ID da análise |
| fornecedor_id | INT | ID do fornecedor |
| valor_solicitado | DECIMAL(15,2) | Valor solicitado |
| score | DECIMAL(5,2) | Score calculado |
| resultado | VARCHAR(20) | Resultado da análise |
| mensagem | VARCHAR(255) | Detalhes do resultado |
| data_analise | TIMESTAMP | Data/hora da análise |

### Exemplos de Uso

```sql
-- Análise de crédito
DECLARE v_resultado VARCHAR(20);
DECLARE v_mensagem VARCHAR(255);
CALL sp_analise_credito(1, 5000.00, v_resultado, v_mensagem);

-- Consulta histórico de análises
SELECT * FROM financeiro_analise_credito
WHERE fornecedor_id = 1
ORDER BY data_analise DESC;
```

## Tabela: financeiro_contas

Cadastro de contas bancárias.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| banco_id | INT | ID do banco |
| tipo_conta | VARCHAR(20) | Tipo da conta |
| agencia | VARCHAR(10) | Número da agência |
| conta | VARCHAR(20) | Número da conta |
| digito | VARCHAR(2) | Dígito verificador |
| descricao | VARCHAR(100) | Descrição da conta |
| saldo_inicial | DECIMAL(15,2) | Saldo inicial |
| saldo_atual | DECIMAL(15,2) | Saldo atual |
| data_abertura | DATE | Data de abertura |
| status | VARCHAR(20) | Status da conta |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: financeiro_movimentacoes

Registro de movimentações financeiras.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| conta_id | INT | ID da conta |
| tipo | VARCHAR(20) | Tipo da movimentação |
| valor | DECIMAL(15,2) | Valor da movimentação |
| data_movimento | DATE | Data do movimento |
| descricao | TEXT | Descrição |
| categoria_id | INT | ID da categoria |
| documento | VARCHAR(50) | Número do documento |
| conciliado | BOOLEAN | Status de conciliação |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: financeiro_contas_pagar

Controle de contas a pagar.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| fornecedor_id | INT | ID do fornecedor |
| tipo_documento | VARCHAR(50) | Tipo do documento |
| numero_documento | VARCHAR(50) | Número do documento |
| valor_total | DECIMAL(15,2) | Valor total |
| data_emissao | DATE | Data de emissão |
| data_vencimento | DATE | Data de vencimento |
| status | VARCHAR(20) | Status do pagamento |
| forma_pagamento | VARCHAR(50) | Forma de pagamento |
| conta_id | INT | ID da conta |
| categoria_id | INT | ID da categoria |
| observacoes | TEXT | Observações |
| anexo_path | VARCHAR(255) | Caminho do anexo |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: financeiro_contas_receber

Controle de contas a receber.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| cliente_id | INT | ID do cliente |
| tipo_documento | VARCHAR(50) | Tipo do documento |
| numero_documento | VARCHAR(50) | Número do documento |
| valor_total | DECIMAL(15,2) | Valor total |
| data_emissao | DATE | Data de emissão |
| data_vencimento | DATE | Data de vencimento |
| status | VARCHAR(20) | Status do recebimento |
| forma_recebimento | VARCHAR(50) | Forma de recebimento |
| conta_id | INT | ID da conta |
| categoria_id | INT | ID da categoria |
| observacoes | TEXT | Observações |
| anexo_path | VARCHAR(255) | Caminho do anexo |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Tabela: financeiro_categorias

Categorias para classificação financeira.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único |
| nome | VARCHAR(100) | Nome da categoria |
| tipo | VARCHAR(20) | Tipo (Receita/Despesa) |
| categoria_pai_id | INT | ID da categoria pai |
| descricao | TEXT | Descrição |
| created_at | TIMESTAMP | Data de criação |
| updated_at | TIMESTAMP | Data da atualização |

## Funcionalidades Principais

### 1. Contas Bancárias
- Múltiplas contas
- Saldos e extratos
- Conciliação bancária
- Transferências

### 2. Contas a Pagar
- Programação de pagamentos
- Aprovações
- Baixas automáticas
- Rateios

### 3. Contas a Receber
- Controle de recebimentos
- Baixas automáticas
- Inadimplência
- Renegociação

### 4. Fluxo de Caixa
- Projeções
- DRE
- Centro de custos
- Orçamentos

## Integrações

### 1. Bancárias
- APIs bancárias
- EDI
- CNAB
- PIX

### 2. Fiscal
- Notas fiscais
- Impostos
- DAS
- SPED

### 3. Contábil
- Plano de contas
- Lançamentos
- Balancetes
- Demonstrativos

## Relatórios

### 1. Gerenciais
- DRE
- Fluxo de caixa
- Inadimplência
- Projeções

### 2. Operacionais
- Extratos
- Movimentações
- Pagamentos
- Recebimentos

### 3. Análises
- Indicadores
- Gráficos
- Comparativos
- Tendências

## Boas Práticas

### 1. Controles
- Conciliação diária
- Backup de comprovantes
- Aprovações
- Auditoria

### 2. Segurança
- Níveis de acesso
- Logs de operações
- Backup
- Criptografia

### 3. Compliance
- SOX
- LGPD
- Políticas internas
- Auditoria externa

## Dashboards em Tempo Real

### 1. KPIs Financeiros
**View**: `vw_kpis_financeiros`
- Saldo total atual
- Contas a pagar/receber do dia
- Total vencido e quantidade
- Saldo projetado (30 dias)

### 2. Evolução Diária
**View**: `vw_evolucao_diaria`
- Últimos 30 dias
- Resultado diário
- Saldo acumulado
- Tendência de evolução

### 3. Distribuição de Despesas
**View**: `vw_distribuicao_despesas`
- Categorização hierárquica
- Valor total por categoria
- Percentual sobre total
- Quantidade de lançamentos

### 4. Monitoramento em Tempo Real
**Procedure**: `sp_monitorar_alteracoes`
- Execução a cada minuto
- Detecção de variações
- Alertas automáticos
- Registro de alterações

## Exemplos de Uso

```sql
-- Atualizar dashboard completo
DECLARE @kpis JSON;
DECLARE @evolucao JSON;
DECLARE @distribuicao JSON;
CALL sp_atualizar_dashboard(@kpis, @evolucao, @distribuicao);

-- Consultar KPIs
SELECT * FROM vw_kpis_financeiros;

-- Evolução últimos 30 dias
SELECT * FROM vw_evolucao_diaria
ORDER BY date DESC;

-- Top 10 categorias de despesa
SELECT * FROM vw_distribuicao_despesas
LIMIT 10;
```

### Estrutura do JSON Retornado

```json
{
    "kpis": {
        "saldo_total": 50000.00,
        "pagar_hoje": 1500.00,
        "receber_hoje": 2000.00,
        "total_vencido": 500.00,
        "qtd_titulos_vencidos": 3,
        "saldo_projetado_30d": 55000.00
    },
    "evolucao": [
        {
            "data": "2024-12-13",
            "resultado": 500.00,
            "acumulado": 50000.00
        }
    ],
    "distribuicao": [
        {
            "categoria": "Despesas Operacionais > Energia",
            "valor": 1000.00,
            "percentual": 15.5,
            "qtd_lancamentos": 5
        }
    ]
}
```

### Monitoramento de Alterações

O sistema monitora continuamente alterações significativas:
- Execução a cada minuto via event scheduler
- Alerta para variações > 10% no saldo
- Registro em log para auditoria
- Facilita detecção de anomalias
