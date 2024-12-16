<?php
$title = $registro->id ? 'Editar Registro Inicial' : 'Novo Registro Inicial';
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
                    <li class="breadcrumb-item"><a href="/custos-extras/registro-inicial">Registros Iniciais</a></li>
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
                <form id="formRegistroInicial" action="<?php echo $registro->id ? '/custos-extras/registro-inicial/'.$registro->id : '/custos-extras/registro-inicial'; ?>" method="POST">
                    <?php if($registro->id): ?>
                        <input type="hidden" name="_method" value="PUT">
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informações do Registro</h3>
                        </div>
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
                                        <select class="form-control" id="cliente_id" name="cliente_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($clientes as $cliente): ?>
                                                <option value="<?php echo $cliente->id; ?>" <?php echo $registro->cliente_id == $cliente->id ? 'selected' : ''; ?>>
                                                    <?php echo $cliente->nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipo_custo_id">Tipo de Custo <span class="text-danger">*</span></label>
                                        <select class="form-control" id="tipo_custo_id" name="tipo_custo_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($tiposCustos as $tipoCusto): ?>
                                                <option value="<?php echo $tipoCusto->id; ?>" <?php echo $registro->tipo_custo_id == $tipoCusto->id ? 'selected' : ''; ?>>
                                                    <?php echo $tipoCusto->nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="valor">Valor <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control money" id="valor" name="valor" 
                                               value="<?php echo number_format($registro->valor, 2, ',', '.'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="data_registro">Data de Registro <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="data_registro" name="data_registro" 
                                               value="<?php echo $registro->data_registro ? date('Y-m-d', strtotime($registro->data_registro)) : ''; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status_id">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="status_id" name="status_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($statusList as $status): ?>
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
                                        <label for="descricao">Descrição</label>
                                        <textarea class="form-control" id="descricao" name="descricao" rows="3" maxlength="1000"><?php echo $registro->descricao; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                        <a href="/custos-extras/registro-inicial" class="btn btn-default">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Máscara para campo de valor
    $('.money').mask('#.##0,00', {
        reverse: true
    });

    // Validação do formulário
    $('#formRegistroInicial').validate({
        rules: {
            cliente_id: {
                required: true
            },
            tipo_custo_id: {
                required: true
            },
            valor: {
                required: true,
                number: true
            },
            data_registro: {
                required: true,
                date: true
            },
            status_id: {
                required: true
            },
            descricao: {
                maxlength: 1000
            }
        },
        messages: {
            cliente_id: {
                required: "Por favor, selecione um cliente"
            },
            tipo_custo_id: {
                required: "Por favor, selecione um tipo de custo"
            },
            valor: {
                required: "Por favor, informe o valor",
                number: "Por favor, informe um valor válido"
            },
            data_registro: {
                required: "Por favor, informe a data de registro",
                date: "Por favor, informe uma data válida"
            },
            status_id: {
                required: "Por favor, selecione um status"
            },
            descricao: {
                maxlength: "A descrição deve ter no máximo 1000 caracteres"
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
