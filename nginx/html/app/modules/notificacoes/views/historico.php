<?php
$title = 'Histórico de Notificações';
include_once '../../layouts/sidebar.php';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?php echo $title; ?></h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <nav class="sidebar">
            <ul>
                <li><a href="/auditoria"><i class="icon-auditoria"></i> Auditoria</a></li>
                <li><a href="/relatorios"><i class="icon-relatorios"></i> Relatórios</a></li>
                <li><a href="/notificacoes"><i class="icon-notificacoes"></i> Notificações</a></li>
                <li><a href="/custos_extras"><i class="icon-custos"></i> Custos Extras</a></li>
                <li><a href="/cadastros"><i class="icon-cadastros"></i> Cadastros</a></li>
                <li><a href="/configuracoes"><i class="icon-configuracoes"></i> Configurações</a></li>
            </ul>
        </nav>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Mensagem</th>
                    <th>Status</th>
                    <th>Data/Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notificacoes as $notificacao): ?>
                    <tr>
                        <td><?php echo $notificacao->mensagem; ?></td>
                        <td><?php echo $notificacao->status; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($notificacao->data_hora)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
