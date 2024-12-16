# Casos de Uso - Módulo Financeiro

## 1. Gestão de Contas a Pagar

### UC-FIN-001: Lançamento de Conta a Pagar
**Ator Principal**: Usuário Financeiro

**Pré-condições**:
1. Usuário autenticado com permissões financeiras
2. Fornecedor cadastrado no sistema
3. Categorias financeiras cadastradas

**Fluxo Principal**:
1. Sistema valida limite de crédito do fornecedor
2. Sistema registra conta a pagar
3. Sistema atualiza saldo do fornecedor
4. Sistema gera notificações de vencimento

**Fluxos Alternativos**:
- **FA1**: Limite de crédito excedido
  1. Sistema exibe mensagem de erro
  2. Sistema registra tentativa nos logs
  3. Operação é cancelada

- **FA2**: Fornecedor bloqueado
  1. Sistema exibe alerta
  2. Requer aprovação especial

### UC-FIN-002: Análise de Crédito
**Ator Principal**: Analista Financeiro

**Pré-condições**:
1. Fornecedor cadastrado
2. Histórico financeiro disponível

**Fluxo Principal**:
1. Sistema calcula score de crédito
2. Sistema avalia critérios:
   - Dias de atraso atual
   - Histórico de atrasos
   - Utilização do limite
3. Sistema determina resultado
4. Sistema registra análise

**Fluxos Alternativos**:
- **FA1**: Score Intermediário
  1. Sistema marca para análise manual
  2. Notifica analista responsável

## 2. Conciliação Bancária

### UC-FIN-003: Conciliação de Movimentações
**Ator Principal**: Contador

**Pré-condições**:
1. Extrato bancário importado
2. Movimentações registradas

**Fluxo Principal**:
1. Sistema compara saldos
2. Sistema identifica movimentações
3. Sistema marca itens conciliados
4. Sistema registra diferenças

**Fluxos Alternativos**:
- **FA1**: Diferença de Valores
  1. Sistema destaca divergências
  2. Permite ajuste manual
  3. Registra justificativa

## 3. Fechamento Mensal

### UC-FIN-004: Fechamento do Período
**Ator Principal**: Gerente Financeiro

**Pré-condições**:
1. Todas conciliações realizadas
2. Período anterior fechado

**Fluxo Principal**:
1. Sistema totaliza movimentações
2. Sistema calcula resultados
3. Sistema registra fechamento
4. Sistema bloqueia alterações

**Fluxos Alternativos**:
- **FA1**: Pendências Encontradas
  1. Sistema lista pendências
  2. Bloqueia fechamento
  3. Notifica responsáveis

## 4. Fluxo de Caixa

### UC-FIN-005: Projeção de Fluxo
**Ator Principal**: Gestor

**Pré-condições**:
1. Contas a pagar/receber registradas
2. Saldos atualizados

**Fluxo Principal**:
1. Sistema calcula saldo inicial
2. Sistema projeta receitas
3. Sistema projeta despesas
4. Sistema gera relatório diário

**Fluxos Alternativos**:
- **FA1**: Saldo Negativo Projetado
  1. Sistema emite alerta
  2. Destaca período crítico
  3. Sugere ações corretivas

## 5. Regras de Negócio Principais

### RN01 - Cálculo de Score de Crédito
- Score base: 100 pontos
- Deduções:
  - Atraso atual > 90 dias: -50 pontos
  - Atraso atual > 30 dias: -30 pontos
  - Atraso atual > 0 dias: -15 pontos
  - Cada atraso nos últimos 6 meses: -5 pontos
  - % utilização limite: -(utilização * 20)

### RN02 - Aprovação de Crédito
- Score < 30: Reprovado automático
- Score 30-60: Análise manual
- Score > 60: Aprovado automático

### RN03 - Notificações
- Vencimento em 5 dias
- Na data do vencimento
- Após 1, 5, 10, 30 dias de atraso

### RN04 - Fechamento Mensal
- Requer conciliação bancária
- Requer aprovação do gestor
- Bloqueia alterações retroativas
- Gera relatórios obrigatórios

## 6. Requisitos Não-Funcionais

### RNF01 - Desempenho
- Análise de crédito < 5 segundos
- Projeção de fluxo < 10 segundos
- Conciliação < 1 minuto/1000 registros

### RNF02 - Disponibilidade
- Sistema disponível 24/7
- Backup diário automático
- Recuperação < 4 horas

### RNF03 - Segurança
- Registro de todas alterações
- Trilha de auditoria completa
- Segregação de funções
