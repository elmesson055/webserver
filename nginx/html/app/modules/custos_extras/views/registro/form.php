<?php
$title = $registro->id ? 'Editar Registro de Custo Extra' : 'Novo Registro de Custo Extra';
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
                    <li class="breadcrumb-item"><a href="/custos-extras/registro">Custos Extras</a></li>
                    <li class="breadcrumb-item active"><?php echo $registro->id ? 'Editar' : 'Novo'; ?></li>
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
                    <form id="formRegistro" action="<?php echo $registro->id ? '/custos-extras/registro/'.$registro->id : '/custos-extras/registro'; ?>" method="POST">
                        <?php if($registro->id): ?>
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="data_registro">Data do Registro <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="data_registro" name="data_registro" 
                                               value="<?php echo $registro->data_registro; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="numero_pedido">Número do Pedido</label>
                                        <input type="text" class="form-control" id="numero_pedido" name="numero_pedido" 
                                               value="<?php echo $registro->numero_pedido; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="numero_nota">Número da Nota</label>
                                        <input type="text" class="form-control" id="numero_nota" name="numero_nota" 
                                               value="<?php echo $registro->numero_nota; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="valor">Valor <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">R$</span>
                                            </div>
                                            <input type="text" class="form-control" id="valor" name="valor" 
                                                   value="<?php echo number_format($registro->valor, 2, ',', '.'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cliente_id">Cliente <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="cliente_id" name="cliente_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($clientes as $cliente): ?>
                                                <option value="<?php echo $cliente->id; ?>" <?php echo $registro->cliente_id == $cliente->id ? 'selected' : ''; ?>>
                                                    <?php echo $cliente->razao_social; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="embarcador_id">Embarcador <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="embarcador_id" name="embarcador_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($embarcadores as $embarcador): ?>
                                                <option value="<?php echo $embarcador->id; ?>" <?php echo $registro->embarcador_id == $embarcador->id ? 'selected' : ''; ?>>
                                                    <?php echo $embarcador->razao_social; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipo_custo_id">Tipo de Custo <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="tipo_custo_id" name="tipo_custo_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($tipos_custo as $tipo): ?>
                                                <option value="<?php echo $tipo->id; ?>" <?php echo $registro->tipo_custo_id == $tipo->id ? 'selected' : ''; ?>>
                                                    <?php echo $tipo->nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="motorista_id">Motorista</label>
                                        <select class="form-control select2" id="motorista_id" name="motorista_id">
                                            <option value="">Selecione...</option>
                                            <?php foreach($motoristas as $motorista): ?>
                                                <option value="<?php echo $motorista->id; ?>" <?php echo $registro->motorista_id == $motorista->id ? 'selected' : ''; ?>>
                                                    <?php echo $motorista->nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status_id">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="status_id" name="status_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($status_list as $status): ?>
                                                <option value="<?php echo $status->id; ?>" <?php echo $registro->status_id == $status->id ? 'selected' : ''; ?>>
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
                                        <label for="observacoes">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"><?php echo $registro->observacoes; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <?php if($registro->id): ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">Documentos Anexados</h3>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Nome</th>
                                                                <th>Data</th>
                                                                <th>Tamanho</th>
                                                                <th width="100">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(empty($documentos)): ?>
                                                                <tr>
                                                                    <td colspan="4" class="text-center">Nenhum documento anexado</td>
                                                                </tr>
                                                            <?php else: ?>
                                                                <?php foreach($documentos as $doc): ?>
                                                                    <tr>
                                                                        <td><?php echo $doc->nome; ?></td>
                                                                        <td><?php echo date('d/m/Y H:i', strtotime($doc->data_upload)); ?></td>
                                                                        <td><?php echo $doc->tamanho; ?></td>
                                                                        <td>
                                                                            <div class="btn-group">
                                                                                <a href="/custos-extras/documentos/<?php echo $doc->id; ?>/download" 
                                                                                   class="btn btn-default btn-sm" title="Download">
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
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="/custos-extras/registro" class="btn btn-default">Cancelar</a>
                            <?php if($registro->id): ?>
                                <button type="button" class="btn btn-success float-right" onclick="$('#modalUpload').modal('show')">
                                    <i class="fas fa-file-upload"></i> Anexar Documento
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if($registro->id): ?>
<!-- Modal de Upload -->
<div class="modal fade" id="modalUpload" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Anexar Documento</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUpload" enctype="multipart/form-data">
                    <input type="hidden" name="registro_id" value="<?php echo $registro->id; ?>">
                    <div class="form-group">
                        <label>Selecione o arquivo</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="documento" name="documento" required>
                            <label class="custom-file-label" for="documento">Escolher arquivo</label>
                        </div>
                        <small class="form-text text-muted">
                            Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="uploadDocumento()">Enviar</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
$(document).ready(function() {
    // Inicializa Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Máscara para valor
    $('#valor').mask('#.##0,00', {reverse: true});

    // Validação do formulário
    $('#formRegistro').validate({
        rules: {
            data_registro: {
                required: true
            },
            numero_pedido: {
                maxlength: 20
            },
            numero_nota: {
                maxlength: 20
            },
            valor: {
                required: true
            },
            cliente_id: {
                required: true
            },
            embarcador_id: {
                required: true
            },
            tipo_custo_id: {
                required: true
            },
            status_id: {
                required: true
            },
            observacoes: {
                maxlength: 500
            }
        },
        messages: {
            data_registro: {
                required: "Por favor, informe a data do registro"
            },
            numero_pedido: {
                maxlength: "O número do pedido deve ter no máximo 20 caracteres"
            },
            numero_nota: {
                maxlength: "O número da nota deve ter no máximo 20 caracteres"
            },
            valor: {
                required: "Por favor, informe o valor"
            },
            cliente_id: {
                required: "Por favor, selecione o cliente"
            },
            embarcador_id: {
                required: "Por favor, selecione o embarcador"
            },
            tipo_custo_id: {
                required: "Por favor, selecione o tipo de custo"
            },
            status_id: {
                required: "Por favor, selecione o status"
            },
            observacoes: {
                maxlength: "As observações devem ter no máximo 500 caracteres"
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

    // Atualiza o nome do arquivo selecionado
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});

function uploadDocumento() {
    let formData = new FormData($('#formUpload')[0]);
    
    $.ajax({
        url: '/custos-extras/documentos/upload',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#modalUpload').modal('hide');
            window.location.reload();
        },
        error: function(xhr) {
            Swal.fire('Erro!', 'Ocorreu um erro ao enviar o documento.', 'error');
        }
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
                    window.location.reload();
                },
                error: function(xhr) {
                    Swal.fire('Erro!', 'Ocorreu um erro ao excluir o documento.', 'error');
                }
            });
        }
    });
}
</script>
