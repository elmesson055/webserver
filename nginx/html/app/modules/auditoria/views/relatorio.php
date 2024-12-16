<?php
$title = 'Relatório de Auditoria';
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
        <form method="GET" action="/auditoria/relatorio">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="usuario" placeholder="Filtrar por usuário">
                </div>
                <div class="col">
                    <input type="date" class="form-control" name="data_inicio" placeholder="Data Início">
                </div>
                <div class="col">
                    <input type="date" class="form-control" name="data_fim" placeholder="Data Fim">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        <div class="mt-3">
            <a href="/auditoria/exportar/pdf" class="btn btn-secondary">Exportar PDF</a>
            <a href="/auditoria/exportar/excel" class="btn btn-secondary">Exportar Excel</a>
        </div>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Ação</th>
                    <th>Descrição</th>
                    <th>Data/Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditorias as $auditoria): ?>
                    <tr>
                        <td><?php echo $auditoria->usuario_id; ?></td>
                        <td><?php echo $auditoria->acao; ?></td>
                        <td><?php echo $auditoria->descricao; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($auditoria->data_hora)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
