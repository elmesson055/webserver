<?php 
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth_check.php'; // Garante que usuário está logado
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Alterar Senha</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                            <?= $_SESSION['message'] ?>
                        </div>
                        <?php 
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                        ?>
                    <?php endif; ?>

                    <form method="POST" action="/profile/change-password">
                        <div class="form-group mb-3">
                            <label for="current_password">Senha Atual</label>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="new_password">Nova Senha</label>
                            <input type="password" class="form-control" id="new_password" 
                                   name="new_password" required minlength="8" 
                                   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                            <small class="form-text text-muted">
                                A senha deve ter no mínimo 8 caracteres, incluindo letras maiúsculas, 
                                minúsculas e números.
                            </small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="confirm_password">Confirme a Nova Senha</label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Alterar Senha</button>
                            <a href="/profile" class="btn btn-outline-secondary">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('confirm_password').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (newPassword !== confirmPassword) {
        this.setCustomValidity('As senhas não conferem');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
