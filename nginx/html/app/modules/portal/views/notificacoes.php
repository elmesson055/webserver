<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bell"></i> Notificações</h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-success" id="marcarTodasLidas">
                            <i class="fas fa-check-double"></i> Marcar todas como lidas
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($notificacoes)): ?>
                        <p class="text-muted text-center">Nenhuma notificação encontrada.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($notificacoes as $notif): ?>
                                <div class="list-group-item list-group-item-action <?= !$notif->lida ? 'bg-light' : '' ?>">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <h6 class="mb-1">
                                            <?php if (!$notif->lida): ?>
                                                <span class="badge bg-primary me-2">Nova</span>
                                            <?php endif; ?>
                                            <?= $notif->titulo ?>
                                        </h6>
                                        <small class="text-muted">
                                            <?= $notif->created_at->diffForHumans() ?>
                                        </small>
                                    </div>
                                    <p class="mb-1"><?= $notif->mensagem ?></p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            Tipo: <span class="badge bg-<?= $this->getNotificacaoClass($notif->tipo) ?>">
                                                <?= ucfirst($notif->tipo) ?>
                                            </span>
                                        </small>
                                        <?php if (!$notif->lida): ?>
                                            <button type="button" class="btn btn-sm btn-outline-primary marcar-lida" 
                                                    data-id="<?= $notif->id ?>">
                                                <i class="fas fa-check"></i> Marcar como lida
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Paginação -->
                        <?php if ($notificacoes->hasPages()): ?>
                            <div class="d-flex justify-content-center mt-4">
                                <?= $notificacoes->links() ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Marcar uma notificação como lida
    $('.marcar-lida').click(function() {
        var id = $(this).data('id');
        var button = $(this);
        
        $.post('/portal/notificacoes/marcar-lida/' + id, function(response) {
            if (response.success) {
                button.closest('.list-group-item').removeClass('bg-light');
                button.closest('.list-group-item').find('.badge.bg-primary').remove();
                button.remove();
            }
        });
    });

    // Marcar todas as notificações como lidas
    $('#marcarTodasLidas').click(function() {
        $.post('/portal/notificacoes/marcar-todas-lidas', function(response) {
            if (response.success) {
                location.reload();
            }
        });
    });
});
</script>

<?php
    // Helper function para definir a classe do badge de acordo com o tipo de notificação
    private function getNotificacaoClass($tipo)
    {
        switch ($tipo) {
            case 'info':
                return 'info';
            case 'warning':
                return 'warning';
            case 'error':
                return 'danger';
            case 'success':
                return 'success';
            default:
                return 'secondary';
        }
    }
?>
