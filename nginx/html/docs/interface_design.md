# Documentação da Interface do Sistema Custo Extras

## 1. Estrutura de Arquivos

```
custo-extras/
├── assets/
│   ├── css/
│   │   ├── sidebar.css    # Estilos do menu lateral
│   │   └── style.css      # Estilos globais
│   └── images/
│       ├── logo.png       # Logo do sistema
│       └── user-avatar.png # Avatar padrão do usuário
├── views/
│   ├── includes/
│   │   ├── header.php     # Cabeçalho comum
│   │   ├── footer.php     # Rodapé comum
│   │   └── sidebar.php    # Menu lateral
│   └── [módulos]/         # Páginas específicas dos módulos
```

## 2. Menu Lateral (Sidebar)

### 2.1. Estrutura HTML
O menu lateral é implementado no arquivo `sidebar.php` e consiste em:

- Logo e nome do sistema
- Seções de menu com ícones
- Área do usuário
- Links de ação rápida

### 2.2. Seções do Menu

#### Dashboard
```php
<div class="menu-section">
    <a href="/views/dashboard" class="menu-item">
        <div class="menu-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <span>Dashboard</span>
    </a>
</div>
```

#### Cadastros
- Embarcadores
- Fornecedores
- Clientes
- Motoristas

#### Custos Extras
- Follow-up
- Aprovação
- Financeiro

#### Relatórios
- Análise de Custos
- Performance

#### Configurações
- Usuários
- Tipos
- Parâmetros

### 2.3. Estilos CSS

#### Cores Principais
```css
:root {
    --primary-color: #3498db;    /* Azul principal */
    --secondary-color: #2c3e50;  /* Azul escuro */
    --success-color: #2ecc71;    /* Verde */
    --warning-color: #f1c40f;    /* Amarelo */
    --danger-color: #e74c3c;     /* Vermelho */
}
```

#### Classes CSS Principais

- `.sidebar`: Container principal do menu
- `.menu-section`: Seção de menu
- `.menu-item`: Item do menu
- `.menu-icon`: Ícone do menu
- `.sidebar-footer`: Área do usuário

### 2.4. Responsividade e Otimização de Espaço

O menu lateral foi otimizado para melhor aproveitamento do espaço vertical, permitindo visualizar todos os itens sem necessidade de rolagem excessiva:

#### Dimensões Otimizadas
```css
.sidebar {
    width: 200px;        /* Largura base otimizada */
    padding: 0.25rem;    /* Padding reduzido */
}

.menu-item {
    padding: 0.35rem 0.75rem;  /* Espaçamento interno reduzido */
    min-height: 28px;          /* Altura mínima para clicabilidade */
}

.menu-icon {
    width: 16px;         /* Ícones menores */
    height: 16px;
    flex-shrink: 0;      /* Previne distorção */
}
```

#### Tipografia Otimizada
```css
.sidebar-brand-text {
    font-size: 0.9rem;   /* Título menor */
}

.menu-item span {
    font-size: 0.8rem;   /* Texto dos itens menor */
}

.menu-header {
    font-size: 0.7rem;   /* Cabeçalhos de seção menores */
}
```

#### Espaçamentos Reduzidos
```css
.menu-section {
    margin-bottom: 0.5rem;  /* Menor espaço entre seções */
}

.sidebar-brand {
    padding: 0.5rem;       /* Cabeçalho mais compacto */
    gap: 0.5rem;
}
```

#### Responsividade Aprimorada
```css
@media (max-width: 768px) {
    .sidebar {
        width: 180px;      /* Ainda mais compacto em telas menores */
    }
    
    .menu-icon {
        width: 14px;       /* Ícones ainda menores */
        height: 14px;
    }
    
    .menu-item span {
        font-size: 0.75rem;  /* Texto ainda menor */
    }
}
```

#### Scrollbar Otimizada
```css
.sidebar {
    overflow-y: auto;
    scrollbar-width: thin;
}

.sidebar::-webkit-scrollbar {
    width: 4px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
}
```

Estas otimizações garantem:
- Melhor visualização de todos os itens do menu
- Redução do uso de scroll
- Manutenção da usabilidade
- Interface mais compacta e eficiente
- Adaptação adequada a diferentes tamanhos de tela

### 2.5. Estilos CSS

#### Cores Principais
```css
:root {
    --primary-color: #3498db;    /* Azul principal */
    --secondary-color: #2c3e50;  /* Azul escuro */
    --success-color: #2ecc71;    /* Verde */
    --warning-color: #f1c40f;    /* Amarelo */
    --danger-color: #e74c3c;     /* Vermelho */
}
```

#### Classes CSS Principais

- `.sidebar`: Container principal do menu
- `.menu-section`: Seção de menu
- `.menu-item`: Item do menu
- `.menu-icon`: Ícone do menu
- `.sidebar-footer`: Área do usuário

## 3. Estilo Global

### 3.1. Componentes Estilizados

#### Cards
```css
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}
```

#### Botões
```css
.btn {
    border-radius: 5px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
}
```

#### Tabelas
```css
.table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
}
```

### 3.2. Animações

```css
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.5s ease forwards;
}
```

## 4. Integração com Frameworks

### 4.1. Dependências Externas
```html
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
```

### 4.2. Plugins Utilizados
- DataTables para tabelas interativas
- Select2 para selects aprimorados
- Máscaras de input com jQuery Mask

## 5. Manutenção e Atualizações

### 5.1. Adicionando Novos Itens ao Menu

1. Adicione o HTML no arquivo `sidebar.php`:
```php
<a href="/views/novo-modulo" class="menu-item">
    <div class="menu-icon">
        <i class="fas fa-icon-name"></i>
    </div>
    <span>Novo Módulo</span>
</a>
```

2. Adicione os estilos específicos em `sidebar.css` se necessário

### 5.2. Personalizando Temas

Para alterar o tema do sistema:

1. Modifique as variáveis CSS em `style.css`:
```css
:root {
    --primary-color: #seu-valor;
    --secondary-color: #seu-valor;
    /* ... outras cores ... */
}
```

2. Atualize os estilos específicos em `sidebar.css`

## 6. Boas Práticas

### 6.1. Estrutura de Páginas
- Use o template base (header.php + sidebar.php + footer.php)
- Mantenha o conteúdo dentro de `<div class="main-content">`
- Utilize os componentes e classes padrão

### 6.2. Responsividade
- Teste em diferentes dispositivos
- Use as classes do Bootstrap para grid
- Verifique o comportamento do menu lateral

### 6.3. Performance
- Otimize imagens
- Use lazy loading quando apropriado
- Minimize requisições HTTP

## 7. Troubleshooting

### Problemas Comuns e Soluções

1. Menu não aparece:
   - Verifique se `sidebar.php` está sendo incluído em `header.php`
   - Confirme que os arquivos CSS estão sendo carregados

2. Ícones não aparecem:
   - Verifique se Font Awesome está carregado
   - Confirme o nome correto do ícone

3. Problemas de responsividade:
   - Verifique as media queries
   - Teste em diferentes navegadores

## 8. Futuras Melhorias

### 8.1. Sugestões de Implementação
- Tema escuro
- Menu lateral recolhível
- Notificações em tempo real
- Customização por usuário
- Atalhos de teclado

### 8.2. Otimizações Planejadas
- Melhorar performance em dispositivos móveis
- Adicionar mais animações
- Implementar temas customizáveis
- Melhorar acessibilidade
