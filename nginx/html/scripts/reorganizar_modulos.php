<?php
/**
 * Script para reorganizar os módulos do sistema seguindo o padrão
 */

function createModuleStructure($modulePath) {
    // Criar diretórios padrão
    $directories = [
        'views',
        'views/components',
        'models',
        'controllers',
        'config',
        'assets',
        'assets/js',
        'assets/css',
        'assets/img'
    ];

    foreach ($directories as $dir) {
        $path = $modulePath . '/' . $dir;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            echo "Criado diretório: $path\n";
        }
    }

    // Criar .htaccess
    $htaccess = $modulePath . '/.htaccess';
    if (!file_exists($htaccess)) {
        file_put_contents($htaccess, 
            "DirectoryIndex views/index.php\n" .
            "RewriteEngine On\n" .
            "RewriteRule ^index\\.php$ views/index.php [L]\n"
        );
        echo "Criado arquivo: $htaccess\n";
    }

    // Criar index.php
    $index = $modulePath . '/index.php';
    if (!file_exists($index)) {
        file_put_contents($index, 
            "<?php\n" .
            "header('Location: views/index.php');\n" .
            "exit();\n"
        );
        echo "Criado arquivo: $index\n";
    }

    // Criar functions.php
    $functions = $modulePath . '/functions.php';
    if (!file_exists($functions)) {
        file_put_contents($functions, 
            "<?php\n" .
            "// Funções específicas do módulo\n"
        );
        echo "Criado arquivo: $functions\n";
    }

    // Criar config/config.php
    $config = $modulePath . '/config/config.php';
    if (!file_exists($config)) {
        file_put_contents($config, 
            "<?php\n" .
            "// Configurações específicas do módulo\n"
        );
        echo "Criado arquivo: $config\n";
    }
}

// Diretório base dos módulos
$baseDir = dirname(dirname(__FILE__)) . '/app/modules';

// Lista de módulos para reorganizar
$modules = [
    'admin',
    'auditoria',
    'auth',
    'cadastros',
    'common',
    'custos',
    'custos_extras',
    'dashboard',
    'embarcadores',
    'errors',
    'financeiro',
    'layouts',
    'monitoring',
    'notificacoes',
    'painel',
    'portal',
    'relatorios',
    'tests',
    'tms',
    'usuarios',
    'wms',
    'wms-mobile'
];

// Reorganizar cada módulo
foreach ($modules as $module) {
    $modulePath = $baseDir . '/' . $module;
    if (is_dir($modulePath)) {
        echo "\nReorganizando módulo: $module\n";
        echo "--------------------------------\n";
        createModuleStructure($modulePath);
    }
}

echo "\nReorganização concluída!\n";
