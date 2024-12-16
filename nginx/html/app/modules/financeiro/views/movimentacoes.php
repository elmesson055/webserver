<?php include_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Movimentações Financeiras</h4>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <form class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <?php foreach ($status as $codigo => $nome): ?>
                                    <option value="<?= $codigo ?>" <?= $filtros['status'] === $codigo ? 'selected' : '' ?>>
                                        <?= $nome ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo</label>
                            <select name="tipo" class="form-select">
                                <option value="">Todos</option>
                                <?php foreach ($tipos as $codigo => $nome): ?>
                                    <option value="<?= $codigo ?>" <?= $filtros['tipo'] === $codigo ? 'selected' : '' ?>>
                                        <?= $nome ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data Inicial</label>
                            <input type="date" name="data_inicio" class="form-control" value="<?= $filtros['data_inicio'] ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Data Final</label>
                            <input type="date" name="data_fim" class="form-control" value="<?= $filtros['data_fim'] ?>">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <a href="/financeiro/movimentacoes" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Limpar
                            </a>
                        </div>
                    </form>

                    <!-- Tabela de Movimentações -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data Vencimento</th>
                                    <th>Tipo</th>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Data Pagamento</th>
                                    <th>Forma Pagamento</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movimentacoes as $mov): ?>
                                <tr class="<?= $mov->isAtrasado() ? 'table-danger' : '' ?>">
                                    <td><?= $mov->getDataVencimentoFormatada() ?></td>
                                    <td><?= $tipos[$mov->tipo] ?></td>
                                    <td><?= $mov->descricao ?></td>
                                    <td><?= $mov->getValorFormatado() ?></td>
                                    <td>
                                        <span class="badge bg-<?= $mov->status === 'PAGO' ? 'success' : 
                                            ($mov->status === 'PENDENTE' ? 'warning' : 
                                            ($mov->status === 'CANCELADO' ? 'danger' : 'info')) ?>">
                                            <?= $status[$mov->status] ?>
                                        </span>
                                    </td>
                                    <td><?= $mov->getDataPagamentoFormatada() ?></td>
                                    <td><?= $mov->forma_pagamento ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" onclick="verDetalhes(<?= $mov->id ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($mov->status === 'PENDENTE'): ?>
                                            <button type="button" class="btn btn-sm btn-success" onclick="enviarComprovante(<?= $mov->id ?>)">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <?php if ($movimentacoes->lastPage() > 1): ?>
                    <nav aria-label="Navegação">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= $movimentacoes->currentPage() === 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $movimentacoes->previousPageUrl() ?>">Anterior</a>
                            </li>
                            
                            <?php for ($i = 1; $i <= $movimentacoes->lastPage(); $i++): ?>
                            <li class="page-item <?= $movimentacoes->currentPage() === $i ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $movimentacoes->url($i) ?>"><?= $i ?></a>
                            </li>
                            <?php endfor; ?>

                            <li class="page-item <?= $movimentacoes->currentPage() === $movimentacoes->lastPage() ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $movimentacoes->nextPageUrl() ?>">Próxima</a>
                            </li>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalhes -->
<div class="modal fade" id="modalDetalhes" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes da Movimentação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tipo:</strong> <span id="detalhe-tipo"></span></p>
                        <p><strong>Descrição:</strong> <span id="detalhe-descricao"></span></p>
                        <p><strong>Valor:</strong> <span id="detalhe-valor"></span></p>
                        <p><strong>Status:</strong> <span id="detalhe-status"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Data Vencimento:</strong> <span id="detalhe-vencimento"></span></p>
                        <p><strong>Data Pagamento:</strong> <span id="detalhe-pagamento"></span></p>
                        <p><strong>Forma Pagamento:</strong> <span id="detalhe-forma"></span></p>
                        <p><strong>Número Documento:</strong> <span id="detalhe-documento"></span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p><strong>Observações:</strong></p>
                        <p id="detalhe-observacoes"></p>
                    </div>
                </div>
                <div class="row mt-3" id="area-comprovante">
                    <div class="col-12">
                        <p><strong>Comprovante:</strong></p>
                        <p id="detalhe-comprovante"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Comprovante -->
<div class="modal fade" id="modalComprovante" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar Comprovante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formComprovante">
                    <input type="hidden" id="movimentacao_id" name="movimentacao_id">
                    <div class="mb-3">
                        <label class="form-label">Arquivo</label>
                        <input type="file" name="comprovante" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="uploadComprovante()">Enviar</button>
            </div>
        </div>
    </div>
</div>

<script>
function verDetalhes(id) {
    fetch(`/financeiro/movimentacoes/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const mov = data.data;
                document.getElementById('detalhe-tipo').textContent = tipos[mov.tipo];
                document.getElementById('detalhe-descricao').textContent = mov.descricao;
                document.getElementById('detalhe-valor').textContent = new Intl.NumberFormat('pt-BR', { 
                    style: 'currency', 
                    currency: 'BRL' 
                }).format(mov.valor);
                document.getElementById('detalhe-status').textContent = status[mov.status];
                document.getElementById('detalhe-vencimento').textContent = new Date(mov.data_vencimento).toLocaleDateString('pt-BR');
                document.getElementById('detalhe-pagamento').textContent = mov.data_pagamento ? 
                    new Date(mov.data_pagamento).toLocaleDateString('pt-BR') : '-';
                document.getElementById('detalhe-forma').textContent = mov.forma_pagamento || '-';
                document.getElementById('detalhe-documento').textContent = mov.numero_documento || '-';
                document.getElementById('detalhe-observacoes').textContent = mov.observacoes || '-';
                
                const areaComprovante = document.getElementById('area-comprovante');
                const detalheComprovante = document.getElementById('detalhe-comprovante');
                
                if (mov.comprovante) {
                    areaComprovante.style.display = 'block';
                    detalheComprovante.innerHTML = `
                        <a href="${mov.comprovante}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Baixar Comprovante
                        </a>`;
                } else {
                    areaComprovante.style.display = 'none';
                }

                $('#modalDetalhes').modal('show');
            }
        });
}

function enviarComprovante(id) {
    document.getElementById('movimentacao_id').value = id;
    $('#modalComprovante').modal('show');
}

function uploadComprovante() {
    const form = document.getElementById('formComprovante');
    const formData = new FormData(form);
    const id = document.getElementById('movimentacao_id').value;

    fetch(`/financeiro/movimentacoes/${id}/comprovante`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            $('#modalComprovante').modal('hide');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            toastr.error(data.message);
        }
    })
    .catch(error => {
        toastr.error('Erro ao enviar comprovante');
    });
}
</script>

<?php include_once VIEWS_PATH . '/layout/footer.php'; ?>
