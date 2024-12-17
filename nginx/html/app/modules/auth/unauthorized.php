<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/config/config.php';
$page_title = "Acesso Não Autorizado";
require_once HEADER_PATH;
?>

<div class="container-fluid px-4">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="dynamics-workspace-card text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-lock text-warning" style="font-size: 4rem;"></i>
                </div>
                <h1 class="h3 mb-3">Acesso Não Autorizado</h1>
                <p class="text-muted mb-4">
                    Você não tem permissão para acessar esta página.
                    Entre em contato com o administrador do sistema se acredita que isso é um erro.
                </p>
                <a href="/app/modules/dashboard/index.php" class="btn btn-dynamics">
                    <i class="fas fa-home me-2"></i>Voltar para o Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once FOOTER_PATH; ?>
