<?php include_once VIEWS_PATH . '/layout/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Dados Bancários</h4>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalDadosBancarios">
                        <i class="fas fa-plus"></i> Adicionar Conta
                    </button>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Banco</th>
                                    <th>Agência</th>
                                    <th>Conta</th>
                                    <th>Tipo</th>
                                    <th>Titular</th>
                                    <th>CPF/CNPJ</th>
                                    <th>PIX</th>
                                    <th>Principal</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dadosBancarios as $dados): ?>
                                <tr>
                                    <td><?= $bancos[$dados->banco] ?? $dados->banco ?></td>
                                    <td><?= $dados->agencia ?></td>
                                    <td><?= $dados->conta ?></td>
                                    <td><?= $tiposConta[$dados->tipo_conta] ?></td>
                                    <td><?= $dados->titular ?></td>
                                    <td><?= $dados->cpf_cnpj_titular ?></td>
                                    <td>
                                        <?php if ($dados->pix_tipo): ?>
                                            <?= $tiposPix[$dados->pix_tipo] ?>: <?= $dados->pix_chave ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($dados->principal): ?>
                                            <span class="badge bg-success">Principal</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" onclick="editarDadosBancarios(<?= $dados->id ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="excluirDadosBancarios(<?= $dados->id ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dados Bancários -->
<div class="modal fade" id="modalDadosBancarios" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dados Bancários</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formDadosBancarios">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Banco</label>
                                <select name="banco" id="banco" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($bancos as $codigo => $nome): ?>
                                        <option value="<?= $codigo ?>"><?= $nome ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Agência</label>
                                <input type="text" name="agencia" id="agencia" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Conta</label>
                                <input type="text" name="conta" id="conta" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Conta</label>
                                <select name="tipo_conta" id="tipo_conta" class="form-select" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($tiposConta as $codigo => $nome): ?>
                                        <option value="<?= $codigo ?>"><?= $nome ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Titular</label>
                                <input type="text" name="titular" id="titular" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">CPF/CNPJ do Titular</label>
                                <input type="text" name="cpf_cnpj_titular" id="cpf_cnpj_titular" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" name="principal" id="principal" class="form-check-input">
                                    <label class="form-check-label">Conta Principal</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo PIX</label>
                                <select name="pix_tipo" id="pix_tipo" class="form-select">
                                    <option value="">Selecione...</option>
                                    <?php foreach ($tiposPix as $codigo => $nome): ?>
                                        <option value="<?= $codigo ?>"><?= $nome ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Chave PIX</label>
                                <input type="text" name="pix_chave" id="pix_chave" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="salvarDadosBancarios()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
function salvarDadosBancarios() {
    const form = document.getElementById('formDadosBancarios');
    const formData = new FormData(form);

    fetch('/financeiro/dados-bancarios/salvar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            $('#modalDadosBancarios').modal('hide');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            Object.values(data.errors).forEach(error => {
                toastr.error(error);
            });
        }
    })
    .catch(error => {
        toastr.error('Erro ao salvar dados bancários');
    });
}

function editarDadosBancarios(id) {
    fetch(`/financeiro/dados-bancarios/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const dados = data.data;
                Object.keys(dados).forEach(key => {
                    const input = document.getElementById(key);
                    if (input) {
                        input.value = dados[key];
                    }
                });
                $('#modalDadosBancarios').modal('show');
            }
        });
}

function excluirDadosBancarios(id) {
    if (confirm('Deseja realmente excluir estes dados bancários?')) {
        fetch(`/financeiro/dados-bancarios/${id}/excluir`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                setTimeout(() => window.location.reload(), 1000);
            } else {
                toastr.error(data.message);
            }
        });
    }
}

// Máscaras
$(document).ready(function() {
    $('#cpf_cnpj_titular').inputmask({
        mask: ['999.999.999-99', '99.999.999/9999-99'],
        keepStatic: true
    });

    $('#agencia').inputmask('9999-9');
    $('#conta').inputmask('99999-9');
});

// Validação do PIX
$('#pix_tipo').change(function() {
    const tipo = $(this).val();
    const input = $('#pix_chave');
    
    input.val('');
    input.inputmask('remove');

    if (tipo === 'CPF') {
        input.inputmask('999.999.999-99');
    } else if (tipo === 'CNPJ') {
        input.inputmask('99.999.999/9999-99');
    } else if (tipo === 'TELEFONE') {
        input.inputmask('+55 99 99999-9999');
    }
});
</script>

<?php include_once VIEWS_PATH . '/layout/footer.php'; ?>
