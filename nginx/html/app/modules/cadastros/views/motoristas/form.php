<?php
$title = $motorista->id ? 'Editar Motorista' : 'Novo Motorista';
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
                    <li class="breadcrumb-item"><a href="/cadastros/motoristas">Motoristas</a></li>
                    <li class="breadcrumb-item active"><?php echo $motorista->id ? 'Editar' : 'Novo'; ?></li>
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
                    <form id="formMotorista" action="<?php echo $motorista->id ? '/cadastros/motoristas/'.$motorista->id : '/cadastros/motoristas'; ?>" method="POST">
                        <?php if($motorista->id): ?>
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
                                               value="<?php echo $motorista->nome; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cpf">CPF <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cpf" name="cpf" 
                                               value="<?php echo $motorista->cpf; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="rg">RG</label>
                                        <input type="text" class="form-control" id="rg" name="rg" 
                                               value="<?php echo $motorista->rg; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cnh">CNH <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cnh" name="cnh" 
                                               value="<?php echo $motorista->cnh; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="categoria_cnh">Categoria CNH <span class="text-danger">*</span></label>
                                        <select class="form-control" id="categoria_cnh" name="categoria_cnh" required>
                                            <option value="">Selecione...</option>
                                            <option value="A" <?php echo $motorista->categoria_cnh == 'A' ? 'selected' : ''; ?>>A</option>
                                            <option value="B" <?php echo $motorista->categoria_cnh == 'B' ? 'selected' : ''; ?>>B</option>
                                            <option value="C" <?php echo $motorista->categoria_cnh == 'C' ? 'selected' : ''; ?>>C</option>
                                            <option value="D" <?php echo $motorista->categoria_cnh == 'D' ? 'selected' : ''; ?>>D</option>
                                            <option value="E" <?php echo $motorista->categoria_cnh == 'E' ? 'selected' : ''; ?>>E</option>
                                            <option value="AB" <?php echo $motorista->categoria_cnh == 'AB' ? 'selected' : ''; ?>>AB</option>
                                            <option value="AC" <?php echo $motorista->categoria_cnh == 'AC' ? 'selected' : ''; ?>>AC</option>
                                            <option value="AD" <?php echo $motorista->categoria_cnh == 'AD' ? 'selected' : ''; ?>>AD</option>
                                            <option value="AE" <?php echo $motorista->categoria_cnh == 'AE' ? 'selected' : ''; ?>>AE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="validade_cnh">Validade CNH <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="validade_cnh" name="validade_cnh" 
                                               value="<?php echo $motorista->validade_cnh; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="mopp">MOPP</label>
                                        <select class="form-control" id="mopp" name="mopp">
                                            <option value="1" <?php echo $motorista->mopp == 1 ? 'selected' : ''; ?>>Sim</option>
                                            <option value="0" <?php echo $motorista->mopp == 0 ? 'selected' : ''; ?>>Não</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cep">CEP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cep" name="cep" 
                                               value="<?php echo $motorista->cep; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="endereco">Endereço <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="endereco" name="endereco" 
                                               value="<?php echo $motorista->endereco; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="numero">Número <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="numero" name="numero" 
                                               value="<?php echo $motorista->numero; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="complemento">Complemento</label>
                                        <input type="text" class="form-control" id="complemento" name="complemento" 
                                               value="<?php echo $motorista->complemento; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bairro">Bairro <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" 
                                               value="<?php echo $motorista->bairro; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cidade">Cidade <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" 
                                               value="<?php echo $motorista->cidade; ?>" required>
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
                                                <option value="<?php echo $sigla; ?>" <?php echo $motorista->estado == $sigla ? 'selected' : ''; ?>>
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
                                               value="<?php echo $motorista->telefone; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">E-mail</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo $motorista->email; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="observacoes">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3"><?php echo $motorista->observacoes; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="1" <?php echo $motorista->status == 1 ? 'selected' : ''; ?>>Ativo</option>
                                            <option value="0" <?php echo $motorista->status == 0 ? 'selected' : ''; ?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="/cadastros/motoristas" class="btn btn-default">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $('#formMotorista').validate({
        rules: {
            nome: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            cpf: {
                required: true,
                cpf: true
            },
            rg: {
                maxlength: 20
            },
            cnh: {
                required: true,
                minlength: 11,
                maxlength: 11
            },
            categoria_cnh: {
                required: true
            },
            validade_cnh: {
                required: true,
                date: true
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
                email: true,
                maxlength: 100
            },
            observacoes: {
                maxlength: 500
            }
        },
        messages: {
            nome: {
                required: "Por favor, informe o nome",
                minlength: "O nome deve ter no mínimo 3 caracteres",
                maxlength: "O nome deve ter no máximo 100 caracteres"
            },
            cpf: {
                required: "Por favor, informe o CPF",
                cpf: "Por favor, informe um CPF válido"
            },
            rg: {
                maxlength: "O RG deve ter no máximo 20 caracteres"
            },
            cnh: {
                required: "Por favor, informe a CNH",
                minlength: "A CNH deve ter 11 caracteres",
                maxlength: "A CNH deve ter 11 caracteres"
            },
            categoria_cnh: {
                required: "Por favor, selecione a categoria da CNH"
            },
            validade_cnh: {
                required: "Por favor, informe a validade da CNH",
                date: "Por favor, informe uma data válida"
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
    $('#cpf').mask('000.000.000-00');
    $('#cep').mask('00000-000');
    $('#telefone').mask('(00) 00000-0000');
    $('#cnh').mask('00000000000');

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
