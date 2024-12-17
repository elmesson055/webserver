<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fluentui/style-utilities/dist/css/fabric.min.css">
    <link rel="stylesheet" href="/app/modules/dashboard/assets/css/dynamics-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dynamics-layout">
        <!-- Sidebar -->
        <aside class="dynamics-sidebar">
            <div class="p-3">
                <div class="d-flex align-items-center mb-4">
                    <img src="/app/assets/images/logo.png" alt="Logo" class="me-2" style="height: 30px;">
                    <span class="fs-5 fw-bold">Sistema</span>
                </div>
                
                <nav class="nav flex-column">
                    <a href="#" class="nav-link active text-white">
                        <i class="fas fa-home me-2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" class="nav-link text-white-50">
                        <i class="fas fa-users me-2"></i>
                        <span>Usuários</span>
                    </a>
                    <a href="#" class="nav-link text-white-50">
                        <i class="fas fa-truck me-2"></i>
                        <span>Veículos</span>
                    </a>
                    <a href="#" class="nav-link text-white-50">
                        <i class="fas fa-file-alt me-2"></i>
                        <span>Documentos</span>
                    </a>
                </nav>
            </div>
        </aside>

        <div class="flex-grow-1">
            <!-- Header -->
            <header class="dynamics-header">
                <button class="btn text-white d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="d-flex align-items-center">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Pesquisar...">
                        <button class="btn btn-outline-light" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <a href="#" class="text-white me-3">
                        <i class="fas fa-bell"></i>
                    </a>
                    <a href="#" class="text-white me-3">
                        <i class="fas fa-cog"></i>
                    </a>
                    <div class="dropdown">
                        <a href="#" class="text-white dropdown-toggle" data-bs-toggle="dropdown">
                            <?php echo htmlspecialchars($nome_completo); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><a class="dropdown-item" href="#">Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/app/modules/auth/logout.php">Sair</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="dynamics-content">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>

                <!-- Summary Cards -->
                <div class="dynamics-summary-card">
                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-number"><?php echo $stats['total_usuarios']; ?></div>
                            <div class="summary-label">Usuários Ativos</div>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-number"><?php echo $stats['total_veiculos']; ?></div>
                            <div class="summary-label">Veículos Ativos</div>
                        </div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="summary-content">
                            <div class="summary-number"><?php echo $stats['docs_pendentes']; ?></div>
                            <div class="summary-label">Documentos Pendentes</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="dynamics-card mb-4">
                    <h5 class="card-title mb-3">Ações Rápidas</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <button class="dynamics-btn w-100">
                                <i class="fas fa-plus me-2"></i>
                                Novo Usuário
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="dynamics-btn w-100">
                                <i class="fas fa-truck me-2"></i>
                                Novo Veículo
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="dynamics-btn w-100">
                                <i class="fas fa-file me-2"></i>
                                Novo Documento
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="dynamics-btn w-100">
                                <i class="fas fa-chart-bar me-2"></i>
                                Relatórios
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="dynamics-card">
                    <h5 class="card-title mb-3">Atividade Recente</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Usuário</th>
                                    <th>Ação</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>16/12/2024</td>
                                    <td>João Silva</td>
                                    <td>Cadastro de Veículo</td>
                                    <td><span class="badge bg-success">Concluído</span></td>
                                </tr>
                                <tr>
                                    <td>16/12/2024</td>
                                    <td>Maria Santos</td>
                                    <td>Atualização de Documento</td>
                                    <td><span class="badge bg-warning">Pendente</span></td>
                                </tr>
                                <tr>
                                    <td>15/12/2024</td>
                                    <td>Carlos Oliveira</td>
                                    <td>Novo Usuário</td>
                                    <td><span class="badge bg-success">Concluído</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/app/modules/dashboard/assets/js/dashboard.js"></script>
</body>
</html>
