<?php
// Require necessary files
require_once dirname(dirname(dirname(__DIR__))) . '/config/config.php';
require_once dirname(dirname(dirname(__DIR__))) . '/app/Core/Database.php';

// Use the namespaced Database class
use App\Core\Database;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login.php');
    exit();
}

// Include header
require_once dirname(dirname(dirname(__DIR__))) . '/app/common/header.php';

// Get database connection
try {
    $db = Database::getInstance()->getConnection();
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Error connecting to database. Please try again later.");
}
?>

<!-- Dynamics 365 Style Header -->
<div class="dynamics-header">
    <div class="header-title">
        <h1>Usuários</h1>
    </div>
    <div class="header-actions">
        <div class="search-container">
            <input type="text" class="dynamics-search" placeholder="Pesquisar usuários..." id="searchUsers">
            <i class="ms-Icon ms-Icon--Search"></i>
        </div>
        <button class="dynamics-button primary" onclick="openNewUserModal()">
            <i class="ms-Icon ms-Icon--AddFriend"></i>
            Novo Usuário
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="dynamics-summary">
    <div class="summary-card">
        <div class="card-icon">
            <i class="ms-Icon ms-Icon--People"></i>
        </div>
        <div class="card-content">
            <div class="card-value" id="totalUsers">0</div>
            <div class="card-label">Total de Usuários</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon active">
            <i class="ms-Icon ms-Icon--ActivateOrders"></i>
        </div>
        <div class="card-content">
            <div class="card-value" id="activeUsers">0</div>
            <div class="card-label">Usuários Ativos</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon inactive">
            <i class="ms-Icon ms-Icon--BlockContact"></i>
        </div>
        <div class="card-content">
            <div class="card-value" id="inactiveUsers">0</div>
            <div class="card-label">Usuários Inativos</div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="dynamics-table-container">
    <table class="dynamics-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Status</th>
                <th>Último Acesso</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="usersTableBody">
            <!-- Table content will be loaded dynamically -->
        </tbody>
    </table>
</div>

<!-- User Modal -->
<div class="dynamics-modal" id="userModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Novo Usuário</h2>
            <button class="close-button" onclick="closeUserModal()">×</button>
        </div>
        <div class="modal-body">
            <form id="userForm">
                <input type="hidden" id="userId" name="userId">
                <div class="form-group">
                    <label for="userName">Nome</label>
                    <input type="text" id="userName" name="userName" required>
                </div>
                <div class="form-group">
                    <label for="userEmail">Email</label>
                    <input type="email" id="userEmail" name="userEmail" required>
                </div>
                <div class="form-group">
                    <label for="userRole">Perfil</label>
                    <select id="userRole" name="userRole" required>
                        <option value="">Selecione um perfil</option>
                        <option value="admin">Administrador</option>
                        <option value="manager">Gerente</option>
                        <option value="user">Usuário</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="userStatus">Status</label>
                    <select id="userStatus" name="userStatus" required>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="dynamics-button secondary" onclick="closeUserModal()">Cancelar</button>
            <button class="dynamics-button primary" onclick="saveUser()">Salvar</button>
        </div>
    </div>
</div>

<script src="/app/modules/usuarios/usuarios.js"></script>

<?php require_once dirname(dirname(dirname(__DIR__))) . '/app/common/footer.php'; ?>
