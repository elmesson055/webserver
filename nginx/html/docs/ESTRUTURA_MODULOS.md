# Estrutura e Padrões de Desenvolvimento

Este documento descreve a estrutura padrão dos módulos e as diretrizes de desenvolvimento que devem ser seguidas ao criar ou modificar módulos no sistema.

## Estrutura de Diretórios

Cada módulo deve seguir a seguinte estrutura de diretórios:

```
/app/modules/nome_modulo/
├── .htaccess           # Configurações de acesso e redirecionamento
├── index.php          # Redirecionamento para views/index.php
├── functions.php      # Funções específicas do módulo
├── config/           # Configurações específicas do módulo
│   └── config.php
├── models/           # Classes de modelo
│   └── *.php
├── controllers/      # Controladores
│   └── *.php
├── views/           # Arquivos de visualização
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   └── components/  # Componentes reutilizáveis
└── assets/         # Recursos específicos do módulo
    ├── js/
    ├── css/
    └── img/
```

## Diretrizes de Desenvolvimento

### 1. Organização de Arquivos

#### 1.1 Views (views/)
- Arquivos de interface do usuário
- Nomenclatura: descritiva e em minúsculas (ex: `lista_usuarios.php`)
- Componentes reutilizáveis devem ficar em `views/components/`

#### 1.2 Models (models/)
- Classes de modelo para manipulação de dados
- Nomenclatura: PascalCase (ex: `UsuarioModel.php`)
- Um arquivo por classe
- Deve conter apenas lógica relacionada aos dados

#### 1.3 Controllers (controllers/)
- Classes controladoras para lógica de negócio
- Nomenclatura: PascalCase (ex: `UsuarioController.php`)
- Deve mediar a interação entre Models e Views

#### 1.4 Assets (assets/)
- JavaScript: `assets/js/`
- CSS: `assets/css/`
- Imagens: `assets/img/`
- Nomenclatura: minúsculas com hífens (ex: `style-main.css`)

### 2. Padrões de Código

#### 2.1 PHP
- Indentação: 4 espaços
- Chaves em nova linha
- Nomes de variáveis em camelCase
- Nomes de classes em PascalCase
- Comentários descritivos para funções
- Validação de entrada de dados
- Tratamento de erros com try/catch

```php
class UsuarioController 
{
    public function create($dados)
    {
        try {
            // Validação
            if (empty($dados['nome'])) {
                throw new Exception('Nome é obrigatório');
            }

            // Processamento
            $model = new UsuarioModel();
            return $model->criar($dados);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
```

#### 2.2 JavaScript
- Usar ES6+ quando possível
- Modularizar código
- Evitar variáveis globais
- Usar strict mode

```javascript
'use strict';

const Usuario = {
    init() {
        this.bindEvents();
    },

    bindEvents() {
        document.querySelector('.form-usuario').addEventListener('submit', this.handleSubmit);
    },

    handleSubmit(e) {
        e.preventDefault();
        // Lógica de submissão
    }
};
```

#### 2.3 CSS
- Usar BEM para nomenclatura de classes
- Organizar por componentes
- Evitar !important
- Usar variáveis CSS para temas

```css
/* Exemplo BEM */
.usuario-card {
    /* Estilos do card */
}

.usuario-card__titulo {
    /* Estilos do título */
}

.usuario-card--destaque {
    /* Modificador para cards em destaque */
}
```

### 3. Segurança

#### 3.1 Autenticação
- Validar sessão em todas as páginas restritas
- Usar hash seguro para senhas (password_hash)
- Implementar proteção contra CSRF
- Usar HTTPS para transmissão de dados

#### 3.2 Banco de Dados
- Usar prepared statements
- Escapar dados de saída
- Validar inputs
- Limitar permissões do usuário do banco

#### 3.3 Arquivos
- Validar uploads
- Limitar tipos de arquivo
- Usar nomes aleatórios para arquivos
- Armazenar fora da raiz web

### 4. Performance

#### 4.1 Front-end
- Minificar CSS/JS em produção
- Otimizar imagens
- Usar lazy loading
- Implementar cache de browser

#### 4.2 Back-end
- Usar cache quando possível
- Otimizar queries
- Paginar resultados grandes
- Implementar rate limiting

### 5. Testes

#### 5.1 Estrutura de Testes
- Testes unitários em `/app/modules/tests/`
- Separar por módulo (ex: `/tests/auth/`)
- Nomear arquivos descritivamente
- Documentar casos de teste

### 6. Versionamento

#### 6.1 Git
- Commits descritivos
- Uma feature por branch
- Code review antes do merge
- Seguir GitFlow

### 7. Documentação

#### 7.1 Código
- Documentar funções complexas
- Explicar regras de negócio
- Manter README atualizado
- Documentar APIs

#### 7.2 Banco de Dados
- Documentar esquema
- Manter scripts SQL em `/app/modules/database/sql/`
- Versionar alterações
- Documentar procedures

## Exemplos

### Estrutura Básica de um Módulo Novo

1. Criar estrutura de diretórios:
```bash
mkdir -p app/modules/novo_modulo/{views,models,controllers,config,assets/{js,css,img}}
```

2. Criar arquivos base:
```bash
touch app/modules/novo_modulo/index.php
touch app/modules/novo_modulo/.htaccess
touch app/modules/novo_modulo/functions.php
touch app/modules/novo_modulo/config/config.php
```

### Implementação Básica

1. index.php:
```php
<?php
header('Location: views/index.php');
exit();
```

2. .htaccess:
```apache
DirectoryIndex views/index.php
RewriteEngine On
RewriteRule ^index\.php$ views/index.php [L]
```

3. functions.php:
```php
<?php
if (!defined('BASE_PATH')) exit();

function modulo_funcao_exemplo() {
    // Implementação
}
```

## Conclusão

Seguir estas diretrizes garante:
- Código consistente e manutenível
- Melhor organização do projeto
- Facilidade para novos desenvolvedores
- Segurança e performance otimizadas

Mantenha este documento atualizado conforme o projeto evolui.
