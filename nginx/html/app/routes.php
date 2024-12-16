<?php

function checkPermission($permission) {
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['permissions'])) {
        return false;
    }
    return in_array($permission, $_SESSION['user']['permissions']);
}

// Define as rotas e suas permissões necessárias
$routes = [
    'dashboard' => [
        'permission' => 'view_dashboard',
        'file' => 'dashboard.php'
    ],
    'users' => [
        'permission' => 'manage_users',
        'file' => 'users/index.php'
    ],
    'roles' => [
        'permission' => 'manage_roles',
        'file' => 'roles/index.php'
    ],
    'costs' => [
        'permission' => 'manage_costs',
        'file' => 'costs/index.php'
    ],
    'types' => [
        'permission' => 'manage_types',
        'file' => 'types/index.php'
    ],
    'clients' => [
        'permission' => 'manage_clients',
        'file' => 'clients/index.php'
    ],
    'reports' => [
        'permission' => 'view_reports',
        'file' => 'reports/index.php'
    ],
    'drivers' => [
        'permission' => 'manage_drivers',
        'file' => 'drivers/index.php'
    ],
    'fornecedores' => [
        'permission' => 'manage_fornecedores',
        'file' => 'fornecedores/index.php'
    ],
    'followup' => [
        'permission' => 'manage_followup',
        'file' => 'followup/index.php'
    ],
    'analytics' => [
        'permission' => 'view_analytics',
        'file' => 'analytics/index.php'
    ]
];
