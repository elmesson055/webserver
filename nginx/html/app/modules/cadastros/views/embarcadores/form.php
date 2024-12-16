<?php $title = isset($embarcador) ? 'Editar Embarcador' : 'Novo Embarcador'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?php echo $title; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/cadastros/embarcadores">Embarcadores</a></li>
                    <li class="breadcrumb-item active"><?php echo $title; ?></li>
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
                        <h3 class="card-title">Dados do Embarcador</h3>
                    </div>
                    
                    <form id="formEmbarcador" action="<?php echo isset($embarcador) ? '/cadastros/embarcadores/'.$embarcador->id : '/cadastros/embarcadores'; ?>" method="POST">
                        <?php if(isset($embarcador)): ?>
                            <input type="hidden" name="_method" value="PUT">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="razao_social">Razão Social</label>
                                        <input type="text" class="form-control" id="razao_social" name="razao_social" 
                                               value="<?php echo isset($embarcador) ? $embarcador->razao_social : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cnpj">CNPJ</label>
                                        <input type="text" class="form-control cnpj" id="cnpj" name="cnpj" 
                                               value="<?php echo isset($embarcador) ? $embarcador->cnpj : ''; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="endereco">Endereço</label>
                                        <input type="text" class="form-control" id="endereco" name="endereco" 
                                               value="<?php echo isset($embarcador) ? $embarcador->endereco : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="numero">Número</label>
                                        <input type="text" class="form-control" id="numero" name="numero" 
                                               value="<?php echo isset($embarcador) ? $embarcador->numero : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="complemento">Complemento</label>
                                        <input type="text" class="form-control" id="complemento" name="complemento" 
                                               value="<?php echo isset($embarcador) ? $embarcador->complemento : ''; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bairro">Bairro</label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" 
                                               value="<?php echo isset($embarcador) ? $embarcador->bairro : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" 
                                               value="<?php echo isset($embarcador) ? $embarcador->cidade : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <select class="form-control" id="estado" name="estado" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach($estados as $uf => $nome): ?>
                                                <option value="<?php echo $uf; ?>" <?php echo isset($embarcador) && $embarcador->estado == $uf ? 'selected' : ''; ?>>
                                                    <?php echo $nome; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cep">CEP</label>
                                        <input type="text" class="form-control cep" id="cep" name="cep" 
                                               value="<?php echo isset($embarcador) ? $embarcador->cep : ''; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="telefone">Telefone</label>
                                        <input type="text" class="form-control telefone" id="telefone" name="telefone" 
                                               value="<?php echo isset($embarcador) ? $embarcador->telefone : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">E-mail</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo isset($embarcador) ? $embarcador->email : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="contato">Contato</label>
                                        <input type="text" class="form-control" id="contato" name="contato" 
                                               value="<?php echo isset($embarcador) ? $embarcador->contato : ''; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="1" <?php echo isset($embarcador) && $embarcador->status == 1 ? 'selected' : ''; ?>>Ativo</option>
                                            <option value="0" <?php echo isset($embarcador) && $embarcador->status == 0 ? 'selected' : ''; ?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="/cadastros/embarcadores" class="btn btn-default">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Máscaras
    $('.cnpj').mask('00.000.000/0000-00');
    $('.cep').mask('00000-000');
    $('.telefone').mask('(00) 0000-0000');
    
    // Busca CEP
    $('#cep').blur(function() {
        var cep = $(this).val().replace(/\D/g, '');
        if (cep != "") {
            $.get('https://viacep.com.br/ws/'+ cep +'/json/', function(data) {
                if (!("erro" in data)) {
                    $('#endereco').val(data.logradouro);
                    $('#bairro').val(data.bairro);
                    $('#cidade').val(data.localidade);
                    $('#estado').val(data.uf);
                }
            });
        }
    });
    
    // Validação do formulário
    $('#formEmbarcador').validate({
        rules: {
            razao_social: {
                required: true,
                minlength: 3
            },
            cnpj: {
                required: true,
                cnpj: true
            },
            endereco: {
                required: true
            },
            numero: {
                required: true
            },
            bairro: {
                required: true
            },
            cidade: {
                required: true
            },
            estado: {
                required: true
            },
            cep: {
                required: true,
                cep: true
            },
            telefone: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            contato: {
                required: true
            }
        },
        messages: {
            razao_social: {
                required: "Por favor, informe a razão social",
                minlength: "A razão social deve ter pelo menos 3 caracteres"
            },
            cnpj: {
                required: "Por favor, informe o CNPJ",
                cnpj: "Por favor, informe um CNPJ válido"
            },
            endereco: {
                required: "Por favor, informe o endereço"
            },
            numero: {
                required: "Por favor, informe o número"
            },
            bairro: {
                required: "Por favor, informe o bairro"
            },
            cidade: {
                required: "Por favor, informe a cidade"
            },
            estado: {
                required: "Por favor, selecione o estado"
            },
            cep: {
                required: "Por favor, informe o CEP",
                cep: "Por favor, informe um CEP válido"
            },
            telefone: {
                required: "Por favor, informe o telefone"
            },
            email: {
                required: "Por favor, informe o e-mail",
                email: "Por favor, informe um e-mail válido"
            },
            contato: {
                required: "Por favor, informe o contato"
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
