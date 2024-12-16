<?php 
use App\Helpers\Messages;
require_once __DIR__ . '/../includes/header.php'; 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h1 class="display-1"><?= Messages::error('404', 'heading') ?></h1>
            <h2 class="mb-4"><?= Messages::error('404', 'title') ?></h2>
            <p class="mb-4"><?= Messages::error('404', 'message') ?></p>
            <a href="/" class="btn btn-primary"><?= Messages::error('404', 'back_link') ?></a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
