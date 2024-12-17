// Variáveis globais
let usuarioModal;
let formUsuario;
let modoEdicao = false;

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    usuarioModal = new bootstrap.Modal(document.getElementById('modalUsuario'));
    formUsuario = document.getElementById('formUsuario');
    
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Listener para o formulário de filtros
    document.getElementById('formFiltros').addEventListener('submit', function(e) {
        e.preventDefault();
        aplicarFiltros();
    });
    
    // Reset do modal ao fechar
    document.getElementById('modalUsuario').addEventListener('hidden.bs.modal', function() {
        resetForm();
    });
    
    // Validação de senha
    document.getElementById('confirmar_senha').addEventListener('input', function() {
        validarSenhas();
    });
});

// Funções de CRUD
function novoUsuario() {
    modoEdicao = false;
    document.getElementById('modalTitle').textContent = 'Novo Usuário';
    document.getElementById('senhaGroup').style.display = 'block';
    document.getElementById('statusGroup').style.display = 'none';
    usuarioModal.show();
}

async function editarUsuario(id) {
    try {
        modoEdicao = true;
        document.getElementById('modalTitle').textContent = 'Editar Usuário';
        document.getElementById('senhaGroup').style.display = 'none';
        document.getElementById('statusGroup').style.display = 'block';
        
        const response = await fetch(`/app/modules/usuarios/api.php?action=obter&id=${id}`);
        const data = await response.json();
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        preencherFormulario(data);
        usuarioModal.show();
        
    } catch (error) {
        mostrarErro('Erro ao carregar usuário', error.message);
    }
}

async function salvarUsuario() {
    try {
        if (!formUsuario.checkValidity()) {
            formUsuario.reportValidity();
            return;
        }
        
        if (!modoEdicao && !validarSenhas()) {
            return;
        }
        
        const formData = new FormData(formUsuario);
        const id = formData.get('id');
        const url = `/app/modules/usuarios/api.php?action=${id ? 'atualizar' : 'criar'}`;
        
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.error) {
            throw new Error(data.error);
        }
        
        usuarioModal.hide();
        mostrarSucesso('Sucesso', 'Usuário salvo com sucesso!');
        setTimeout(() => window.location.reload(), 1500);
        
    } catch (error) {
        mostrarErro('Erro ao salvar usuário', error.message);
    }
}

async function excluirUsuario(id) {
    try {
        const result = await Swal.fire({
            title: 'Confirmar exclusão?',
            text: "Esta ação não poderá ser revertida!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        });
        
        if (result.isConfirmed) {
            const response = await fetch(`/app/modules/usuarios/api.php?action=excluir&id=${id}`, {
                method: 'POST'
            });
            
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            mostrarSucesso('Sucesso', 'Usuário excluído com sucesso!');
            setTimeout(() => window.location.reload(), 1500);
        }
        
    } catch (error) {
        mostrarErro('Erro ao excluir usuário', error.message);
    }
}

// Funções auxiliares
function preencherFormulario(dados) {
    const campos = ['id', 'nome', 'sobrenome', 'email', 'funcao_id', 'status'];
    campos.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (elemento) {
            elemento.value = dados[campo] || '';
        }
    });
}

function resetForm() {
    formUsuario.reset();
    document.getElementById('usuario_id').value = '';
    const senhas = document.querySelectorAll('input[type="password"]');
    senhas.forEach(senha => senha.value = '');
}

function validarSenhas() {
    const senha = document.getElementById('senha').value;
    const confirmar = document.getElementById('confirmar_senha').value;
    
    if (senha !== confirmar) {
        mostrarErro('Erro de validação', 'As senhas não conferem!');
        return false;
    }
    
    return true;
}

function aplicarFiltros() {
    const formData = new FormData(document.getElementById('formFiltros'));
    const params = new URLSearchParams(formData);
    window.location.href = `?${params.toString()}`;
}

// Funções de UI
function mostrarSucesso(titulo, mensagem) {
    Swal.fire({
        title: titulo,
        text: mensagem,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

function mostrarErro(titulo, mensagem) {
    Swal.fire({
        title: titulo,
        text: mensagem,
        icon: 'error'
    });
}

// Efeitos visuais
document.querySelectorAll('.dynamics-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const ripple = document.createElement('span');
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');

        this.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 1000);
    });
});
