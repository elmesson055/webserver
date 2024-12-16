<?php
$title = $tipo->id ? 'Editar Tipo de Documento' : 'Novo Tipo de Documento';
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
                    <li class="breadcrumb-item"><a href="/cadastros/tipos-documentos">Tipos de Documentos</a></li>
                    <li class="breadcrumb-item active"><?php echo $tipo->id ? 'Editar' : 'Novo'; ?></li>
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
                    <form id="formTipoDocumento" action="<?php echo $tipo->id ? '/cadastros/tipos-documentos/'.$tipo->id : '/cadastros/tipos-documentos'; ?>" method="POST">
                        <?php if($tipo->id): ?>
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
                                        <label for="nome">Nome <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nome" name="nome" 
                                               value="<?php echo $tipo->nome; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="codigo">Código</label>
                                        <input type="text" class="form-control" id="codigo" name="codigo" 
                                               value="<?php echo $tipo->codigo; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="descricao">Descrição</label>
                                        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo $tipo->descricao; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="extensoes_permitidas">Extensões Permitidas <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="extensoes_permitidas" name="extensoes_permitidas" 
                                               value="<?php echo $tipo->extensoes_permitidas; ?>" required
                                               placeholder="Ex: pdf,doc,docx,jpg,png">
                                        <small class="form-text text-muted">
                                            Separe as extensões por vírgula, sem espaços
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tamanho_maximo_mb">Tamanho Máximo (MB) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="tamanho_maximo_mb" name="tamanho_maximo_mb" 
                                               value="<?php echo $tipo->tamanho_maximo_mb; ?>" required min="1" max="100">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="obrigatorio">Obrigatório</label>
                                        <select class="form-control" id="obrigatorio" name="obrigatorio">
                                            <option value="1" <?php echo $tipo->obrigatorio == 1 ? 'selected' : ''; ?>>Sim</option>
                                            <option value="0" <?php echo $tipo->obrigatorio == 0 ? 'selected' : ''; ?>>Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="1" <?php echo $tipo->status == 1 ? 'selected' : ''; ?>>Ativo</option>
                                            <option value="0" <?php echo $tipo->status == 0 ? 'selected' : ''; ?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="/cadastros/tipos-documentos" class="btn btn-default">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#formTipoDocumento').validate({
        rules: {
            nome: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            codigo: {
                maxlength: 20
            },
            descricao: {
                maxlength: 500
            },
            extensoes_permitidas: {
                required: true,
                pattern: /^[a-z0-9]+(,[a-z0-9]+)*$/
            },
            tamanho_maximo_mb: {
                required: true,
                number: true,
                min: 1,
                max: 100
            }
        },
        messages: {
            nome: {
                required: "Por favor, informe o nome",
                minlength: "O nome deve ter no mínimo 3 caracteres",
                maxlength: "O nome deve ter no máximo 100 caracteres"
            },
            codigo: {
                maxlength: "O código deve ter no máximo 20 caracteres"
            },
            descricao: {
                maxlength: "A descrição deve ter no máximo 500 caracteres"
            },
            extensoes_permitidas: {
                required: "Por favor, informe as extensões permitidas",
                pattern: "Formato inválido. Use apenas letras minúsculas e números, separados por vírgula"
            },
            tamanho_maximo_mb: {
                required: "Por favor, informe o tamanho máximo",
                number: "Por favor, informe um número válido",
                min: "O tamanho mínimo é 1 MB",
                max: "O tamanho máximo é 100 MB"
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
});
</script>
