<?php
// Carrega o bootstrap da aplicação
require_once dirname(__DIR__, 3) . '/app/bootstrap.php';

// Carrega os includes necessários
require_once PUBLIC_PATH . '/includes/header.php';
require_once PUBLIC_PATH . '/includes/auth_check.php';
?>

<div class="container-fluid mt-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestão de Embarcadores</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#novoEmbarcadorModal">
            <i class="fas fa-plus"></i> Novo Embarcador
        </button>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
        </div>
        <div class="card-body">
            <form id="filtroForm" class="row g-3">
                <div class="col-md-3">
                    <label for="razaoSocial" class="form-label">Razão Social</label>
                    <input type="text" class="form-control" id="razaoSocial" name="razao_social">
                </div>
                <div class="col-md-2">
                    <label for="cnpj" class="form-label">CNPJ</label>
                    <input type="text" class="form-control" id="cnpj" name="cnpj">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Todos</option>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-eraser"></i> Limpar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Embarcadores -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Embarcadores Cadastrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="embarcadoresTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Razão Social</th>
                            <th>CNPJ</th>
                            <th>Contato</th>
                            <th>Status</th>
                            <th>Data Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>Empresa Exemplo LTDA</td>
                            <td>00.000.000/0001-00</td>
                            <td>(11) 1234-5678</td>
                            <td><span class="badge bg-success">Ativo</span></td>
                            <td>08/12/2024</td>
                            <td>
                                <button class="btn btn-sm btn-info" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-primary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" title="Desativar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Embarcador -->
<div class="modal fade" id="novoEmbarcadorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Embarcador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="embarcadorForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="razaoSocialModal" class="form-label">Razão Social</label>
                            <input type="text" class="form-control" id="razaoSocialModal" required>
                        </div>
                        <div class="col-md-6">
                            <label for="cnpjModal" class="form-label">CNPJ</label>
                            <input type="text" class="form-control" id="cnpjModal" required>
                        </div>
                        <div class="col-md-6">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email">
                        </div>
                        <div class="col-12">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="endereco">
                        </div>
                        <div class="col-md-4">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade">
                        </div>
                        <div class="col-md-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado">
                                <option value="">Selecione...</option>
                                <option value="SP">São Paulo</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <!-- Adicionar outros estados -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="salvarEmbarcador()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<!-- Máscaras de Input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializa DataTable
    $('#embarcadoresTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        }
    });

    // Inicializa máscaras
    $('#cnpj, #cnpjModal').mask('00.000.000/0000-00');
    $('#telefone').mask('(00) 0000-00009');
    $('#cep').mask('00000-000');
});

function salvarEmbarcador() {
    // Implementar lógica de salvamento
    alert('Função de salvamento a ser implementada');
    $('#novoEmbarcadorModal').modal('hide');
}
</script>

<?php require_once PUBLIC_PATH . '/includes/footer.php'; ?>
