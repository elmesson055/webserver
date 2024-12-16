<?php
$title = $status->id ? 'Editar Status' : 'Novo Status';
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
                    <li class="breadcrumb-item"><a href="/cadastros/status-gerais">Status Gerais</a></li>
                    <li class="breadcrumb-item active"><?php echo $status->id ? 'Editar' : 'Novo'; ?></li>
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
                    <form id="formStatus" action="<?php echo $status->id ? '/cadastros/status-gerais/'.$status->id : '/cadastros/status-gerais'; ?>" method="POST">
                        <?php if($status->id): ?>
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
                                               value="<?php echo $status->nome; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="codigo">Código <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="codigo" name="codigo" 
                                               value="<?php echo $status->codigo; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipo">Tipo <span class="text-danger">*</span></label>
                                        <select class="form-control" id="tipo" name="tipo" required>
                                            <option value="">Selecione...</option>
                                            <option value="INICIAL" <?php echo $status->tipo == 'INICIAL' ? 'selected' : ''; ?>>Inicial</option>
                                            <option value="PROCESSAMENTO" <?php echo $status->tipo == 'PROCESSAMENTO' ? 'selected' : ''; ?>>Processamento</option>
                                            <option value="APROVACAO" <?php echo $status->tipo == 'APROVACAO' ? 'selected' : ''; ?>>Aprovação</option>
                                            <option value="FINANCEIRO" <?php echo $status->tipo == 'FINANCEIRO' ? 'selected' : ''; ?>>Financeiro</option>
                                            <option value="CONCLUIDO" <?php echo $status->tipo == 'CONCLUIDO' ? 'selected' : ''; ?>>Concluído</option>
                                            <option value="CANCELADO" <?php echo $status->tipo == 'CANCELADO' ? 'selected' : ''; ?>>Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cor">Cor <span class="text-danger">*</span></label>
                                        <input type="color" class="form-control" id="cor" name="cor" 
                                               value="<?php echo $status->cor; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="descricao">Descrição</label>
                                        <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo $status->descricao; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ordem">Ordem <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="ordem" name="ordem" 
                                               value="<?php echo $status->ordem; ?>" required min="1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="1" <?php echo $status->status == 1 ? 'selected' : ''; ?>>Ativo</option>
                                            <option value="0" <?php echo $status->status == 0 ? 'selected' : ''; ?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="permite_edicao">Permite Edição</label>
                                        <select class="form-control" id="permite_edicao" name="permite_edicao">
                                            <option value="1" <?php echo $status->permite_edicao == 1 ? 'selected' : ''; ?>>Sim</option>
                                            <option value="0" <?php echo $status->permite_edicao == 0 ? 'selected' : ''; ?>>Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="requer_observacao">Requer Observação</label>
                                        <select class="form-control" id="requer_observacao" name="requer_observacao">
                                            <option value="1" <?php echo $status->requer_observacao == 1 ? 'selected' : ''; ?>>Sim</option>
                                            <option value="0" <?php echo $status->requer_observacao == 0 ? 'selected' : ''; ?>>Não</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="/cadastros/status-gerais" class="btn btn-default">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#formStatus').validate({
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
            tipo: {
                required: true
            },
            cor: {
                required: true
            },
            ordem: {
                required: true,
                number: true,
                min: 1
            },
            descricao: {
                maxlength: 500
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
            tipo: {
                required: "Por favor, selecione um tipo"
            },
            cor: {
                required: "Por favor, selecione uma cor"
            },
            ordem: {
                required: "Por favor, informe a ordem",
                number: "Por favor, informe um número válido",
                min: "A ordem deve ser maior que 0"
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
});
</script>
