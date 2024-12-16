<?php
require_once __DIR__ . '/../routes.php';

function isActiveMenu($page) {
    $currentPage = $_GET['page'] ?? 'dashboard';
    return $currentPage === $page ? 'active' : '';
}
?>

<nav class="bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-white font-bold">Custo Extras</span>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <?php if (checkPermission('view_dashboard')): ?>
                            <a href="?page=dashboard" class="<?= isActiveMenu('dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('manage_costs')): ?>
                            <a href="?page=costs" class="<?= isActiveMenu('costs') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> px-3 py-2 rounded-md text-sm font-medium">Custos</a>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('manage_clients')): ?>
                            <a href="?page=clients" class="<?= isActiveMenu('clients') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> px-3 py-2 rounded-md text-sm font-medium">Clientes</a>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('manage_drivers')): ?>
                            <a href="?page=drivers" class="<?= isActiveMenu('drivers') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> px-3 py-2 rounded-md text-sm font-medium">Motoristas</a>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('manage_fornecedores')): ?>
                            <a href="?page=fornecedores" class="<?= isActiveMenu('fornecedores') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> px-3 py-2 rounded-md text-sm font-medium">Fornecedores</a>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('manage_followup')): ?>
                            <a href="?page=followup" class="<?= isActiveMenu('followup') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> px-3 py-2 rounded-md text-sm font-medium">Follow-up</a>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('view_reports')): ?>
                            <a href="?page=reports" class="<?= isActiveMenu('reports') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> px-3 py-2 rounded-md text-sm font-medium">Relatórios</a>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('view_analytics')): ?>
                            <a href="?page=analytics" class="<?= isActiveMenu('analytics') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> px-3 py-2 rounded-md text-sm font-medium">Análises</a>
                        <?php endif; ?>
                        
                        <?php if (checkPermission('manage_users')): ?>
                            <a href="?page=users" class="<?= isActiveMenu('users') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' ?> px-3 py-2 rounded-md text-sm font-medium">Usuários</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <div class="ml-3 relative">
                        <div class="flex items-center">
                            <span class="text-gray-300 mr-4"><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                            <a href="logout.php" class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Sair</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
