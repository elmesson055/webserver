<?php
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/sidebar.php';

// Verifica permissão
if (!hasPermission('manage_users')) {
    header('Location: /403');
    exit;
}

$id = $_GET['id'] ?? null;
$user = null;
$isEdit = !empty($id);

if ($id) {
    // Buscar dados do usuário
    $user = getUserById($id);
    if (!$user) {
        header('Location: /404');
        exit;
    }
}

$departments = ['Transportes', 'Custos', 'Financeiro'];
$roles = getRoles();
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $isEdit ? 'Editar' : 'Novo' ?> Usuário</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/views/config/usuarios">Usuários</a></li>
                        <li class="breadcrumb-item active"><?= $isEdit ? 'Editar' : 'Novo' ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form id="userForm" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nome *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Nome de Usuário *</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department">Departamento *</label>
                                    <select class="form-control" id="department" name="department" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?= htmlspecialchars($dept) ?>" <?= isset($user['department']) && $user['department'] === $dept ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($dept) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role_id">Perfil *</label>
                                    <select class="form-control" id="role_id" name="role_id" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= htmlspecialchars($role['id']) ?>" <?= isset($user['role_id']) && $user['role_id'] == $role['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($role['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="active">Status</label>
                                    <select class="form-control" id="active" name="active">
                                        <option value="1" <?= isset($user['active']) && $user['active'] == 1 ? 'selected' : '' ?>>Ativo</option>
                                        <option value="0" <?= isset($user['active']) && $user['active'] == 0 ? 'selected' : '' ?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php if (!$isEdit): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Senha *</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmar Senha *</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="row mt-4">
                            <div class="col-12">
                                <a href="/views/config/usuarios" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary float-right">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    const isEdit = <?= json_encode($isEdit) ?>;
    
    // Inicializa validação do formulário
    $('#userForm').validate({
        rules: {
            name: 'required',
            email: {
                required: true,
                email: true
            },
            username: 'required',
            department: 'required',
            role_id: 'required',
            password: {
                required: !isEdit,
                minlength: 8
            },
            password_confirmation: {
                required: !isEdit,
                equalTo: '#password'
            }
        },
        messages: {
            name: 'Por favor, informe o nome',
            email: {
                required: 'Por favor, informe o email',
                email: 'Por favor, informe um email válido'
            },
            username: 'Por favor, informe o nome de usuário',
            department: 'Por favor, selecione o departamento',
            role_id: 'Por favor, selecione o perfil',
            password: {
                required: 'Por favor, informe a senha',
                minlength: 'A senha deve ter pelo menos 8 caracteres'
            },
            password_confirmation: {
                required: 'Por favor, confirme a senha',
                equalTo: 'As senhas não conferem'
            }
        },
        submitHandler: function(form) {
            const userId = <?= $id ?? 'null' ?>;
            const url = userId ? `/api/users/${userId}` : '/api/users';
            const method = userId ? 'PUT' : 'POST';
            
            $.ajax({
                url: url,
                method: method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        setTimeout(() => window.location.href = '/views/config/usuarios', 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Erro ao salvar usuário');
                }
            });
            
            return false;
        }
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
