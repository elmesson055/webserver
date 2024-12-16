// Dynamics 365 Users Management JavaScript

// Global variables for pagination
let currentPage = 1;
let totalPages = 1;
let itemsPerPage = 10;
let users = [];

// Initialize the page
document.addEventListener('DOMContentLoaded', () => {
    loadUsers();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    // Search input
    document.getElementById('searchUsers').addEventListener('input', debounce(handleSearch, 300));
    
    // Filters
    document.getElementById('statusFilter').addEventListener('change', handleFilters);
    document.getElementById('roleFilter').addEventListener('change', handleFilters);
    document.getElementById('deptFilter').addEventListener('change', handleFilters);
    
    // Pagination
    document.getElementById('prevPage').addEventListener('click', () => changePage(-1));
    document.getElementById('nextPage').addEventListener('click', () => changePage(1));
}

// Load users from the API
async function loadUsers() {
    try {
        showLoading();
        const response = await fetch('/app/modules/usuarios/api.php?action=list');
        const data = await response.json();
        
        if (data.success) {
            users = data.users;
            updateSummaryCards(data.summary);
            renderUsers();
        } else {
            showNotification('error', 'Erro ao carregar usuários: ' + data.message);
        }
    } catch (error) {
        showNotification('error', 'Erro ao carregar usuários: ' + error.message);
    } finally {
        hideLoading();
    }
}

// Update summary cards with current statistics
function updateSummaryCards(summary) {
    document.getElementById('totalUsers').textContent = summary.total || 0;
    document.getElementById('activeUsers').textContent = summary.active || 0;
    document.getElementById('inactiveUsers').textContent = summary.inactive || 0;
    document.getElementById('newUsers').textContent = summary.new || 0;
}

// Render users table with current filters and pagination
function renderUsers() {
    const filteredUsers = filterUsers();
    totalPages = Math.ceil(filteredUsers.length / itemsPerPage);
    
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageUsers = filteredUsers.slice(start, end);
    
    const tbody = document.getElementById('usersTableBody');
    tbody.innerHTML = '';
    
    pageUsers.forEach(user => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${formatRole(user.role)}</td>
            <td>${formatDepartment(user.department)}</td>
            <td>
                <span class="status-badge ${user.status}">
                    ${formatStatus(user.status)}
                </span>
            </td>
            <td>${formatDate(user.last_access)}</td>
            <td class="actions">
                <button class="dynamics-button icon" onclick="editUser(${user.id})" title="Editar">
                    <i class="ms-Icon ms-Icon--Edit"></i>
                </button>
                <button class="dynamics-button icon" onclick="deleteUser(${user.id})" title="Excluir">
                    <i class="ms-Icon ms-Icon--Delete"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
    
    updatePagination();
}

// Filter users based on current filter values
function filterUsers() {
    const statusFilter = document.getElementById('statusFilter').value;
    const roleFilter = document.getElementById('roleFilter').value;
    const deptFilter = document.getElementById('deptFilter').value;
    const searchTerm = document.getElementById('searchUsers').value.toLowerCase();
    
    return users.filter(user => {
        const matchesStatus = !statusFilter || user.status === statusFilter;
        const matchesRole = !roleFilter || user.role === roleFilter;
        const matchesDept = !deptFilter || user.department === deptFilter;
        const matchesSearch = !searchTerm || 
            user.name.toLowerCase().includes(searchTerm) ||
            user.email.toLowerCase().includes(searchTerm);
        
        return matchesStatus && matchesRole && matchesDept && matchesSearch;
    });
}

// Handle search input
function handleSearch() {
    currentPage = 1;
    renderUsers();
}

// Handle filter changes
function handleFilters() {
    currentPage = 1;
    renderUsers();
}

// Change current page
function changePage(delta) {
    const newPage = currentPage + delta;
    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        renderUsers();
    }
}

// Update pagination controls
function updatePagination() {
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = currentPage === totalPages;
    document.getElementById('pageInfo').textContent = `Página ${currentPage} de ${totalPages}`;
}

// Modal functions
function openNewUserModal() {
    document.getElementById('modalTitle').textContent = 'Novo Usuário';
    document.getElementById('userForm').reset();
    document.getElementById('userModal').classList.add('active');
}

function openEditUserModal(user) {
    document.getElementById('modalTitle').textContent = 'Editar Usuário';
    
    // Fill form with user data
    document.getElementById('userName').value = user.name;
    document.getElementById('userEmail').value = user.email;
    document.getElementById('userRole').value = user.role;
    document.getElementById('userDepartment').value = user.department;
    document.getElementById('userStatus').value = user.status;
    
    document.getElementById('userModal').classList.add('active');
}

function closeUserModal() {
    document.getElementById('userModal').classList.remove('active');
}

// Save user (create or update)
async function saveUser() {
    const form = document.getElementById('userForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const userData = {
        name: document.getElementById('userName').value,
        email: document.getElementById('userEmail').value,
        role: document.getElementById('userRole').value,
        department: document.getElementById('userDepartment').value,
        status: document.getElementById('userStatus').value
    };
    
    try {
        showLoading();
        const response = await fetch('/app/modules/usuarios/api.php?action=save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        });
        
        const data = await response.json();
        if (data.success) {
            showNotification('success', 'Usuário salvo com sucesso!');
            closeUserModal();
            loadUsers();
        } else {
            showNotification('error', 'Erro ao salvar usuário: ' + data.message);
        }
    } catch (error) {
        showNotification('error', 'Erro ao salvar usuário: ' + error.message);
    } finally {
        hideLoading();
    }
}

// Delete user
async function deleteUser(userId) {
    if (!confirm('Tem certeza que deseja excluir este usuário?')) {
        return;
    }
    
    try {
        showLoading();
        const response = await fetch(`/app/modules/usuarios/api.php?action=delete&id=${userId}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        if (data.success) {
            showNotification('success', 'Usuário excluído com sucesso!');
            loadUsers();
        } else {
            showNotification('error', 'Erro ao excluir usuário: ' + data.message);
        }
    } catch (error) {
        showNotification('error', 'Erro ao excluir usuário: ' + error.message);
    } finally {
        hideLoading();
    }
}

// Utility functions
function formatRole(role) {
    const roles = {
        admin: 'Administrador',
        manager: 'Gerente',
        user: 'Usuário'
    };
    return roles[role] || role;
}

function formatDepartment(dept) {
    const departments = {
        ti: 'TI',
        rh: 'RH',
        financeiro: 'Financeiro',
        operacional: 'Operacional'
    };
    return departments[dept] || dept;
}

function formatStatus(status) {
    const statuses = {
        active: 'Ativo',
        inactive: 'Inativo'
    };
    return statuses[status] || status;
}

function formatDate(date) {
    if (!date) return '-';
    return new Date(date).toLocaleString('pt-BR');
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

// Loading indicator
function showLoading() {
    document.querySelector('.dynamics-loading').classList.add('active');
}

function hideLoading() {
    document.querySelector('.dynamics-loading').classList.remove('active');
}

// Notification system
function showNotification(type, message) {
    const notification = document.createElement('div');
    notification.className = `dynamics-notification ${type}`;
    notification.innerHTML = `
        <i class="ms-Icon ms-Icon--${type === 'success' ? 'Completed' : 'Error'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
