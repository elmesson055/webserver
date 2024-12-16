# Configuração do Banco de Dados

## Credenciais
- **Host:** localhost
- **Banco:** logistica_transportes
- **Usuário:** logistica_admin
- **Senha:** LOg1s1ca2024!
- **Charset:** utf8

## Configuração PDO
```php
$config = [
    'host' => 'localhost',
    'dbname' => 'logistica_transportes',
    'username' => 'logistica_admin',
    'password' => 'LOg1s1ca2024!',
    'charset' => 'utf8',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 5
    ]
];
```

## Verificação de Conexão
Para verificar se as credenciais estão corretas, execute no MySQL:

```sql
-- Verificar se o usuário existe
SELECT User, Host FROM mysql.user WHERE User = 'logistica_admin';

-- Verificar permissões do usuário
SHOW GRANTS FOR 'logistica_admin'@'localhost';
```

## Troubleshooting
Se houver erro de conexão, verifique:
1. Se o serviço MySQL está rodando
2. Se o usuário existe no banco
3. Se a senha está correta
4. Se o usuário tem permissão no banco de dados
5. Se o banco de dados existe
