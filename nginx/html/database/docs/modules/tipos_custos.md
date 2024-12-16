# Módulo de Tipos de Custos

## Tabela: `tipos_custos`

Gerencia a categorização e configuração dos diferentes tipos de custos no sistema.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único do tipo de custo |
| nome | VARCHAR(100) | Nome do tipo de custo |
| descricao | TEXT | Descrição detalhada do tipo de custo |
| categoria | VARCHAR(50) | Categoria do custo (Fixo, Variável, Eventual) |
| natureza | ENUM | Natureza do custo (Receita, Despesa) |
| codigo_contabil | VARCHAR(20) | Código para integração contábil |
| requer_aprovacao | BOOLEAN | Indica se requer aprovação para lançamento |
| valor_limite | DECIMAL(10,2) | Valor limite para aprovação automática |
| status | ENUM | Status do tipo de custo (Ativo, Inativo) |
| created_at | TIMESTAMP | Data de criação do registro |
| updated_at | TIMESTAMP | Data da última atualização |
| created_by | INT | ID do usuário que criou o registro |
| updated_by | INT | ID do usuário que atualizou o registro |

### Relacionamentos

- **Custos Extras**: Um tipo de custo pode estar associado a vários registros (`custos_extras_registros_iniciais`)
- **Configurações**: Configurações específicas para cada tipo de custo (`tipos_custos_config`)
- **Aprovadores**: Usuários aprovadores para cada tipo de custo (`tipos_custos_aprovadores`)

### Índices

- PRIMARY KEY (`id`)
- UNIQUE KEY (`nome`)
- INDEX (`categoria`)
- INDEX (`natureza`)
- INDEX (`status`)

### Regras de Negócio

1. **Categorização**
   - Custos podem ser categorizados como Fixos, Variáveis ou Eventuais
   - Cada categoria pode ter regras específicas de aprovação
   - Limites de valores podem ser definidos por categoria

2. **Aprovações**
   - Configuração de níveis de aprovação
   - Definição de aprovadores por tipo de custo
   - Regras de alçada baseadas em valor

3. **Contabilização**
   - Integração com plano de contas contábil
   - Regras específicas por natureza do custo
   - Geração de lançamentos contábeis

4. **Controles**
   - Monitoramento de limites de aprovação
   - Histórico de alterações em configurações
   - Relatórios de utilização por tipo

### Configurações Específicas

1. **Aprovação Automática**
   - Valores abaixo do limite configurado
   - Regras específicas por categoria
   - Exceções por tipo de usuário

2. **Notificações**
   - Alertas para aprovadores
   - Notificações de limite excedido
   - Comunicados de alterações em configurações

3. **Integrações**
   - Sistema contábil
   - Fluxo de caixa
   - Relatórios gerenciais

### Observações

- Tipos de custos são fundamentais para a organização financeira
- Impactam diretamente no fluxo de aprovações
- Base para relatórios gerenciais e análises financeiras
