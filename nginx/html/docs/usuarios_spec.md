# Especificação de Tela e Funcionalidades - Gestão de Usuários

## Modelo de Dados (Tabela users)

### Campos
- `id`: Identificador único
- `username`: Nome de usuário (único)
- `password`: Senha (hash bcrypt)
- `name`: Nome completo
- `email`: E-mail (único)
- `role_id`: Função/Papel do usuário
- `department`: Departamento 
  - Opções: Transportes, Custos, Financeiro
- `active`: Status do usuário (ativo/inativo)
- `last_login`: Último login
- `created_at`: Data de criação
- `updated_at`: Data da última atualização
- `created_by`: Usuário que criou o registro
- `updated_by`: Usuário que atualizou o registro
- `deleted_at`: Data de exclusão (soft delete)
- `password_reset_token`: Token para redefinição de senha
- `password_reset_expires_at`: Validade do token de redefinição

## Funcionalidades da Tela

### Listagem de Usuários
- Tabela com colunas:
  - Nome
  - E-mail
  - Departamento
  - Função
  - Status
- Opções de filtro:
  - Por departamento
  - Por função
  - Por status (ativo/inativo)
- Botões de ação:
  - Novo Usuário
  - Editar
  - Desativar/Ativar

### Formulário de Cadastro/Edição
- Campos:
  - Nome completo (obrigatório)
  - Nome de usuário (obrigatório, único)
  - E-mail (obrigatório, único)
  - Departamento (seleção)
  - Função/Papel (seleção de roles existentes)
  - Status (ativo/inativo)
  - Senha (apenas em novo cadastro)

### Alteração de Senha
- Disponível para usuários logados
- Acessível via menu de perfil
- Campos:
  - Senha atual (obrigatório)
  - Nova senha (obrigatório)
  - Confirmação da nova senha (obrigatório)
- Regras:
  - Senha atual deve ser válida
  - Nova senha deve seguir política de senhas
  - Nova senha não pode ser igual à atual
  - Usuário deve estar logado

### Recuperação de Senha
- Disponível na tela de login ("Esqueceu a senha?")
- Processo em duas etapas:
  1. Solicitação de recuperação:
     - Usuário informa e-mail
     - Sistema envia link de recuperação
     - Link válido por 1 hora
  2. Redefinição de senha:
     - Usuário acessa link do e-mail
     - Informa nova senha
     - Confirma nova senha
- Regras de segurança:
  - Token único por solicitação
  - Expiração automática após 1 hora
  - Invalidação do token após uso
  - Notificação por e-mail
  - Mensagens genéricas para não expor dados

### Regras de Negócio
- Apenas administradores podem criar/editar usuários
- Não é possível excluir usuários, apenas desativar
- Senhas devem:
  - Ter no mínimo 8 caracteres
  - Conter letras maiúsculas e minúsculas
  - Conter números
  - Conter caracteres especiais
- Usuário não pode criar conta própria
- Cada usuário só pode ver/editar informações do próprio departamento

## Permissões Necessárias
- `manage_users`: Permissão para gerenciar usuários
- `view_users`: Permissão para visualizar usuários

## Fluxo de Autenticação
1. Login
2. Verificação de credenciais
3. Verificação de status (ativo/inativo)
4. Registro de último login
5. Redirecionamento baseado na função

## Considerações de Segurança
- Implementar proteção contra força bruta
- Limitar tentativas de login
- Registrar tentativas de login
- Criptografar senhas com bcrypt
- Implementar autenticação de dois fatores (futuro)
- Tokens de recuperação de senha seguros (32 bytes)
- Expiração automática de tokens não utilizados
- Registro de alterações de senha
- Notificações por e-mail para mudanças sensíveis

## Implementação Técnica

### Estrutura de Arquivos
```
custo-extras/
├── classes/
│   └── Router.php           # Gerenciador de rotas
├── controllers/
│   ├── AuthController.php   # Controlador de autenticação
│   └── ProfileController.php # Controlador de perfil
├── views/
│   ├── auth/
│   │   ├── login.php           # Página de login
│   │   ├── forgot-password.php # Formulário de recuperação
│   │   └── reset-password.php  # Formulário de redefinição
│   ├── profile/
│   │   └── change-password.php # Alteração de senha
│   └── errors/
│       └── 404.php            # Página de erro 404
└── routes.php                 # Configuração de rotas
```

### Rotas Implementadas
```php
// Autenticação
GET  /login                  # Página de login
POST /auth/login            # Processar login

// Recuperação de Senha
GET  /auth/forgot-password  # Formulário de recuperação
POST /auth/forgot-password  # Processar solicitação
GET  /auth/reset-password   # Formulário de redefinição
POST /auth/reset-password   # Processar nova senha

// Perfil e Alteração de Senha
GET  /profile/change-password  # Formulário de alteração
POST /profile/change-password  # Processar alteração
```

