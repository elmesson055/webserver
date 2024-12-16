# Documentação de Rotas - Sistema de Gestão de Custos Extras

## Visão Geral
O sistema utiliza um roteador personalizado que suporta diferentes métodos HTTP (GET, POST, PUT, DELETE) e proteção de rotas através de autenticação.

## Estrutura de Rotas

### Rotas Públicas
Rotas que não requerem autenticação:

| Método | Rota | Controller | Ação | Descrição |
|--------|------|------------|------|-----------|
| GET | `/login` | AuthController | loginForm | Exibe formulário de login |
| POST | `/login` | AuthController | login | Processa o login |
| GET | `/logout` | AuthController | logout | Realiza o logout |
| GET | `/forgot-password` | AuthController | forgotPasswordForm | Exibe formulário de recuperação de senha |
| POST | `/forgot-password` | AuthController | forgotPassword | Processa a recuperação de senha |
| GET | `/reset-password` | AuthController | resetPasswordForm | Exibe formulário de redefinição de senha |
| POST | `/reset-password` | AuthController | resetPassword | Processa a redefinição de senha |

### Rotas Protegidas

#### Dashboard
| Método | Rota | Controller | Ação | Descrição |
|--------|------|------------|------|-----------|
| GET | `/` | DashboardController | index | Página inicial do dashboard |
| GET | `/dashboard` | DashboardController | index | Página inicial do dashboard |

#### Cadastros - Embarcadores
| Método | Rota | Controller | Ação | Descrição |
|--------|------|------------|------|-----------|
| GET | `/cadastros/embarcadores` | EmbarcadorController | index | Lista de embarcadores |
| GET | `/cadastros/embarcadores/novo` | EmbarcadorController | create | Form de novo embarcador |
| POST | `/cadastros/embarcadores` | EmbarcadorController | store | Salva novo embarcador |
| GET | `/cadastros/embarcadores/editar/{id}` | EmbarcadorController | edit | Form de edição |
| PUT | `/cadastros/embarcadores/{id}` | EmbarcadorController | update | Atualiza embarcador |
| DELETE | `/cadastros/embarcadores/{id}` | EmbarcadorController | delete | Remove embarcador |

#### Custos Extras
| Método | Rota | Controller | Ação | Descrição |
|--------|------|------------|------|-----------|
| GET | `/custos` | CustosController | index | Lista de custos extras |
| GET | `/custos/novo` | CustosController | create | Form de novo custo |
| POST | `/custos` | CustosController | store | Salva novo custo |
| GET | `/custos/editar/{id}` | CustosController | edit | Form de edição |
| PUT | `/custos/{id}` | CustosController | update | Atualiza custo |

#### Configurações (Acesso Admin)
| Método | Rota | Controller | Ação | Descrição |
|--------|------|------------|------|-----------|
| GET | `/config/usuarios` | UsersController | index | Lista de usuários |
| GET | `/config/usuarios/novo` | UsersController | create | Form de novo usuário |
| POST | `/config/usuarios` | UsersController | store | Salva novo usuário |
| GET | `/config/usuarios/editar/{id}` | UsersController | edit | Form de edição |
| PUT | `/config/usuarios/{id}` | UsersController | update | Atualiza usuário |
| POST | `/config/usuarios/{id}/toggle-status` | UsersController | toggleStatus | Altera status |

### API Routes
| Método | Rota | Controller | Ação | Descrição |
|--------|------|------------|------|-----------|
| GET | `/api/users` | UsersController | list | Lista usuários (JSON) |
| POST | `/api/users` | UsersController | store | Cria usuário (JSON) |
| PUT | `/api/users/{id}` | UsersController | update | Atualiza usuário (JSON) |
| POST | `/api/users/{id}/toggle-status` | UsersController | toggleStatus | Altera status (JSON) |

## Rotas de Autenticação

### Login
- **Rota:** `/` ou `/login`
- **Arquivo:** `app/modules/auth/login.php`
- **Método:** GET/POST
- **Descrição:** Página de login do sistema
- **Parâmetros POST:**
  - usuario: string (nome de usuário)
  - senha: string (senha do usuário)
- **Redirecionamentos:**
  - Sucesso: /dashboard
  - Erro: Permanece na página com mensagem de erro

### Logout
- **Rota:** `/logout`
- **Arquivo:** `app/modules/auth/logout.php`
- **Método:** GET
- **Descrição:** Encerra a sessão do usuário
- **Redirecionamentos:**
  - Sempre: /login

### Dashboard
- **Rota:** `/dashboard`
- **Arquivo:** `app/modules/dashboard/index.php`
- **Método:** GET
- **Descrição:** Página principal após o login
- **Requer Autenticação:** Sim
- **Redirecionamentos:**
  - Não autenticado: /login

## Estrutura de Arquivos Importantes

### Arquivos de Configuração
- `/config/config.php`: Configurações gerais do sistema
- `/config/database.php`: Configurações de banco de dados
- `/config/session.php`: Configurações de sessão

### Arquivos de Sistema
- `/index.php`: Ponto de entrada principal
- `/routes/routes.php`: Definição das rotas
- `/app/functions.php`: Funções globais

## Middleware e Proteções
- Todas as rotas após o login requerem autenticação
- Sessões são gerenciadas via PHP nativo
- Proteção contra SQL Injection via PDO
- Sanitização de entrada de dados
- Proteção contra CSRF (a ser implementada)

## Convenções
1. Todas as rotas são definidas em `/routes/routes.php`
2. Arquivos de módulos ficam em `/app/modules/{nome_modulo}/`
3. Views ficam em `/views/`
4. Assets ficam em `/assets/`

## Tratamento de Erros
- Em caso de erro de roteamento, o sistema retorna um status 404 e exibe a página de erro correspondente
- Erros são registrados no log do sistema para diagnóstico
- Mensagens amigáveis são exibidas ao usuário através de sessão flash

## Proteção de Rotas
- Rotas protegidas utilizam o método `auth()` do Router
- Verificação de autenticação é realizada antes de processar a rota
- Usuários não autenticados são redirecionados para a página de login
- Configurações de usuário são acessíveis apenas para administradores