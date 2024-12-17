<?php
session_start();

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
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fluentui/style-utilities/dist/css/fabric.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/app/modules/dashboard/assets/css/dynamics-theme.css">
    <style>
        :root {
            --dynamics-primary: #0078D4;
            --dynamics-secondary: #605E5C;
            --dynamics-background: #F8F9FA;
        }
        
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--dynamics-background);
        }
        
        .dynamics-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .dynamics-content {
            flex-grow: 1;
            padding: 2rem;
        }
        
        .dynamics-card {
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
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
                <div class="container-fluid px-4">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="pt-3 pb-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">Usuários</li>
                        </ol>
                    </nav>

                    <!-- Título e Ações -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0">Gerenciamento de Usuários</h1>
                        <?php if (check_user_permission('usuarios.create')): ?>
                            <button class="btn btn-dynamics" data-bs-toggle="modal" data-bs-target="#modalNovoUsuario">
                                <i class="fas fa-plus me-2"></i>Novo Usuário
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Cards de Sumário -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="dynamics-summary-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="dynamics-summary-number">
                                            <?php echo count($usuarios); ?>
                                        </div>
                                        <div class="dynamics-summary-label">Total de Usuários</div>
                                    </div>
                                    <div class="dynamics-summary-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="dynamics-summary-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="dynamics-summary-number dynamics-success">
                                            <?php 
                                            echo count(array_filter($usuarios, function($u) { 
                                                return $u['status'] === 'Ativo'; 
                                            })); 
                                            ?>
                                        </div>
                                        <div class="dynamics-summary-label">Usuários Ativos</div>
                                    </div>
                                    <div class="dynamics-summary-icon dynamics-success">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Área de Trabalho Principal -->
                    <div class="dynamics-workspace-card">
                        <!-- Filtros -->
                        <div class="dynamics-filters mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="filtroStatus" class="form-label">Status:</label>
                                        <select class="form-select" id="filtroStatus">
                                            <option value="">Todos</option>
                                            <option value="Ativo">Ativo</option>
                                            <option value="Inativo">Inativo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="filtroFuncao" class="form-label">Função:</label>
                                        <select class="form-select" id="filtroFuncao">
                                            <option value="">Todas</option>
                                            <?php
                                            $sql_funcoes = "SELECT DISTINCT nome FROM funcoes WHERE status = 'Ativo' ORDER BY nome";
                                            $conn = get_database_connection();
                                            $result_funcoes = $conn->query($sql_funcoes);
                                            while ($funcao = $result_funcoes->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($funcao['nome']) . "'>" . htmlspecialchars($funcao['nome']) . "</option>";
                                            }
                                            $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="busca" class="form-label">Buscar:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="busca" placeholder="Digite para buscar...">
                                            <button class="btn btn-dynamics" type="button">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabela -->
                        <div class="table-responsive">
                            <table class="dynamics-table" id="tabelaUsuarios">
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
                                        <td>
                                            <div class="dynamics-table-title"><?php echo htmlspecialchars($usuario['nome']); ?></div>
                                        </td>
                                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                        <td><?php echo htmlspecialchars($usuario['funcao']); ?></td>
                                        <td>
                                            <span class="dynamics-badge <?php echo $usuario['status'] === 'Ativo' ? 'dynamics-badge-success' : 'dynamics-badge-secondary'; ?>">
                                                <?php echo htmlspecialchars($usuario['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dynamics-actions">
                                                <?php if (check_user_permission('usuarios.edit')): ?>
                                                <button type="button" class="btn btn-dynamics btn-sm" title="Editar"
                                                        onclick="editarUsuario(<?php echo $usuario['id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <?php endif; ?>
                                                <?php if (check_user_permission('usuarios.delete')): ?>
                                                <button type="button" class="btn btn-dynamics-danger btn-sm" title="Excluir"
                                                        onclick="confirmarExclusao(<?php echo $usuario['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Novo Usuário -->
    <div class="modal fade" id="modalNovoUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Novo Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNovoUsuario">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" required>
                        </div>
                        <div class="mb-3">
                            <label for="funcao" class="form-label">Função</label>
                            <select class="form-select" id="funcao" required>
                                <option value="">Selecione...</option>
                                <?php
                                $conn = get_database_connection();
                                $result_funcoes = $conn->query($sql_funcoes);
                                while ($funcao = $result_funcoes->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($funcao['nome']) . "'>" . htmlspecialchars($funcao['nome']) . "</option>";
                                }
                                $conn->close();
                                ?>
                            </select>
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
                    <button type="submit" form="formNovoUsuario" class="btn btn-dynamics">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edição -->
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarUsuario">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editNome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="editNome" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSenha" class="form-label">Nova Senha (opcional)</label>
                            <input type="password" class="form-control" id="editSenha">
                            <small class="form-text text-muted">Deixe em branco para manter a senha atual</small>
                        </div>
                        <div class="mb-3">
                            <label for="editFuncao" class="form-label">Função</label>
                            <select class="form-select" id="editFuncao" required>
                                <option value="">Selecione...</option>
                                <?php
                                $conn = get_database_connection();
                                $result_funcoes = $conn->query($sql_funcoes);
                                while ($funcao = $result_funcoes->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($funcao['nome']) . "'>" . htmlspecialchars($funcao['nome']) . "</option>";
                                }
                                $conn->close();
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" required>
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formEditarUsuario" class="btn btn-dynamics">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Exclusão -->
    <div class="modal fade" id="modalExcluirUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="deleteId">
                    <p>Tem certeza que deseja excluir este usuário?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta ação não poderá ser desfeita.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-dynamics-danger" onclick="excluirUsuario()">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(document).ready(function() {
        // Inicializa o DataTable
        var table = $('#tabelaUsuarios').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
            },
            order: [[0, 'asc']],
            pageLength: 25,
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            initComplete: function() {
                // Aplica classes do Dynamics aos elementos do DataTable
                $('.dataTables_length select').addClass('form-select');
                $('.dataTables_filter input').addClass('form-control');
                $('.paginate_button').addClass('btn btn-dynamics');
            }
        });

        // Filtro por status
        $('#filtroStatus').on('change', function() {
            table.column(3).search(this.value).draw();
        });

        // Filtro por função
        $('#filtroFuncao').on('change', function() {
            table.column(2).search(this.value).draw();
        });

        // Busca geral
        $('#busca').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Form handlers
        $('#formNovoUsuario').on('submit', function(e) {
            e.preventDefault();
            salvarNovoUsuario();
        });

        $('#formEditarUsuario').on('submit', function(e) {
            e.preventDefault();
            salvarEdicaoUsuario();
        });
    });

    function editarUsuario(id) {
        $.ajax({
            url: 'get_usuario.php',
            type: 'GET',
            data: { id: id },
            success: function(response) {
                var data = JSON.parse(response);
                $('#editId').val(data.id);
                $('#editNome').val(data.nome);
                $('#editEmail').val(data.email);
                $('#editFuncao').val(data.funcao);
                $('#editStatus').val(data.status);
                $('#modalEditarUsuario').modal('show');
            }
        });
    }

    function salvarNovoUsuario() {
        var formData = {
            nome: $('#nome').val(),
            email: $('#email').val(),
            senha: $('#senha').val(),
            funcao: $('#funcao').val(),
            status: $('#status').val()
        };

        $.ajax({
            url: 'save_usuario.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $('#modalNovoUsuario').modal('hide');
                    location.reload();
                } else {
                    alert('Erro ao salvar: ' + result.message);
                }
            }
        });
    }

    function salvarEdicaoUsuario() {
        var formData = {
            id: $('#editId').val(),
            nome: $('#editNome').val(),
            email: $('#editEmail').val(),
            senha: $('#editSenha').val(),
            funcao: $('#editFuncao').val(),
            status: $('#editStatus').val()
        };

        $.ajax({
            url: 'save_usuario.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $('#modalEditarUsuario').modal('hide');
                    location.reload();
                } else {
                    alert('Erro ao salvar: ' + result.message);
                }
            }
        });
    }

    function confirmarExclusao(id) {
        $('#deleteId').val(id);
        $('#modalExcluirUsuario').modal('show');
    }

    function excluirUsuario() {
        var id = $('#deleteId').val();
        $.ajax({
            url: 'delete_usuario.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $('#modalExcluirUsuario').modal('hide');
                    location.reload();
                } else {
                    alert('Erro ao excluir: ' + result.message);
                }
            }
        });
    }
    </script>

    <?php require_once FOOTER_PATH; ?>
</body>
</html>