### Fluxo de Recuperação de Senha
1. Usuário clica em "Esqueceu a senha?" na tela de login
2. Sistema exibe formulário para informar e-mail
3. Após submissão:
   - Sistema gera token único
   - Salva token e data de expiração
   - Envia e-mail com link de recuperação
4. Usuário acessa link do e-mail
5. Sistema valida token e expiração
6. Usuário define nova senha
7. Sistema atualiza senha e invalida token

### Fluxo de Alteração de Senha
1. Usuário acessa opção no menu de perfil
2. Sistema exibe formulário com campos:
   - Senha atual
   - Nova senha
   - Confirmação
3. Após submissão:
   - Sistema valida senha atual
   - Verifica requisitos da nova senha
   - Atualiza senha no banco
   - Registra alteração

### Componentes do Sistema

#### Router (Router.php)
- Gerencia rotas da aplicação
- Mapeia URLs para controllers
- Trata rotas não encontradas (404)
- Executa controllers com injeção de dependências

#### AuthController
- Gerencia autenticação
- Processa recuperação de senha
- Envia e-mails de recuperação
- Valida e processa tokens

#### ProfileController
- Gerencia perfil do usuário
- Processa alteração de senha
- Valida senhas e requisitos
- Atualiza dados no banco

## Ambiente de Desenvolvimento

### Configuração Docker
- Container MySQL: `mysql_custos`
- Porta do servidor web: 8088
- Configurações de banco:
  ```php
  'host' => 'mysql_custos',
  'dbname' => 'custo_extras',
  'charset' => 'utf8',
  'username' => 'custos',
  'password' => 'custo#123'
  ```

### Estrutura de Arquivos
```
custo-extras/
├── config/
│   ├── database.php         # Configurações do banco
│   └── routes_config.php    # Configuração de rotas
├── public/
│   ├── index.php           # Ponto de entrada da aplicação
│   └── .htaccess          # Configurações do Apache
├── views/
│   ├── auth/
│   │   ├── login.php
│   │   ├── forgot-password.php
│   │   └── reset-password.php
│   └── errors/
│       └── 404.php
└── database/
    └── migrations/
        └── update_users_table.sql
```

### Arquivos na Raiz (Acesso Direto)
- `login.php`: Página principal de login
- `forgot-password.php`: Formulário de recuperação de senha
- `reset-password.php`: Redefinição de senha com token

## Implementações Realizadas

### 1. Sistema de Autenticação
- Login com email e senha
- Hash bcrypt para senhas
- Validação de usuário ativo
- Registro de último acesso
- Redirecionamento pós-login

### 2. Recuperação de Senha
#### 2.1 Solicitação (forgot-password.php)
```php
// Exemplo de implementação
if (POST) {
    - Validação do email
    - Geração de token único
    - Armazenamento do token no banco
    - Envio de email com link
}
```

#### 2.2 Redefinição (reset-password.php)
```php
// Exemplo de implementação
if (POST) {
    - Validação do token
    - Verificação de expiração
    - Atualização da senha
    - Invalidação do token
}
```

### 3. Banco de Dados
#### 3.1 Campos Adicionados
```sql
ALTER TABLE users 
ADD COLUMN password_reset_token VARCHAR(100) NULL,
ADD COLUMN password_reset_expires_at TIMESTAMP NULL,
ADD COLUMN last_login TIMESTAMP NULL;
```

#### 3.2 Índices e Constraints
```sql
CREATE INDEX idx_password_reset_token ON users(password_reset_token);
ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL;
```

### 4. Segurança Implementada
- Tokens únicos de 32 bytes
- Expiração de token em 1 hora
- Sanitização de inputs
- Proteção contra SQL injection
- Mensagens genéricas para não expor dados
- Headers de segurança no .htaccess

### 5. URLs e Endpoints
```
http://localhost:8088/login.php
http://localhost:8088/forgot-password.php
http://localhost:8088/reset-password.php?token={token}
```

### 6. Fluxos de Processo

#### 6.1 Recuperação de Senha
1. Usuário acessa login.php
2. Clica em "Esqueceu a senha?"
3. Preenche email em forgot-password.php
4. Sistema:
   - Gera token
   - Salva no banco
   - Envia email
5. Usuário:
   - Recebe email
   - Clica no link
   - Define nova senha
6. Sistema:
   - Valida token
   - Atualiza senha
   - Redireciona para login

#### 6.2 Validações
- Email existente e ativo
- Senha mínima de 8 caracteres
- Token válido e não expirado
- Confirmação de senha

