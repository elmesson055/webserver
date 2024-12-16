<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-file-alt"></i> Documentos</h5>
                    <p class="card-text display-6"><?= $documentosCount ?>/<?= $documentosTotal ?></p>
                    <div class="progress progress-small">
                        <div class="progress-bar bg-white" role="progressbar" style="width: <?= ($documentosCount/$documentosTotal)*100 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-check-circle"></i> Status</h5>
                    <p class="card-text"><?= $status ?></p>
                    <small><?= $statusMessage ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-clock"></i> Pendências</h5>
                    <p class="card-text display-6"><?= $pendenciasCount ?></p>
                    <small><?= $proximaExpiracao ?></small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-envelope"></i> Mensagens</h5>
                    <p class="card-text display-6"><?= $unreadMessages ?></p>
                    <small>Mensagens não lidas</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Resumo Financeiro</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Saldo</h6>
                                    <h4><?= $fornecedor->getSaldoFormatado() ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Valor em Aberto</h6>
                                    <h4><?= $fornecedor->getValorEmAbertoFormatado() ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Limite de Crédito</h6>
                                    <h4><?= $fornecedor->getLimiteCreditoFormatado() ?></h4>
                                    <div class="progress progress-small">
                                        <div class="progress-bar bg-<?= $fornecedor->getPorcentagemLimiteUtilizado() > 80 ? 'danger' : 'success' ?>"
                                             role="progressbar"
                                             style="width: <?= $fornecedor->getPorcentagemLimiteUtilizado() ?>%">
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        Disponível: <?= $fornecedor->getLimiteDisponivelFormatado() ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Status Financeiro</h6>
                                    <h4><?= $fornecedor->getStatusFinanceiroFormatado() ?></h4>
                                    <?php if ($fornecedor->dias_atraso > 0): ?>
                                        <small class="text-danger">
                                            <?= $fornecedor->dias_atraso ?> dias em atraso
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Últimas Movimentações</h5>
                    <a href="/portal/financeiro/movimentacoes" class="btn btn-sm btn-primary">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Tipo</th>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ultimasMovimentacoes as $mov): ?>
                                <tr>
                                    <td><?= $mov->getDataVencimentoFormatada() ?></td>
                                    <td><?= $mov->tipo ?></td>
                                    <td><?= $mov->descricao ?></td>
                                    <td><?= $mov->getValorFormatado() ?></td>
                                    <td>
                                        <span class="badge bg-<?= $mov->status === 'PAGO' ? 'success' : 
                                            ($mov->status === 'PENDENTE' ? 'warning' : 
                                            ($mov->status === 'CANCELADO' ? 'danger' : 'info')) ?>">
                                            <?= $mov->status ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Documentos Pendentes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($documentosPendentes)): ?>
                        <p class="text-success"><i class="fas fa-check-circle"></i> Nenhum documento pendente!</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($documentosPendentes as $doc): ?>
                                <a href="/portal/documentos/upload/<?= $doc['id'] ?>" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= $doc['nome'] ?></h6>
                                        <small class="text-danger">Vence em <?= $doc['dias_para_vencer'] ?> dias</small>
                                    </div>
                                    <small><?= $doc['descricao'] ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell"></i>
                        Últimas Notificações
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($notificacoes)): ?>
                        <p class="text-muted">Nenhuma notificação recente.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($notificacoes as $notif): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= $notif['titulo'] ?></h6>
                                        <small class="text-muted"><?= $notif['data'] ?></small>
                                    </div>
                                    <p class="mb-1"><?= $notif['mensagem'] ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt"></i>
                        Documentos Recentes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($documentosRecentes)): ?>
                        <p class="text-muted">Nenhum documento recente.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Data Upload</th>
                                        <th>Validade</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($documentosRecentes as $doc): ?>
                                        <tr>
                                            <td><?= $doc['nome'] ?></td>
                                            <td><?= $doc['data_upload'] ?></td>
                                            <td><?= $doc['validade'] ?></td>
                                            <td>
                                                <span class="badge bg-<?= $doc['status_class'] ?>">
                                                    <?= $doc['status'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/portal/documentos/view/<?= $doc['id'] ?>" class="btn btn-sm btn-info" title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="/portal/documentos/download/<?= $doc['id'] ?>" class="btn btn-sm btn-success" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
