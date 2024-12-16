<?php require_once __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Recuperação de Senha</div>
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

                    <form method="POST" action="/auth/forgot-password">
                        <div class="form-group mb-3">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <small class="form-text text-muted">Digite o e-mail cadastrado na sua conta.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Link de Recuperação</button>
                        <a href="/login" class="btn btn-link">Voltar para Login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
