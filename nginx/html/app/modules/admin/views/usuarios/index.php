<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../common/header.php';
?>

<!-- Dynamics 365 Style Header -->
<div class="dynamics-header">
    <div class="header-title">
        <h1>Usuários</h1>
    </div>
    <div class="header-actions">
        <div class="search-container">
            <input type="text" class="dynamics-search" placeholder="Pesquisar usuários..." id="searchUsers">
            <i class="fluent-search"></i>
        </div>
        <button class="dynamics-button primary" onclick="openNewUserModal()">
            <i class="fluent-add"></i>
            Novo Usuário
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="dynamics-summary">
    <div class="summary-card">
        <div class="card-value" id="totalUsers">0</div>
        <div class="card-label">Total de Usuários</div>
        <i class="fluent-people"></i>
    </div>
    <div class="summary-card">
        <div class="card-value" id="activeUsers">0</div>
        <div class="card-label">Usuários Ativos</div>
        <i class="fluent-presence-available"></i>
    </div>
    <div class="summary-card">
        <div class="card-value" id="inactiveUsers">0</div>
        <div class="card-label">Usuários Inativos</div>
        <i class="fluent-presence-busy"></i>
    </div>
    <div class="summary-card">
        <div class="card-value" id="newUsers">0</div>
        <div class="card-label">Novos (30 dias)</div>
        <i class="fluent-calendar"></i>
    </div>
</div>

<!-- Main Content -->
<div class="dynamics-content">
    <!-- Filters -->
    <div class="dynamics-filters">
        <div class="filter-group">
            <label>Status</label>
            <select class="dynamics-select" id="statusFilter">
                <option value="">Todos</option>
                <option value="active">Ativo</option>
                <option value="inactive">Inativo</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Perfil</label>
            <select class="dynamics-select" id="roleFilter">
                <option value="">Todos</option>
                <option value="admin">Administrador</option>
                <option value="manager">Gerente</option>
                <option value="user">Usuário</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Departamento</label>
            <select class="dynamics-select" id="deptFilter">
                <option value="">Todos</option>
                <option value="ti">TI</option>
                <option value="rh">RH</option>
                <option value="financeiro">Financeiro</option>
                <option value="operacional">Operacional</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="dynamics-table-container">
        <table class="dynamics-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Departamento</th>
                    <th>Status</th>
                    <th>Último Acesso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                <!-- Dados serão carregados via JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="dynamics-pagination">
        <button class="dynamics-button" id="prevPage" disabled>
            <i class="fluent-chevron-left"></i>
        </button>
        <span id="pageInfo">Página 1 de 1</span>
        <button class="dynamics-button" id="nextPage" disabled>
            <i class="fluent-chevron-right"></i>
        </button>
    </div>
</div>

<!-- New/Edit User Modal -->
<div class="dynamics-modal" id="userModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Novo Usuário</h2>
            <button class="close-button" onclick="closeUserModal()">
                <i class="fluent-dismiss"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="userForm">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="dynamics-input" id="userName" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="dynamics-input" id="userEmail" required>
                </div>
                <div class="form-group">
                    <label>Perfil</label>
                    <select class="dynamics-select" id="userRole" required>
                        <option value="admin">Administrador</option>
                        <option value="manager">Gerente</option>
                        <option value="user">Usuário</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Departamento</label>
                    <select class="dynamics-select" id="userDepartment" required>
                        <option value="ti">TI</option>
                        <option value="rh">RH</option>
                        <option value="financeiro">Financeiro</option>
                        <option value="operacional">Operacional</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select class="dynamics-select" id="userStatus" required>
                        <option value="active">Ativo</option>
                        <option value="inactive">Inativo</option>
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

<?php require_once __DIR__ . '/../../common/footer.php'; ?>
