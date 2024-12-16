// Dynamics 365 Users Management JavaScript

// Global variables
let currentPage = 1;
let totalPages = 1;
let users = [];
let filters = {
    search: '',
    status: '',
    role: '',
    department: ''
};

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    initializeEventListeners();
    updateSummaryCards();
});

// Initialize event listeners
function initializeEventListeners() {
    // Search input
    document.getElementById('searchUsers').addEventListener('input', debounce(function(e) {
        filters.search = e.target.value;
        loadUsers();
    }, 300));

    // Filters
    ['status', 'role', 'dept'].forEach(filter => {
        document.getElementById(`${filter}Filter`).addEventListener('change', function(e) {
            filters[filter] = e.target.value;
            loadUsers();
        });
    });

    // Pagination
    document.getElementById('prevPage').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            loadUsers();
        }
    });

    document.getElementById('nextPage').addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            loadUsers();
        }
    });
}

// Load users with filters
async function loadUsers() {
    try {
        showLoading();
        
        const response = await fetch('/api/users?' + new URLSearchParams({
            page: currentPage,
            ...filters
        }));

        const data = await response.json();
        
        if (data.success) {
            users = data.users;
            totalPages = data.totalPages;
            updateUsersTable();
            updatePagination();
            updateSummaryCards();
        } else {
            showError('Erro ao carregar usuários');
        }
    } catch (error) {
        console.error('Error loading users:', error);
        showError('Erro ao carregar usuários');
    } finally {
        hideLoading();
    }
}

// Update users table
function updateUsersTable() {
    const tbody = document.getElementById('usersTableBody');
    tbody.innerHTML = '';

    users.forEach(user => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <div class="user-info">
                    <div class="user-avatar">${getInitials(user.name)}</div>
                    <span>${user.name}</span>
                </div>
            </td>
            <td>${user.email}</td>
            <td>${getRoleBadge(user.role)}</td>
            <td>${user.department}</td>
            <td>${getStatusBadge(user.status)}</td>
            <td>${formatDate(user.lastAccess)}</td>
            <td>
                <div class="table-actions">
                    <button class="dynamics-button icon" onclick="editUser(${user.id})" title="Editar">
                        <i class="fluent-edit"></i>
                    </button>
                    <button class="dynamics-button icon" onclick="toggleUserStatus(${user.id})" title="${user.status === 'active' ? 'Desativar' : 'Ativar'}">
                        <i class="fluent-power"></i>
                    </button>
                    <button class="dynamics-button icon" onclick="resetPassword(${user.id})" title="Redefinir Senha">
                        <i class="fluent-key"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Update summary cards
function updateSummaryCards() {
    // These values should come from the API
    document.getElementById('totalUsers').textContent = users.length;
    document.getElementById('activeUsers').textContent = users.filter(u => u.status === 'active').length;
    document.getElementById('inactiveUsers').textContent = users.filter(u => u.status === 'inactive').length;
    document.getElementById('newUsers').textContent = users.filter(u => isNew(u.createdAt)).length;
}

// Update pagination
function updatePagination() {
    document.getElementById('pageInfo').textContent = `Página ${currentPage} de ${totalPages}`;
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
}

// Modal functions
function openNewUserModal() {
    document.getElementById('modalTitle').textContent = 'Novo Usuário';
    document.getElementById('userForm').reset();
    document.getElementById('userModal').classList.add('show');
}

function closeUserModal() {
    document.getElementById('userModal').classList.remove('show');
}

async function saveUser() {
    try {
        const formData = {
            name: document.getElementById('userName').value,
            email: document.getElementById('userEmail').value,
            role: document.getElementById('userRole').value,
            department: document.getElementById('userDepartment').value,
            status: document.getElementById('userStatus').value
        };

        showLoading();

        const response = await fetch('/api/users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            showSuccess('Usuário salvo com sucesso');
            closeUserModal();
            loadUsers();
        } else {
            showError(data.message || 'Erro ao salvar usuário');
        }
    } catch (error) {
        console.error('Error saving user:', error);
        showError('Erro ao salvar usuário');
    } finally {
        hideLoading();
    }
}

// Utility functions
function getInitials(name) {
    return name
        .split(' ')
        .map(n => n[0])
        .join('')
        .toUpperCase()
        .substring(0, 2);
}

function getRoleBadge(role) {
    const badges = {
        admin: { class: 'badge-primary', label: 'Administrador' },
        manager: { class: 'badge-success', label: 'Gerente' },
        user: { class: 'badge-info', label: 'Usuário' }
    };
    const badge = badges[role] || { class: 'badge-secondary', label: role };
    return `<span class="dynamics-badge ${badge.class}">${badge.label}</span>`;
}

function getStatusBadge(status) {
    const badges = {
        active: { class: 'badge-success', label: 'Ativo' },
        inactive: { class: 'badge-danger', label: 'Inativo' }
    };
    const badge = badges[status] || { class: 'badge-secondary', label: status };
    return `<span class="dynamics-badge ${badge.class}">${badge.label}</span>`;
}

function formatDate(date) {
    return new Date(date).toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function isNew(date) {
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
    return new Date(date) > thirtyDaysAgo;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Loading and notifications
function showLoading() {
    // Implement loading indicator
}

function hideLoading() {
    // Hide loading indicator
}

function showSuccess(message) {
    // Implement success notification
}

function showError(message) {
    // Implement error notification
}
