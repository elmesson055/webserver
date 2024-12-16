# Documentação do Banco de Dados

Este diretório contém a documentação detalhada do esquema do banco de dados, organizada por módulos.

## Estrutura
- `schema/`: Scripts SQL com a estrutura completa do banco
- `modules/`: Documentação específica de cada módulo
- `diagrams/`: Diagramas ER e relacionamentos
- `migrations/`: Histórico e documentação das migrações

## Diretrizes de Documentação

### 1. Ao Criar Novos Módulos

1. **Documentação Markdown**
   - Criar arquivo `.md` na pasta `modules/` usando o template `_template.md`
   - Incluir descrição geral do módulo
   - Listar todas as tabelas e seus relacionamentos
   - Documentar regras de negócio específicas
   - Adicionar exemplos de uso quando relevante

2. **Definições SQL**
   - Adicionar scripts SQL em `schema/additional_schema.sql`
   - Incluir:
     - Criação de tabelas
     - Índices
     - Chaves estrangeiras
     - Triggers necessários
     - Procedures/Functions
   - Comentar cada bloco SQL explicando sua função

3. **Migrações**
   - Criar arquivo de migração datado
   - Incluir scripts UP e DOWN
   - Documentar mudanças no `migrations/README.md`

### 2. Ao Alterar Módulos Existentes

1. **Atualização da Documentação**
   - Atualizar o arquivo `.md` correspondente
   - Adicionar notas de alteração com data
   - Documentar impactos em outros módulos

2. **Alterações SQL**
   - Criar nova migração para alterações
   - Atualizar `schema/additional_schema.sql`
   - Manter histórico de alterações em comentários

3. **Verificações Obrigatórias**
   - Validar impacto em módulos relacionados
   - Atualizar diagramas se necessário
   - Verificar integridade referencial

### 3. Padrões de Nomenclatura

1. **Tabelas**
   - Nomes em português
   - Usar snake_case
   - Prefixo do módulo quando apropriado

2. **Campos**
   - Nomes descritivos em português
   - Usar snake_case
   - Sufixos padronizados (_id, _data, _status)

3. **Índices e Constraints**
   - Prefixo idx_ para índices
   - Prefixo fk_ para foreign keys
   - Prefixo uk_ para unique keys

### 4. Requisitos de Documentação

1. **Obrigatório para Cada Tabela**
   - Descrição do propósito
   - Lista completa de campos
   - Relacionamentos
   - Índices
   - Regras de negócio

2. **Obrigatório para Cada Módulo**
   - Visão geral
   - Dependências
   - Fluxo de dados
   - Casos de uso principais

### 5. Verificação e Pendências

1. **Checklist de Implementação**
   - Verificar criação de todas as tabelas
   - Confirmar índices e relacionamentos
   - Validar triggers e procedures
   - Atualizar documentação

2. **Scripts de Verificação**
   - Executar queries de validação
   - Verificar integridade referencial
   - Confirmar existência de objetos do banco

3. **Gestão de Pendências**
   - Manter lista atualizada de pendências
   - Priorizar itens pendentes
   - Documentar decisões técnicas

## Módulos Atuais
1. Cadastros
2. Portal do Fornecedor
3. Financeiro
4. Documentos
5. Notificações
6. Auditoria
7. Configurações
8. Integrações
9. Painel
10. Relatórios
11. Segurança
12. Status Emissão
13. Tipos Custos
14. Clientes
15. Custos Extras
16. Layouts
17. Logs
18. Fornecedores
