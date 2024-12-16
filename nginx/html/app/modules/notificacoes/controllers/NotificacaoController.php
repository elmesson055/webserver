<?php
namespace App\Modules\Notificacoes\Controllers;

use App\Core\Controller;
use App\Modules\Notificacoes\Models\Notificacao;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Memcached;

class NotificacaoController extends Controller {
    private $cache;

    public function __construct() {
        $this->cache = new Memcached();
        $this->cache->addServer('localhost', 11211);
    }

    public function enviarEmail($usuarioEmail, $mensagem) {
        $mail = new PHPMailer(true);
        try {
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Defina o servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com'; // Seu e-mail
            $mail->Password = 'your_password'; // Sua senha
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Remetente e destinatário
            $mail->setFrom('from@example.com', 'Nome');
            $mail->addAddress($usuarioEmail);

            // Conteúdo do e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Notificação Importante';
            $mail->Body    = $mensagem;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function registrarNotificacao($usuarioId, $mensagem) {
        $notificacao = new Notificacao();
        $notificacao->usuario_id = $usuarioId;
        $notificacao->mensagem = $mensagem;
        $notificacao->status = 'enviada';
        $notificacao->data_hora = now(); // Método para obter a data/hora atual
        $notificacao->save();
    }

    public function listar() {
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 10;
        $categoria = $_GET['categoria'] ?? null;
        $status = $_GET['status'] ?? null;
        $dataInicio = $_GET['data_inicio'] ?? null;
        $dataFim = $_GET['data_fim'] ?? null;

        $cacheKey = "notificacoes_" . md5(json_encode($_GET));
        $result = $this->cache->get($cacheKey);

        if (!$result) {
            $query = Notificacao::query();
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('titulo', 'LIKE', "%{$search}%")
                      ->orWhere('mensagem', 'LIKE', "%{$search}%");
                });
            }

            if ($categoria) {
                $query->where('categoria', $categoria);
            }

            if ($status) {
                $query->where('status', $status);
            }

            if ($dataInicio) {
                $query->where('data_hora', '>=', $dataInicio);
            }

            if ($dataFim) {
                $query->where('data_hora', '<=', $dataFim);
            }

            $total = $query->count();
            $notificacoes = $query->orderBy('data_hora', 'desc')
                                 ->offset(($page - 1) * $perPage)
                                 ->limit($perPage)
                                 ->get();

            $totalPages = ceil($total / $perPage);
            
            $result = [
                'notificacoes' => $notificacoes,
                'total' => $total,
                'totalPages' => $totalPages
            ];

            $this->cache->set($cacheKey, $result, 300); // Cache por 5 minutos
        }

        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }

        extract($result);
        include_once '../views/index.php';
    }

    public function filtrar() {
        $this->listar();
    }

    public function exportarCSV() {
        $notificacoes = Notificacao::orderBy('data_hora', 'desc')->get();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="notificacoes.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Cabeçalho do CSV
        fputcsv($output, ['ID', 'Título', 'Mensagem', 'Status', 'Data/Hora', 'Data Leitura', 'Categoria']);
        
        // Dados
        foreach ($notificacoes as $notificacao) {
            fputcsv($output, [
                $notificacao->id,
                $notificacao->titulo,
                $notificacao->mensagem,
                $notificacao->status,
                $notificacao->data_hora,
                $notificacao->data_leitura,
                $notificacao->categoria
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function visualizar($id) {
        $notificacao = Notificacao::find($id);
        if (!$notificacao) {
            $_SESSION['error'] = 'Notificação não encontrada.';
            header('Location: /notificacoes');
            exit;
        }
        include_once '../views/visualizar.php';
    }

    public function marcarComoLida($id) {
        $notificacao = Notificacao::find($id);
        if ($notificacao) {
            $notificacao->status = 'Lida';
            $notificacao->data_leitura = date('Y-m-d H:i:s');
            $notificacao->save();
            
            // Limpar cache
            $this->cache->delete("notificacoes_*");
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
            
            $_SESSION['success'] = 'Notificação marcada como lida.';
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Notificação não encontrada']);
                exit;
            }
            
            $_SESSION['error'] = 'Notificação não encontrada.';
        }
        header('Location: /notificacoes');
        exit;
    }

    public function excluir($id) {
        $notificacao = Notificacao::find($id);
        if ($notificacao) {
            $notificacao->delete();
            
            // Limpar cache
            $this->cache->delete("notificacoes_*");
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
            
            $_SESSION['success'] = 'Notificação excluída com sucesso.';
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Notificação não encontrada']);
                exit;
            }
            
            $_SESSION['error'] = 'Notificação não encontrada.';
        }
        header('Location: /notificacoes');
        exit;
    }

    public function criar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validação
            $erros = [];
            if (empty($_POST['titulo'])) {
                $erros[] = 'O título é obrigatório';
            }
            if (empty($_POST['mensagem'])) {
                $erros[] = 'A mensagem é obrigatória';
            }
            
            if (!empty($erros)) {
                $_SESSION['errors'] = $erros;
                $_SESSION['old'] = $_POST;
                header('Location: /notificacoes/criar');
                exit;
            }

            $notificacao = new Notificacao();
            $notificacao->titulo = $_POST['titulo'];
            $notificacao->mensagem = $_POST['mensagem'];
            $notificacao->categoria = $_POST['categoria'] ?? 'Geral';
            $notificacao->status = 'Não lida';
            $notificacao->data_hora = date('Y-m-d H:i:s');
            $notificacao->save();

            // Limpar cache
            $this->cache->delete("notificacoes_*");

            // Notificar via WebSocket
            $this->notificarWebSocket($notificacao);

            $_SESSION['success'] = 'Notificação criada com sucesso.';
            header('Location: /notificacoes');
            exit;
        }
        include_once '../views/criar.php';
    }

    private function notificarWebSocket($notificacao) {
        $client = new \WebSocket\Client("ws://localhost:8080");
        try {
            $client->send(json_encode([
                'id' => $notificacao->id,
                'titulo' => $notificacao->titulo,
                'mensagem' => $notificacao->mensagem,
                'data_hora' => $notificacao->data_hora,
                'categoria' => $notificacao->categoria
            ]));
        } catch (\Exception $e) {
            error_log("Erro ao enviar notificação via WebSocket: " . $e->getMessage());
        } finally {
            $client->close();
        }
    }

    public function regras() {
        $regras = NotificacaoRegra::orderBy('id', 'desc')->get();
        include_once '../views/regras.php';
    }

    public function historico() {
        $historico = Notificacao::where('status', 'Lida')
                               ->orderBy('data_leitura', 'desc')
                               ->get();
        include_once '../views/historico.php';
    }

    public function buscarNotificacoes() {
        // Aqui você deve implementar a lógica para buscar as notificações no banco de dados
        // Exemplo:
        return Notificacao::orderBy('data_hora', 'desc')->get();
    }
}
