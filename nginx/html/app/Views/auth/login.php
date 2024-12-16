<?php 
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Login</h4>
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

                    <form method="POST" action="/auth/login">
                        <div class="form-group mb-3">
                            <label for="username">Usuário</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   required autofocus>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Senha</label>
                            <input type="password" class="form-control" id="password" 
                                   name="password" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="/auth/forgot-password" class="text-decoration-none">
                                Esqueceu a senha?
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mensagem informativa sobre navegadores suportados -->
            <div class="text-center mt-3">
                <small class="text-muted">
                    Para melhor experiência, utilize Chrome, Firefox ou Edge.
                </small>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