### 7. Mensagens ao Usuário
- Sucesso no envio do email
- Token inválido ou expirado
- Senha alterada com sucesso
- Erros de validação

### 8. Configurações de Email
```php
$to = $email;
$subject = "Recuperação de Senha - Sistema Custo Extras";
$message = "Link de recuperação válido por 1 hora...";
$headers = "From: noreply@custoextras.com.br";
```

## Próximos Passos
1. Implementar logs de alteração de senha
2. Adicionar notificação de alteração por email
3. Implementar limite de tentativas de recuperação
4. Adicionar autenticação de dois fatores

## Configuração do Ambiente Docker

### Containers
1. **Web Server (apache_php)**
   - Imagem: `php:8.1-apache`
   - Porta: 8088:80
   - Volume principal: `C:/custo-extras:/var/www/html`
   - Extensões PHP instaladas:
     - pdo_mysql
     - mysqli
     - zip
   - Módulos Apache:
     - mod_rewrite

2. **Banco de Dados (mysql_custos)**
   - Imagem: `mysql:8.0`
   - Porta: 3307:3306
   - Credenciais:
     - Database: custo_extras
     - Usuário: custos
     - Senha: custo#123
   - Volume: `C:/backup_mysql_custo-extras:/var/lib/mysql`

3. **Composer (composer_service)**
   - Imagem: `composer:latest`
   - Volume: `C:/custo-extras:/app`
   - Modo: Serviço auxiliar para gerenciamento de dependências

### Volumes Persistentes
- Código fonte: `C:/custo-extras:/var/www/html`
- Dados MySQL: `C:/backup_mysql_custo-extras:/var/lib/mysql`
- Configuração Apache: `./custom-apache.conf:/etc/apache2/sites-available/000-default.conf`

### Comandos Úteis
```bash
# Iniciar os containers
docker-compose up -d

# Verificar status
docker-compose ps

# Acessar container web
docker exec -it apache_php bash

# Acessar MySQL
docker exec -it mysql_custos mysql -ucustos -pcusto#123 custo_extras

# Executar composer
docker exec -it composer_service composer install
```

### Portas e URLs
- Aplicação Web: http://localhost:8088
- MySQL: localhost:3307
  - Host: mysql_custos (dentro da rede Docker)
  - Host: localhost (acesso externo)
  - Porta: 3307

### Configuração do PHP
- Versão: 8.1
- Extensões habilitadas:
  - PDO MySQL
  - MySQLi
  - ZIP

### Configuração do Apache
- DocumentRoot: `/var/www/html/public`
- mod_rewrite habilitado
- Permissões ajustadas para www-data

### Backup e Persistência
- Dados do MySQL persistidos em `C:/backup_mysql_custo-extras`
- Logs do Apache em volume dedicado
- Código fonte montado em tempo real

### Troubleshooting
1. **Permissões**:
   ```bash
   docker exec apache_php chown -R www-data:www-data /var/www/html
   ```

2. **Logs**:
   ```bash
   docker logs apache_php
   docker logs mysql_custos
   ```

3. **Reiniciar Serviços**:
   ```bash
   docker-compose restart web
   docker-compose restart db
   ```

### Campos do Banco de Dados

#### Tabela users
```sql
-- Campos de autenticação
password VARCHAR(255) NOT NULL           # Hash bcrypt da senha
password_reset_token VARCHAR(100) NULL   # Token para recuperação de senha
password_reset_expires_at TIMESTAMP NULL # Data de expiração do token
last_login TIMESTAMP NULL               # Data do último login

-- Índices
idx_password_reset_token ON (password_reset_token)
idx_last_login ON (last_login)
```

### Status da Implementação

✅ Login com email e senha
✅ Recuperação de senha por email
✅ Redefinição de senha com token
✅ Registro de último login
✅ Interface moderna com Tailwind CSS
✅ Feedback visual com SweetAlert2
✅ Proteção contra SQL Injection
✅ Tokens seguros com expiração
✅ Mensagens genéricas de segurança
✅ Validações de senha forte

### Próximas Atualizações Sugeridas

1. Implementar limite de tentativas de login
2. Adicionar autenticação de dois fatores
3. Registro de histórico de alterações de senha
4. Notificação por email após alteração de senha
5. Política de expiração de senhas
6. Blacklist de senhas comuns
7. Captcha após múltiplas tentativas

## Gerenciamento de Containers Docker

### Scripts de Gerenciamento

O projeto Custo Extras fornece scripts de gerenciamento de containers Docker para facilitar o desenvolvimento e implantação.

