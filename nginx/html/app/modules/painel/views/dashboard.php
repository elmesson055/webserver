<?php
$title = 'Painel de Controle';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?php echo $title; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active">Painel de Controle</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Visão Geral de Todos os Custos -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>R$ <?php echo number_format($totalCustos, 2, ',', '.'); ?></h3>
                        <p>Total de Custos</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>R$ <?php echo number_format($totalReceitas, 2, ',', '.'); ?></h3>
                        <p>Total de Receitas</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>R$ <?php echo number_format($saldo, 2, ',', '.'); ?></h3>
                        <p>Saldo Atual</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo $custosPendentes; ?></h3>
                        <p>Aprovações Pendentes</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mais informações <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- Gráficos de Status -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Distribuição por Status</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <!-- Atividades Recentes -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Atividades Recentes</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($atividadesRecentes as $atividade): ?>
                                <li class="list-group-item">
                                    <?php echo $atividade->descricao; ?>
                                    <span class="float-right text-muted text-sm">
                                        <?php echo date('d/m/Y H:i', strtotime($atividade->data_hora)); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Status
    var statusDistribution = <?php echo json_encode($statusDistribution); ?>;

    // Atualizar o gráfico com os dados de status
    var ctx = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: statusDistribution.map(function(item) { return item.label; }),
            datasets: [{
                data: statusDistribution.map(function(item) { return item.value; }),
                backgroundColor: ['#007bff', '#28a745', '#dc3545', '#ffc107'],
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
        }
    });
</script>
