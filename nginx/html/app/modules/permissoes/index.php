<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';

// Verifica a sessão
check_session();

// Verifica se o usuário tem permissão
if (!check_user_permission('funcoes.view')) {
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
                            <option value="dashboard">Dashboard</option>
                            <option value="usuarios">Usuários</option>
                            <option value="funcoes">Funções</option>
                            <option value="embarcadores">Embarcadores</option>
                            <option value="fornecedores">Fornecedores</option>
                            <option value="sefaz">SEFAZ</option>
                            <option value="documentos">Documentos</option>
                            <option value="monitor">Monitor</option>
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
            <i class="fas fa-key me-1"></i>
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
                            <th>Funções com Acesso</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Conexão com o banco
                        $conn = get_database_connection();

                        // Consulta SQL para listar permissões com funções associadas
                        $sql = "SELECT p.id, p.nome, p.descricao, p.modulo, 
                                GROUP_CONCAT(f.nome SEPARATOR ', ') as funcoes
                                FROM permissoes p
                                LEFT JOIN funcao_permissoes fp ON p.id = fp.permissao_id
                                LEFT JOIN funcoes f ON fp.funcao_id = f.id
                                GROUP BY p.id, p.nome, p.descricao, p.modulo
                                ORDER BY p.modulo, p.nome";
                        
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['modulo']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['funcoes']) . "</td>";
                                echo "<td class='text-center'>";
                                if (check_user_permission('funcoes.edit')) {
                                    echo "<button class='btn btn-primary btn-sm me-1' onclick='editarPermissao(" . $row['id'] . ")'>";
                                    echo "<i class='fas fa-edit'></i></button>";
                                }
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
                        <input type="text" class="form-control" id="nome" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" required>
                    </div>
                    <div class="mb-3">
                        <label for="modulo" class="form-label">Módulo</label>
                        <input type="text" class="form-control" id="modulo" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Funções com Acesso</label>
                        <div id="funcoesList">
                            <!-- Checkboxes serão carregados via AJAX -->
                        </div>
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
            
            // Carrega as funções
            $('#funcoesList').empty();
            data.funcoes.forEach(function(funcao) {
                var checked = funcao.tem_permissao ? 'checked' : '';
                $('#funcoesList').append(`
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="${funcao.id}" 
                               id="funcao${funcao.id}" ${checked}>
                        <label class="form-check-label" for="funcao${funcao.id}">
                            ${funcao.nome}
                        </label>
                    </div>
                `);
            });
            
            $('#modalPermissao').modal('show');
        }
    });
}

// Função para salvar permissão
function salvarPermissao() {
    var funcoesIds = [];
    $('#funcoesList input:checked').each(function() {
        funcoesIds.push($(this).val());
    });

    var formData = {
        id: $('#permissaoId').val(),
        descricao: $('#descricao').val(),
        funcoes: funcoesIds
    };

    $.ajax({
        url: 'save_permissao.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            var result = JSON.parse(response);
            if (result.success) {
                $('#modalPermissao').modal('hide');
                location.reload();
            } else {
                alert('Erro ao salvar: ' + result.message);
            }
        }
    });
}
</script>

<?php require_once FOOTER_PATH; ?>
