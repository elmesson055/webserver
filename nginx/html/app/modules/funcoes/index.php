<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';

// Verifica a sessão
check_session();

// Verifica se o usuário tem permissão
if (!check_user_permission('funcoes.view')) {
    header("Location: /app/modules/auth/login.php");
    exit;
}

$page_title = "Gerenciamento de Funções";
require_once HEADER_PATH;

// Carregar funções do banco
$conn = get_database_connection();
$sql = "SELECT id, nome, descricao, status FROM funcoes ORDER BY nome";
$result = $conn->query($sql);
$funcoes = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $funcoes[] = $row;
    }
}
$conn->close();
?>

<div class="container-fluid px-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="pt-3 pb-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Funções</li>
        </ol>
    </nav>

    <!-- Título e Ações -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><?php echo $page_title; ?></h1>
        <?php if (check_user_permission('funcoes.create')): ?>
            <button class="btn btn-dynamics" data-bs-toggle="modal" data-bs-target="#modalNovaFuncao">
                <i class="fas fa-plus me-2"></i>Nova Função
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
                            <?php echo count($funcoes); ?>
                        </div>
                        <div class="dynamics-summary-label">Total de Funções</div>
                    </div>
                    <div class="dynamics-summary-icon">
                        <i class="fas fa-users-cog"></i>
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
                            echo count(array_filter($funcoes, function($f) { 
                                return $f['status'] === 'Ativo'; 
                            })); 
                            ?>
                        </div>
                        <div class="dynamics-summary-label">Funções Ativas</div>
                    </div>
                    <div class="dynamics-summary-icon dynamics-success">
                        <i class="fas fa-check-circle"></i>
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
            <table class="dynamics-table" id="tabelaFuncoes">
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
                            <div class="dynamics-table-title"><?php echo htmlspecialchars($funcao['nome']); ?></div>
                        </td>
                        <td><?php echo htmlspecialchars($funcao['descricao']); ?></td>
                        <td>
                            <span class="dynamics-badge <?php echo $funcao['status'] === 'Ativo' ? 'dynamics-badge-success' : 'dynamics-badge-secondary'; ?>">
                                <?php echo htmlspecialchars($funcao['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="dynamics-actions">
                                <?php if (check_user_permission('funcoes.edit')): ?>
                                <button type="button" class="btn btn-dynamics btn-sm" title="Editar"
                                        onclick="editarFuncao(<?php echo $funcao['id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php endif; ?>
                                <?php if (check_user_permission('funcoes.delete')): ?>
                                <button type="button" class="btn btn-dynamics-danger btn-sm" title="Excluir"
                                        onclick="confirmarExclusao(<?php echo $funcao['id']; ?>)">
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
                <button type="submit" form="formNovaFuncao" class="btn btn-dynamics">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição -->
<div class="modal fade" id="modalEditarFuncao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Função</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarFuncao">
                    <input type="hidden" id="editId">
                    <div class="mb-3">
                        <label for="editNome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="editNome" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="editDescricao" rows="3" required></textarea>
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
                <button type="submit" form="formEditarFuncao" class="btn btn-dynamics">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exclusão -->
<div class="modal fade" id="modalExcluirFuncao" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteId">
                <p>Tem certeza que deseja excluir esta função?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Esta ação não poderá ser desfeita.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-dynamics-danger" onclick="excluirFuncao()">Excluir</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializa o DataTable
    var table = $('#tabelaFuncoes').DataTable({
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
        table.column(2).search(this.value).draw();
    });

    // Busca geral
    $('#busca').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Form handlers
    $('#formNovaFuncao').on('submit', function(e) {
        e.preventDefault();
        salvarNovaFuncao();
    });

    $('#formEditarFuncao').on('submit', function(e) {
        e.preventDefault();
        salvarEdicaoFuncao();
    });
});

function editarFuncao(id) {
    $.ajax({
        url: 'get_funcao.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            var data = JSON.parse(response);
            $('#editId').val(data.id);
            $('#editNome').val(data.nome);
            $('#editDescricao').val(data.descricao);
            $('#editStatus').val(data.status);
            $('#modalEditarFuncao').modal('show');
        }
    });
}

function salvarNovaFuncao() {
    var formData = {
        nome: $('#nome').val(),
        descricao: $('#descricao').val(),
        status: $('#status').val()
    };

    $.ajax({
        url: 'save_funcao.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            var result = JSON.parse(response);
            if (result.success) {
                $('#modalNovaFuncao').modal('hide');
                location.reload();
            } else {
                alert('Erro ao salvar: ' + result.message);
            }
        }
    });
}

function salvarEdicaoFuncao() {
    var formData = {
        id: $('#editId').val(),
        nome: $('#editNome').val(),
        descricao: $('#editDescricao').val(),
        status: $('#editStatus').val()
    };

    $.ajax({
        url: 'save_funcao.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            var result = JSON.parse(response);
            if (result.success) {
                $('#modalEditarFuncao').modal('hide');
                location.reload();
            } else {
                alert('Erro ao salvar: ' + result.message);
            }
        }
    });
}

function confirmarExclusao(id) {
    $('#deleteId').val(id);
    $('#modalExcluirFuncao').modal('show');
}

function excluirFuncao() {
    var id = $('#deleteId').val();
    $.ajax({
        url: 'delete_funcao.php',
        type: 'POST',
        data: { id: id },
        success: function(response) {
            var result = JSON.parse(response);
            if (result.success) {
                $('#modalExcluirFuncao').modal('hide');
                location.reload();
            } else {
                alert('Erro ao excluir: ' + result.message);
            }
        }
    });
}
</script>

<?php require_once FOOTER_PATH; ?>
