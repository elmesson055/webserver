# Configuração do Sistema

## Descrição
Este documento descreve todas as configurações globais do sistema definidas em `config.php`.

## Configurações Principais

### Informações da Aplicação
```php
define('APP_NAME', 'Sistema de Gestão de Custos Extras');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // Pode ser: 'development', 'staging', 'production'
```

### Informações da Empresa
```php
define('COMPANY_NAME', 'Elmesson Analytics');
define('DEVELOPER_NAME', 'Elmesson');
```

### Informações de Contato
```php
define('SUPPORT_EMAIL', 'elmesson@outlook.com');
define('SUPPORT_PHONE', '(38) 98824-9631');
```

### Caminhos do Sistema
```php
define('BASE_PATH', __DIR__ . '/..');
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('ASSETS_PATH', BASE_PATH . '/assets');
```

## Como Utilizar

### Acessando Configurações
```php
// Em qualquer arquivo PHP do sistema
echo APP_NAME; // Retorna: Sistema de Gestão de Custos Extras
echo COMPANY_NAME; // Retorna: Elmesson Analytics
```

### Incluindo o Arquivo de Configuração
```php
require_once __DIR__ . '/config/config.php';
```

## Modificando Configurações

### Ambiente de Desenvolvimento
1. Abra o arquivo `config.php`
2. Localize a constante desejada
3. Modifique o valor conforme necessário
4. Salve o arquivo

### Ambiente de Produção
1. Crie um backup do arquivo atual
2. Faça as alterações necessárias
3. Teste em ambiente de staging
4. Deploy para produção

## Boas Práticas

1. **Segurança**
   - Nunca compartilhe credenciais sensíveis
   - Use variáveis de ambiente para dados sensíveis
   - Mantenha backups seguros

2. **Manutenção**
   - Documente todas as alterações
   - Mantenha um histórico de versões
   - Teste após modificações

3. **Organização**
   - Agrupe constantes relacionadas
   - Use nomes descritivos
   - Mantenha comentários atualizados

## Troubleshooting

### Problemas Comuns

1. **Constante não definida**
   - Verificar se config.php está incluído
   - Confirmar nome da constante
   - Verificar case-sensitivity

2. **Valor incorreto**
   - Verificar arquivo de configuração
   - Confirmar ambiente atual
   - Verificar cache do sistema

## Suporte
Para dúvidas ou problemas:
- Email: elmesson@outlook.com
- Tel: (38) 98824-9631
