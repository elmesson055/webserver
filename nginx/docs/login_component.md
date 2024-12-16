# Componente de Login

## Descrição
Este documento descreve as especificações e estilos do componente de login do sistema.

## Estrutura do Login

### Dimensões do Container
```css
.login-container {
    width: 90%;
    max-width: 320px;
    padding: 1.5rem;
}
```

### Elementos Visuais
- Background: Branco semi-transparente (rgba(255, 255, 255, 0.95))
- Border Radius: 8px
- Box Shadow: Sutil (0 2px 10px rgba(0, 0, 0, 0.1))

## Especificações de Estilo

### Cabeçalho
- Margem inferior: 1.2rem
- Tamanho da fonte do título: 1.5rem
- Cor do título: #333

### Campos de Entrada
- Padding: 0.5rem 0.8rem 0.5rem 2rem
- Tamanho da fonte: 0.9rem
- Borda: 1px solid #ddd
- Border Radius: 4px

### Botão de Login
- Largura: 100%
- Padding: 0.6rem
- Tamanho da fonte: 0.9rem
- Background: #007bff
- Border Radius: 4px
- Margem superior: 0.8rem

### Espaçamentos
- Entre grupos de form: 0.8rem
- Entre elementos: 0.8rem

## Como Implementar

### 1. Incluir CSS Necessário
```html
<link href="/assets/css/login.css" rel="stylesheet">
```

### 2. Estrutura HTML Básica
```html
<div class="login-container">
    <div class="login-header">
        <h1>Login</h1>
    </div>
    
    <form method="post" action="login.php">
        <div class="form-group">
            <input type="text" 
                   class="form-control" 
                   name="username" 
                   placeholder="Usuário">
        </div>
        
        <div class="form-group">
            <input type="password" 
                   class="form-control" 
                   name="password" 
                   placeholder="Senha">
        </div>
        
        <button type="submit" class="login-button">
            Entrar
        </button>
    </form>
</div>
```

## Responsividade

### Mobile (<768px)
- Container mantém 90% da largura da tela
- Máximo de 320px de largura
- Espaçamentos reduzidos proporcionalmente

### Desktop (≥768px)
- Container mantém largura máxima de 320px
- Centralizado na tela
- Espaçamentos padrão mantidos

## Personalização

### Cores
Para alterar as cores principais, modifique as seguintes propriedades:
```css
.login-container {
    background: rgba(255, 255, 255, 0.95);
}

.login-button {
    background: #007bff;
}
```

### Tamanhos
Para ajustar o tamanho do container:
```css
.login-container {
    max-width: 320px; /* Ajuste conforme necessário */
    padding: 1.5rem; /* Ajuste conforme necessário */
}
```

## Boas Práticas

1. **Acessibilidade**
   - Manter contraste adequado entre texto e background
   - Incluir labels para campos de formulário
   - Usar atributos aria quando necessário

2. **Segurança**
   - Sempre usar HTTPS
   - Implementar proteção contra CSRF
   - Limitar tentativas de login

3. **Performance**
   - Minimizar CSS em produção
   - Usar compressão de assets
   - Implementar cache adequado

## Manutenção

### Arquivos Relacionados
- `/assets/css/login.css` - Estilos do componente
- `/login.php` - Lógica de autenticação
- `/includes/footer.php` - Componente do rodapé

### Atualizações
1. Sempre testar em múltiplos dispositivos
2. Verificar compatibilidade cross-browser
3. Manter documentação atualizada

## Suporte
Para dúvidas ou problemas:
- Email: elmesson@outlook.com
- Tel: (38) 98824-9631
