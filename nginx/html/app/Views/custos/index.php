<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth_check.php';
?>

<div class="container-fluid mt-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestão de Custos Extras</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#novoCustoModal">
            <i class="fas fa-plus"></i> Novo Registro
        </button>
    </div>

    <!-- Filtros Avançados -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros Avançados</h6>
        </div>
        <div class="card-body">
            <form id="filtroForm" class="row g-3">
                <div class="col-md-3">
                    <label for="dataInicio" class="form-label">Data Início</label>
                    <input type="date" class="form-control" id="dataInicio" name="data_inicio">
                </div>
                <div class="col-md-3">
                    <label for="dataFim" class="form-label">Data Fim</label>
                    <input type="date" class="form-control" id="dataFim" name="data_fim">
                </div>
                <div class="col-md-3">
                    <label for="embarcador" class="form-label">Embarcador</label>
                    <select class="form-select" id="embarcador" name="embarcador_id">
                        <option value="">Selecione...</option>
                        <option value="1">Empresa A</option>
                        <option value="2">Empresa B</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="fornecedor" class="form-label">Fornecedor</label>
                    <select class="form-select" id="fornecedor" name="fornecedor_id">
                        <option value="">Selecione...</option>
                        <option value="1">Fornecedor X</option>
                        <option value="2">Fornecedor Y</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="tipoCusto" class="form-label">Tipo de Custo</label>
                    <select class="form-select" id="tipoCusto" name="tipo_custo_id">
                        <option value="">Todos</option>
                        <option value="1">Frete</option>
                        <option value="2">Estadia</option>
                        <option value="3">Pedágio</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="statusFollowup" class="form-label">Status</label>
                    <select class="form-select" id="statusFollowup" name="status_id">
                        <option value="">Todos</option>
                        <option value="1">Pendente</option>
                        <option value="2">Em Aprovação</option>
                        <option value="3">Aprovado</option>
                        <option value="4">Rejeitado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="numeroRecibo" class="form-label">Nº Recibo</label>
                    <input type="text" class="form-control" id="numeroRecibo" name="numero_recibo">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-eraser"></i> Limpar
                    </button>
                    <button type="button" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Exportar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Custos -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Registros de Custos Extras</h6>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-filter"></i> Meus Registros
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-clock"></i> Pendentes
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-check"></i> Aprovados
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="custosTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data Registro</th>
                            <th>Embarcador</th>
                            <th>Fornecedor</th>
                            <th>Tipo</th>
                            <th>Nº Recibo</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>08/12/2024</td>
                            <td>Empresa A</td>
                            <td>Fornecedor X</td>
                            <td>Frete</td>
                            <td>REC123456</td>
                            <td>R$ 1.500,00</td>
                            <td><span class="badge bg-warning">Pendente</span></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" title="Aprovar">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" title="Rejeitar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo Custo -->
<div class="modal fade" id="novoCustoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Registro de Custo Extra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="custoForm" class="row g-3">
                    <div class="col-md-6">
                        <label for="embarcadorModal" class="form-label">Embarcador</label>
                        <select class="form-select" id="embarcadorModal" required>
                            <option value="">Selecione...</option>
                            <option value="1">Empresa A</option>
                            <option value="2">Empresa B</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="fornecedorModal" class="form-label">Fornecedor</label>
                        <select class="form-select" id="fornecedorModal" required>
                            <option value="">Selecione...</option>
                            <option value="1">Fornecedor X</option>
                            <option value="2">Fornecedor Y</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tipoCustoModal" class="form-label">Tipo de Custo</label>
                        <select class="form-select" id="tipoCustoModal" required>
                            <option value="">Selecione...</option>
                            <option value="1">Frete</option>
                            <option value="2">Estadia</option>
                            <option value="3">Pedágio</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="numeroReciboModal" class="form-label">Nº Recibo</label>
                        <input type="text" class="form-control" id="numeroReciboModal" required>
                    </div>
                    <div class="col-md-4">
                        <label for="valorModal" class="form-label">Valor</label>
                        <input type="text" class="form-control" id="valorModal" required>
                    </div>
                    <div class="col-md-12">
                        <label for="observacao" class="form-label">Observação</label>
                        <textarea class="form-control" id="observacao" rows="3"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label for="anexos" class="form-label">Anexos</label>
                        <input type="file" class="form-control" id="anexos" multiple>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="salvarCusto()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<!-- Select2 para melhorar selects -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Máscaras de Input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializa DataTable
    $('#custosTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        order: [[1, 'desc']] // Ordena por data de registro
    });

    // Inicializa Select2
    $('.form-select').select2({
        theme: 'bootstrap-5'
    });

    // Inicializa máscaras
    $('#valorModal').mask('#.##0,00', {reverse: true});
});

function salvarCusto() {
    // Implementar lógica de salvamento
    alert('Função de salvamento a ser implementada');
    $('#novoCustoModal').modal('hide');
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
