<?php
use App\Helpers\Messages;
/**
 * Página de erro de banco de dados
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Messages::error('db', 'title') ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h1 class="display-4 text-danger mb-4"><?= Messages::error('db', 'heading') ?></h1>
                        <p class="lead"><?= Messages::error('db', 'message') ?></p>
                        <hr>
                        <p><?= Messages::error('db', 'submessage') ?></p>
                        <div class="mt-4">
                            <a href="/" class="btn btn-primary"><?= Messages::error('db', 'back_link') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
