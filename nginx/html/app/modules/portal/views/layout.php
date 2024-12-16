<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal do Fornecedor</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #2c3e50;
            color: white;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 10px 20px;
            margin: 5px 0;
        }
        .sidebar .nav-link:hover {
            background-color: #34495e;
        }
        .sidebar .nav-link.active {
            background-color: #3498db;
        }
        .main-content {
            padding: 20px;
        }
        .navbar {
            background-color: #3498db;
        }
        .navbar-brand {
            color: white !important;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 15px;
        }
        .document-card {
            position: relative;
            margin-bottom: 20px;
        }
        .progress-small {
            height: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/portal">
                <i class="fas fa-building"></i> Portal do Fornecedor
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= $fornecedor->razao_social ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/portal/perfil"><i class="fas fa-user-edit"></i> Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/portal/logout"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>" href="/portal">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'documentos' ? 'active' : '' ?>" href="/portal/documentos">
                            <i class="fas fa-file-alt"></i> Documentos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'dados' ? 'active' : '' ?>" href="/portal/dados">
                            <i class="fas fa-edit"></i> Atualizar Dados
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#menuFinanceiro">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Financeiro</span>
                            <i class="fas fa-angle-down"></i>
                        </a>
                        <div class="collapse" id="menuFinanceiro">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link <?= $currentPage == 'dados-bancarios' ? 'active' : '' ?>" href="/portal/financeiro/dados-bancarios">
                                        <i class="fas fa-university"></i> Dados Bancários
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= $currentPage == 'movimentacoes' ? 'active' : '' ?>" href="/portal/financeiro/movimentacoes">
                                        <i class="fas fa-exchange-alt"></i> Movimentações
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage == 'mensagens' ? 'active' : '' ?>" href="/portal/mensagens">
                            <i class="fas fa-envelope"></i> Mensagens
                            <?php if (isset($unreadMessages) && $unreadMessages > 0): ?>
                                <span class="badge bg-danger"><?= $unreadMessages ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="col-md-10 main-content">
                <?php if (isset($flash['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $flash['success'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($flash['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= $flash['error'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            // Tooltip initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>
</body>
</html>
