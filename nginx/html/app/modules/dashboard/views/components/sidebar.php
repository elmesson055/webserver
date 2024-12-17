<aside class="dynamics-sidebar">
    <div class="p-3">
        <div class="d-flex align-items-center mb-4">
            <img src="/app/assets/images/logo.svg" alt="Logo" class="me-2" style="height: 30px;">
            <span class="fs-5 fw-bold text-white">Sistema</span>
        </div>
        
        <nav class="nav flex-column">
            <a href="/app/modules/dashboard" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : ''; ?> text-white">
                <i class="fas fa-home me-2"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="/app/modules/usuarios" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/usuarios') !== false ? 'active' : ''; ?> text-white">
                <i class="fas fa-users me-2"></i>
                <span>Usuários</span>
            </a>

            <a href="/app/modules/funcoes" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/funcoes') !== false ? 'active' : ''; ?> text-white">
                <i class="fas fa-user-tag me-2"></i>
                <span>Funções</span>
            </a>

            <a href="/app/modules/permissoes" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/permissoes') !== false ? 'active' : ''; ?> text-white">
                <i class="fas fa-key me-2"></i>
                <span>Permissões</span>
            </a>

            <div class="nav-section-divider my-3"></div>

            <a href="/app/modules/configuracoes" class="nav-link text-white">
                <i class="fas fa-cog me-2"></i>
                <span>Configurações</span>
            </a>
        </nav>
    </div>
</aside>

<style>
.nav-link {
    padding: 0.75rem 1rem;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.nav-link.active {
    background-color: var(--dynamics-primary);
}

.nav-section-divider {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.dynamics-sidebar .nav-link i {
    width: 20px;
    text-align: center;
}

/* Animação suave ao mudar de página */
.nav-link.active {
    position: relative;
    overflow: hidden;
}

.nav-link.active::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
    animation: shine 1s ease-in-out;
}

@keyframes shine {
    0% {
        transform: translateX(-100%) skewX(-15deg);
    }
    100% {
        transform: translateX(100%) skewX(-15deg);
    }
}
</style>
