<?php
$title = $cliente->id ? 'Editar Cliente' : 'Novo Cliente';
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
                    <li class="breadcrumb-item"><a href="/cadastros/clientes">Clientes</a></li>
                    <li class="breadcrumb-item active"><?php echo $cliente->id ? 'Editar' : 'Novo'; ?></li>
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
                    <form id="formCliente" action="<?php echo $cliente->id ? '/cadastros/clientes/'.$cliente->id : '/cadastros/clientes'; ?>" method="POST">
                        <?php if($cliente->id): ?>
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
                                        <label for="razao_social">Razão Social <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="razao_social" name="razao_social" 
                                               value="<?php echo $cliente->razao_social; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nome_fantasia">Nome Fantasia</label>
                                        <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" 
                                               value="<?php echo $cliente->nome_fantasia; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cnpj">CNPJ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cnpj" name="cnpj" 
                                               value="<?php echo $cliente->cnpj; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="ie">Inscrição Estadual</label>
                                        <input type="text" class="form-control" id="ie" name="ie" 
                                               value="<?php echo $cliente->ie; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="segmento">Segmento <span class="text-danger">*</span></label>
                                        <select class="form-control" id="segmento" name="segmento" required>
                                            <option value="">Selecione...</option>
                                            <option value="industria" <?php echo $cliente->segmento == 'industria' ? 'selected' : ''; ?>>Indústria</option>
                                            <option value="comercio" <?php echo $cliente->segmento == 'comercio' ? 'selected' : ''; ?>>Comércio</option>
                                            <option value="servicos" <?php echo $cliente->segmento == 'servicos' ? 'selected' : ''; ?>>Serviços</option>
                                            <option value="outros" <?php echo $cliente->segmento == 'outros' ? 'selected' : ''; ?>>Outros</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cep">CEP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cep" name="cep" 
                                               value="<?php echo $cliente->cep; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="endereco">Endereço <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="endereco" name="endereco" 
                                               value="<?php echo $cliente->endereco; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="numero">Número <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="numero" name="numero" 
                                               value="<?php echo $cliente->numero; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="complemento">Complemento</label>
                                        <input type="text" class="form-control" id="complemento" name="complemento" 
                                               value="<?php echo $cliente->complemento; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bairro">Bairro <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" 
                                               value="<?php echo $cliente->bairro; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cidade">Cidade <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" 
                                               value="<?php echo $cliente->cidade; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="estado">Estado <span class="text-danger">*</span></label>
                                        <select class="form-control" id="estado" name="estado" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($estados as $sigla => $nome): ?>
                                                <option value="<?php echo $sigla; ?>" <?php echo $cliente->estado == $sigla ? 'selected' : ''; ?>>
                                                    <?php echo $nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="telefone">Telefone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="telefone" name="telefone" 
                                               value="<?php echo $cliente->telefone; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">E-mail <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo $cliente->email; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="observacoes">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"><?php echo $cliente->observacoes; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="1" <?php echo $cliente->status == 1 ? 'selected' : ''; ?>>Ativo</option>
                                            <option value="0" <?php echo $cliente->status == 0 ? 'selected' : ''; ?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="/cadastros/clientes" class="btn btn-default">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#formCliente').validate({
        rules: {
            razao_social: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            nome_fantasia: {
                maxlength: 100
            },
            cnpj: {
                required: true,
                cnpj: true
            },
            ie: {
                maxlength: 20
            },
            segmento: {
                required: true
            },
            cep: {
                required: true,
                postalcode: true
            },
            endereco: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            numero: {
                required: true,
                maxlength: 10
            },
            complemento: {
                maxlength: 50
            },
            bairro: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            cidade: {
                required: true,
                minlength: 2,
                maxlength: 50
            },
            estado: {
                required: true
            },
            telefone: {
                required: true,
                phone: true
            },
            email: {
                required: true,
                email: true,
                maxlength: 100
            },
            observacoes: {
                maxlength: 500
            }
        },
        messages: {
            razao_social: {
                required: "Por favor, informe a razão social",
                minlength: "A razão social deve ter no mínimo 3 caracteres",
                maxlength: "A razão social deve ter no máximo 100 caracteres"
            },
            nome_fantasia: {
                maxlength: "O nome fantasia deve ter no máximo 100 caracteres"
            },
            cnpj: {
                required: "Por favor, informe o CNPJ",
                cnpj: "Por favor, informe um CNPJ válido"
            },
            ie: {
                maxlength: "A inscrição estadual deve ter no máximo 20 caracteres"
            },
            segmento: {
                required: "Por favor, selecione o segmento"
            },
            cep: {
                required: "Por favor, informe o CEP",
                postalcode: "Por favor, informe um CEP válido"
            },
            endereco: {
                required: "Por favor, informe o endereço",
                minlength: "O endereço deve ter no mínimo 3 caracteres",
                maxlength: "O endereço deve ter no máximo 100 caracteres"
            },
            numero: {
                required: "Por favor, informe o número",
                maxlength: "O número deve ter no máximo 10 caracteres"
            },
            complemento: {
                maxlength: "O complemento deve ter no máximo 50 caracteres"
            },
            bairro: {
                required: "Por favor, informe o bairro",
                minlength: "O bairro deve ter no mínimo 2 caracteres",
                maxlength: "O bairro deve ter no máximo 50 caracteres"
            },
            cidade: {
                required: "Por favor, informe a cidade",
                minlength: "A cidade deve ter no mínimo 2 caracteres",
                maxlength: "A cidade deve ter no máximo 50 caracteres"
            },
            estado: {
                required: "Por favor, selecione o estado"
            },
            telefone: {
                required: "Por favor, informe o telefone",
                phone: "Por favor, informe um telefone válido"
            },
            email: {
                required: "Por favor, informe o e-mail",
                email: "Por favor, informe um e-mail válido",
                maxlength: "O e-mail deve ter no máximo 100 caracteres"
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

    // Máscaras
    $('#cnpj').mask('00.000.000/0000-00');
    $('#cep').mask('00000-000');
    $('#telefone').mask('(00) 00000-0000');

    // Busca CEP
    $('#cep').blur(function() {
        var cep = $(this).val().replace(/\D/g, '');
        if (cep != "") {
            var validacep = /^[0-9]{8}$/;
            if(validacep.test(cep)) {
                $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                    if (!("erro" in dados)) {
                        $("#endereco").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#cidade").val(dados.localidade);
                        $("#estado").val(dados.uf);
                    }
                });
            }
        }
    });
});
</script>
