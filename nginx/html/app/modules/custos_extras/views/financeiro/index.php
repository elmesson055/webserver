<?php
$title = 'Financeiro - Custos Extras';
include_once '../../layouts/sidebar.php';
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
                    <li class="breadcrumb-item active">Financeiro</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Cards de Resumo -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>R$ <?php echo number_format($totais->a_pagar, 2, ',', '.'); ?></h3>
                        <p>A Pagar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>R$ <?php echo number_format($totais->pagos, 2, ',', '.'); ?></h3>
                        <p>Pagos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>R$ <?php echo number_format($totais->vencidos, 2, ',', '.'); ?></h3>
                        <p>Vencidos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>R$ <?php echo number_format($totais->cancelados, 2, ',', '.'); ?></h3>
                        <p>Cancelados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Filtros</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form action="/custos-extras/financeiro" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Período</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="data_inicial" value="<?php echo $filtros['data_inicial'] ?? ''; ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">até</span>
                                    </div>
                                    <input type="date" class="form-control" name="data_final" value="<?php echo $filtros['data_final'] ?? ''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cliente</label>
                                <select class="form-control select2" name="cliente_id">
                                    <option value="">Todos</option>
                                    <?php foreach($clientes as $cliente): ?>
                                        <option value="<?php echo $cliente->id; ?>" 
                                                <?php echo ($filtros['cliente_id'] ?? '') == $cliente->id ? 'selected' : ''; ?>>
                                            <?php echo $cliente->nome; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select2" name="status_id">
                                    <option value="">Todos</option>
                                    <?php foreach($todosStatus as $status): ?>
                                        <option value="<?php echo $status->id; ?>" 
                                                <?php echo ($filtros['status_id'] ?? '') == $status->id ? 'selected' : ''; ?>>
                                            <?php echo $status->nome; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo de Custo</label>
                                <select class="form-control select2" name="tipo_custo_id">
                                    <option value="">Todos</option>
                                    <?php foreach($tiposCusto as $tipo): ?>
                                        <option value="<?php echo $tipo->id; ?>" 
                                                <?php echo ($filtros['tipo_custo_id'] ?? '') == $tipo->id ? 'selected' : ''; ?>>
                                            <?php echo $tipo->nome; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <a href="/custos-extras/financeiro" class="btn btn-default">
                                <i class="fas fa-times"></i> Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Pagamentos -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title">Pagamentos</h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-file-export"></i> Exportar
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/custos-extras/financeiro/relatorio?formato=pdf<?php echo isset($_SERVER['QUERY_STRING']) ? '&'.$_SERVER['QUERY_STRING'] : ''; ?>">
                                    <i class="far fa-file-pdf"></i> PDF
                                </a>
                                <a class="dropdown-item" href="/custos-extras/financeiro/relatorio?formato=excel<?php echo isset($_SERVER['QUERY_STRING']) ? '&'.$_SERVER['QUERY_STRING'] : ''; ?>">
                                    <i class="far fa-file-excel"></i> Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Vencimento</th>
                            <th>Status</th>
                            <th>Documentos</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($pagamentos)): ?>
                            <tr>
                                <td colspan="9" class="text-center">Nenhum registro encontrado</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($pagamentos as $pagamento): ?>
                                <tr>
                                    <td><?php echo $pagamento->codigo; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($pagamento->data)); ?></td>
                                    <td><?php echo $pagamento->cliente->nome; ?></td>
                                    <td><?php echo $pagamento->tipo_custo->nome; ?></td>
                                    <td>R$ <?php echo number_format($pagamento->valor, 2, ',', '.'); ?></td>
                                    <td>
                                        <?php if($pagamento->vencimento): ?>
                                            <span class="<?php echo strtotime($pagamento->vencimento) < time() ? 'text-danger' : ''; ?>">
                                                <?php echo date('d/m/Y', strtotime($pagamento->vencimento)); ?>
                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: <?php echo $pagamento->status->cor; ?>">
                                            <?php echo $pagamento->status->nome; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($pagamento->documentos_count > 0): ?>
                                            <span class="badge badge-info">
                                                <i class="fas fa-file-alt"></i> <?php echo $pagamento->documentos_count; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-file-alt"></i> 0
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if($pagamento->status->permite_pagamento): ?>
                                                <button type="button" class="btn btn-success btn-sm" title="Registrar Pagamento" 
                                                        onclick="registrarPagamento(<?php echo $pagamento->id; ?>)">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-default btn-sm" title="Documentos" 
                                                    onclick="verDocumentos(<?php echo $pagamento->id; ?>)">
                                                <i class="fas fa-file-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-default btn-sm" title="Histórico" 
                                                    onclick="verHistorico(<?php echo $pagamento->id; ?>)">
                                                <i class="fas fa-history"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(isset($paginacao)): ?>
                <div class="card-footer clearfix">
                    <?php echo $paginacao; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Modal de Documentos -->
<div class="modal fade" id="modalDocumentos" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Documentos</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                        <tbody id="listaDocumentos">
                            <tr>
                                <td colspan="5" class="text-center">Carregando...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Histórico -->
<div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Histórico</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="timeline" id="timelineHistorico">
                    <div>Carregando...</div>
                </div>
            </div>
        </div>
    </div>
</div>

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
let pagamentoId = null;

// Inicialização de componentes
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        language: 'pt-BR'
    });

    $('.money').mask('#.##0,00', {
        reverse: true
    });
});

// Documentos
function verDocumentos(id) {
    $('#modalDocumentos').modal('show');
    carregarDocumentos(id);
}

function carregarDocumentos(id) {
    $.get(`/custos-extras/financeiro/${id}/documentos`, function(response) {
        let html = '';
        if(response.length === 0) {
            html = '<tr><td colspan="5" class="text-center">Nenhum documento encontrado</td></tr>';
        } else {
            response.forEach(function(doc) {
                html += `
                    <tr>
                        <td>${doc.tipo_documento.nome}</td>
                        <td>${doc.nome_original}</td>
                        <td>${doc.data_upload}</td>
                        <td>${doc.tamanho_formatado}</td>
                        <td>
                            <div class="btn-group">
                                <a href="/documentos/${doc.id}/download" class="btn btn-default btn-sm" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }
        $('#listaDocumentos').html(html);
    });
}

// Histórico
function verHistorico(id) {
    $('#modalHistorico').modal('show');
    carregarHistorico(id);
}

function carregarHistorico(id) {
    $.get(`/custos-extras/financeiro/${id}/historico`, function(response) {
        let html = '';
        if(response.length === 0) {
            html = '<div>Nenhum histórico encontrado</div>';
        } else {
            response.forEach(function(hist) {
                html += `
                    <div class="time-label">
                        <span class="bg-info">${hist.data}</span>
                    </div>
                    <div>
                        <i class="fas ${hist.icone} bg-${hist.cor}"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> ${hist.hora}</span>
                            <h3 class="timeline-header">${hist.titulo}</h3>
                            <div class="timeline-body">
                                ${hist.descricao}
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        $('#timelineHistorico').html(html);
    });
}

// Pagamento
function registrarPagamento(id) {
    pagamentoId = id;
    $('#modalPagamento').modal('show');
}

function confirmarPagamento() {
    if(!$('#formPagamento').valid()) {
        return;
    }

    let formData = new FormData($('#formPagamento')[0]);
    
    $.ajax({
        url: `/custos-extras/financeiro/${pagamentoId}/pagar`,
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
                window.location.reload();
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
