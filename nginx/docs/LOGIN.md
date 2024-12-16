# Documentação Técnica - Tela de Login

## Visão Geral
A tela de login do Sistema de Custos Extras foi implementada seguindo o padrão visual do Microsoft Dynamics 365, com um layout moderno e dividido. Esta documentação detalha a implementação, estrutura e manutenção da tela de login.

## Estrutura de Arquivos

```
public/
├── views/
│   └── auth/
│       └── login.php      # Template principal da tela de login
├── assets/
│   └── css/
│       └── login.css      # Estilos específicos da tela de login
```

## Componentes Principais

### 1. Layout Dividido
O layout é dividido em duas seções principais:

```html
<div class="login-container">
    <div class="login-form-container">
        <!-- Formulário de login -->
    </div>
    <div class="login-image-container">
        <!-- Imagem de fundo -->
    </div>
</div>
```

#### Lado Esquerdo (Formulário)
- Fundo semi-transparente com efeito de blur
- Formulário centralizado
- Logo e campos de entrada estilizados

#### Lado Direito (Imagem)
- Imagem de fundo em tamanho original
- Carregada dinamicamente via JavaScript
- Mantém proporção e cobertura total

### 2. Estilos CSS

#### Containers Principais
```css
.login-container {
    min-height: 100vh;
    display: flex;
}

.login-form-container {
    width: 50%;
    background: rgba(255, 255, 255, 0.95);
}

.login-image-container {
    width: 50%;
    background-size: cover;
}
```

#### Efeito de Blur
```css
.login-form-container::before {
    content: '';
    position: absolute;
    background: inherit;
    backdrop-filter: blur(10px);
    z-index: -1;
}
```

### 3. Funcionalidades JavaScript

#### Toggle de Visibilidade da Senha
```javascript
const togglePassword = document.querySelector('#togglePassword');
togglePassword.addEventListener('click', function() {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
});
```

#### Carregamento da Imagem de Fundo
```javascript
const bgImage = document.getElementById('bgImage');
bgImage.style.backgroundImage = 'url(https://source.unsplash.com/random/1920x1080/?workspace,office)';
```

## Responsividade

### Breakpoints
```css
@media (max-width: 768px) {
    .login-container {
        flex-direction: column;
    }
    
    .login-form-container,
    .login-image-container {
        width: 100%;
    }
}
```

## Segurança

### Validações
1. Campos obrigatórios marcados com `required`
2. Validação de email no campo de usuário
3. Proteção contra CSRF através do token Laravel
4. Sanitização de inputs no backend

### Mensagens de Erro
```php
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
        <?php echo $_SESSION['message']; ?>
    </div>
<?php endif; ?>
```

## Manutenção e Customização

### Alterando a Logo
1. Localize a div com classe `login-logo`
2. Modifique o conteúdo do `h1` conforme necessário
3. Ajuste as cores através das classes CSS

### Modificando a Imagem de Fundo
1. Altere a URL no JavaScript:
```javascript
bgImage.style.backgroundImage = 'url(sua-nova-url-aqui)';
```

### Personalizando Cores
As principais variáveis de cor estão definidas no CSS:
```css
:root {
    --primary-color: #2d3748;
    --accent-color: #fbbf24;
    --text-color: #2d3748;
    --border-color: #e2e8f0;
}
```

## Solução de Problemas

### Problemas Comuns

1. **Imagem de Fundo Não Carrega**
   - Verifique a conexão com internet
   - Confirme se a URL da imagem está acessível
   - Verifique console do navegador para erros

2. **Efeito de Blur Não Funciona**
   - Confirme suporte do navegador a `backdrop-filter`
   - Verifique se o z-index está correto
   - Tente adicionar prefixos de vendor (-webkit-, -moz-)

3. **Responsividade Incorreta**
   - Verifique meta viewport tag
   - Confirme breakpoints no CSS
   - Teste em diferentes dispositivos

### Depuração

1. **Verificar Carregamento de Recursos**
   ```javascript
   console.log('Login.js carregado');
   console.log('Tentando carregar imagem de fundo...');
   ```

2. **Monitorar Eventos de Formulário**
   ```javascript
   form.addEventListener('submit', (e) => {
       console.log('Formulário submetido');
       console.log('Dados:', new FormData(form));
   });
   ```

## Boas Práticas

1. **Performance**
   - Otimize imagens antes do upload
   - Minimize requisições HTTP
   - Use cache quando apropriado

2. **Acessibilidade**
   - Mantenha contraste adequado
   - Use labels apropriados
   - Implemente navegação por teclado

3. **Segurança**
   - Implemente rate limiting
   - Use HTTPS
   - Sanitize todas as entradas
   - Implemente políticas de senha fortes

## Referências

1. [Microsoft Dynamics 365 Design Guidelines](https://docs.microsoft.com/en-us/dynamics365/get-started/design)
2. [MDN Web Docs - Backdrop Filter](https://developer.mozilla.org/en-US/docs/Web/CSS/backdrop-filter)
3. [Bootstrap Documentation](https://getbootstrap.com/docs/5.1)
