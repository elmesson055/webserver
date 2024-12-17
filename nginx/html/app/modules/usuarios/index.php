<?php
session_start();

// Verificar se usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: /app/modules/auth/views/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fluentui/style-utilities/dist/css/fabric.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/app/modules/dashboard/assets/css/dynamics-theme.css">
    <style>
        :root {
            --dynamics-primary: #0078D4;
            --dynamics-secondary: #605E5C;
            --dynamics-background: #F8F9FA;
        }
        
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--dynamics-background);
        }
        
        .dynamics-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .dynamics-content {
            flex-grow: 1;
            padding: 2rem;
        }
        
        .dynamics-card {
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="dynamics-layout">
        <!-- Sidebar -->
        <?php include dirname(__DIR__) . '/dashboard/views/components/sidebar.php'; ?>
        <div class="flex-grow-1">
            <!-- Header -->
            <?php include dirname(__DIR__) . '/dashboard/views/components/header.php'; ?>
            <!-- Content -->
            <main class="dynamics-content">
                <div class="dynamics-card">
                    <h1 class="h3 mb-4">Usuários</h1>
                    <p>Módulo de gerenciamento de usuários.</p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Esta é uma página de teste para verificar o acesso ao módulo.
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle do sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.dynamics-sidebar').classList.toggle('collapsed');
            document.querySelector('.dynamics-content').classList.toggle('expanded');
        });
    </script>
</body>
</html>
