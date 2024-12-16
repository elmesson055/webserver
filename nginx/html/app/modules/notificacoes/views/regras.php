<?php
$title = 'Regras de Notificação';
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
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regras as $regra): ?>
                    <tr>
                        <td><?php echo $regra->id; ?></td>
                        <td><?php echo $regra->descricao; ?></td>
                        <td><?php echo $regra->acoes; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
