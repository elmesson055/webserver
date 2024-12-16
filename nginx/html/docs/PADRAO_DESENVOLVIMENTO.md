# Padrões de Desenvolvimento

## 1. Banco de Dados

### 1.1 Idioma
- Nomes de tabelas: Português do Brasil
- Nomes de colunas: Português do Brasil
- Comentários no código: Português do Brasil
- Dados: Português do Brasil (quando aplicável)

### 1.2 Fuso Horário
- Desenvolvimento baseado em GMT-3 (Horário de Brasília)
- Usar NOW() para timestamps
- Formato de exibição de data: DD/MM/YYYY HH:mm

### 1.3 Padrões de Nomenclatura
- Tabelas em minúsculo e plural (exemplo: usuarios, modulos)
- Chaves primárias: id (auto increment)
- Timestamps padrão: criado_em, atualizado_em
- Status padrão: ativo (boolean)
- Usar underscore para separar palavras
- Não usar acentos ou caracteres especiais nos nomes

### 1.4 Tabelas Principais
- usuarios
- permissoes
- grupos
- modulos
- usuario_permissoes
- usuario_grupos
- modulo_permissoes

### 1.5 Convenções
- Chaves estrangeiras: nome_tabela_id (exemplo: usuario_id)
- Índices: idx_nome_tabela_coluna
- Constraints: fk_tabela_origem_tabela_destino

## 2. Documentação
- Todos os scripts SQL devem ter comentários explicativos
- Documentar alterações importantes no banco
- Manter este documento atualizado
- Criar scripts de rollback para alterações críticas
