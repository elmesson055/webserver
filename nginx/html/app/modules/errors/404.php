<?php
// Página de erro 404 - Recurso não encontrado
header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Não Encontrada - Sistema de Logística</title>
    <!-- Fluent UI Icons -->
    <link rel="stylesheet" href="https://static2.sharepointonline.com/files/fabric/assets/icons/fabric-icons-inline.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f2f5;
            color: #323130;
        }
        .error-container {
            text-align: center;
            background-color: white;
            padding: 2rem;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
        }
        .error-icon {
            font-size: 4rem;
            color: #605e5c;
            margin-bottom: 1rem;
        }
        h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #323130;
        }
        p {
            color: #605e5c;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        .btn-back {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: #0078d4;
            color: white;
            text-decoration: none;
            border-radius: 2px;
            transition: background-color 0.2s;
        }
        .btn-back:hover {
            background-color: #106ebe;
        }
        .btn-back i {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <i class="ms-Icon ms-Icon--ErrorBadge error-icon"></i>
        <h1>Página Não Encontrada</h1>
        <p>
            Desculpe, a página que você está procurando não existe ou foi movida.
            <br>
            Por favor, verifique o endereço digitado ou retorne à página inicial.
        </p>
        <a href="/dashboard" class="btn-back">
            <i class="ms-Icon ms-Icon--Home"></i>
            Voltar ao Início
        </a>
    </div>
</body>
</html>
<?php
exit();
