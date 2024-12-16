<?php
$title = $cobranca->id ? 'Editar Cobrança' : 'Nova Cobrança';
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
                    <li class="breadcrumb-item"><a href="/custos-extras/cobranca">Cobrança</a></li>
                    <li class="breadcrumb-item active"><?php echo $cobranca->id ? 'Editar' : 'Novo'; ?></li>
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
                    <form id="formCobranca" action="<?php echo $cobranca->id ? '/custos-extras/cobranca/'.$cobranca->id : '/custos-extras/cobranca'; ?>" method="POST">
                        <?php if($cobranca->id): ?>
                            <input type="hidden" name="_method" value="PUT">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <?php if(isset($errors) && !empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cliente_id">Cliente <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="cliente_id" name="cliente_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($clientes as $cliente): ?>
                                                <option value="<?php echo $cliente->id; ?>" 
                                                        <?php echo $cobranca->cliente_id == $cliente->id ? 'selected' : ''; ?>>
                                                    <?php echo $cliente->nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipo_custo_id">Tipo de Custo <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="tipo_custo_id" name="tipo_custo_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($tiposCusto as $tipo): ?>
                                                <option value="<?php echo $tipo->id; ?>" 
                                                        <?php echo $cobranca->tipo_custo_id == $tipo->id ? 'selected' : ''; ?>>
                                                    <?php echo $tipo->nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data">Data <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="data" name="data" 
                                               value="<?php echo $cobranca->data; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="valor">Valor <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control money" id="valor" name="valor" 
                                               value="<?php echo $cobranca->valor; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="vencimento">Vencimento</label>
                                        <input type="date" class="form-control" id="vencimento" name="vencimento" 
                                               value="<?php echo $cobranca->vencimento; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status_id">Status <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="status_id" name="status_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($todosStatus as $status): ?>
                                                <option value="<?php echo $status->id; ?>" 
                                                        <?php echo $cobranca->status_id == $status->id ? 'selected' : ''; ?>>
                                                    <?php echo $status->nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="descricao">Descrição</label>
                                        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo $cobranca->descricao; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="observacoes">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"><?php echo $cobranca->observacoes; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <?php if($cobranca->id): ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Documentos Anexados</label>
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
                                                                            <button type="button" class="btn btn-default btn-sm" title="Excluir" 
                                                                                    onclick="excluirDocumento(<?php echo $doc->id; ?>)">
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
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Anexar Novo Documento</label>
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
                                                        <button type="submit" class="btn btn-default">
                                                            <i class="fas fa-upload"></i> Upload
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="/custos-extras/cobranca" class="btn btn-default">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Inicializa Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        language: 'pt-BR'
    });

    // Inicializa máscara de valor
    $('.money').mask('#.##0,00', {
        reverse: true
    });

    // Inicializa input de arquivo customizado
    bsCustomFileInput.init();

    // Validação do formulário
    $('#formCobranca').validate({
        rules: {
            cliente_id: {
                required: true
            },
            tipo_custo_id: {
                required: true
            },
            data: {
                required: true
            },
            valor: {
                required: true
            },
            status_id: {
                required: true
            },
            descricao: {
                maxlength: 1000
            },
            observacoes: {
                maxlength: 1000
            }
        },
        messages: {
            cliente_id: {
                required: "Por favor, selecione o cliente"
            },
            tipo_custo_id: {
                required: "Por favor, selecione o tipo de custo"
            },
            data: {
                required: "Por favor, informe a data"
            },
            valor: {
                required: "Por favor, informe o valor"
            },
            status_id: {
                required: "Por favor, selecione o status"
            },
            descricao: {
                maxlength: "A descrição deve ter no máximo 1000 caracteres"
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
                    window.location.reload();
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
});

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
                        window.location.reload();
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
</script>
