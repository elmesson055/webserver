<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Redefinir Senha</div>
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

                    <form method="POST" action="/auth/reset-password">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        
                        <div class="form-group mb-3">
                            <label for="password">Nova Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required 
                                   minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                            <small class="form-text text-muted">
                                A senha deve ter no mínimo 8 caracteres, incluindo letras maiúsculas, minúsculas e números.
                            </small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="confirm_password">Confirme a Nova Senha</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Redefinir Senha</button>
                        <a href="/login" class="btn btn-link">Voltar para Login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
