<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toast Utilities -->
<script src="/assets/js/toast.js"></script>

<!-- Inicialização do Toast para mensagens da sessão -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['flash'])): ?>
        const flash = <?php echo json_encode($_SESSION['flash']); ?>;
        showToast(flash.type, flash.message, flash.redirect || null);
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>
});</script>
