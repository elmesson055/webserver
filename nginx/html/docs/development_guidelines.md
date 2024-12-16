# Diretrizes de Desenvolvimento

## 1. Estrutura de Arquivos

### 1.1 Organização de Diretórios
```
c:/webserver/nginx/html/
├── app/
│   ├── classes/      # Classes do sistema
│   ├── modules/      # Módulos funcionais
│   ├── Views/        # Páginas principais
│   └── functions.php # Funções globais
├── config/           # Configurações
├── docs/            # Documentação
└── sql/             # Scripts SQL
```

### 1.2 Padrão de Nomenclatura
- Arquivos: lowercase com underscore (ex: `user_profile.php`)
- Classes: PascalCase (ex: `Database.php`)
- Funções: camelCase (ex: `getUserById()`)
- Tabelas: lowercase com underscore (ex: `user_permissions`)

## 2. Criando Novas Páginas

### 2.1 Estrutura Básica
```php
<?php
// 1. Includes necessários
require_once dirname(dirname(dirname(__FILE__))) . '/config/session.php';
require_once dirname(dirname(dirname(__FILE__))) . '/functions.php';
require_once dirname(dirname(dirname(__FILE__))) . '/init.php';

// 2. Verificação de autenticação
if (!isset($_SESSION['user_id'])) {
    redirect('/app/modules/auth/login.php');
}

// 3. Verificação de permissões
if (!hasRole('required_role')) {
    redirect('/app/Views/dashboard/index.php');
}

// 4. Lógica da página
try {
    $db = connectDB();
    // Código específico da página
} catch (Exception $e) {
    error_log("Erro: " . $e->getMessage());
}
?>

<!-- 5. HTML da página -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Meta tags obrigatórias -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título da Página - Sistema de Logística</title>
    
    <!-- CSS obrigatório -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <?php include dirname(dirname(dirname(__FILE__))) . '/modules/common/sidebar.php'; ?>
            </div>

            <!-- Conteúdo -->
            <div class="col-md-9 col-lg-10 content">
                <!-- Conteúdo específico da página -->
            </div>
        </div>
    </div>

    <!-- JavaScript obrigatório -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

## 3. Banco de Dados

### 3.1 Conexão
```php
$db = connectDB();
```

### 3.2 Queries
```php
// Sempre usar prepared statements
$stmt = $db->prepare("SELECT * FROM table WHERE id = ?");
$stmt->execute([$id]);
```

### 3.3 Tratamento de Erros
```php
try {
    // Código do banco
} catch (PDOException $e) {
    error_log("Erro no banco: " . $e->getMessage());
    // Tratar erro apropriadamente
}
```

## 4. Segurança

### 4.1 Autenticação
- Sempre verificar se o usuário está logado
- Validar sessão em cada página
- Implementar timeout de sessão

### 4.2 Autorização
- Verificar permissões antes de cada ação
- Usar `hasRole()` para verificar papéis
- Documentar permissões necessárias

### 4.3 Inputs
```php
// Sanitização
$input = trim(htmlspecialchars($_POST['input'], ENT_QUOTES, 'UTF-8'));

// Validação
if (empty($input)) {
    $error = 'Campo obrigatório';
}
```

## 5. Interface do Usuário

### 5.1 Framework CSS
- Bootstrap 5.3.0
- Font Awesome 6.0.0

### 5.2 Layout
- Usar grid system do Bootstrap
- Manter consistência com o design existente
- Seguir padrões de acessibilidade

### 5.3 Responsividade
- Testar em diferentes dispositivos
- Usar classes responsivas do Bootstrap
- Implementar breakpoints apropriados

## 6. Documentação

### 6.1 Código
```php
/**
 * Descrição da função
 * @param type $parameter descrição
 * @return type descrição
 */
function example($parameter) {
    // Comentários explicativos quando necessário
}
```

### 6.2 Alterações
1. Atualizar CHANGELOG.md
2. Documentar novas funcionalidades
3. Atualizar documentação existente

### 6.3 APIs
- Documentar endpoints
- Especificar parâmetros
- Descrever respostas

## 7. Controle de Qualidade

### 7.1 Antes do Commit
- Testar funcionalidade
- Verificar permissões
- Validar inputs
- Testar em diferentes navegadores

### 7.2 Logs
- Usar error_log() para erros
- Documentar exceções
- Manter logs organizados

### 7.3 Performance
- Otimizar queries
- Minimizar requisições
- Cachear quando possível

## 8. Manutenção

### 8.1 Arquivos de Configuração
- Manter configurações centralizadas
- Documentar alterações
- Usar variáveis de ambiente

### 8.2 Dependências
- Manter versões atualizadas
- Documentar requisitos
- Testar compatibilidade

## 9. Fluxo de Trabalho

### 9.1 Novo Desenvolvimento
1. Consultar documentação
2. Verificar permissões necessárias
3. Implementar seguindo diretrizes
4. Testar exaustivamente
5. Documentar alterações
6. Atualizar CHANGELOG.md

### 9.2 Correções
1. Identificar causa raiz
2. Implementar correção
3. Testar cenário original
4. Documentar solução
5. Atualizar logs se necessário

## 10. Checklist Final

- [ ] Código segue padrões de nomenclatura
- [ ] Autenticação implementada
- [ ] Permissões verificadas
- [ ] Inputs sanitizados
- [ ] Queries usando prepared statements
- [ ] Tratamento de erros implementado
- [ ] Interface responsiva
- [ ] Documentação atualizada
- [ ] CHANGELOG.md atualizado
- [ ] Testes realizados
- [ ] Logs implementados
