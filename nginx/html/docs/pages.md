# Documentação de Páginas - Sistema Custo Extras

## 1. Módulo de Cadastros Básicos

### 1.1 Cadastro de Embarcadores
- CRUD completo
- Campos principais:
  - Código (auto)
  - Razão Social
  - CNPJ
  - Endereço completo
  - Contatos
  - Status (ativo/inativo)
  - Data de cadastro
  - Usuário responsável

### 1.2 Cadastro de Fornecedores
- CRUD completo
- Campos principais:
  - Código (auto)
  - Razão Social
  - CNPJ
  - Endereço completo
  - Contatos
  - Status (ativo/inativo)
  - Data de cadastro
  - Usuário responsável

### 1.3 Cadastro de Clientes
- CRUD completo
- Campos principais:
  - Código (auto)
  - Razão Social
  - CNPJ
  - Endereço completo
  - Contatos
  - Status (ativo/inativo)
  - Data de cadastro
  - Usuário responsável

### 1.4 Cadastro de Usuários
- CRUD completo
- Campos principais:
  - ID (auto)
  - Nome completo
  - Email
  - Senha (hash)
  - Perfil/Grupo
  - Departamento (Transportes/Custos/Financeiro)
  - Status (ativo/inativo)
  - Permissões
  - Data de cadastro
  - Último acesso

### 1.5 Cadastro de Motoristas
- CRUD completo
- Campos principais:
  - Código (auto)
  - Nome completo
  - CPF
  - CNH
  - Contatos
  - Status (ativo/inativo)
  - Data de cadastro
  - Usuário responsável

## 2. Módulo de Tipos e Configurações

### 2.1 Tipos de Carga
- CRUD simples
- Campos:
  - ID
  - Descrição
  - Status
  - Data de cadastro
  - Usuário responsável

### 2.2 Tipos de Documentos
- CRUD simples
- Campos:
  - ID
  - Descrição
  - Status
  - Prazo padrão
  - Data de cadastro
  - Usuário responsável

### 2.3 Tipos de Custos
- CRUD simples
- Campos:
  - ID
  - Descrição
  - Categoria
  - Status
  - Data de cadastro
  - Usuário responsável

### 2.4 Status Gerais
- CRUD simples
- Campos:
  - ID
  - Descrição
  - Cor (para identificação visual)
  - Ordem
  - Data de cadastro
  - Usuário responsável

### 2.5 Status de Follow-up
- CRUD simples
- Campos:
  - ID
  - Descrição
  - Cor (para identificação visual)
  - Ordem
  - Departamento responsável
  - Prazo em dias
  - Data de cadastro
  - Usuário responsável

### 2.6 Status de Emissão
- CRUD simples
- Campos:
  - ID
  - Descrição
  - Cor (para identificação visual)
  - Ordem
  - Data de cadastro
  - Usuário responsável

### 2.7 Configurações de Alertas
- Interface de configuração
- Campos configuráveis:
  - Prazo de cobrança (padrão 3 dias)
  - Alertas de documentos
  - Notificações por email
  - Regras de workflow
  - Data última alteração
  - Usuário responsável

## 3. Módulo de Custos Extras (Follow-up)

### 3.1 Registro Inicial (Primeiro Status)
- Interface principal
- Campos obrigatórios:
  - Data Registro (auto)
  - Responsável Registro (auto)
  - Embarcador (select)
  - Fornecedor (select)
  - Número Recibo
  - Tipo Carga (select)
  - Tipo Custo (select)
  - Romaneio
  - Nº Referência
  - Nº NFS
  - Valor Total
  - Quantidade
  - Status Follow-up
- Funcionalidades:
  - Validações em tempo real
  - Upload de documentos
  - Log de alterações
  - Histórico de modificações

### 3.2 Cobrança (Segundo Status)
- Interface de busca avançada
- Filtros de busca:
  - Período (data início/fim)
  - Embarcador
  - Fornecedor
  - Cliente
  - Número Recibo
  - Nº NFS
- Campos adicionais:
  - Data Cobrança
  - Responsável pela Cobrança
- Funcionalidades:
  - Grid de resultados
  - Histórico de interações
  - Upload de documentos
  - Log de alterações

### 3.3 Aprovação (Terceiro Status)
- Interface de busca avançada
- Campos adicionais:
  - Data Aprovação
  - Valor Aprovado
  - Número da Ocorrência
  - Observação
- Funcionalidades:
  - Histórico de alterações
  - Justificativas
  - Anexos de documentos
  - Log de modificações

### 3.4 Financeiro (Quarto Status)
- Interface de busca avançada
- Campos adicionais:
  - Tipo Documento (select)
  - Nº CTE/NFS
  - Data Emissão FAT/CTE
  - Data de Pagamento
- Funcionalidades:
  - Geração de PDF com dados completos
  - Exportação de relatórios
  - Log de alterações
  - Histórico de documentos

#### 3.4.1 Relatório PDF
O PDF gerado deve conter:
- Cabeçalho com logo e dados da empresa
- Dados completos do follow-up:
  - Informações do registro inicial
  - Dados da cobrança
  - Informações da aprovação
  - Dados financeiros
- Rodapé com:
  - Data/hora da geração
  - Usuário responsável
  - Numeração de páginas
  - Informações adicionais

## 4. Módulo de Gestão

### 4.1 Dashboard
- KPIs principais:
  - Follow-ups por status
  - Valores por status
  - Prazos próximos do vencimento
  - Alertas pendentes
- Gráficos interativos
- Filtros personalizáveis
- Exportação de dados

### 4.2 Gestão de Times
- Estrutura organizacional:
  - Gestores de Transportes
  - Gestores de Custos
  - Gestores Financeiros
- Donos de processos
- Responsáveis por etapas
- Indicadores de performance

## 5. Módulo de Relatórios

### 5.1 Relatórios de Cadastros
- Listagens customizáveis
- Filtros avançados
- Exportação (PDF/Excel)
- Histórico de geração

### 5.2 Relatórios de Follow-up
- Por status
- Por período
- Por responsável
- Por valores
- Por prazos
- Exportação (PDF/Excel)

### 5.3 Relatórios Financeiros
- Custos por período
- Custos por tipo
- Custos por embarcador
- Análises comparativas
- Exportação (PDF/Excel)

### 5.4 Relatórios de Auditoria
- Log de alterações
- Histórico de acessos
- Alterações por usuário
- Exportação (PDF/Excel)

## 6. Recursos Globais

### 6.1 Auditoria
- Log de todas as alterações:
  - Usuário responsável
  - Data/hora
  - IP de acesso
  - Valores anteriores/novos
  - Tipo de operação

### 6.2 Permissões
- Controle por perfil
- Controle por usuário
- Controle por ação
- Registro de acessos
- Log de alterações

### 6.3 Alertas
- Sistema de notificações:
  - Prazo de cobrança (3 dias)
  - Documentos pendentes
  - Aprovações pendentes
  - Pagamentos próximos
- Emails automáticos
- Alertas na interface
- Configuração flexível

### 6.4 Anexos
- Upload de documentos
- Visualização inline
- Controle de versões
- Tipos permitidos
- Log de alterações
