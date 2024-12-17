document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips do Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Toggle do Sidebar
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.dynamics-sidebar');
    const content = document.querySelector('.dynamics-content');
    const header = document.querySelector('.dynamics-header');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            sidebar.classList.toggle('show');
            
            // Ajustar margens do conteúdo e header
            if (sidebar.classList.contains('collapsed')) {
                content.style.marginLeft = '48px';
                header.style.left = '48px';
            } else {
                content.style.marginLeft = '250px';
                header.style.left = '250px';
            }
        });
    }

    // Fechar sidebar ao clicar fora em dispositivos móveis
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Animação dos cards de sumário
    const summaryItems = document.querySelectorAll('.summary-item');
    summaryItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Hover effect nos botões de ação rápida
    const actionButtons = document.querySelectorAll('.dynamics-btn');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Adicionar efeito de ripple nos botões
    const buttons = document.querySelectorAll('.dynamics-btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const ripple = document.createElement('span');
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');

            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 1000);
        });
    });

    // Atualizar layout responsivo
    function updateResponsiveLayout() {
        if (window.innerWidth <= 768) {
            sidebar.classList.add('collapsed');
            content.style.marginLeft = '0';
            header.style.left = '0';
        } else {
            sidebar.classList.remove('collapsed');
            content.style.marginLeft = '250px';
            header.style.left = '250px';
        }
    }

    // Chamar no carregamento e no redimensionamento
    updateResponsiveLayout();
    window.addEventListener('resize', updateResponsiveLayout);
});
