<?php
namespace App\Modules\Usuarios\Controllers;

use App\Modules\Usuarios\Models\Usuario;
use App\Core\Database;

class UsuarioController {
    private $usuarioModel;
    
    public function __construct() {
        $db = Database::getInstance();
        $this->usuarioModel = new Usuario($db);
    }
    
    public function index() {
        // Verificar se o usuário está logado e tem permissão
        if (!isset($_SESSION['user_id'])) {
            header('Location: /app/modules/auth/views/login.php');
            exit();
        }
        
        $filtros = [
            'busca' => $_GET['busca'] ?? '',
            'status' => $_GET['status'] ?? ''
        ];
        
        $usuarios = $this->usuarioModel->listarUsuarios($filtros);
        $funcoes = $this->usuarioModel->listarFuncoes();
        
        // Incluir a view
        include __DIR__ . '/../views/index.php';
    }
    
    public function criar() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Método não permitido');
            }
            
            // Validar dados
            $dados = $this->validarDados($_POST);
            
            // Criar usuário
            if ($this->usuarioModel->criarUsuario($dados)) {
                $_SESSION['success'] = 'Usuário criado com sucesso!';
                echo json_encode(['success' => true]);
            } else {
                throw new \Exception('Erro ao criar usuário');
            }
            
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    public function atualizar($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Método não permitido');
            }
            
            // Validar dados
            $dados = $this->validarDados($_POST, false);
            
            // Atualizar usuário
            if ($this->usuarioModel->atualizarUsuario($id, $dados)) {
                $_SESSION['success'] = 'Usuário atualizado com sucesso!';
                echo json_encode(['success' => true]);
            } else {
                throw new \Exception('Erro ao atualizar usuário');
            }
            
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    public function excluir($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Método não permitido');
            }
            
            if ($this->usuarioModel->excluirUsuario($id)) {
                $_SESSION['success'] = 'Usuário excluído com sucesso!';
                echo json_encode(['success' => true]);
            } else {
                throw new \Exception('Erro ao excluir usuário');
            }
            
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    private function validarDados($dados, $validarSenha = true) {
        $erros = [];
        
        if (empty($dados['nome'])) {
            $erros[] = 'Nome é obrigatório';
        }
        
        if (empty($dados['email'])) {
            $erros[] = 'E-mail é obrigatório';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'E-mail inválido';
        }
        
        if ($validarSenha && empty($dados['senha'])) {
            $erros[] = 'Senha é obrigatória';
        }
        
        if (empty($dados['funcao_id'])) {
            $erros[] = 'Função é obrigatória';
        }
        
        if (!empty($erros)) {
            throw new \Exception(implode(', ', $erros));
        }
        
        return $dados;
    }
}
