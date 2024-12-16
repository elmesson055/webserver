# Componente de Rodapé Padrão

## Descrição
O rodapé padrão é um componente reutilizável que deve ser incluído em todas as páginas do sistema. Ele exibe informações importantes como copyright, versão, ambiente e informações de suporte.

## Localização dos Arquivos
- **Componente PHP**: `/includes/footer.php`
- **Estilos CSS**: `/assets/css/footer.css`

## Como Usar

### 1. Incluir o CSS
Adicione o link para o CSS do rodapé no `<head>` da sua página:
```html
<link href="/assets/css/footer.css" rel="stylesheet">
```

### 2. Incluir e Renderizar o Rodapé
No local onde deseja exibir o rodapé, adicione:
```php
<?php
require_once __DIR__ . '/includes/footer.php';
render_footer(); // Tema claro
// ou
render_footer(true); // Tema escuro
?>
```

### 3. Configurações
As configurações do rodapé são definidas em `config/config.php`:
```php
define('APP_NAME', 'Sistema de Gestão de Custos Extras');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development');
define('COMPANY_NAME', 'Elmesson Analytics');
define('DEVELOPER_NAME', 'Elmesson');
define('SUPPORT_EMAIL', 'elmesson@outlook.com');
define('SUPPORT_PHONE', '(39) 98824-9631');
```

## Temas Disponíveis
1. **Tema Claro** (`footer-light`):
   - Fundo branco com transparência
   - Texto escuro
   - Borda superior sutil

2. **Tema Escuro** (`footer-dark`):
   - Fundo escuro com transparência
   - Texto claro
   - Sombra de texto para melhor legibilidade

## Responsividade
O componente é totalmente responsivo e se ajusta automaticamente em telas menores:
- Fonte menor em dispositivos móveis
- Padding ajustado para melhor visualização
- Espaçamento adequado do conteúdo principal

## Estrutura do HTML Gerado
```html
<footer class="footer footer-[theme]">
    <div class="copyright">
        <!-- Copyright -->
        <!-- Versão e Ambiente -->
        <!-- Desenvolvedor e Link para Documentação -->
        <!-- Informações de Suporte -->
    </div>
</footer>
```

## Considerações de Uso
1. Certifique-se de que o elemento pai tenha `position: relative`
2. Adicione `padding-bottom` adequado ao `body` para evitar sobreposição
3. O rodapé sempre ficará no final da página, mesmo com pouco conteúdo
4. Em páginas de scroll, o rodapé permanecerá fixo na parte inferior

## Manutenção
Para atualizar as informações exibidas no rodapé:
1. Edite as constantes em `config/config.php`
2. As alterações serão refletidas em todas as páginas que usam o componente

## Exemplos de Uso

### Página Normal
```php
<!DOCTYPE html>
<html>
<head>
    <!-- Outros CSS -->
    <link href="/assets/css/footer.css" rel="stylesheet">
</head>
<body>
    <!-- Conteúdo da página -->
    
    <?php
    require_once __DIR__ . '/includes/footer.php';
    render_footer(); // Tema claro
    ?>
</body>
</html>
```

### Página de Login/Dark Mode
```php
<!DOCTYPE html>
<html>
<head>
    <!-- Outros CSS -->
    <link href="/assets/css/footer.css" rel="stylesheet">
</head>
<body class="dark-mode">
    <!-- Conteúdo da página -->
    
    <?php
    require_once __DIR__ . '/includes/footer.php';
    render_footer(true); // Tema escuro
    ?>
</body>
</html>
```
