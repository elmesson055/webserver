# Documentação do Sistema de Autenticação

## Visão Geral do Banco de Dados

### Tabelas de Autenticação

#### 1. `access_levels`
**Descrição**: Gerencia os níveis de acesso no sistema
- **Campos**:
  - `id`: Identificador único
  - `name`: Nome do nível de acesso (ex: admin, manager, user)
  - `description`: Descrição detalhada do nível
  - `created_at`: Timestamp de criação
  - `updated_at`: Timestamp da última atualização

#### 2. `users`
**Descrição**: Armazena informações dos usuários
- **Campos**:
  - `id`: Identificador único do usuário
  - `username`: Nome de usuário único
  - `email`: Endereço de e-mail único
  - `password_hash`: Hash da senha (nunca armazenar senha em texto plano)
  - `access_level_id`: Nível de acesso do usuário
  - `first_name`: Primeiro nome
  - `last_name`: Sobrenome
  - `profile_picture`: Caminho da foto de perfil
  - `is_active`: Status de ativação da conta
  - `last_login`: Timestamp do último login
  - `created_at`: Timestamp de criação da conta
  - `updated_at`: Timestamp da última atualização

#### 3. `login_attempts`
**Descrição**: Registra tentativas de login
- **Campos**:
  - `id`: Identificador único
  - `user_id`: Usuário associado (pode ser nulo para tentativas com usuário inexistente)
  - `username`: Nome de usuário tentado
  - `ip_address`: Endereço IP da tentativa
  - `success`: Indica se o login foi bem-sucedido
  - `attempt_time`: Timestamp da tentativa

#### 4. `password_reset_tokens`
**Descrição**: Gerencia tokens de recuperação de senha
- **Campos**:
  - `id`: Identificador único
  - `user_id`: Usuário solicitando recuperação
  - `token`: Token único de recuperação
  - `expires_at`: Timestamp de expiração do token
  - `created_at`: Timestamp de criação do token

#### 5. `user_sessions`
**Descrição**: Controla sessões de usuário
- **Campos**:
  - `id`: Identificador da sessão
  - `user_id`: Usuário da sessão
  - `ip_address`: Endereço IP da sessão
  - `user_agent`: Informações do navegador/dispositivo
  - `payload`: Dados adicionais da sessão
  - `last_activity`: Timestamp da última atividade

## Relacionamentos

- `users.access_level_id` → `access_levels.id`
- `login_attempts.user_id` → `users.id`
- `password_reset_tokens.user_id` → `users.id`
- `user_sessions.user_id` → `users.id`

## Segurança e Boas Práticas

1. **Senhas**:
   - Sempre use `password_hash()` para criptografar
   - Nunca armazene senhas em texto plano
   - Use sal (salt) para aumentar a segurança

2. **Tokens**:
   - Tokens de recuperação de senha têm tempo de expiração
   - Tokens são gerados de forma única e segura

3. **Sessões**:
   - Sessões são rastreadas por IP e user agent
   - Implementar timeout de sessão

## Níveis de Acesso Padrão

- `admin`: Acesso total ao sistema
- `manager`: Acesso a funcionalidades administrativas
- `user`: Acesso limitado

## Considerações de Performance

- Índices criados para campos frequentemente consultados
- Trigger para atualização automática de `last_login`

## Próximos Passos

1. Implementar validações no código
2. Configurar política de senhas fortes
3. Implementar autenticação de dois fatores
4. Monitorar e registrar tentativas de acesso suspeitas

## Dependências

- MySQL 5.7+
- PHP com extensão PDO
- Suporte a UTF-8mb4

## Backup e Manutenção

- Realizar backups regulares
- Revisar logs de tentativas de login
- Manter usuários e níveis de acesso atualizados

## Diretrizes para Criação e Modificação de Tabelas

### Política de Desenvolvimento de Banco de Dados

#### Criação de Novas Tabelas
1. **Nomenclatura**:
   - Use nomes em snake_case
   - Seja descritivo e conciso
   - Prefixe tabelas relacionadas a módulos específicos

2. **Estrutura Padrão**:
   - Sempre incluir campos:
     ```sql
     id INT AUTO_INCREMENT PRIMARY KEY
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
     ```

3. **Documentação Obrigatória**:
   - Criar comentário SQL explicando o propósito da tabela
   - Documentar cada campo no README.md correspondente

#### Modificação de Tabelas Existentes
1. **Versionamento de Migrações**:
   - Criar arquivo de migração numerado sequencialmente
   - Exemplo: `002_alter_users_table.sql`
   - Incluir comando de rollback quando possível

2. **Regras de Alteração**:
   - Evitar remover colunas com dados históricos
   - Usar `ALTER TABLE` para modificações
   - Testar em ambiente de homologação antes de produção

#### Boas Práticas
- **Chaves Estrangeiras**: Sempre usar `ON DELETE` e `ON UPDATE` apropriados
- **Indexação**: Criar índices para campos frequentemente consultados
- **Charset**: Usar `utf8mb4_unicode_ci` para suporte completo de caracteres
- **Integridade**: Validar dados no código e no banco

#### Processo de Implementação
1. Desenvolver script SQL de migração
2. Revisar com outro desenvolvedor
3. Testar em ambiente de desenvolvimento
4. Documentar alterações
5. Aplicar em ambiente de homologação
6. Validar em produção com aprovação

#### Exemplo de Migração
```sql
-- Migração 002: Adicionar campo de departamento
ALTER TABLE users 
ADD COLUMN department_id INT,
ADD FOREIGN KEY (department_id) 
REFERENCES departments(id) 
ON DELETE SET NULL;
```

#### Ferramentas Recomendadas
- Usar migrations do Laravel ou Doctrine
- Considerar ferramentas de versionamento de banco como Liquibase
- Manter backup antes de cada alteração significativa

### Considerações Finais
- **Comunicação** é fundamental
- Sempre discuta alterações com a equipe
- Mantenha a documentação atualizada
- Priorize a integridade e consistência dos dados

---

**Nota**: Esta documentação é parte do sistema de autenticação. Consulte a equipe de desenvolvimento para alterações.
