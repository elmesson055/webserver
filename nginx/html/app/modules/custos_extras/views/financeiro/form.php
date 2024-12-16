<?php
$title = 'Detalhes do Pagamento';
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
                    <li class="breadcrumb-item"><a href="/custos-extras/financeiro">Financeiro</a></li>
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
                                        <span class="info-box-number"><?php echo $pagamento->codigo; ?></span>
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
                                        <span class="info-box-number">R$ <?php echo number_format($pagamento->valor, 2, ',', '.'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <p class="form-control-static"><?php echo $pagamento->cliente->nome; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de Custo</label>
                                    <p class="form-control-static"><?php echo $pagamento->tipo_custo->nome; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data</label>
                                    <p class="form-control-static"><?php echo date('d/m/Y', strtotime($pagamento->data)); ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Vencimento</label>
                                    <p class="form-control-static">
                                        <?php echo $pagamento->vencimento ? date('d/m/Y', strtotime($pagamento->vencimento)) : '-'; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <p class="form-control-static">
                                        <span class="badge" style="background-color: <?php echo $pagamento->status->cor; ?>">
                                            <?php echo $pagamento->status->nome; ?>
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
                                        <?php echo $pagamento->descricao ?: '-'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observações</label>
                                    <p class="form-control-static">
                                        <?php echo $pagamento->observacoes ?: '-'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Pagamento -->
                <?php if($pagamento->pagamento): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informações do Pagamento</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Data do Pagamento</label>
                                        <p class="form-control-static">
                                            <?php echo date('d/m/Y', strtotime($pagamento->pagamento->data_pagamento)); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Valor Pago</label>
                                        <p class="form-control-static">
                                            R$ <?php echo number_format($pagamento->pagamento->valor_pago, 2, ',', '.'); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Forma de Pagamento</label>
                                        <p class="form-control-static">
                                            <?php echo $pagamento->pagamento->forma_pagamento; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Usuário</label>
                                        <p class="form-control-static">
                                            <?php echo $pagamento->pagamento->usuario->nome; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Observações do Pagamento</label>
                                        <p class="form-control-static">
                                            <?php echo $pagamento->pagamento->observacoes ?: '-'; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

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
                                <?php if($pagamento->status->permite_pagamento): ?>
                                    <button type="button" class="btn btn-success" onclick="registrarPagamento(<?php echo $pagamento->id; ?>)">
                                        <i class="fas fa-dollar-sign"></i> Registrar Pagamento
                                    </button>
                                <?php endif; ?>
                                <a href="/custos-extras/financeiro" class="btn btn-default">
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

<!-- Modal de Pagamento -->
<div class="modal fade" id="modalPagamento" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPagamento">
                    <div class="form-group">
                        <label>Data do Pagamento <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="data_pagamento" required>
                    </div>
                    <div class="form-group">
                        <label>Valor Pago <span class="text-danger">*</span></label>
                        <input type="text" class="form-control money" name="valor_pago" required>
                    </div>
                    <div class="form-group">
                        <label>Forma de Pagamento <span class="text-danger">*</span></label>
                        <select class="form-control" name="forma_pagamento" required>
                            <option value="">Selecione...</option>
                            <option value="DINHEIRO">Dinheiro</option>
                            <option value="PIX">PIX</option>
                            <option value="CARTAO">Cartão</option>
                            <option value="BOLETO">Boleto</option>
                            <option value="TRANSFERENCIA">Transferência</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Observações</label>
                        <textarea class="form-control" name="observacoes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarPagamento()">
                    <i class="fas fa-dollar-sign"></i> Registrar Pagamento
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Inicialização de componentes
$(document).ready(function() {
    $('.money').mask('#.##0,00', {
        reverse: true
    });
});

// Pagamento
function registrarPagamento(id) {
    $('#modalPagamento').modal('show');
}

function confirmarPagamento() {
    if(!$('#formPagamento').valid()) {
        return;
    }

    let formData = new FormData($('#formPagamento')[0]);
    
    $.ajax({
        url: `/custos-extras/financeiro/${<?php echo $pagamento->id; ?>}/pagar`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#modalPagamento').modal('hide');
            Swal.fire(
                'Sucesso!',
                'Pagamento registrado com sucesso.',
                'success'
            ).then(() => {
                window.location.href = '/custos-extras/financeiro';
            });
        },
        error: function(xhr) {
            Swal.fire(
                'Erro!',
                'Ocorreu um erro ao registrar o pagamento.',
                'error'
            );
        }
    });
}

// Validação do formulário
$('#formPagamento').validate({
    rules: {
        data_pagamento: {
            required: true
        },
        valor_pago: {
            required: true
        },
        forma_pagamento: {
            required: true
        },
        observacoes: {
            maxlength: 1000
        }
    },
    messages: {
        data_pagamento: {
            required: "Por favor, informe a data do pagamento"
        },
        valor_pago: {
            required: "Por favor, informe o valor pago"
        },
        forma_pagamento: {
            required: "Por favor, selecione a forma de pagamento"
        },
        observacoes: {
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
</script>
