<?php
// Carrega o bootstrap que já inclui as funções necessárias
require_once __DIR__ . '/../../../app/bootstrap.php';

// Verifica permissão
if (!hasPermission('view_users')) {
    header('Location: /403');
    exit;
}

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

$departments = ['Transportes', 'Custos', 'Financeiro'];
$roles = getRoles(); // Função helper para buscar roles
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gestão de Usuários</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Usuários</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Lista de Usuários</h3>
                        <?php if (hasPermission('manage_users')): ?>
                            <a href="/views/config/usuarios/create.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Novo Usuário
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" id="filter-department">
                                <option value="">Todos os Departamentos</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= htmlspecialchars($dept) ?>"><?= htmlspecialchars($dept) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="filter-role">
                                <option value="">Todas as Funções</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= htmlspecialchars($role['id']) ?>"><?= htmlspecialchars($role['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="filter-status">
                                <option value="">Todos os Status</option>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="users-table">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Departamento</th>
                                    <th>Função</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dados serão carregados via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Ação</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja alterar o status deste usuário?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicialização da DataTable
    const table = $('#users-table').DataTable({
        ajax: {
            url: '/api/users',
            data: function(d) {
                d.department = $('#filter-department').val();
                d.role = $('#filter-role').val();
                d.status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'name' },
            { data: 'email' },
            { data: 'department' },
            { data: 'role' },
            { 
                data: 'active',
                render: function(data) {
                    return data == 1 
                        ? '<span class="badge badge-success">Ativo</span>'
                        : '<span class="badge badge-danger">Inativo</span>';
                }
            },
            {
                data: null,
                render: function(data) {
                    let html = '';
                    <?php if (hasPermission('manage_users')): ?>
                    html += `
                        <a href="/views/config/usuarios/edit.php?id=${data.id}" 
                           class="btn btn-sm btn-info" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm ${data.active == 1 ? 'btn-danger' : 'btn-success'} toggle-status"
                                data-id="${data.id}" 
                                data-status="${data.active}"
                                title="${data.active == 1 ? 'Desativar' : 'Ativar'}">
                            <i class="fas fa-${data.active == 1 ? 'times' : 'check'}"></i>
                        </button>
                    `;
                    <?php endif; ?>
                    return html;
                }
            }
        ],
        language: {
            url: '/assets/js/dataTables.portuguese.json'
        }
    });

    // Atualiza a tabela quando os filtros mudam
    $('.form-control').change(function() {
        table.ajax.reload();
    });

    // Manipula a alteração de status
    let userId, newStatus;
    $('#users-table').on('click', '.toggle-status', function() {
        userId = $(this).data('id');
        newStatus = $(this).data('status') == 1 ? 0 : 1;
        $('#confirmModal').modal('show');
    });

    $('#confirmAction').click(function() {
        $.ajax({
            url: '/api/users/' + userId + '/toggle-status',
            method: 'POST',
            data: { status: newStatus },
            success: function() {
                $('#confirmModal').modal('hide');
                table.ajax.reload();
                toastr.success('Status atualizado com sucesso!');
            },
            error: function() {
                toastr.error('Erro ao atualizar status.');
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
