<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-edit"></i> Atualizar Dados</h5>
                </div>
                <div class="card-body">
                    <form id="atualizarDadosForm" action="<?= route('portal.dados.atualizar') ?>" method="POST">
                        <!-- Dados Básicos -->
                        <h6 class="mb-3">Dados Básicos</h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="razao_social" class="form-label">Razão Social</label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social" 
                                           value="<?= $fornecedor->razao_social ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                                    <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" 
                                           value="<?= $fornecedor->nome_fantasia ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="cnpj" class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" id="cnpj" value="<?= $fornecedor->cnpj ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="inscricao_estadual" class="form-label">Inscrição Estadual</label>
                                    <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual" 
                                           value="<?= $fornecedor->inscricao_estadual ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="inscricao_municipal" class="form-label">Inscrição Municipal</label>
                                    <input type="text" class="form-control" id="inscricao_municipal" name="inscricao_municipal" 
                                           value="<?= $fornecedor->inscricao_municipal ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Endereço -->
                        <h6 class="mb-3">Endereço</h6>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="cep" class="form-label">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" 
                                           value="<?= $fornecedor->cep ?>" required>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label for="logradouro" class="form-label">Logradouro</label>
                                    <input type="text" class="form-control" id="logradouro" name="logradouro" 
                                           value="<?= $fornecedor->logradouro ?>" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label for="numero" class="form-label">Número</label>
                                    <input type="text" class="form-control" id="numero" name="numero" 
                                           value="<?= $fornecedor->numero ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="complemento" class="form-label">Complemento</label>
                                    <input type="text" class="form-control" id="complemento" name="complemento" 
                                           value="<?= $fornecedor->complemento ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="bairro" class="form-label">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" 
                                           value="<?= $fornecedor->bairro ?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" 
                                           value="<?= $fornecedor->cidade ?>" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">UF</label>
                                    <input type="text" class="form-control" id="estado" name="estado" 
                                           value="<?= $fornecedor->estado ?>" required maxlength="2">
                                </div>
                            </div>
                        </div>

                        <!-- Contato -->
                        <h6 class="mb-3">Contato</h6>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" 
                                           value="<?= $fornecedor->telefone ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= $fornecedor->email ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="contato" class="form-label">Nome do Contato</label>
                                    <input type="text" class="form-control" id="contato" name="contato" 
                                           value="<?= $fornecedor->contato ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Senha -->
                        <h6 class="mb-3">Alterar Senha</h6>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="senha_atual" class="form-label">Senha Atual</label>
                                    <input type="password" class="form-control" id="senha_atual" name="senha_atual">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="nova_senha" class="form-label">Nova Senha</label>
                                    <input type="password" class="form-control" id="nova_senha" name="nova_senha">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                                    <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha">
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Máscaras
    $('#cnpj').mask('00.000.000/0000-00');
    $('#cep').mask('00000-000');
    $('#telefone').mask('(00) 00000-0000');
    
    // Consulta CEP
    $('#cep').blur(function() {
        var cep = $(this).val().replace(/\D/g, '');
        
        if (cep.length === 8) {
            $.get('https://viacep.com.br/ws/' + cep + '/json/', function(data) {
                if (!data.erro) {
                    $('#logradouro').val(data.logradouro);
                    $('#bairro').val(data.bairro);
                    $('#cidade').val(data.localidade);
                    $('#estado').val(data.uf);
                }
            });
        }
    });

    // Validação do formulário
    $('#atualizarDadosForm').submit(function(e) {
        var novaSenha = $('#nova_senha').val();
        var confirmarSenha = $('#confirmar_senha').val();
        
        if (novaSenha && novaSenha !== confirmarSenha) {
            e.preventDefault();
            alert('As senhas não conferem!');
            return false;
        }
        
        return true;
    });
});
</script>
