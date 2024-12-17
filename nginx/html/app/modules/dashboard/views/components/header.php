<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /app/modules/auth/views/login.php');
    exit();
}
?>
<header class="dynamics-header">
    <div class="d-flex justify-content-between align-items-center p-3">
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-dark me-3" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="h5 mb-0">Sistema</h1>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="dropdown me-3">
                <button class="btn btn-link text-dark dropdown-toggle" type="button" id="notificationsDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger rounded-pill">0</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                    <div class="dropdown-header">Notificações</div>
                    <div class="dropdown-divider"></div>
                    <div class="p-3 text-center text-muted">
                        Nenhuma notificação
                    </div>
                </div>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-link text-dark dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <div class="me-2 d-none d-md-block">
                        <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Usuário'); ?></div>
                        <div class="small text-muted"><?php echo htmlspecialchars($_SESSION['user_role'] ?? 'Função'); ?></div>
                    </div>
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fas fa-user"></i>
                    </div>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="/app/modules/usuarios/perfil">
                        <i class="fas fa-user-circle me-2"></i>
                        Meu Perfil
                    </a>
                    <a class="dropdown-item" href="/app/modules/configuracoes">
                        <i class="fas fa-cog me-2"></i>
                        Configurações
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="/app/modules/auth/logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
.dynamics-header {
    background-color: #fff;
    border-bottom: 1px solid #e5e5e5;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.dropdown-menu {
    box-shadow: 0 2px 8px rgba(0,0,0,.15);
    border: 1px solid #e5e5e5;
}

.dropdown-item {
    padding: .5rem 1rem;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item i {
    width: 20px;
    text-align: center;
}

.badge {
    position: absolute;
    top: 0;
    right: 0;
    transform: translate(50%, -50%);
}

#sidebarToggle {
    padding: 0.5rem;
    border-radius: 4px;
}

#sidebarToggle:hover {
    background-color: #f8f9fa;
}
</style>

<script>
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.dynamics-sidebar').classList.toggle('collapsed');
});
</script>
