<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UsersController extends Controller
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
        
        // Verifica permissão global para o controller
        if (!hasPermission('view_users')) {
            $_SESSION['message'] = "Você não tem permissão para acessar esta página.";
            $_SESSION['message_type'] = "danger";
            $this->redirect('/dashboard');
        }
    }

    /**
     * Lista todos os usuários
     */
    public function index()
    {
        $data = [
            'title' => 'Gestão de Usuários',
            'departments' => getDepartments(),
            'roles' => getRoles()
        ];
        
        return $this->view('config/usuarios/index', $data);
    }

    /**
     * Exibe formulário de criação
     */
    public function create()
    {
        if (!hasPermission('manage_users')) {
            $_SESSION['message'] = "Você não tem permissão para criar usuários.";
            $_SESSION['message_type'] = "danger";
            $this->redirect('/config/usuarios');
        }

        $data = [
            'title' => 'Novo Usuário',
            'departments' => getDepartments(),
            'roles' => getRoles()
        ];
        
        return $this->view('config/usuarios/form', $data);
    }

    /**
     * Exibe formulário de edição
     */
    public function edit($id)
    {
        if (!hasPermission('manage_users')) {
            $_SESSION['message'] = "Você não tem permissão para editar usuários.";
            $_SESSION['message_type'] = "danger";
            $this->redirect('/config/usuarios');
        }
        
        $user = $this->model->find($id);
        if (!$user) {
            $_SESSION['message'] = "Usuário não encontrado.";
            $_SESSION['message_type'] = "danger";
            $this->redirect('/config/usuarios');
        }
        
        $data = [
            'title' => 'Editar Usuário',
            'user' => $user,
            'departments' => getDepartments(),
            'roles' => getRoles()
        ];
        
        return $this->view('config/usuarios/form', $data);
    }

    /**
     * Lista usuários (API)
     */
    public function list()
    {
        try {
            $filters = [
                'search' => $_GET['search'] ?? null,
                'department' => $_GET['department'] ?? null,
                'role' => $_GET['role'] ?? null,
                'status' => isset($_GET['status']) ? (int)$_GET['status'] : null
            ];
            
            $users = $this->model->getAll($filters);
            
            foreach ($users as &$user) {
                $user['status_badge'] = $user['active'] 
                    ? '<span class="badge badge-success">Ativo</span>'
                    : '<span class="badge badge-danger">Inativo</span>';
                    
                $user['actions'] = $this->generateActions($user);
            }
            
            echo json_encode([
                'status' => 'success',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Erro ao buscar usuários: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cria novo usuário (API)
     */
    public function store()
    {
        try {
            if (!hasPermission('manage_users')) {
                throw new \Exception("Sem permissão para criar usuários");
            }

            $data = $this->getRequestData();
            $userId = $this->model->create($data);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Usuário criado com sucesso',
                'data' => ['id' => $userId]
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Atualiza usuário existente (API)
     */
    public function update($id)
    {
        try {
            if (!hasPermission('manage_users')) {
                throw new \Exception("Sem permissão para editar usuários");
            }

            $data = $this->getRequestData();
            $this->model->update($id, $data);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Usuário atualizado com sucesso'
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Alterna status do usuário (API)
     */
    public function toggleStatus($id)
    {
        try {
            if (!hasPermission('manage_users')) {
                throw new \Exception("Sem permissão para alterar status de usuários");
            }

            $this->model->toggleStatus($id);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Status alterado com sucesso'
            ]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Gera HTML das ações disponíveis para cada usuário
     */
    protected function generateActions($user)
    {
        if (!hasPermission('manage_users')) {
            return '';
        }

        $actions = '<div class="btn-group">';
        
        // Botão de editar
        $actions .= sprintf(
            '<a href="/config/usuarios/editar/%d" class="btn btn-sm btn-info" title="Editar">
                <i class="fas fa-edit"></i>
            </a>',
            $user['id']
        );
        
        // Botão de alternar status
        $actions .= sprintf(
            '<button type="button" class="btn btn-sm btn-%s toggle-status" data-id="%d" title="%s">
                <i class="fas fa-%s"></i>
            </button>',
            $user['active'] ? 'warning' : 'success',
            $user['id'],
            $user['active'] ? 'Desativar' : 'Ativar',
            $user['active'] ? 'ban' : 'check'
        );
        
        $actions .= '</div>';
        
        return $actions;
    }

    /**
     * Obtém e sanitiza dados da requisição
     */
    protected function getRequestData()
    {
        $data = $_POST;
        
        // Remove campos vazios
        return array_filter($data, function($value) {
            return $value !== '';
        });
    }
}