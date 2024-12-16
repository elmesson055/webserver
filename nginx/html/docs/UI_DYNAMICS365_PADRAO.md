# Padrão de Interface - Microsoft Dynamics 365

## Sumário
1. [Estrutura de Layout](#estrutura-de-layout)
2. [Esquema de Cores](#esquema-de-cores)
3. [Tipografia](#tipografia)
4. [Componentes](#componentes)
5. [Navegação](#navegação)
6. [Responsividade](#responsividade)
7. [Boas Práticas](#boas-práticas)

## Estrutura de Layout

### Header (Cabeçalho)
- Altura: 48px
- Background: #0078D4 (Azure Blue)
- Elementos:
  - Logo à esquerda
  - Barra de pesquisa central
  - Ícones de notificação, configurações e perfil à direita
  - Menu hamburguer para telas menores

### Sidebar (Barra Lateral)
- Largura: 250px (expandida) / 48px (recolhida)
- Background: #1B1B1F (Dark Navy)
- Elementos:
  - Logo/Nome da empresa no topo
  - Menu de navegação com ícones e labels
  - Botão para recolher/expandir
  - Indicador visual do item ativo

### Área de Conteúdo Principal
- Background: #F8F9FA (Light Gray)
- Padding: 24px
- Breadcrumb no topo
- Título da página
- Área de conteúdo flexível

## Esquema de Cores

### Cores Principais
```css
--dynamics-primary: #0078D4;     /* Azure Blue - Cor principal */
--dynamics-secondary: #605E5C;   /* Gray - Textos secundários */
--dynamics-background: #FFFFFF;  /* White - Fundo geral */
--dynamics-surface: #F8F9FA;    /* Light Gray - Fundo secundário */
--dynamics-sidebar: #1B1B1F;    /* Dark Navy - Sidebar */
```

### Cores de Status
```css
--dynamics-success: #107C10;     /* Verde - Sucesso */
--dynamics-warning: #797673;     /* Amarelo - Alerta */
--dynamics-error: #D83B01;      /* Vermelho - Erro */
--dynamics-info: #0078D4;       /* Azul - Informação */
```

## Tipografia

### Família de Fontes
```css
font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
```

### Tamanhos de Fonte
- Título Principal: 24px
- Subtítulos: 18px
- Texto Regular: 14px
- Texto Pequeno: 12px

## Componentes

### Cards de Workspace
- Dimensões: 280px x auto
- Background: White
- Border: 1px solid #E1E1E1
- Border-radius: 2px
- Padding: 16px
- Sombra hover: 0 4px 8px rgba(0,0,0,0.1)

### Cards de Sumário
- Layout: Grid 4 colunas
- Background: White
- Border-radius: 2px
- Padding: 16px
- Conteúdo:
  - Número grande
  - Descrição
  - Ícone ou indicador

### Tabelas de Dados
- Header:
  - Background: #F8F9FA
  - Font-weight: 600
  - Border-bottom: 1px solid #E1E1E1
- Células:
  - Padding: 8px 16px
  - Border-bottom: 1px solid #E1E1E1
- Linhas alternadas:
  - Background hover: #F8F9FA

### Gráficos e Dashboards
- Container:
  - Background: White
  - Padding: 16px
  - Border: 1px solid #E1E1E1
  - Border-radius: 2px
- Legendas:
  - Fonte: 12px
  - Cor: #605E5C

## Navegação

### Menu Principal
- Itens:
  - Padding: 8px 16px
  - Ícone à esquerda
  - Label alinhado
  - Indicador de submenu
- Estados:
  - Hover: Background rgba(255,255,255,0.1)
  - Ativo: Background #0078D4

### Breadcrumb
- Fonte: 12px
- Cor: #605E5C
- Separador: "/"
- Último item em negrito

## Responsividade

### Breakpoints
```css
--mobile: 320px;
--tablet: 768px;
--desktop: 1024px;
--widescreen: 1440px;
```

### Adaptações Mobile
- Sidebar recolhida por padrão
- Menu hamburguer no header
- Cards em coluna única
- Tabelas com scroll horizontal

## Boas Práticas

### Performance
1. Lazy loading para imagens
2. Compressão de assets
3. Minificação de CSS/JS
4. Caching de componentes

### Acessibilidade
1. Alto contraste suficiente
2. Tamanhos de fonte ajustáveis
3. Suporte a navegação por teclado
4. Labels e aria-tags adequados

### UX/UI
1. Feedback visual para ações
2. Loading states para operações
3. Mensagens de erro claras
4. Tooltips informativos
5. Confirmação para ações destrutivas

## Exemplos de Implementação

### Card de Workspace
```html
<div class="dynamics-workspace-card">
    <div class="card-icon">
        <i class="fluent-icon"></i>
    </div>
    <div class="card-content">
        <h3>Título do Workspace</h3>
        <p>Descrição breve</p>
    </div>
</div>
```

### Card de Sumário
```html
<div class="dynamics-summary-card">
    <div class="card-value">32</div>
    <div class="card-label">Solicitações Ativas</div>
    <div class="card-icon">
        <i class="fluent-clipboard-list"></i>
    </div>
</div>
```

### Tabela de Dados
```html
<table class="dynamics-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>MR-000014</td>
            <td>Verificação de Equipamento</td>
            <td><span class="status-badge new">Novo</span></td>
            <td>
                <button class="dynamics-button">Editar</button>
            </td>
        </tr>
    </tbody>
</table>
```

## Recursos e Dependências

### Bibliotecas Recomendadas
1. Fluent UI Icons
2. Chart.js para gráficos
3. AG Grid para tabelas complexas
4. React ou Vue.js para componentes

### Assets Necessários
1. Set completo de ícones Fluent UI
2. Fontes Segoe UI
3. Imagens otimizadas
4. Sprites CSS para performance

## Manutenção e Atualizações

1. Versionamento de componentes
2. Documentação de mudanças
3. Testes de regressão visual
4. Feedback dos usuários
5. Acompanhamento de atualizações do Dynamics 365
