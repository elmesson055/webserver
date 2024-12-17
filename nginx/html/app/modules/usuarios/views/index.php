<?php
// Verificar se usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: /app/modules/auth/views/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fluentui/style-utilities/dist/css/fabric.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/app/modules/dashboard/assets/css/dynamics-theme.css">
    <link rel="stylesheet" href="/app/modules/usuarios/assets/css/usuarios.css">
</head>
<body>
    <div class="dynamics-layout">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../dashboard/views/components/sidebar.php'; ?>

        <div class="flex-grow-1">
            <!-- Header -->
            <?php include __DIR__ . '/../../dashboard/views/components/header.php'; ?>

            <!-- Content -->
            <main class="dynamics-content">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/app/modules/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Usuários</li>
                    </ol>
                </nav>

                <!-- Header com Título e Botões -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Gerenciar Usuários</h1>
                    <button class="dynamics-btn" data-bs-toggle="modal" data-bs-target="#modalUsuario">
                        <i class="fas fa-plus me-2"></i>
                        Novo Usuário
                    </button>
                </div>

                <!-- Filtros -->
                <div class="dynamics-card mb-4">
                    <form id="formFiltros" class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="busca" name="busca" 
                                       placeholder="Buscar" value="<?php echo htmlspecialchars($_GET['busca'] ?? ''); ?>">
                                <label for="busca">Buscar por nome ou e-mail</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" id="status" name="status">
                                    <option value="">Todos</option>
                                    <option value="ativo" <?php echo ($_GET['status'] ?? '') === 'ativo' ? 'selected' : ''; ?>>Ativos</option>
                                    <option value="inativo" <?php echo ($_GET['status'] ?? '') === 'inativo' ? 'selected' : ''; ?>>Inativos</option>
                                </select>
                                <label for="status">Status</label>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <button type="submit" class="dynamics-btn w-100">
                                <i class="fas fa-search me-2"></i>
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Lista de Usuários -->
                <div class="dynamics-card">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Função</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($usuario['nome'] . ' ' . $usuario['sobrenome']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['funcao_nome']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $usuario['status'] === 'ativo' ? 'success' : 'secondary'; ?>">
                                                <?php echo ucfirst($usuario['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-link btn-sm text-primary" 
                                                    onclick="editarUsuario(<?php echo $usuario['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-link btn-sm text-danger" 
                                                    onclick="excluirUsuario(<?php echo $usuario['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Usuário -->
    <div class="modal fade" id="modalUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Novo Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formUsuario">
                        <input type="hidden" id="usuario_id" name="id">
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="nome" name="nome" required>
                            <label for="nome">Nome</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="sobrenome" name="sobrenome" required>
                            <label for="sobrenome">Sobrenome</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" required>
                            <label for="email">E-mail</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select" id="funcao_id" name="funcao_id" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($funcoes as $funcao): ?>
                                    <option value="<?php echo $funcao['id']; ?>">
                                        <?php echo htmlspecialchars($funcao['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="funcao_id">Função</label>
                        </div>

                        <div id="senhaGroup">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="senha" name="senha">
                                <label for="senha">Senha</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha">
                                <label for="confirmar_senha">Confirmar Senha</label>
                            </div>
                        </div>

                        <div class="form-floating mb-3" id="statusGroup" style="display: none;">
                            <select class="form-select" id="status" name="status">
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                            </select>
                            <label for="status">Status</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="dynamics-btn" onclick="salvarUsuario()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/app/modules/usuarios/assets/js/usuarios.js"></script>
</body>
</html>
