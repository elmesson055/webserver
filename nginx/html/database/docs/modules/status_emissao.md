# Módulo de Status de Emissão

## Tabela: `status_emissao`

Controla os diferentes estados e fluxos de trabalho para documentos e processos no sistema.

### Estrutura

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | INT | Identificador único do status |
| nome | VARCHAR(50) | Nome do status |
| descricao | TEXT | Descrição detalhada do status |
| tipo | VARCHAR(30) | Tipo do status (Documento, Processo, Financeiro) |
| cor | VARCHAR(7) | Cor de identificação do status (formato #RRGGBB) |
| ordem | INT | Ordem de exibição/sequência do status |
| permite_edicao | BOOLEAN | Indica se permite edição no status |
| requer_motivo | BOOLEAN | Indica se requer motivo para mudança |
| status | ENUM | Status do registro (Ativo, Inativo) |
| created_at | TIMESTAMP | Data de criação do registro |
| updated_at | TIMESTAMP | Data da última atualização |
| created_by | INT | ID do usuário que criou o registro |
| updated_by | INT | ID do usuário que atualizou o registro |

### Relacionamentos

- **Documentos**: Status associados a documentos (`documentos`)
- **Processos**: Status associados a processos (`processos`)
- **Histórico**: Histórico de mudanças de status (`historico_status`)

### Índices

- PRIMARY KEY (`id`)
- UNIQUE KEY (`nome`, `tipo`)
- INDEX (`tipo`)
- INDEX (`status`)
- INDEX (`ordem`)

### Regras de Negócio

1. **Fluxos de Trabalho**
   - Definição de sequência de status
   - Regras de transição entre status
   - Permissões por tipo de usuário

2. **Controle de Edição**
   - Status que permitem edição
   - Bloqueio de alterações em status específicos
   - Registro de alterações em auditoria

3. **Notificações**
   - Alertas de mudança de status
   - Notificações para responsáveis
   - Lembretes de pendências

### Transições de Status

1. **Documentos**
   - Rascunho → Em Análise → Aprovado/Rejeitado
   - Controle de versões por mudança
   - Histórico de aprovações

2. **Processos**
   - Iniciado → Em Andamento → Concluído
   - Pontos de controle e verificação
   - Registro de responsáveis

3. **Financeiro**
   - Pendente → Em Aprovação → Aprovado → Pago
   - Regras por valor e tipo
   - Integrações com contas

### Configurações

1. **Permissões**
   - Níveis de acesso por status
   - Grupos de usuários autorizados
   - Restrições por departamento

2. **Automatizações**
   - Mudanças automáticas de status
   - Ações programadas
   - Integrações com outros módulos

3. **Personalização**
   - Cores e ícones por status
   - Mensagens personalizadas
   - Templates de notificação

### Observações

- Status são configuráveis por tipo de processo
- Impactam no fluxo de trabalho do sistema
- Base para relatórios de produtividade
