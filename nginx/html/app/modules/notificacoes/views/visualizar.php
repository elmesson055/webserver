<?php
include_once '../../layouts/sidebar.php';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Visualizar Notificação</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-right">
                    <a href="/notificacoes" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detalhes da Notificação</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Título</label>
                            <p class="form-control-static"><?php echo $notificacao->titulo; ?></p>
                        </div>
                        <div class="form-group">
                            <label>Mensagem</label>
                            <p class="form-control-static"><?php echo $notificacao->mensagem; ?></p>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <p class="form-control-static">
                                <span class="badge badge-<?php echo $notificacao->status == 'Lida' ? 'success' : 'warning'; ?>">
                                    <?php echo $notificacao->status; ?>
                                </span>
                            </p>
                        </div>
                        <div class="form-group">
                            <label>Data/Hora</label>
                            <p class="form-control-static"><?php echo date('d/m/Y H:i', strtotime($notificacao->data_hora)); ?></p>
                        </div>
                        <?php if ($notificacao->data_leitura): ?>
                            <div class="form-group">
                                <label>Data de Leitura</label>
                                <p class="form-control-static"><?php echo date('d/m/Y H:i', strtotime($notificacao->data_leitura)); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <?php if ($notificacao->status !== 'Lida'): ?>
                    <a href="/notificacoes/marcar-como-lida/<?php echo $notificacao->id; ?>" class="btn btn-success">
                        <i class="fas fa-check"></i> Marcar como Lida
                    </a>
                <?php endif; ?>
                <a href="/notificacoes/excluir/<?php echo $notificacao->id; ?>" 
                   class="btn btn-danger"
                   onclick="return confirm('Tem certeza que deseja excluir esta notificação?')">
                    <i class="fas fa-trash"></i> Excluir
                </a>
            </div>
        </div>
    </div>
</section>
