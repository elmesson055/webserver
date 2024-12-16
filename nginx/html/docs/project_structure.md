# Estrutura do Projeto

## Diretórios Principais
```
c:/webserver/nginx/html/
├── app/
│   ├── classes/
│   │   └── Database.php         # Classe de conexão com banco de dados
│   ├── modules/
│   │   ├── auth/
│   │   │   └── login.php        # Página de login principal
│   │   └── common/
│   │       └── sidebar.php      # Componente da barra lateral
│   ├── Views/
│   │   └── dashboard/
│   │       └── index.php        # Dashboard principal após login
│   ├── functions.php            # Funções globais do sistema
│   └── init.php                 # Inicialização do sistema
├── config/
│   ├── database.php             # Configurações do banco de dados
│   └── session.php              # Configurações de sessão
├── docs/                        # Documentação do projeto
└── sql/                         # Scripts SQL para setup do banco
```

## Rotas Principais

| URL | Arquivo Físico | Descrição |
|-----|---------------|------------|
| `/app/modules/auth/login.php` | `app/modules/auth/login.php` | Página de login |
| `/app/Views/dashboard/index.php` | `app/Views/dashboard/index.php` | Dashboard principal |

## Fluxo de Autenticação

1. O usuário acessa `/app/modules/auth/login.php`
2. Após login bem-sucedido, é redirecionado para `/app/Views/dashboard/index.php`
3. O dashboard verifica a sessão e permissões do usuário
4. A barra lateral (sidebar) é carregada com base nas permissões do usuário

## Arquivos de Configuração

### Nginx (c:/webserver/nginx/conf/nginx.conf)
```nginx
location ~ \.php$ {
    try_files $uri =404;
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass   127.0.0.1:9000;
    fastcgi_index  index.php;
    fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
    include        fastcgi_params;
    fastcgi_param  PATH_INFO $fastcgi_path_info;
}
```

### Database (config/database.php)
- Host: localhost
- Database: logistica
- Charset: utf8mb4
- Collation: utf8mb4_unicode_ci

## Tabelas Principais

### users
- id (PK)
- username
- email
- password_hash
- role_id (FK)
- is_active
- last_login
- created_at
- updated_at

### permissions
- id (PK)
- role_id (FK)
- module_id (FK)
- can_view
- can_create
- can_edit
- can_delete

### modules
- id (PK)
- name
- icon
- url
- order
- is_active

## Credenciais de Teste
- Username: admin_logistica
- Password: Log@2024#Adm

## Procedimentos de Manutenção

### Reiniciar Nginx
1. Abrir prompt de comando como administrador
2. Navegar até `c:/webserver/nginx`
3. Executar: `nginx -s reload`

### Logs
- Logs do PHP: `c:/webserver/nginx/logs/php_errors.log`
- Logs do Nginx: `c:/webserver/nginx/logs/error.log`

## Observações Importantes
1. Sempre manter esta documentação atualizada ao fazer alterações no sistema
2. Seguir a estrutura de diretórios estabelecida
3. Não criar arquivos duplicados
4. Manter as rotas consistentes com a documentação
5. Documentar quaisquer alterações nas configurações
