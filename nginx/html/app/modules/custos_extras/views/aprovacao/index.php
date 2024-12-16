<?php
$title = 'Aprovação de Custos Extras';
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
                    <li class="breadcrumb-item active">Aprovação de Custos Extras</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
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
                <form action="/custos-extras/aprovacao" method="GET">
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
                            <a href="/custos-extras/aprovacao" class="btn btn-default">
                                <i class="fas fa-times"></i> Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Aprovações -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title">Aprovações Pendentes</h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-file-export"></i> Exportar
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/custos-extras/aprovacao/relatorio?formato=pdf<?php echo isset($_SERVER['QUERY_STRING']) ? '&'.$_SERVER['QUERY_STRING'] : ''; ?>">
                                    <i class="far fa-file-pdf"></i> PDF
                                </a>
                                <a class="dropdown-item" href="/custos-extras/aprovacao/relatorio?formato=excel<?php echo isset($_SERVER['QUERY_STRING']) ? '&'.$_SERVER['QUERY_STRING'] : ''; ?>">
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
                            <th>Status</th>
                            <th>Documentos</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($aprovacoes)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Nenhum registro encontrado</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($aprovacoes as $aprovacao): ?>
                                <tr>
                                    <td><?php echo $aprovacao->codigo; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($aprovacao->data)); ?></td>
                                    <td><?php echo $aprovacao->cliente->nome; ?></td>
                                    <td><?php echo $aprovacao->tipo_custo->nome; ?></td>
                                    <td>R$ <?php echo number_format($aprovacao->valor, 2, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge" style="background-color: <?php echo $aprovacao->status->cor; ?>">
                                            <?php echo $aprovacao->status->nome; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($aprovacao->documentos_count > 0): ?>
                                            <span class="badge badge-info">
                                                <i class="fas fa-file-alt"></i> <?php echo $aprovacao->documentos_count; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-file-alt"></i> 0
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-success btn-sm" title="Aprovar" 
                                                    onclick="aprovar(<?php echo $aprovacao->id; ?>)">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" title="Rejeitar" 
                                                    onclick="rejeitar(<?php echo $aprovacao->id; ?>)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <button type="button" class="btn btn-default btn-sm" title="Documentos" 
                                                    onclick="verDocumentos(<?php echo $aprovacao->id; ?>)">
                                                <i class="fas fa-file-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-default btn-sm" title="Histórico" 
                                                    onclick="verHistorico(<?php echo $aprovacao->id; ?>)">
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
let aprovacaoId = null;

// Inicialização de componentes
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        language: 'pt-BR'
    });
});

// Documentos
function verDocumentos(id) {
    $('#modalDocumentos').modal('show');
    carregarDocumentos(id);
}

function carregarDocumentos(id) {
    $.get(`/custos-extras/aprovacao/${id}/documentos`, function(response) {
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
    $.get(`/custos-extras/aprovacao/${id}/historico`, function(response) {
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

// Aprovação
function aprovar(id) {
    aprovacaoId = id;
    $('#modalAprovacao').modal('show');
}

function confirmarAprovacao() {
    if(!$('#formAprovacao').valid()) {
        return;
    }

    let observacoes = $('#formAprovacao textarea[name="observacoes"]').val();
    
    $.ajax({
        url: `/custos-extras/aprovacao/${aprovacaoId}/aprovar`,
        method: 'POST',
        data: { observacoes },
        success: function(response) {
            $('#modalAprovacao').modal('hide');
            Swal.fire(
                'Aprovado!',
                'Custo extra aprovado com sucesso.',
                'success'
            ).then(() => {
                window.location.reload();
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
    aprovacaoId = id;
    $('#modalRejeicao').modal('show');
}

function confirmarRejeicao() {
    if(!$('#formRejeicao').valid()) {
        return;
    }

    let observacoes = $('#formRejeicao textarea[name="observacoes"]').val();
    
    $.ajax({
        url: `/custos-extras/aprovacao/${aprovacaoId}/rejeitar`,
        method: 'POST',
        data: { observacoes },
        success: function(response) {
            $('#modalRejeicao').modal('hide');
            Swal.fire(
                'Rejeitado!',
                'Custo extra rejeitado com sucesso.',
                'success'
            ).then(() => {
                window.location.reload();
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
