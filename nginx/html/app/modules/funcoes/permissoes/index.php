<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/modules/auth/session.php';

// Verifica a sessão
check_session();

// Verifica se o usuário tem permissão
if (!check_user_permission('config.funcoes')) {
    header("Location: /app/modules/auth/login.php");
    exit;
}

$page_title = "Gerenciamento de Permissões";
require_once HEADER_PATH;
?>

<div class="container-fluid px-4">
    <h1 class="mt-4"><?php echo $page_title; ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="/app/index.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/app/modules/funcoes/index.php">Funções</a></li>
        <li class="breadcrumb-item active">Permissões</li>
    </ol>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="filtroModulo">Módulo:</label>
                        <select class="form-control" id="filtroModulo">
                            <option value="">Todos</option>
                            <option value="embarcadores">Embarcadores</option>
                            <option value="fornecedores">Fornecedores</option>
                            <option value="clientes">Clientes</option>
                            <option value="motoristas">Motoristas</option>
                            <option value="sefaz">SEFAZ</option>
                            <option value="documentos">Documentos</option>
                            <option value="relatorios">Relatórios</option>
                            <option value="monitor">Monitor</option>
                            <option value="config">Configurações</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="busca">Buscar:</label>
                        <input type="text" class="form-control" id="busca" placeholder="Digite para buscar...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Permissões -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Lista de Permissões
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tabelaPermissoes" width="100%">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Módulo</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Conexão com o banco
                        $conn = get_database_connection();

                        // Consulta SQL
                        $sql = "SELECT id, nome, descricao, modulo, criado_em FROM permissoes ORDER BY modulo, nome";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['modulo']) . "</td>";
                                echo "<td>" . date('d/m/Y H:i', strtotime($row['criado_em'])) . "</td>";
                                echo "<td class='text-center'>";
                                echo "<button class='btn btn-primary btn-sm me-1' onclick='editarPermissao(" . $row['id'] . ")'><i class='fas fa-edit'></i></button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição -->
<div class="modal fade" id="modalPermissao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Permissão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPermissao">
                    <input type="hidden" id="permissaoId">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" required>
                    </div>
                    <div class="mb-3">
                        <label for="modulo" class="form-label">Módulo</label>
                        <select class="form-control" id="modulo" required>
                            <option value="embarcadores">Embarcadores</option>
                            <option value="fornecedores">Fornecedores</option>
                            <option value="clientes">Clientes</option>
                            <option value="motoristas">Motoristas</option>
                            <option value="sefaz">SEFAZ</option>
                            <option value="documentos">Documentos</option>
                            <option value="relatorios">Relatórios</option>
                            <option value="monitor">Monitor</option>
                            <option value="config">Configurações</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="salvarPermissao()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializa o DataTable
    var table = $('#tabelaPermissoes').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
        },
        order: [[2, 'asc'], [0, 'asc']]
    });

    // Filtro por módulo
    $('#filtroModulo').on('change', function() {
        table.column(2).search(this.value).draw();
    });

    // Busca geral
    $('#busca').on('keyup', function() {
        table.search(this.value).draw();
    });
});

// Função para editar permissão
function editarPermissao(id) {
    $.ajax({
        url: 'get_permissao.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            var data = JSON.parse(response);
            $('#permissaoId').val(data.id);
            $('#nome').val(data.nome);
            $('#descricao').val(data.descricao);
            $('#modulo').val(data.modulo);
            $('#modalPermissao').modal('show');
        }
    });
}

// Função para salvar permissão
function salvarPermissao() {
    var formData = {
        id: $('#permissaoId').val(),
        nome: $('#nome').val(),
        descricao: $('#descricao').val(),
        modulo: $('#modulo').val()
    };

    $.ajax({
        url: 'save_permissao.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                $('#modalPermissao').modal('hide');
                location.reload();
            } else {
                alert('Erro ao salvar: ' + response.message);
            }
        }
    });
}
</script>

<?php require_once FOOTER_PATH; ?>
