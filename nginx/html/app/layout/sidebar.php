<?php
$currentPage = basename($_SERVER['PHP_SELF']);
error_log("Página atual: " . $currentPage);

require_once __DIR__ . '/../../app/init.php';
require_once __DIR__ . '/../../app/Middleware/AuthorizationMiddleware.php';

// Garantir que temos uma conexão com o banco de dados
if (!isset($db)) {
    $db = connectDB();
}

$authMiddleware = new App\Middleware\AuthorizationMiddleware($db);

$menuItems = [
    [
        'url' => 'dashboard.php',
        'title' => 'Dashboard',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
        'permission' => 'dashboard_view'
    ],
    [
        'url' => 'follow_up.php',
        'title' => 'Follow Up',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>',
        'permission' => ''
    ],
    [
        'url' => 'costs.php',
        'title' => 'Custos',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'permission' => 'cost_view'
    ],
    [
        'url' => 'fornecedores.php',
        'title' => 'Fornecedores',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
        'permission' => 'fornecedor_view'
    ],
    [
        'url' => 'types.php',
        'title' => 'Tipos',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>',
        'permission' => 'type_management'
    ],
    [
        'url' => 'clients.php',
        'title' => 'Clientes',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
        'permission' => 'client_view'
    ],
    [
        'url' => 'drivers.php',
        'title' => 'Motoristas',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
        'permission' => ''
    ],
    [
        'url' => 'shippers.php',
        'title' => 'Embarcadores',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>',
        'permission' => 'shipper_management'
    ],
    [
        'url' => 'users.php',
        'title' => 'Usuários',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
        'permission' => 'user_management'
    ],
    [
        'url' => 'reports.php',
        'title' => 'Relatórios',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
        'permission' => 'report_view'
    ],
    [
        'url' => 'system_settings.php',
        'title' => 'Configurações',
        'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
        'permission' => 'manage_system_settings'
    ]
];

// Filtrar itens de menu baseado nas permissões do usuário
try {
    error_log("Filtrando menu para usuário: " . ($_SESSION['user_id'] ?? 'Não definido'));
    error_log("Dados do usuário: " . json_encode($_SESSION['user'] ?? []));
    error_log("URL atual: " . $_SERVER['REQUEST_URI']);
    error_log("PHP_SELF: " . $_SERVER['PHP_SELF']);
    
    $filteredMenuItems = isset($_SESSION['user_id']) 
        ? $authMiddleware->filterMenuItems($menuItems, $_SESSION['user_id']) 
        : $menuItems;
} catch (Exception $e) {
    // Log do erro
    error_log('Erro ao filtrar menu: ' . $e->getMessage());
    $filteredMenuItems = $menuItems;
}

// Adicionar log de depuração para cada item do menu
foreach ($filteredMenuItems as $item) {
    error_log("Menu item: " . $item['title'] . " - URL: " . $item['url']);
}

?>

<!-- Sidebar -->
<aside class="fixed left-0 top-0 h-screen w-64 bg-gray-800 text-white shadow-lg z-10">
    <!-- Logo/Brand -->
    <div class="px-6 py-4 border-b border-gray-700">
        <h1 class="text-xl font-bold">Custo Extras</h1>
    </div>

    <!-- Navigation -->
    <nav class="mt-4">
        <ul>
            <?php foreach ($filteredMenuItems as $item): ?>
                <?php 
                $isActive = $currentPage === $item['url'];
                $activeClass = $isActive ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white';
                ?>
                <li>
                    <a href="<?= PUBLIC_URL . '/' . $item['url'] ?>" 
                       class="flex items-center px-6 py-3 <?= $activeClass ?> transition-colors duration-200">
                        <?= $item['icon'] ?>
                        <span class="ml-3"><?= $item['title'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- User Info -->
    <div class="absolute bottom-0 w-full border-t border-gray-700">
        <div class="px-6 py-4">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <div class="ml-3">
                    <p class="text-sm font-medium"><?= $_SESSION['user']['name'] ?? 'Usuário' ?></p>
                    <p class="text-xs text-gray-400"><?= $_SESSION['user']['role_name'] ?? 'Cargo' ?></p>
                </div>
            </div>
            <a href="logout.php" class="mt-4 block text-center py-2 px-4 bg-red-600 hover:bg-red-700 rounded-md text-sm font-medium transition-colors duration-200">
                Sair
            </a>
        </div>
    </div>
</aside>

<!-- Main Content Wrapper -->
<div class="ml-64 min-h-screen bg-gray-100">
    <!-- Content goes here -->
    <div class="p-6">
        <?php if (isset($pageTitle)): ?>
            <h1 class="text-2xl font-bold mb-6"><?= $pageTitle ?></h1>
        <?php endif; ?>
