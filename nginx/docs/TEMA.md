# Documentação do Tema - Microsoft Dynamics 365

## Visão Geral
Esta documentação descreve a implementação do tema visual baseado no Microsoft Dynamics 365 para o Sistema de Custos Extras. O tema foi desenvolvido para proporcionar uma experiência de usuário moderna, consistente e responsiva.

## Estrutura de Arquivos

```
public/
├── assets/
│   ├── css/
│   │   └── dynamics-theme.css    # Estilos do tema
│   └── js/
│       └── dynamics-theme.js     # Interações do tema
└── views/
    └── includes/
        ├── header.php            # Cabeçalho com navbar
        ├── sidebar.php           # Menu lateral
        └── footer.php            # Rodapé com scripts
```

## Componentes Principais

### 1. Variáveis CSS
O tema utiliza variáveis CSS para manter consistência nas cores e facilitar personalizações:

```css
:root {
    --ms-blue: #0078d4;          /* Cor principal */
    --ms-light-blue: #106ebe;    /* Cor secundária */
    --ms-bg-light: #f8f9fa;      /* Fundo claro */
    --ms-text: #323130;          /* Cor do texto */
    --ms-border: #edebe9;        /* Cor das bordas */
    --ms-hover: #f3f2f1;         /* Cor de hover */
}
```

### 2. Layout Principal
O layout é dividido em três áreas principais:

1. **Navbar** (48px altura)
   - Posição fixa no topo
   - Contém logo, busca e ações do usuário
   - Responsivo em telas menores

2. **Sidebar** (280px largura)
   - Menu lateral fixo
   - Suporte a submenus expansíveis
   - Retrátil em telas menores

3. **Área de Conteúdo**
   - Margem à esquerda de 280px
   - Padding de 2rem
   - Ajusta-se quando sidebar está retraída

## Funcionalidades JavaScript

### 1. Gerenciamento de Menu
```javascript
// Expandir/retrair submenus
document.querySelectorAll('.sidebar-group > a').forEach(function(element) {
    element.addEventListener('click', function(e) {
        e.preventDefault();
        const submenu = this.nextElementSibling;
        submenu.classList.toggle('show');
    });
});
```

### 2. Responsividade
```javascript
// Toggle do menu em dispositivos móveis
const toggleButton = document.querySelector('[data-bs-toggle="sidebar"]');
const sidebar = document.querySelector('.sidebar');

toggleButton.addEventListener('click', function() {
    sidebar.classList.toggle('show');
});
```

## Classes CSS Principais

### 1. Navbar
```css
.navbar {
    background-color: var(--ms-blue);
    height: 48px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
```

### 2. Sidebar
```css
.sidebar {
    width: 280px;
    height: calc(100vh - 48px);
    position: fixed;
    background: white;
}

.sidebar-item {
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
```

### 3. Cards
```css
.card {
    border-radius: 2px;
    border: 1px solid var(--ms-border);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
```

## Responsividade

### Breakpoints
- **lg** (992px): Ponto principal para alternar entre layout desktop e móvel

### Comportamentos Responsivos
```css
@media (max-width: 992px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s;
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .content-area {
        margin-left: 0;
    }
}
```

## Dependências Externas

1. **Bootstrap 5.1.3**
   - Framework CSS base
   - Componentes responsivos
   - Sistema de grid

2. **Font Awesome 6.0.0**
   - Ícones consistentes
   - Suporte a animações

## Manutenção e Atualizações

### Modificando Cores
Para alterar as cores do tema, modifique as variáveis CSS no arquivo `dynamics-theme.css`:

```css
:root {
    --ms-blue: #seu-novo-azul;
    --ms-light-blue: #seu-novo-azul-claro;
    /* ... */
}
```

### Adicionando Novos Itens ao Menu
1. Adicione o novo item em `sidebar.php`:
```php
<a href="/sua-rota" class="sidebar-item">
    <i class="fas fa-seu-icone"></i>
    <span>Seu Item</span>
</a>
```

2. Para submenu:
```php
<div class="sidebar-group">
    <a href="#seuSubmenu" class="sidebar-item" data-bs-toggle="collapse">
        <!-- ... -->
    </a>
    <div class="collapse" id="seuSubmenu">
        <!-- Itens do submenu -->
    </div>
</div>
```

## Solução de Problemas

### Menu não Expande
1. Verifique se o Bootstrap JS está carregado
2. Confirme que os IDs dos submenus são únicos
3. Verifique erros no console do navegador

### Estilo Não Aplicado
1. Confirme que o arquivo CSS está sendo carregado
2. Verifique a ordem de carregamento dos CSS
3. Inspecione os elementos para conflitos de estilo

### Problemas de Responsividade
1. Teste em diferentes navegadores
2. Verifique breakpoints no CSS
3. Confirme que o viewport meta tag está presente

## Considerações de Performance

1. **CSS**
   - Minifique em produção
   - Use específicidade adequada
   - Evite aninhamento excessivo

2. **JavaScript**
   - Carregue de forma assíncrona
   - Use delegação de eventos
   - Evite manipulação excessiva do DOM

3. **Assets**
   - Comprima imagens
   - Use cache adequado
   - Considere lazy loading

## Acessibilidade

1. **Navegação por Teclado**
   - Todos os itens são focáveis
   - Tab order lógico
   - Indicadores visuais de foco

2. **ARIA Labels**
   - Botões têm descrições
   - Menus têm roles apropriados
   - Estados são comunicados

3. **Contraste**
   - Texto legível
   - Cores acessíveis
   - Ícones com contraste adequado
