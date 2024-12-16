<?php
use App\Helpers\Messages;
// Carrega o bootstrap que já inclui as funções necessárias
require_once __DIR__ . '/../../app/bootstrap.php';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12 text-center">
                    <h1 class="m-0"><?= Messages::error('403', 'heading') ?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="error-page">
                        <h2 class="headline text-danger">403</h2>
                        <div class="error-content mt-4">
                            <h3><i class="fas fa-exclamation-triangle text-danger"></i> <?= Messages::error('403', 'heading') ?></h3>
                            <p>
                                <?= Messages::error('403', 'message') ?><br>
                                <a href="/dashboard"><?= Messages::error('403', 'back_link') ?></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-page {
    margin: 20px auto;
    padding: 20px;
    max-width: 600px;
}

.error-page .headline {
    font-size: 100px;
    font-weight: 300;
}

.error-page .error-content {
    margin-left: 20px;
}

.error-page h3 {
    font-size: 28px;
    margin-bottom: 20px;
}

.error-page a {
    color: #dc3545;
    text-decoration: none;
}

.error-page a:hover {
    text-decoration: underline;
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
