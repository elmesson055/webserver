<?php

namespace App\Core;

class Controller {
    protected $model;
    protected $viewPath;
    protected $itemsPerPage = 10;

    /**
     * Construtor da classe Controller
     */
    public function __construct() {
        // Garante que o bootstrap foi carregado
        if (!defined('BASE_PATH')) {
            require_once dirname(__DIR__, 2) . '/app/bootstrap.php';
        }

        // Inicia a sessão se ainda não foi iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Carrega um modelo
     * @param string $model Nome do modelo a ser carregado
     * @return Model
     */
    protected function model($model) {
        $modelClass = "\\App\\Models\\{$model}";
        return new $modelClass();
    }

    /**
     * Renderiza uma view
     * @param string $view Nome da view
     * @param array $data Dados para a view
     * @return void
     */
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = APP_PATH . "/Views/{$view}.php";
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View {$view} não encontrada");
        }
        
        ob_start();
        require_once $viewFile;
        $content = ob_get_clean();
        
        require_once PUBLIC_PATH . '/includes/header.php';
        echo $content;
        require_once PUBLIC_PATH . '/includes/footer.php';
    }

    /**
     * Retorna uma resposta JSON
     * @param mixed $data Dados a serem retornados
     * @param int $statusCode Código de status HTTP
     * @return void
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redireciona para uma URL
     * @param string $url URL para redirecionamento
     * @return void
     */
    protected function redirect($url) {
        if (!headers_sent()) {
            header("Location: /{$url}");
            exit;
        } else {
            echo '<script>window.location.href="/' . $url . '";</script>';
            exit;
        }
    }

    /**
     * Redireciona de volta para a página anterior
     * @return void
     */
    protected function back() {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * Valida dados com base em regras
     * @param array $data Dados a serem validados
     * @param array $rules Regras de validação
     * @return array Array com erros de validação
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $ruleString) {
            $fieldRules = explode('|', $ruleString);
            
            foreach ($fieldRules as $rule) {
                // Separa regra e parâmetros
                $params = [];
                if (strpos($rule, ':') !== false) {
                    list($rule, $paramStr) = explode(':', $rule);
                    $params = explode(',', $paramStr);
                }

                // Aplica a regra
                switch ($rule) {
                    case 'required':
                        if (!isset($data[$field]) || empty($data[$field])) {
                            $errors[$field][] = "O campo {$field} é obrigatório";
                        }
                        break;

                    case 'email':
                        if (!empty($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = "O campo {$field} deve ser um e-mail válido";
                        }
                        break;

                    case 'min':
                        if (!empty($data[$field]) && strlen($data[$field]) < $params[0]) {
                            $errors[$field][] = "O campo {$field} deve ter no mínimo {$params[0]} caracteres";
                        }
                        break;

                    case 'max':
                        if (!empty($data[$field]) && strlen($data[$field]) > $params[0]) {
                            $errors[$field][] = "O campo {$field} deve ter no máximo {$params[0]} caracteres";
                        }
                        break;

                    case 'unique':
                        if (!empty($data[$field])) {
                            $table = $params[0];
                            $column = $params[1] ?? $field;
                            $except = $params[2] ?? null;

                            $query = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
                            $bindings = [$data[$field]];

                            if ($except) {
                                $query .= " AND id != ?";
                                $bindings[] = $except;
                            }

                            $db = Database::getInstance();
                            $stmt = $db->prepare($query);
                            $stmt->execute($bindings);
                            $result = $stmt->fetch();

                            if ($result['count'] > 0) {
                                $errors[$field][] = "O valor do campo {$field} já está em uso";
                            }
                        }
                        break;

                    case 'password_strength':
                        if (!empty($data[$field])) {
                            if (!preg_match('/[A-Z]/', $data[$field])) {
                                $errors[$field][] = "A senha deve conter pelo menos uma letra maiúscula";
                            }
                            if (!preg_match('/[a-z]/', $data[$field])) {
                                $errors[$field][] = "A senha deve conter pelo menos uma letra minúscula";
                            }
                            if (!preg_match('/[0-9]/', $data[$field])) {
                                $errors[$field][] = "A senha deve conter pelo menos um número";
                            }
                            if (!preg_match('/[^A-Za-z0-9]/', $data[$field])) {
                                $errors[$field][] = "A senha deve conter pelo menos um caractere especial";
                            }
                        }
                        break;

                    case 'same':
                        if (!empty($data[$field]) && (!isset($data[$params[0]]) || $data[$field] !== $data[$params[0]])) {
                            $errors[$field][] = "Os campos {$field} e {$params[0]} devem ser iguais";
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }

    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    protected function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    protected function index() {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        
        if ($search && method_exists($this->model, 'getSearchFields')) {
            $result = $this->model->search($search, $this->model->getSearchFields(), $page, $this->itemsPerPage);
        } else {
            $result = $this->model->paginate($page, $this->itemsPerPage);
        }
        
        return $this->view($this->viewPath . '/index', [
            'items' => $result['data'],
            'pagination' => [
                'total' => $result['total'],
                'current_page' => $result['current_page'],
                'last_page' => $result['last_page']
            ],
            'search' => $search
        ]);
    }

    protected function create() {
        return $this->view($this->viewPath . '/create');
    }

    protected function store() {
        try {
            $data = $_POST;
            
            if (method_exists($this, 'validateStore')) {
                $errors = $this->validateStore($data);
                if (!empty($errors)) {
                    if ($this->isAjax()) {
                        return $this->json(['errors' => $errors], 422);
                    }
                    $_SESSION['errors'] = $errors;
                    $_SESSION['old'] = $data;
                    return $this->back();
                }
            }
            
            $id = $this->model->create($data);
            
            if ($this->isAjax()) {
                return $this->json(['message' => 'Registro criado com sucesso', 'id' => $id]);
            }
            
            $this->setFlash('success', 'Registro criado com sucesso');
            return $this->redirect($this->viewPath);
            
        } catch (\Exception $e) {
            if ($this->isAjax()) {
                return $this->json(['message' => $e->getMessage()], 500);
            }
            $this->setFlash('error', $e->getMessage());
            return $this->back();
        }
    }

    protected function edit($id) {
        $item = $this->model->find($id);
        
        if (!$item) {
            $this->setFlash('error', 'Registro não encontrado');
            return $this->redirect($this->viewPath);
        }
        
        return $this->view($this->viewPath . '/edit', ['item' => $item]);
    }

    protected function update($id) {
        try {
            $data = $_POST;
            
            if (method_exists($this, 'validateUpdate')) {
                $errors = $this->validateUpdate($data, $id);
                if (!empty($errors)) {
                    if ($this->isAjax()) {
                        return $this->json(['errors' => $errors], 422);
                    }
                    $_SESSION['errors'] = $errors;
                    $_SESSION['old'] = $data;
                    return $this->back();
                }
            }
            
            $this->model->update($id, $data);
            
            if ($this->isAjax()) {
                return $this->json(['message' => 'Registro atualizado com sucesso']);
            }
            
            $this->setFlash('success', 'Registro atualizado com sucesso');
            return $this->redirect($this->viewPath);
            
        } catch (\Exception $e) {
            if ($this->isAjax()) {
                return $this->json(['message' => $e->getMessage()], 500);
            }
            $this->setFlash('error', $e->getMessage());
            return $this->back();
        }
    }

    protected function destroy($id) {
        try {
            $this->model->delete($id);
            
            if ($this->isAjax()) {
                return $this->json(['message' => 'Registro excluído com sucesso']);
            }
            
            $this->setFlash('success', 'Registro excluído com sucesso');
            return $this->redirect($this->viewPath);
            
        } catch (\Exception $e) {
            if ($this->isAjax()) {
                return $this->json(['message' => $e->getMessage()], 500);
            }
            $this->setFlash('error', $e->getMessage());
            return $this->back();
        }
    }
}
