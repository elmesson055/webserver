<?php
$title = 'Cobrança de Custos Extras';
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
                    <li class="breadcrumb-item active">Cobrança de Custos Extras</li>
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
                <form action="/custos-extras/cobranca" method="GET">
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
                            <a href="/custos-extras/cobranca" class="btn btn-default">
                                <i class="fas fa-times"></i> Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Cobranças -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title">Cobranças</h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-file-export"></i> Exportar
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="/custos-extras/cobranca/relatorio?formato=pdf<?php echo isset($_SERVER['QUERY_STRING']) ? '&'.$_SERVER['QUERY_STRING'] : ''; ?>">
                                    <i class="far fa-file-pdf"></i> PDF
                                </a>
                                <a class="dropdown-item" href="/custos-extras/cobranca/relatorio?formato=excel<?php echo isset($_SERVER['QUERY_STRING']) ? '&'.$_SERVER['QUERY_STRING'] : ''; ?>">
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
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($cobrancas)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Nenhum registro encontrado</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($cobrancas as $cobranca): ?>
                                <tr>
                                    <td><?php echo $cobranca->codigo; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($cobranca->data)); ?></td>
                                    <td><?php echo $cobranca->cliente->nome; ?></td>
                                    <td><?php echo $cobranca->tipo_custo->nome; ?></td>
                                    <td>R$ <?php echo number_format($cobranca->valor, 2, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge" style="background-color: <?php echo $cobranca->status->cor; ?>">
                                            <?php echo $cobranca->status->nome; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($cobranca->documentos_count > 0): ?>
                                            <span class="badge badge-info">
                                                <i class="fas fa-file-alt"></i> <?php echo $cobranca->documentos_count; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-file-alt"></i> 0
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/custos-extras/cobranca/<?php echo $cobranca->id; ?>/edit" class="btn btn-default btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-default btn-sm" title="Documentos" 
                                                    onclick="verDocumentos(<?php echo $cobranca->id; ?>)">
                                                <i class="fas fa-file-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-default btn-sm" title="Histórico" 
                                                    onclick="verHistorico(<?php echo $cobranca->id; ?>)">
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
                <hr>
                <form id="formUpload" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de Documento</label>
                                <select class="form-control" name="tipo_documento_id" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach($tiposDocumento as $tipo): ?>
                                        <option value="<?php echo $tipo->id; ?>"><?php echo $tipo->nome; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Arquivo</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="arquivo" required>
                                    <label class="custom-file-label">Escolher arquivo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Upload
                            </button>
                        </div>
                    </div>
                </form>
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

<script>
// Inicialização de componentes
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        language: 'pt-BR'
    });

    bsCustomFileInput.init();
});

// Documentos
function verDocumentos(id) {
    $('#modalDocumentos').modal('show');
    carregarDocumentos(id);
}

function carregarDocumentos(id) {
    $.get(`/custos-extras/cobranca/${id}/documentos`, function(response) {
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
                                <button type="button" class="btn btn-default btn-sm" title="Excluir" 
                                        onclick="excluirDocumento(${doc.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }
        $('#listaDocumentos').html(html);
    });
}

function excluirDocumento(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Deseja realmente excluir este documento?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/documentos/${id}`,
                method: 'DELETE',
                success: function(response) {
                    Swal.fire(
                        'Excluído!',
                        'Documento excluído com sucesso.',
                        'success'
                    ).then(() => {
                        carregarDocumentos(id);
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o documento.',
                        'error'
                    );
                }
            });
        }
    });
}

// Upload de documentos
$('#formUpload').submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    
    $.ajax({
        url: `/custos-extras/cobranca/${cobrancaId}/documentos`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.fire(
                'Sucesso!',
                'Documento enviado com sucesso.',
                'success'
            ).then(() => {
                $('#formUpload')[0].reset();
                carregarDocumentos(cobrancaId);
            });
        },
        error: function(xhr) {
            Swal.fire(
                'Erro!',
                'Ocorreu um erro ao enviar o documento.',
                'error'
            );
        }
    });
});

// Histórico
function verHistorico(id) {
    $('#modalHistorico').modal('show');
    carregarHistorico(id);
}

function carregarHistorico(id) {
    $.get(`/custos-extras/cobranca/${id}/historico`, function(response) {
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
</script>
