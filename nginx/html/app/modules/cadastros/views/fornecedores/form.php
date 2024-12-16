<?php
$title = isset($fornecedor) ? 'Editar Fornecedor' : 'Novo Fornecedor';
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $title ?></h3>
    </div>
    <div class="card-body">
        <form id="fornecedorForm" method="POST" action="<?= url('fornecedores/save') ?>">
            <input type="hidden" name="id" value="<?= isset($fornecedor) ? $fornecedor->id : '' ?>">
            
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $field => $fieldErrors): ?>
                            <?php foreach ($fieldErrors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="razao_social">Razão Social *</label>
                        <input type="text" class="form-control" id="razao_social" name="razao_social" 
                               value="<?= isset($fornecedor) ? $fornecedor->razao_social : '' ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nome_fantasia">Nome Fantasia</label>
                        <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia"
                               value="<?= isset($fornecedor) ? $fornecedor->nome_fantasia : '' ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cnpj">CNPJ *</label>
                        <input type="text" class="form-control" id="cnpj" name="cnpj"
                               value="<?= isset($fornecedor) ? $fornecedor->cnpj : '' ?>" required>
                        <div class="invalid-feedback"></div>
                        <small class="text-muted cnpj-status"></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inscricao_estadual">Inscrição Estadual</label>
                        <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual"
                               value="<?= isset($fornecedor) ? $fornecedor->inscricao_estadual : '' ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inscricao_municipal">Inscrição Municipal</label>
                        <input type="text" class="form-control" id="inscricao_municipal" name="inscricao_municipal"
                               value="<?= isset($fornecedor) ? $fornecedor->inscricao_municipal : '' ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cep">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep"
                               value="<?= isset($fornecedor) ? $fornecedor->cep : '' ?>">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="logradouro">Logradouro</label>
                        <input type="text" class="form-control" id="logradouro" name="logradouro"
                               value="<?= isset($fornecedor) ? $fornecedor->logradouro : '' ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="numero">Número</label>
                        <input type="text" class="form-control" id="numero" name="numero"
                               value="<?= isset($fornecedor) ? $fornecedor->numero : '' ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="complemento">Complemento</label>
                        <input type="text" class="form-control" id="complemento" name="complemento"
                               value="<?= isset($fornecedor) ? $fornecedor->complemento : '' ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type="text" class="form-control" id="bairro" name="bairro"
                               value="<?= isset($fornecedor) ? $fornecedor->bairro : '' ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" class="form-control" id="cidade" name="cidade"
                               value="<?= isset($fornecedor) ? $fornecedor->cidade : '' ?>">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label for="estado">UF</label>
                        <input type="text" class="form-control" id="estado" name="estado"
                               value="<?= isset($fornecedor) ? $fornecedor->estado : '' ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone"
                               value="<?= isset($fornecedor) ? $fornecedor->telefone : '' ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?= isset($fornecedor) ? $fornecedor->email : '' ?>" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="contato">Contato</label>
                        <input type="text" class="form-control" id="contato" name="contato"
                               value="<?= isset($fornecedor) ? $fornecedor->contato : '' ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="observacoes">Observações</label>
                <textarea class="form-control" id="observacoes" name="observacoes" rows="3"><?= isset($fornecedor) ? $fornecedor->observacoes : '' ?></textarea>
            </div>

            <div class="form-group">
                <label for="situacao">Situação</label>
                <select class="form-control" id="situacao" name="situacao">
                    <option value="ativo" <?= (isset($fornecedor) && $fornecedor->situacao == 'ativo') ? 'selected' : '' ?>>Ativo</option>
                    <option value="inativo" <?= (isset($fornecedor) && $fornecedor->situacao == 'inativo') ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= url('fornecedores') ?>" class="btn btn-default">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Máscara para campos
    $('#cnpj').mask('00.000.000/0000-00');
    $('#cep').mask('00000-000');
    $('#telefone').mask('(00) 00000-0000');

    // Validação de CNPJ em tempo real
    $('#cnpj').on('blur', function() {
        var cnpj = $(this).val();
        if (cnpj) {
            $.get('<?= url('fornecedores/validate-cnpj') ?>', { cnpj: cnpj }, function(response) {
                if (response.success) {
                    $('#cnpj').removeClass('is-invalid').addClass('is-valid');
                    $('.cnpj-status').html('<span class="text-success">CNPJ válido - Situação: ' + response.data.situacao + '</span>');
                } else {
                    $('#cnpj').removeClass('is-valid').addClass('is-invalid');
                    $('.cnpj-status').html('<span class="text-danger">' + response.message + '</span>');
                }
            });
        }
    });

    // Preenchimento automático por CEP
    $('#cep').on('blur', function() {
        var cep = $(this).val();
        if (cep) {
            $.get('<?= url('fornecedores/consulta-cep') ?>', { cep: cep }, function(response) {
                if (response.success) {
                    $('#logradouro').val(response.data.logradouro);
                    $('#bairro').val(response.data.bairro);
                    $('#cidade').val(response.data.cidade);
                    $('#estado').val(response.data.estado);
                    $('#cep').removeClass('is-invalid').addClass('is-valid');
                } else {
                    $('#cep').removeClass('is-valid').addClass('is-invalid');
                    $('.invalid-feedback').html(response.message);
                }
            });
        }
    });

    // Validação do formulário
    $('#fornecedorForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?= url('fornecedores') ?>';
                } else {
                    // Mostra erros de validação
                    Object.keys(response.errors).forEach(function(field) {
                        $('#' + field).addClass('is-invalid');
                        $('#' + field).siblings('.invalid-feedback').html(response.errors[field].join('<br>'));
                    });
                }
            }
        });
    });
});
</script>
