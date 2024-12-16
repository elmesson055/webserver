<?php
$title = 'Criar Regra de Notificação';
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
        <form method="POST" action="/notificacoes/criar">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Defina a Regra de Notificação</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="tipo_evento">Tipo de Evento</label>
                        <input type="text" class="form-control" id="tipo_evento" name="tipo_evento" required>
                    </div>
                    <div class="form-group">
                        <label for="mensagem">Mensagem</label>
                        <input type="text" class="form-control" id="mensagem" name="mensagem" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="ativa">Ativa</option>
                            <option value="inativa">Inativa</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Criar Regra</button>
                </div>
            </div>
        </form>
    </div>
</section>
