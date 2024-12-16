<?php
$title = 'Registro de Custos Extras';
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
                    <li class="breadcrumb-item active">Custos Extras</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <form action="/custos-extras/registro" method="GET" class="form-inline">
                                    <div class="input-group mr-2">
                                        <input type="text" name="termo" class="form-control" placeholder="Buscar..." value="<?php echo $filtros['termo'] ?? ''; ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-default" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="input-group mr-2">
                                        <select name="status" class="form-control">
                                            <option value="">Status</option>
                                            <?php foreach($status_list as $status): ?>
                                                <option value="<?php echo $status->id; ?>" <?php echo ($filtros['status'] ?? '') == $status->id ? 'selected' : ''; ?>>
                                                    <?php echo $status->nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <input type="date" name="data_inicial" class="form-control" value="<?php echo $filtros['data_inicial'] ?? ''; ?>">
                                        <div class="input-group-append input-group-prepend">
                                            <span class="input-group-text">até</span>
                                        </div>
                                        <input type="date" name="data_final" class="form-control" value="<?php echo $filtros['data_final'] ?? ''; ?>">
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="/custos-extras/registro/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Novo Registro
                                </a>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-file-export"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/custos-extras/registro/relatorio?formato=pdf">
                                            <i class="far fa-file-pdf"></i> PDF
                                        </a>
                                        <a class="dropdown-item" href="/custos-extras/registro/relatorio?formato=excel">
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
                                    <th>Embarcador</th>
                                    <th>Tipo de Custo</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Documentos</th>
                                    <th width="120">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($registros)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Nenhum registro encontrado</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($registros as $registro): ?>
                                        <tr>
                                            <td><?php echo $registro->id; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($registro->data_registro)); ?></td>
                                            <td><?php echo $registro->cliente_nome; ?></td>
                                            <td><?php echo $registro->embarcador_nome; ?></td>
                                            <td><?php echo $registro->tipo_custo_nome; ?></td>
                                            <td>R$ <?php echo number_format($registro->valor, 2, ',', '.'); ?></td>
                                            <td>
                                                <?php 
                                                    $status_class = [
                                                        'pendente' => 'warning',
                                                        'aprovado' => 'success',
                                                        'rejeitado' => 'danger',
                                                        'em_analise' => 'info'
                                                    ];
                                                    $class = $status_class[$registro->status] ?? 'secondary';
                                                ?>
                                                <span class="badge badge-<?php echo $class; ?>">
                                                    <?php echo $registro->status_nome; ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if($registro->total_documentos > 0): ?>
                                                    <span class="badge badge-info" title="<?php echo $registro->total_documentos; ?> documento(s)">
                                                        <?php echo $registro->total_documentos; ?>
                                                    </span>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="/custos-extras/registro/<?php echo $registro->id; ?>/edit" class="btn btn-default btn-sm" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-default btn-sm" title="Documentos" 
                                                            onclick="verDocumentos(<?php echo $registro->id; ?>)">
                                                        <i class="fas fa-file-alt"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-sm" title="Excluir" 
                                                            onclick="excluir(<?php echo $registro->id; ?>)">
                                                        <i class="fas fa-trash"></i>
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
        </div>
    </div>
</section>

<!-- Modal de Documentos -->
<div class="modal fade" id="modalDocumentos" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Documentos do Registro</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="listaDocumentos"></div>
                <form id="formUpload" class="mt-3" enctype="multipart/form-data">
                    <input type="hidden" id="registro_id" name="registro_id">
                    <div class="form-group">
                        <label>Adicionar Documento</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="documento" name="documento">
                                <label class="custom-file-label" for="documento">Escolher arquivo</label>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function excluir(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Deseja realmente excluir este registro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/custos-extras/registro/${id}`,
                method: 'DELETE',
                success: function(response) {
                    Swal.fire(
                        'Excluído!',
                        'Registro excluído com sucesso.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Erro!',
                        'Ocorreu um erro ao excluir o registro.',
                        'error'
                    );
                }
            });
        }
    });
}

function verDocumentos(id) {
    $('#registro_id').val(id);
    $.get(`/custos-extras/registro/${id}/documentos`, function(response) {
        let html = '<div class="table-responsive"><table class="table table-bordered">';
        html += '<thead><tr><th>Nome</th><th>Data</th><th>Tamanho</th><th>Ações</th></tr></thead><tbody>';
        
        if (response.documentos.length === 0) {
            html += '<tr><td colspan="4" class="text-center">Nenhum documento encontrado</td></tr>';
        } else {
            response.documentos.forEach(function(doc) {
                html += `<tr>
                    <td>${doc.nome}</td>
                    <td>${doc.data_upload}</td>
                    <td>${doc.tamanho}</td>
                    <td>
                        <div class="btn-group">
                            <a href="/custos-extras/documentos/${doc.id}/download" class="btn btn-sm btn-default">
                                <i class="fas fa-download"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-default" onclick="excluirDocumento(${doc.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });
        }
        
        html += '</tbody></table></div>';
        $('#listaDocumentos').html(html);
        $('#modalDocumentos').modal('show');
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
                url: `/custos-extras/documentos/${id}`,
                method: 'DELETE',
                success: function(response) {
                    verDocumentos($('#registro_id').val());
                    Swal.fire('Excluído!', 'Documento excluído com sucesso.', 'success');
                },
                error: function(xhr) {
                    Swal.fire('Erro!', 'Ocorreu um erro ao excluir o documento.', 'error');
                }
            });
        }
    });
}

$(document).ready(function() {
    // Upload de documento
    $('#formUpload').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        
        $.ajax({
            url: '/custos-extras/documentos/upload',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                verDocumentos($('#registro_id').val());
                $('#documento').val('');
                $('.custom-file-label').html('Escolher arquivo');
                Swal.fire('Sucesso!', 'Documento enviado com sucesso.', 'success');
            },
            error: function(xhr) {
                Swal.fire('Erro!', 'Ocorreu um erro ao enviar o documento.', 'error');
            }
        });
    });

    // Atualiza o nome do arquivo selecionado
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});
</script>
