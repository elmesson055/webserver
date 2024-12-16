<?php
$title = $tipo->id ? 'Editar Tipo de Custo' : 'Novo Tipo de Custo';
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
                    <li class="breadcrumb-item"><a href="/cadastros/tipos-custos">Tipos de Custos</a></li>
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
                    <form id="formTipoCusto" action="<?php echo $tipo->id ? '/cadastros/tipos-custos/'.$tipo->id : '/cadastros/tipos-custos'; ?>" method="POST">
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
                                        <label for="codigo">Código <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="codigo" name="codigo" 
                                               value="<?php echo $tipo->codigo; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="categoria">Categoria <span class="text-danger">*</span></label>
                                        <select class="form-control" id="categoria" name="categoria" required>
                                            <option value="">Selecione...</option>
                                            <option value="OPERACIONAL" <?php echo $tipo->categoria == 'OPERACIONAL' ? 'selected' : ''; ?>>Operacional</option>
                                            <option value="ADMINISTRATIVO" <?php echo $tipo->categoria == 'ADMINISTRATIVO' ? 'selected' : ''; ?>>Administrativo</option>
                                            <option value="COMERCIAL" <?php echo $tipo->categoria == 'COMERCIAL' ? 'selected' : ''; ?>>Comercial</option>
                                            <option value="FINANCEIRO" <?php echo $tipo->categoria == 'FINANCEIRO' ? 'selected' : ''; ?>>Financeiro</option>
                                            <option value="OUTROS" <?php echo $tipo->categoria == 'OUTROS' ? 'selected' : ''; ?>>Outros</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="valor_base">Valor Base (R$)</label>
                                        <input type="text" class="form-control money" id="valor_base" name="valor_base" 
                                               value="<?php echo number_format($tipo->valor_base, 2, ',', '.'); ?>">
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
                                        <label for="requer_aprovacao">Requer Aprovação</label>
                                        <select class="form-control" id="requer_aprovacao" name="requer_aprovacao">
                                            <option value="1" <?php echo $tipo->requer_aprovacao == 1 ? 'selected' : ''; ?>>Sim</option>
                                            <option value="0" <?php echo $tipo->requer_aprovacao == 0 ? 'selected' : ''; ?>>Não</option>
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

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="limite_minimo">Limite Mínimo (R$)</label>
                                        <input type="text" class="form-control money" id="limite_minimo" name="limite_minimo" 
                                               value="<?php echo number_format($tipo->limite_minimo, 2, ',', '.'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="limite_maximo">Limite Máximo (R$)</label>
                                        <input type="text" class="form-control money" id="limite_maximo" name="limite_maximo" 
                                               value="<?php echo number_format($tipo->limite_maximo, 2, ',', '.'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="/cadastros/tipos-custos" class="btn btn-default">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Inicializa máscara para campos monetários
    $('.money').mask('#.##0,00', {
        reverse: true
    });

    $('#formTipoCusto').validate({
        rules: {
            nome: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            codigo: {
                required: true,
                maxlength: 20
            },
            categoria: {
                required: true
            },
            valor_base: {
                money: true
            },
            descricao: {
                maxlength: 500
            },
            limite_minimo: {
                money: true
            },
            limite_maximo: {
                money: true
            }
        },
        messages: {
            nome: {
                required: "Por favor, informe o nome",
                minlength: "O nome deve ter no mínimo 3 caracteres",
                maxlength: "O nome deve ter no máximo 100 caracteres"
            },
            codigo: {
                required: "Por favor, informe o código",
                maxlength: "O código deve ter no máximo 20 caracteres"
            },
            categoria: {
                required: "Por favor, selecione uma categoria"
            },
            descricao: {
                maxlength: "A descrição deve ter no máximo 500 caracteres"
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

    // Método de validação customizado para valores monetários
    $.validator.addMethod("money", function(value, element) {
        if (value === "") return true;
        return /^\d{1,3}(\.\d{3})*,\d{2}$/.test(value);
    }, "Por favor, informe um valor monetário válido");
});
</script>
