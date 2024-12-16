<?php
$title = 'Construtor de Relatórios Personalizados';
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
                    <li class="breadcrumb-item active">Construtor de Relatórios</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <form method="POST" action="/relatorios/gerar">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Selecione os Campos e Filtros</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="campos">Campos</label>
                        <select multiple class="form-control" id="campos" name="campos[]">
                            <option value="descricao">Descrição</option>
                            <option value="valor">Valor</option>
                            <option value="data_registro">Data de Registro</option>
                            <option value="status">Status</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filtros">Filtros</label>
                        <input type="text" class="form-control" id="filtros" name="filtros" placeholder="Digite os filtros...">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                </div>
            </div>
        </form>
    </div>
</section>
