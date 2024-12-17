<?php
session_start();

// Verificar se usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: /app/modules/auth/views/login.php');
    exit();
}

// Carregar funções do banco (simulado por enquanto)
$funcoes = [
    ['id' => 1, 'nome' => 'Administrador', 'descricao' => 'Acesso total ao sistema', 'status' => 'Ativo'],
    ['id' => 2, 'nome' => 'Gerente', 'descricao' => 'Gerenciamento de equipes e recursos', 'status' => 'Ativo'],
    ['id' => 3, 'nome' => 'Operador', 'descricao' => 'Operações básicas do sistema', 'status' => 'Ativo'],
    ['id' => 4, 'nome' => 'Visitante', 'descricao' => 'Acesso limitado para visualização', 'status' => 'Inativo']
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funções - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fluentui/style-utilities/dist/css/fabric.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/app/modules/dashboard/assets/css/dynamics-theme.css">
</head>
<body>
    <div class="dynamics-layout">
        <!-- Sidebar -->
        <?php include dirname(__DIR__) . '/dashboard/views/components/sidebar.php'; ?>
        <div class="flex-grow-1">
            <!-- Header -->
            <?php include dirname(__DIR__) . '/dashboard/views/components/header.php'; ?>
            <!-- Content -->
            <main class="dynamics-content">
                <div class="dynamics-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3 mb-0">Funções</h1>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovaFuncao">
                            <i class="fas fa-plus me-2"></i>
                            Nova Função
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($funcoes as $funcao): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($funcao['nome']); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($funcao['descricao']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $funcao['status'] === 'Ativo' ? 'success' : 'secondary'; ?>">
                                            <?php echo htmlspecialchars($funcao['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" title="Editar"
                                                    data-bs-toggle="modal" data-bs-target="#modalEditarFuncao<?php echo $funcao['id']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Excluir"
                                                    data-bs-toggle="modal" data-bs-target="#modalExcluirFuncao<?php echo $funcao['id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
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

    <!-- Modal Nova Função -->
    <div class="modal fade" id="modalNovaFuncao" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Função</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNovaFuncao">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" required>
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formNovaFuncao" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modais de Edição -->
    <?php foreach ($funcoes as $funcao): ?>
    <div class="modal fade" id="modalEditarFuncao<?php echo $funcao['id']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Função</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarFuncao<?php echo $funcao['id']; ?>">
                        <div class="mb-3">
                            <label for="nome<?php echo $funcao['id']; ?>" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome<?php echo $funcao['id']; ?>" 
                                   value="<?php echo htmlspecialchars($funcao['nome']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao<?php echo $funcao['id']; ?>" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao<?php echo $funcao['id']; ?>" 
                                      rows="3" required><?php echo htmlspecialchars($funcao['descricao']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status<?php echo $funcao['id']; ?>" class="form-label">Status</label>
                            <select class="form-select" id="status<?php echo $funcao['id']; ?>" required>
                                <option value="Ativo" <?php echo $funcao['status'] === 'Ativo' ? 'selected' : ''; ?>>Ativo</option>
                                <option value="Inativo" <?php echo $funcao['status'] === 'Inativo' ? 'selected' : ''; ?>>Inativo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formEditarFuncao<?php echo $funcao['id']; ?>" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Exclusão -->
    <div class="modal fade" id="modalExcluirFuncao<?php echo $funcao['id']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir a função <strong><?php echo htmlspecialchars($funcao['nome']); ?></strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta ação não poderá ser desfeita.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger">Excluir</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle do sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dynamics-sidebar').classList.toggle('collapsed');
            document.querySelector('.dynamics-content').classList.toggle('expanded');
        });

        // Prevenir envio do formulário (simulação)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const modal = bootstrap.Modal.getInstance(this.closest('.modal'));
                modal.hide();
                
                // Simular salvamento
                const toast = new bootstrap.Toast(document.createElement('div'));
                toast.show();
            });
        });
    </script>
</body>
</html>
