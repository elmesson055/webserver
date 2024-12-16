<?php
$title = 'Detalhes da Aprovação';
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
                    <li class="breadcrumb-item"><a href="/custos-extras/aprovacao">Aprovação</a></li>
                    <li class="breadcrumb-item active">Detalhes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Informações do Custo Extra -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informações do Custo Extra</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-info"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Código</span>
                                        <span class="info-box-number"><?php echo $aprovacao->codigo; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success">
                                        <i class="fas fa-dollar-sign"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Valor</span>
                                        <span class="info-box-number">R$ <?php echo number_format($aprovacao->valor, 2, ',', '.'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <p class="form-control-static"><?php echo $aprovacao->cliente->nome; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de Custo</label>
                                    <p class="form-control-static"><?php echo $aprovacao->tipo_custo->nome; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data</label>
                                    <p class="form-control-static"><?php echo date('d/m/Y', strtotime($aprovacao->data)); ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Vencimento</label>
                                    <p class="form-control-static">
                                        <?php echo $aprovacao->vencimento ? date('d/m/Y', strtotime($aprovacao->vencimento)) : '-'; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <p class="form-control-static">
                                        <span class="badge" style="background-color: <?php echo $aprovacao->status->cor; ?>">
                                            <?php echo $aprovacao->status->nome; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Descrição</label>
                                    <p class="form-control-static">
                                        <?php echo $aprovacao->descricao ?: '-'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observações</label>
                                    <p class="form-control-static">
                                        <?php echo $aprovacao->observacoes ?: '-'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documentos -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Documentos</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Nome</th>
                                        <th>Data</th>
                                        <th>Tamanho</th>
                                        <th width="100">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($documentos)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Nenhum documento encontrado</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach($documentos as $doc): ?>
                                            <tr>
                                                <td><?php echo $doc->tipo_documento->nome; ?></td>
                                                <td><?php echo $doc->nome_original; ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($doc->created_at)); ?></td>
                                                <td><?php echo $doc->tamanho_formatado; ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="/documentos/<?php echo $doc->id; ?>/download" class="btn btn-default btn-sm" title="Download">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Histórico -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Histórico</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php if(empty($historico)): ?>
                                <div>Nenhum histórico encontrado</div>
                            <?php else: ?>
                                <?php foreach($historico as $hist): ?>
                                    <div class="time-label">
                                        <span class="bg-info"><?php echo $hist->data; ?></span>
                                    </div>
                                    <div>
                                        <i class="fas <?php echo $hist->icone; ?> bg-<?php echo $hist->cor; ?>"></i>
                                        <div class="timeline-item">
                                            <span class="time">
                                                <i class="fas fa-clock"></i> <?php echo $hist->hora; ?>
                                            </span>
                                            <h3 class="timeline-header"><?php echo $hist->titulo; ?></h3>
                                            <div class="timeline-body">
                                                <?php echo $hist->descricao; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if($aprovacao->status->permite_aprovacao): ?>
                                    <button type="button" class="btn btn-success" onclick="aprovar(<?php echo $aprovacao->id; ?>)">
                                        <i class="fas fa-check"></i> Aprovar
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="rejeitar(<?php echo $aprovacao->id; ?>)">
                                        <i class="fas fa-times"></i> Rejeitar
                                    </button>
                                <?php endif; ?>
                                <a href="/custos-extras/aprovacao" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal de Aprovação -->
<div class="modal fade" id="modalAprovacao" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aprovar Custo Extra</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAprovacao">
                    <div class="form-group">
                        <label>Observações</label>
                        <textarea class="form-control" name="observacoes" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarAprovacao()">
                    <i class="fas fa-check"></i> Aprovar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Rejeição -->
<div class="modal fade" id="modalRejeicao" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rejeitar Custo Extra</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formRejeicao">
                    <div class="form-group">
                        <label>Motivo da Rejeição</label>
                        <textarea class="form-control" name="observacoes" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarRejeicao()">
                    <i class="fas fa-times"></i> Rejeitar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Aprovação
function aprovar(id) {
    $('#modalAprovacao').modal('show');
}

function confirmarAprovacao() {
    if(!$('#formAprovacao').valid()) {
        return;
    }

    let observacoes = $('#formAprovacao textarea[name="observacoes"]').val();
    
    $.ajax({
        url: `/custos-extras/aprovacao/${<?php echo $aprovacao->id; ?>}/aprovar`,
        method: 'POST',
        data: { observacoes },
        success: function(response) {
            $('#modalAprovacao').modal('hide');
            Swal.fire(
                'Aprovado!',
                'Custo extra aprovado com sucesso.',
                'success'
            ).then(() => {
                window.location.href = '/custos-extras/aprovacao';
            });
        },
        error: function(xhr) {
            Swal.fire(
                'Erro!',
                'Ocorreu um erro ao aprovar o custo extra.',
                'error'
            );
        }
    });
}

// Rejeição
function rejeitar(id) {
    $('#modalRejeicao').modal('show');
}

function confirmarRejeicao() {
    if(!$('#formRejeicao').valid()) {
        return;
    }

    let observacoes = $('#formRejeicao textarea[name="observacoes"]').val();
    
    $.ajax({
        url: `/custos-extras/aprovacao/${<?php echo $aprovacao->id; ?>}/rejeitar`,
        method: 'POST',
        data: { observacoes },
        success: function(response) {
            $('#modalRejeicao').modal('hide');
            Swal.fire(
                'Rejeitado!',
                'Custo extra rejeitado com sucesso.',
                'success'
            ).then(() => {
                window.location.href = '/custos-extras/aprovacao';
            });
        },
        error: function(xhr) {
            Swal.fire(
                'Erro!',
                'Ocorreu um erro ao rejeitar o custo extra.',
                'error'
            );
        }
    });
}

// Validação dos formulários
$('#formAprovacao').validate({
    rules: {
        observacoes: {
            required: true,
            maxlength: 1000
        }
    },
    messages: {
        observacoes: {
            required: "Por favor, informe as observações",
            maxlength: "As observações devem ter no máximo 1000 caracteres"
        }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
});

$('#formRejeicao').validate({
    rules: {
        observacoes: {
            required: true,
            maxlength: 1000
        }
    },
    messages: {
        observacoes: {
            required: "Por favor, informe o motivo da rejeição",
            maxlength: "O motivo deve ter no máximo 1000 caracteres"
        }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
});
</script>
