# [Nome do Módulo]

## Visão Geral
[Breve descrição do propósito e funcionalidade do módulo]

## Dependências
- [Lista de módulos dos quais este módulo depende]
- [Serviços externos necessários]

## Tabelas

### [nome_tabela_1]
Descrição: [Propósito da tabela]

#### Campos
| Campo | Tipo | Descrição | Restrições |
|-------|------|-----------|------------|
| id | INT | Identificador único | PK, AUTO_INCREMENT |
| ... | ... | ... | ... |

#### Relacionamentos
- FK com tabela X: [Descrição]
- FK com tabela Y: [Descrição]

#### Índices
- `idx_[nome]`: [Campos indexados e propósito]

#### Regras de Negócio
1. [Regra 1]
2. [Regra 2]

### [nome_tabela_2]
[Repetir estrutura para cada tabela]

## Fluxo de Dados
1. [Passo 1 do fluxo]
2. [Passo 2 do fluxo]
...

## Casos de Uso
1. [Caso de uso principal 1]
2. [Caso de uso principal 2]
...

## SQL Definitions
```sql
-- Criação das tabelas
CREATE TABLE IF NOT EXISTS [nome_tabela_1] (
    -- Definição dos campos
);

-- Índices
CREATE INDEX idx_[nome] ON [nome_tabela_1] ([campos]);

-- Foreign Keys
ALTER TABLE [nome_tabela_1]
    ADD CONSTRAINT fk_[nome] FOREIGN KEY ([campo]) REFERENCES [tabela_ref]([campo_ref]);

-- Triggers (se necessário)
DELIMITER //
CREATE TRIGGER [nome_trigger]
    [BEFORE/AFTER] [INSERT/UPDATE/DELETE] ON [nome_tabela_1]
    FOR EACH ROW
BEGIN
    -- Lógica do trigger
END;
//
DELIMITER ;
```

## Histórico de Alterações
| Data | Descrição | Autor |
|------|-----------|-------|
| [YYYY-MM-DD] | Criação inicial do módulo | [Autor] |

## Checklist de Implementação
### Banco de Dados
- [ ] Tabelas criadas e verificadas
  - [ ] [nome_tabela_1]
  - [ ] [nome_tabela_2]
- [ ] Índices implementados
  - [ ] idx_[nome] em [nome_tabela_1]
- [ ] Foreign Keys estabelecidas
  - [ ] fk_[nome] entre [tabela_1] e [tabela_2]
- [ ] Triggers implementados
  - [ ] [nome_trigger_1]
- [ ] Procedures/Functions criadas
  - [ ] [nome_procedure_1]

### Documentação
- [ ] Markdown atualizado
- [ ] SQL Definitions documentadas
- [ ] Diagramas ER atualizados
- [ ] Casos de uso documentados

### Pendências
| Item | Descrição | Prioridade | Status |
|------|-----------|------------|---------|
| [ID] | [Descrição da pendência] | [Alta/Média/Baixa] | [TODO/In Progress] |

### Verificação de Integridade
```sql
-- Scripts de verificação para garantir que tudo foi criado corretamente
SELECT TABLE_NAME 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'database_name' 
AND TABLE_NAME IN ('[nome_tabela_1]', '[nome_tabela_2]');

-- Verificar Foreign Keys
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_SCHEMA = 'database_name'
AND TABLE_NAME IN ('[nome_tabela_1]', '[nome_tabela_2]');

-- Verificar Índices
SHOW INDEX FROM [nome_tabela_1];
SHOW INDEX FROM [nome_tabela_2];

-- Verificar Triggers
SHOW TRIGGERS WHERE `Table` IN ('[nome_tabela_1]', '[nome_tabela_2]');
```

### Notas de Implementação
- [Observações importantes sobre a implementação]
- [Decisões técnicas tomadas]
- [Pontos de atenção]
