# Design System - Custo Extras

## 1. Interface de Login

### 1.1 Elementos Visuais
- **Logo**: Design minimalista com "#" em amarelo (#ecc94b)
- **Background**: Fundo limpo em cinza claro (#f5f5f5)
- **Container**: Branco com transparência (rgba(255, 255, 255, 0.95))
- **Sombras**: Suave elevação com blur (shadow-lg)

### 1.2 Tipografia
- **Fonte Principal**: Inter (Google Fonts)
- **Pesos**: 400 (regular), 500 (medium), 600 (semibold), 700 (bold)
- **Tamanhos**:
  - Logo: text-4xl (36px)
  - Inputs: text-sm (14px)
  - Botão: text-sm com uppercase

### 1.3 Formulário
- **Campos de Entrada**:
  ```css
  .form-input {
      border: none;
      border-bottom: 1px solid #e2e8f0;
      border-radius: 0;
      padding: 0.75rem 0;
      background: transparent;
  }
  ```
- **Ícones**: 
  - Cor: #a0aec0
  - Tamanho: w-5 h-5
  - Posição: Absoluta à direita

### 1.4 Botões
- **Principal (Login)**:
  ```css
  .login-btn {
      background-color: #2d3748;
      transition: all 0.3s ease;
  }
  .login-btn:hover {
      background-color: #1a202c;
  }
  ```

### 1.5 Mensagens de Erro
- Cor: text-red-600
- Tamanho: text-sm
- Alinhamento: text-center
- Margem inferior: mb-6

## 2. CRUD Generator

### 2.1 Componentes Padrão

#### Listagens
```php
class ListComponent {
    protected $columns;
    protected $filters;
    protected $pagination;
    protected $actions;
}
```

#### Formulários
```php
class FormComponent {
    protected $fields;
    protected $validation;
    protected $layout;
}
```

### 2.2 Estrutura de Arquivos
```
/app
  /Generators
    /Templates
      list.php
      form.php
      filter.php
    CrudGenerator.php
    ValidationGenerator.php
    ModelGenerator.php
```

### 2.3 Padrões de Interface

#### Tabelas
- Cabeçalho fixo
- Linhas alternadas
- Ações na última coluna
- Paginação inferior

#### Formulários
- Layout em grid responsivo
- Validação em tempo real
- Feedback visual de erros
- Botões de ação padronizados

### 2.4 Validações Automáticas
- Campos obrigatórios
- Tipos de dados
- Tamanho máximo/mínimo
- Formatos específicos (email, telefone, etc)

### 2.5 Filtros de Busca
- Busca global
- Filtros por coluna
- Exportação de dados
- Ordenação dinâmica

## 3. Implementação

### 3.1 Geração de Código
```php
$generator = new CrudGenerator($tableName);
$generator->generateAll([
    'list' => true,
    'create' => true,
    'update' => true,
    'delete' => true,
    'validation' => true
]);
```

### 3.2 Personalização
```php
$generator->setTemplate('list', 'custom_list.php');
$generator->addValidation('field', 'custom_rule');
$generator->setLayout('two-columns');
```

### 3.3 Hooks
```php
$generator->beforeCreate(function($data) {
    // Manipulação antes de criar
});

$generator->afterUpdate(function($data) {
    // Ações pós atualização
});
```

## 4. Boas Práticas

### 4.1 Performance
- Lazy loading de dados
- Cache de consultas
- Otimização de imagens
- Minificação de assets

### 4.2 Segurança
- Validação server-side
- Sanitização de inputs
- CSRF protection
- XSS prevention

### 4.3 Acessibilidade
- Labels semânticos
- ARIA attributes
- Navegação por teclado
- Contraste adequado

### 4.4 Responsividade
- Mobile-first approach
- Breakpoints consistentes
- Flexbox/Grid layouts
- Touch-friendly

## 5. Manutenção

### 5.1 Versionamento
- Controle de versão do schema
- Migrations automáticas
- Backup de configurações
- Documentação atualizada

### 5.2 Monitoramento
- Logs de erro
- Métricas de uso
- Performance tracking
- Feedback dos usuários

## 6. Melhorias do Sistema

### 6.1 Sistema de Configurações
- **Tabela `system_settings`**
  - Armazenamento de configurações globais
  - Chave-valor flexível
  - Timestamps de criação/atualização
  - Suporte a dados em formato texto

### 6.2 Configurações Padrão
```php
private $defaultSettings = [
    'login_background' => '/assets/images/login-bg.jpg',
    'login_logo' => '/assets/images/logo.png'
];
```

### 6.3 Tratamento de Erros
- Logging detalhado de erros
- Fallback para valores padrão
- Mensagens de erro amigáveis
- Tratamento de exceções PDO

### 6.4 Gerenciamento de Arquivos
- Criação automática de diretórios
- Validação de tipos de arquivo
- Limite de tamanho (5MB)
- Nomes únicos para arquivos

### 6.5 Validações
- Extensões permitidas: jpg, jpeg, png, gif
- Verificação de tamanho máximo
- Validação de permissões de diretório
- Checagem de upload bem-sucedido

### 6.6 Funcionalidades do Sistema
1. **Personalização**
   - Upload de imagem de fundo
   - Configuração de logo
   - Interface administrativa

2. **Persistência**
   - Armazenamento em banco de dados
   - Cache de configurações
   - Histórico de alterações

3. **Fallback e Recuperação**
   - Valores padrão para todas as configurações
   - Recuperação automática em caso de erro
   - Sistema à prova de falhas

### 6.7 Estrutura de Diretórios
```
/public
  /assets
    /images
      login-bg.jpg    # Imagem de fundo padrão
      logo.png        # Logo padrão
```

### 6.8 Próximos Passos
1. Implementação do CRUD Generator
2. Sistema de templates
3. Geração automática de formulários
4. Validações dinâmicas

## 7. Estrutura do Banco de Dados

### 7.1 Tabelas de Autenticação e Autorização

#### Users
```sql
users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    department ENUM('Transportes', 'Custos', 'Financeiro') NOT NULL,
    role_id INT NOT NULL,
    active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT
)
```

#### Roles
```sql
roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT
)
```

#### Permissions
```sql
permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT
)
```

#### Role Permissions
```sql
role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    PRIMARY KEY (role_id, permission_id)
)
```

### 7.2 Relacionamentos

#### Usuários (Users)
- **role_id → roles**: Define o perfil do usuário (ON DELETE RESTRICT)
- **created_by → users**: Usuário que criou o registro (ON DELETE SET NULL)
- **updated_by → users**: Último usuário que atualizou o registro (ON DELETE SET NULL)

#### Perfis (Roles)
- **created_by → users**: Usuário que criou o perfil
- **updated_by → users**: Último usuário que atualizou o perfil
- **role_permissions**: Relacionamento N:N com permissions

#### Permissões (Permissions)
- **created_by → users**: Usuário que criou a permissão
- **updated_by → users**: Último usuário que atualizou a permissão
- **role_permissions**: Relacionamento N:N com roles

### 7.3 Índices de Otimização
- **idx_users_department**: Otimiza consultas por departamento
- **idx_users_role**: Otimiza joins com tabela roles
- **idx_users_active**: Otimiza filtros por status ativo/inativo
- **idx_roles_active**: Otimiza filtros de roles ativos
- **idx_permissions_active**: Otimiza filtros de permissões ativas

### 7.4 Dados Iniciais
- **Roles**: admin, manager, operator
- **Permissions**: user_view, user_create, user_edit, user_delete, role_manage, permission_manage
- **Usuário Admin**: Criado automaticamente com acesso total

### 7.5 Boas Práticas
1. **Auditoria**:
   - Todos os registros mantêm created_at/updated_at
   - Todos os registros mantêm created_by/updated_by
   
2. **Integridade**:
   - Chaves estrangeiras garantem integridade referencial
   - ON DELETE RESTRICT evita exclusão de registros em uso
   - ON DELETE SET NULL para campos de auditoria
   
3. **Performance**:
   - Índices estratégicos para otimização
   - ENUM para campos com valores fixos
   - Tipos de dados otimizados para cada coluna

## 8. Padronização Visual

### 8.1 Objetivos
1. Implementar design system em todas as páginas
2. Manter consistência visual
3. Melhorar experiência do usuário

### 8.2 CRUD Generator
1. Automatizar criação de formulários
2. Padronizar listagens
3. Implementar validações
4. Facilitar manutenção

### 8.3 Próximos Passos
1. Aplicar novo design em todas as páginas
2. Implementar CRUD Generator
3. Adicionar novas funcionalidades
4. Otimizar performance

## 9. CRUD Generator

### 9.1 Visão Geral
O CRUD Generator é uma ferramenta poderosa para automatizar a criação de interfaces e funcionalidades padrão do sistema.
Ele segue as melhores práticas de desenvolvimento e mantém a consistência visual em toda a aplicação.

### 9.2 Estrutura MVC

#### 9.2.1 Models
- **Classe Base**: `App\Core\Model`
- **Operações CRUD**:
  - Create: Inserção de registros
  - Read: Leitura e consulta
  - Update: Atualização de dados
  - Delete: Remoção de registros
- **Funcionalidades Extras**:
  - Paginação automática
  - Filtros e buscas
  - Relacionamentos

#### 9.2.2 Views
- **Templates Modernos**:
  - Tailwind CSS para estilização
  - Layout responsivo
  - Componentes reutilizáveis
- **Páginas Geradas**:
  - Index: Listagem de registros
  - Form: Criação/Edição
  - Show: Visualização detalhada

#### 9.2.3 Controllers
- **Classe Base**: `App\Core\Controller`
- **Métodos Padrão**:
  - index(): Listagem
  - create(): Novo registro
  - store(): Salvar
  - edit(): Edição
  - update(): Atualizar
  - delete(): Remover

### 9.3 Funcionalidades

#### 9.3.1 Geração Automática
- Models com operações CRUD
- Views com templates modernos
- Controllers com métodos padrão
- Rotas automáticas

#### 9.3.2 Sistema de Permissões
- Controle de acesso por rota
- Verificação de permissões
- Redirecionamento seguro
- Mensagens personalizadas

#### 9.3.3 Validação de Dados
- Regras customizáveis
- Mensagens de erro
- Sanitização de inputs
- Proteção contra XSS

#### 9.3.4 Paginação
- Paginação automática
- Controle de itens por página
- Links de navegação
- Contagem total

#### 9.3.5 Pesquisa e Filtros
- Busca por múltiplos campos
- Filtros avançados
- Ordenação de resultados
- Cache de consultas

### 9.4 Design Moderno

#### 9.4.1 Interface
- Layout limpo e intuitivo
- Design responsivo
- Cores consistentes
- Tipografia padronizada

#### 9.4.2 Componentes
- Botões padronizados
- Formulários consistentes
- Tabelas responsivas
- Cards informativos

#### 9.4.3 Feedback
- Mensagens de sucesso/erro
- Confirmações de ações
- Loading states
- Tooltips informativos

### 9.5 Como Usar

#### 9.5.1 Geração de CRUD
1. Acesse: `http://localhost/custo-extras/public/generate.php`
2. Defina a estrutura da tabela
3. Execute o gerador

#### 9.5.2 Arquivos Gerados
```
app/
├── Models/
│   └── User.php
├── Controllers/
│   └── UsersController.php
└── public/
    └── views/
        └── users/
            ├── index.php
            ├── form.php
            └── show.php
```

#### 9.5.3 Personalização
- Modifique templates conforme necessário
- Adicione campos específicos
- Customize validações
- Ajuste o layout

### 9.6 Exemplos

#### 9.6.1 Geração para Users
```php
$generator = new CrudGenerator(
    'users',
    [
        'id',
        'name',
        'email',
        'role',
        'active'
    ]
);
$generator->generate();
```

#### 9.6.2 Geração para Clients
```php
$generator = new CrudGenerator(
    'clients',
    [
        'id',
        'name',
        'email',
        'phone',
        'address'
    ]
);
$generator->generate();
```

### 9.7 Melhores Práticas
1. Mantenha nomes de tabelas no plural
2. Use campos em inglês
3. Sempre inclua timestamps
4. Defina chaves primárias
5. Documente personalizações

## 10. Menu Lateral (Sidebar)

### 10.1 Características do Menu

#### 10.1.1 Posicionamento e Dimensões
- Fixo na lateral esquerda (fixed left-0)
- Largura fixa de 64px (w-16)
- Altura total da tela (h-screen)
- Fundo branco com sombra suave
- Z-index elevado para sobrepor conteúdo

#### 10.1.2 Elementos Visuais
- Ícones monocromáticos
- Tooltips informativos
- Transições suaves
- Feedback visual ao hover
- Sombras sutis

### 10.2 Estrutura do Menu

#### 10.2.1 Seção Superior
- Logo minimalista
- Símbolo "#" em amarelo
- Altura fixa de 64px
- Centralizado

#### 10.2.2 Seção Principal
- Lista de ícones de navegação
- Espaçamento consistente
- Tooltips explicativos
- Hover states

#### 10.2.3 Seção Inferior
- Perfil do usuário
- Botão de logout
- Borda superior suave
- Altura fixa

### 10.3 Páginas Incluídas

#### 10.3.1 Gestão Principal
- **Dashboard**: Visão geral do sistema
- **Custos**: Gestão financeira
- **Clientes**: Gerenciamento de clientes
- **Fornecedores**: Controle de fornecedores

#### 10.3.2 Operacional
- **Transportadoras**: Gestão logística
- **Tipos**: Categorização
- **Usuários**: Controle de acesso
- **Relatórios**: Análises e downloads

#### 10.3.3 Sistema
- **Configurações**: Personalização
- **Logout**: Saída segura

### 10.4 Funcionalidades

#### 10.4.1 Interatividade
- Tooltips ao hover
- Transições de 300ms
- Feedback visual imediato
- Estados ativos

#### 10.4.2 Responsividade
- Colapso em telas pequenas
- Mantém funcionalidade
- Adapta tooltips
- Preserva navegação

#### 10.4.3 Acessibilidade
- Labels para leitores de tela
- Navegação por teclado
- Contraste adequado
- ARIA attributes

### 10.5 Implementação Técnica

#### 10.5.1 Arquivos Necessários
```php
// Incluir em todas as páginas
require_once 'includes/header.php';    // Topo + Sidebar
require_once 'includes/footer.php';    // Rodapé
```

#### 10.5.2 Estrutura HTML
```html
<aside class="fixed left-0 ...">
    <!-- Logo -->
    <div class="h-16 ...">
        <span class="text-yellow-500">#</span>
    </div>
    
    <!-- Menu Items -->
    <nav class="flex-1 ...">
        <!-- Ícones de navegação -->
    </nav>
    
    <!-- User Profile -->
    <div class="h-16 ...">
        <!-- Logout -->
    </div>
</aside>
```

#### 10.5.3 Container Principal
```html
<main class="ml-16 min-h-screen p-6">
    <!-- Conteúdo da página -->
</main>
```

### 10.6 Melhores Práticas

#### 10.6.1 Estrutura de Páginas
1. Incluir header.php no início
2. Colocar conteúdo dentro da tag `<main>`
3. Incluir footer.php no final
4. Manter hierarquia de containers

#### 10.6.2 Performance
1. Ícones SVG inline
2. Classes Tailwind otimizadas
3. Transições suaves
4. Lazy loading quando necessário

#### 10.6.3 Manutenção
1. Centralizar configurações
2. Documentar alterações
3. Seguir padrões estabelecidos
4. Manter consistência visual

## 7. Estrutura de Páginas

### 7.1 Páginas de Acesso
- **login.php**: Página de login com design moderno
  - Autenticação de usuários
  - Interface minimalista
  - Suporte a imagem de fundo personalizada
- **logout.php**: Encerramento de sessão
- **access_denied.php**: Página de acesso negado

### 7.2 Dashboard e Análises
- **dashboard.php**: Página inicial/Dashboard
  - Visão geral do sistema
  - Indicadores principais
  - Gráficos e estatísticas
- **analytics.php**: Análises detalhadas
- **view_analyses.php**: Visualização de análises específicas
- **follow_up.php**: Acompanhamento de processos

### 7.3 Gestão de Custos
- **costs.php**: Lista principal de custos
- **new_cost.php**: Formulário de novo custo
- **add_cost.php**: Processamento de custos
- **get_cost_details.php**: Detalhamento de custos

### 7.4 Clientes
- **clients.php**: Gerenciamento de clientes
  - Listagem
  - Busca
  - Filtros
- **add_client.php**: Adicionar novo cliente
- **delete_client.php**: Remoção de cliente

### 7.5 Fornecedores
- **fornecedores.php**: Lista de fornecedores
- **fornecedor.php**: Detalhes do fornecedor
- **fornecedor_edit.php**: Edição de fornecedor
- **fornecedor_delete.php**: Remoção de fornecedor
- **add_fornecedor_permission.php**: Gestão de permissões

### 7.6 Transportadoras
- **shippers.php**: Gestão de transportadoras
- **shipper_actions.php**: Ações e operações
- **drivers.php**: Controle de motoristas

### 7.7 Tipos e Categorias
- **types.php**: Gerenciamento de tipos
- **add_type.php**: Novo tipo
- **edit_type.php**: Edição de tipo
- **delete_type.php**: Remoção de tipo
- **type_actions.php**: Ações relacionadas

### 7.8 Usuários e Permissões
- **users.php**: Gerenciamento de usuários
- **roles.php**: Controle de papéis
- **check_permissions.php**: Verificações
- **get_role_permissions.php**: Permissões por papel
- **get_user_permissions.php**: Permissões por usuário
- **sync_permissions.php**: Sincronização

### 7.9 Relatórios e Downloads
- **reports.php**: Central de relatórios
- **generate_report.php**: Geração dinâmica
- **download_report.php**: Download geral
- **download_costs.php**: Relatório de custos
- **download_clients.php**: Lista de clientes
- **download_analysis.php**: Análises em PDF

### 7.10 Configurações
- **system_settings.php**: Configurações globais
  - Personalização visual
  - Parâmetros do sistema
  - Upload de arquivos

### 7.11 Sistema
- **500.php**: Página de erro servidor
- **create_admin.php**: Setup inicial
- **index.php**: Redirecionamento
