<?php
include_once '../../layouts/sidebar.php';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Notificações</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-right">
                    <button class="btn btn-primary" onclick="exportToCSV()">
                        <i class="fas fa-download"></i> Exportar CSV
                    </button>
                    <a href="/notificacoes/criar" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nova Notificação
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filtros -->
        <div class="card filter-section mb-4">
            <div class="card-body">
                <form id="filter-form" class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Buscar</label>
                            <input type="text" name="search" class="form-control" value="<?php echo $_GET['search'] ?? ''; ?>" placeholder="Buscar...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Categoria</label>
                            <select name="categoria" class="form-control">
                                <option value="">Todas</option>
                                <option value="Geral" <?php echo ($_GET['categoria'] ?? '') === 'Geral' ? 'selected' : ''; ?>>Geral</option>
                                <option value="Sistema" <?php echo ($_GET['categoria'] ?? '') === 'Sistema' ? 'selected' : ''; ?>>Sistema</option>
                                <option value="Urgente" <?php echo ($_GET['categoria'] ?? '') === 'Urgente' ? 'selected' : ''; ?>>Urgente</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Todos</option>
                                <option value="Lida" <?php echo ($_GET['status'] ?? '') === 'Lida' ? 'selected' : ''; ?>>Lida</option>
                                <option value="Não lida" <?php echo ($_GET['status'] ?? '') === 'Não lida' ? 'selected' : ''; ?>>Não lida</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Data Início</label>
                            <input type="date" name="data_inicio" class="form-control" value="<?php echo $_GET['data_inicio'] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Data Fim</label>
                            <input type="date" name="data_fim" class="form-control" value="<?php echo $_GET['data_fim'] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Notificações -->
        <div class="card">
            <div class="card-body">
                <div class="notification-list">
                    <?php if (empty($notificacoes)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Nenhuma notificação encontrada</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notificacoes as $notificacao): ?>
                            <div class="notification-item" data-id="<?php echo $notificacao->id; ?>">
                                <div class="notification-header">
                                    <h4><?php echo $notificacao->titulo; ?></h4>
                                    <span class="badge badge-<?php echo $notificacao->status === 'Lida' ? 'success' : 'warning'; ?>">
                                        <?php echo $notificacao->status; ?>
                                    </span>
                                </div>
                                <p><?php echo $notificacao->mensagem; ?></p>
                                <div class="notification-footer">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> 
                                            <?php echo date('d/m/Y H:i', strtotime($notificacao->data_hora)); ?>
                                        </small>
                                        <?php if ($notificacao->categoria): ?>
                                            <span class="badge badge-info ml-2"><?php echo $notificacao->categoria; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="notification-actions">
                                        <a href="/notificacoes/visualizar/<?php echo $notificacao->id; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($notificacao->status !== 'Lida'): ?>
                                            <button onclick="markAsRead(<?php echo $notificacao->id; ?>)" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button onclick="deleteNotification(<?php echo $notificacao->id; ?>)" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Paginação -->
                <?php if ($totalPages > 1): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Navegação de página">
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Botão de alternar tema -->
<button id="theme-toggle" class="theme-toggle">
    <i class="fas fa-moon"></i>
</button>

<!-- Scripts -->
<link rel="stylesheet" href="/assets/css/notificacoes.css">
<script src="/assets/js/notificacoes.js"></script>