#### Localização dos Scripts
Todos os scripts estão localizados em: `C:\backup_mysql_custo-extras\`

#### Scripts Disponíveis

1. **Criação de Containers** (`docker_create_containers.bat`)
   - Cria containers MySQL e PHP
   - Configura rede personalizada
   - Define volumes e portas
   - Configurações padrão:
     * MySQL: Porta 3306
     * PHP: Porta 8088
     * Rede: `custo_extras_network`

2. **Instalação de Extensões PHP** (`docker_php_extensions.bat`)
   - Instala dependências do sistema
   - Configura extensões PHP essenciais:
     * pdo
     * pdo_mysql
     * zip
     * gd
     * mbstring
     * xml
     * opcache
   - Instala Composer

3. **Configuração de Desenvolvimento** (`docker_dev_setup.bat`)
   - Configura permissões de arquivos
   - Instala dependências do Composer
   - Configura variáveis de ambiente
   - Executa migrações de banco de dados

4. **Gerenciamento de Containers**
   - `docker_start.bat`: Iniciar containers
   - `docker_stop.bat`: Parar containers
   - `docker_restart.bat`: Reiniciar containers

#### Ordem de Execução Recomendada

1. Criar containers:
   ```
   docker_create_containers.bat
   ```

2. Instalar extensões PHP:
   ```
   docker_php_extensions.bat
   ```

3. Configurar ambiente de desenvolvimento:
   ```
   docker_dev_setup.bat
   ```

#### Pré-requisitos

- Docker instalado
- Projeto clonado em `C:\custo-extras\`
- Conexão com a internet

#### Credenciais Padrão

- **MySQL**
  - Usuário: `custos`
  - Senha: `custo#123`
  - Banco de Dados: `custo_extras`

- **Acesso Web**
  - URL: `http://localhost:8088`

#### Solução de Problemas

- Verifique se o Docker está em execução
- Confirme que não há outros serviços usando as portas 3306 e 8088
- Consulte os logs do Docker em caso de erros

### Backup e Restauração

#### Scripts de Backup

1. **Backup de Banco de Dados** (`backup_database.bat`)
   - Realiza backup dos bancos de dados
   - Compacta backups
   - Mantém os 12 backups mais recentes

2. **Restauração de Banco de Dados** (`restore_docker_database.bat`)
   - Lista backups disponíveis
   - Permite restauração de banco de dados específico

## Layout e Componentes Visuais

### Rodapé Global

O sistema utiliza um rodapé padronizado em todas as páginas, implementado através do arquivo `footer_content.php`.

#### Características do Rodapé:
- **Localização do arquivo**: `/app/layout/footer_content.php`
- **Estilo**: 
  - Fundo branco (`bg-white`)
  - Texto em cinza (`text-gray-600`)
  - Fonte pequena (`text-xs`)
  - Borda superior (`border-t`)
  - Padding vertical de 1rem (`py-4`)
- **Conteúdo**: "2024 Elmesson Analytics. Todos os direitos reservados."
- **Alinhamento**: Centralizado (`text-center`)

#### Implementação:
1. **Em páginas que usam o layout base**:
   - O rodapé é incluído automaticamente através do `base.php`
   - Não é necessária nenhuma ação adicional

2. **Em páginas independentes**:
   ```php
   <?php include __DIR__ . '/../app/layout/footer_content.php'; ?>
   ```

#### Arquivos Afetados:
- `/app/layout/footer_content.php` - Definição do rodapé
- `/app/layout/base.php` - Layout base que inclui o rodapé
- `/public/login.php` - Página de login com inclusão independente do rodapé

#### Manutenção:
Para modificar o rodapé em todas as páginas, basta editar o arquivo `footer_content.php`. As mudanças serão refletidas automaticamente em todo o sistema.

{{ ... }}
- **Conteúdo**: "2024 Elmesson Analytics. Todos os direitos reservados."
{{ ... }}

```

```
{{ ... }}

### Gerenciamento de Cache com Redis

#### Configuração do Redis

1. **Instalação**
   - Script: `docker_redis_setup.bat`
   - Porta padrão: 6379
   - Versão: Redis 6.2 Alpine

2. **Configurações**
   - Host: `redis_custos`
   - Prefixo de chaves: `custos_`
   - TTL padrão: 1 hora
   - TTL longo: 24 horas

3. **Casos de Uso**
   - Cache de consultas frequentes
   - Sessões de usuário
   - Autenticação
   - Dados de permissões
   - Estatísticas e relatórios

#### Exemplo de Uso no Código

```php
// Salvando no cache
$redis->set('user_permissions_123', json_encode($permissions), 3600);

// Recuperando do cache
$cachedPermissions = json_decode($redis->get('user_permissions_123'), true);
```

#### Benefícios
- Armazenamento em memória
- Baixa latência
- Redução de carga no banco de dados
- Melhoria de performance
- Suporte a sessões distribuídas

{{ ... }}
