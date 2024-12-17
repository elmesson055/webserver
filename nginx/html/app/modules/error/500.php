<?php
// Carregar configurações
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(dirname(__DIR__))));
}
require_once BASE_PATH . '/config/app.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro 500 - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fluentui/style-utilities/dist/css/fabric.min.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .error-container {
            text-align: center;
            padding: 2rem;
        }
        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #d83b01;
            margin-bottom: 1rem;
        }
        .error-message {
            font-size: 1.5rem;
            color: #605e5c;
            margin-bottom: 2rem;
        }
        .back-button {
            background-color: #0078d4;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #106ebe;
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <div class="error-message">Ops! Algo deu errado.</div>
        <p class="text-muted mb-4">Nossos engenheiros foram notificados e estão trabalhando para resolver o problema.</p>
        <a href="/app/modules/dashboard" class="back-button">Voltar para o Dashboard</a>
    </div>
</body>
</html>
