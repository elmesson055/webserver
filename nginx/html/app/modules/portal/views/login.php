<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal do Fornecedor</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header i {
            font-size: 48px;
            color: #3498db;
        }
        .login-card {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <i class="fas fa-building mb-3"></i>
                <h2>Portal do Fornecedor</h2>
                <p class="text-muted">Faça login para acessar sua área</p>
            </div>

            <div class="login-card">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= route('portal.login.post') ?>" method="POST">
                    <div class="mb-3">
                        <label for="cnpj" class="form-label">CNPJ</label>
                        <input type="text" class="form-control" id="cnpj" name="cnpj" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="lembrar" name="lembrar">
                        <label class="form-check-label" for="lembrar">Lembrar de mim</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#recuperarSenhaModal">
                        Esqueceu sua senha?
                    </a>
                </div>
            </div>

            <div class="login-footer">
                <p>&copy; <?= date('Y') ?> - Todos os direitos reservados</p>
            </div>
        </div>
    </div>

    <!-- Modal Recuperar Senha -->
    <div class="modal fade" id="recuperarSenhaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recuperar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="recuperarSenhaForm" action="<?= route('portal.recuperar-senha') ?>" method="POST">
                        <div class="mb-3">
                            <label for="cnpj_recuperacao" class="form-label">CNPJ</label>
                            <input type="text" class="form-control" id="cnpj_recuperacao" name="cnpj" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_recuperacao" class="form-label">E-mail cadastrado</label>
                            <input type="email" class="form-control" id="email_recuperacao" name="email" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="recuperarSenhaForm" class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Mask -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#cnpj, #cnpj_recuperacao').mask('00.000.000/0000-00');
        });
    </script>
</body>
</html>
