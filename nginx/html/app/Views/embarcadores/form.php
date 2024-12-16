<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            <?= isset($item) ? 'Editar Embarcador' : 'Novo Embarcador' ?>
        </h1>
        <a href="<?= baseUrl() ?>/embarcadores" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?= baseUrl() ?>/embarcadores/<?= isset($item) ? 'update/' . $item['id'] : 'store' ?>" 
              method="POST" 
              id="embarcadorForm">
            
            <!-- Dados Principais -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Razão Social *
                    </label>
                    <input type="text" 
                           name="razao_social" 
                           value="<?= isset($item) ? htmlspecialchars($item['razao_social']) : '' ?>"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="text-red-500 text-xs error-message"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        CNPJ *
                    </label>
                    <input type="text" 
                           name="cnpj" 
                           value="<?= isset($item) ? htmlspecialchars($item['cnpj']) : '' ?>"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="text-red-500 text-xs error-message"></span>
                </div>
            </div>

            <!-- Endereço -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Endereço *
                    </label>
                    <input type="text" 
                           name="endereco" 
                           value="<?= isset($item) ? htmlspecialchars($item['endereco']) : '' ?>"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="text-red-500 text-xs error-message"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Número
                    </label>
                    <input type="text" 
                           name="numero" 
                           value="<?= isset($item) ? htmlspecialchars($item['numero']) : '' ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Complemento
                    </label>
                    <input type="text" 
                           name="complemento" 
                           value="<?= isset($item) ? htmlspecialchars($item['complemento']) : '' ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bairro
                    </label>
                    <input type="text" 
                           name="bairro" 
                           value="<?= isset($item) ? htmlspecialchars($item['bairro']) : '' ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cidade *
                    </label>
                    <input type="text" 
                           name="cidade" 
                           value="<?= isset($item) ? htmlspecialchars($item['cidade']) : '' ?>"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="text-red-500 text-xs error-message"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Estado *
                    </label>
                    <select name="estado" 
                            required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Selecione...</option>
                        <?php
                        $estados = [
                            'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 
                            'AM' => 'Amazonas', 'BA' => 'Bahia', 'CE' => 'Ceará',
                            'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
                            'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso',
                            'MS' => 'Mato Grosso do Sul', 'MG' => 'Minas Gerais',
                            'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
                            'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro',
                            'RN' => 'Rio Grande do Norte', 'RS' => 'Rio Grande do Sul',
                            'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
                            'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
                        ];
                        foreach ($estados as $uf => $nome):
                            $selected = isset($item) && $item['estado'] === $uf ? 'selected' : '';
                        ?>
                            <option value="<?= $uf ?>" <?= $selected ?>><?= $nome ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-red-500 text-xs error-message"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        CEP
                    </label>
                    <input type="text" 
                           name="cep" 
                           value="<?= isset($item) ? htmlspecialchars($item['cep']) : '' ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Contato -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Telefone
                    </label>
                    <input type="text" 
                           name="telefone" 
                           value="<?= isset($item) ? htmlspecialchars($item['telefone']) : '' ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           name="email" 
                           value="<?= isset($item) ? htmlspecialchars($item['email']) : '' ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="text-red-500 text-xs error-message"></span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Contato
                    </label>
                    <input type="text" 
                           name="contato" 
                           value="<?= isset($item) ? htmlspecialchars($item['contato']) : '' ?>"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" 
                           name="status" 
                           value="1" 
                           <?= (!isset($item) || $item['status']) ? 'checked' : '' ?>
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Ativo</span>
                </label>
            </div>

            <!-- Botões -->
            <div class="flex justify-end gap-2">
                <button type="button" 
                        onclick="window.location.href='<?= baseUrl() ?>/embarcadores'"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Cancelar
                </button>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('embarcadorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Limpa mensagens de erro anteriores
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    
    // Serializa o formulário
    const formData = new FormData(this);
    
    // Envia requisição
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            // Exibe erros de validação
            Object.keys(data.errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    const errorElement = input.nextElementSibling;
                    if (errorElement && errorElement.classList.contains('error-message')) {
                        errorElement.textContent = data.errors[field];
                    }
                }
            });
        } else if (data.message) {
            // Redireciona em caso de sucesso
            window.location.href = '<?= baseUrl() ?>/embarcadores';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao salvar o registro');
    });
});

// Máscara para CNPJ
const cnpjInput = document.querySelector('input[name="cnpj"]');
cnpjInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 14) {
        value = value.replace(/(\d{2})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1/$2');
        value = value.replace(/(\d{4})(\d)/, '$1-$2');
    }
    e.target.value = value;
});

// Máscara para CEP
const cepInput = document.querySelector('input[name="cep"]');
cepInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 8) {
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
    }
    e.target.value = value;
});

// Máscara para telefone
const telefoneInput = document.querySelector('input[name="telefone"]');
telefoneInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        if (value.length > 2) {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
        }
        if (value.length > 6) {
            if (value.length === 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            }
        }
    }
    e.target.value = value;
});
</script>
