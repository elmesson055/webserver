<?php

namespace App\Modules\Portal\Controllers;

use App\Core\Controller;
use App\Core\Services\ExternalApiService;
use App\Modules\Cadastros\Models\Fornecedor;
use App\Modules\Portal\Models\Documento;
use App\Modules\Portal\Models\Notificacao;

class PortalController extends Controller
{
    protected $fornecedor;
    protected $externalApiService;

    public function __construct()
    {
        parent::__construct();
        
        // Verifica se o fornecedor está logado
        if (!$this->session->get('fornecedor_id')) {
            redirect('/portal/login');
        }

        $this->fornecedor = Fornecedor::find($this->session->get('fornecedor_id'));
        $this->externalApiService = new ExternalApiService();
        
        // Compartilha dados comuns com todas as views
        $this->view->share([
            'fornecedor' => $this->fornecedor,
            'unreadMessages' => Notificacao::where('fornecedor_id', $this->fornecedor->id)
                                         ->where('lida', false)
                                         ->count()
        ]);
    }

    public function index()
    {
        $documentos = Documento::where('fornecedor_id', $this->fornecedor->id)->get();
        $documentosTotal = count($documentos);
        $documentosValidos = $documentos->filter(function($doc) {
            return $doc->status === 'Válido';
        })->count();

        $data = [
            'currentPage' => 'dashboard',
            'documentosCount' => $documentosValidos,
            'documentosTotal' => $documentosTotal,
            'status' => $this->fornecedor->status,
            'statusMessage' => $this->getStatusMessage(),
            'pendenciasCount' => $this->getPendenciasCount(),
            'proximaExpiracao' => $this->getProximaExpiracao(),
            'documentosPendentes' => $this->getDocumentosPendentes(),
            'notificacoes' => $this->getNotificacoesRecentes(),
            'documentosRecentes' => $this->getDocumentosRecentes()
        ];

        return $this->view->render('portal/dashboard', $data);
    }

    public function dashboard()
    {
        $fornecedor = $this->getFornecedorAutenticado();
        
        // Atualiza o status financeiro
        $fornecedor->atualizarStatusFinanceiro();

        // Busca as últimas movimentações
        $ultimasMovimentacoes = $fornecedor->movimentacoesFinanceiras()
            ->orderBy('data_vencimento', 'desc')
            ->limit(5)
            ->get();

        // Busca as últimas notificações
        $ultimasNotificacoes = $fornecedor->notificacoes()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Busca os documentos recentes
        $documentosRecentes = $fornecedor->documentos()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Contadores
        $totalDocumentos = $fornecedor->documentos()->count();
        $documentosVencidos = $fornecedor->documentos()
            ->where('data_validade', '<', date('Y-m-d'))
            ->count();
        $documentosPendentes = $fornecedor->documentos()
            ->where('status', 'PENDENTE')
            ->count();

        return view('portal.dashboard', [
            'fornecedor' => $fornecedor,
            'ultimasMovimentacoes' => $ultimasMovimentacoes,
            'ultimasNotificacoes' => $ultimasNotificacoes,
            'documentosRecentes' => $documentosRecentes,
            'totalDocumentos' => $totalDocumentos,
            'documentosVencidos' => $documentosVencidos,
            'documentosPendentes' => $documentosPendentes,
            'currentPage' => 'dashboard'
        ]);
    }

    public function documentos()
    {
        $categorias = $this->getCategoriaComDocumentos();
        
        return $this->view->render('portal/documentos', [
            'currentPage' => 'documentos',
            'categorias' => $categorias
        ]);
    }

    public function documentosPorCategoria($categoriaId)
    {
        $documentos = Documento::where('categoria_id', $categoriaId)
                              ->where('fornecedor_id', $this->fornecedor->id)
                              ->get();
        
        return response()->json($documentos);
    }

    public function uploadDocumento()
    {
        try {
            $documento = new Documento();
            $documento->fornecedor_id = $this->fornecedor->id;
            $documento->categoria_id = request()->post('categoria');
            $documento->nome = request()->post('documento');
            $documento->validade = request()->post('validade');
            $documento->observacoes = request()->post('observacoes');

            // Upload do arquivo
            $arquivo = request()->file('arquivo');
            if ($arquivo && $arquivo->isValid()) {
                $path = $arquivo->store('documentos/' . $this->fornecedor->id);
                $documento->arquivo = $path;
            }

            $documento->save();

            // Cria notificação
            $notificacao = new Notificacao();
            $notificacao->fornecedor_id = $this->fornecedor->id;
            $notificacao->titulo = 'Novo documento enviado';
            $notificacao->mensagem = "O documento {$documento->nome} foi enviado com sucesso.";
            $notificacao->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao fazer upload do documento: ' . $e->getMessage()
            ]);
        }
    }

    public function downloadDocumento($id)
    {
        $documento = Documento::find($id);
        
        if (!$documento || $documento->fornecedor_id !== $this->fornecedor->id) {
            abort(404);
        }

        return response()->download(storage_path('app/' . $documento->arquivo));
    }

    public function visualizarDocumento($id)
    {
        $documento = Documento::find($id);
        
        if (!$documento || $documento->fornecedor_id !== $this->fornecedor->id) {
            abort(404);
        }

        return response()->file(storage_path('app/' . $documento->arquivo));
    }

    protected function getStatusMessage()
    {
        switch ($this->fornecedor->status) {
            case 'Ativo':
                return 'Cadastro completo e documentação em dia';
            case 'Pendente':
                return 'Existem documentos pendentes de envio';
            case 'Suspenso':
                return 'Documentação vencida ou irregular';
            default:
                return 'Status não definido';
        }
    }

    protected function getPendenciasCount()
    {
        return Documento::where('fornecedor_id', $this->fornecedor->id)
                       ->where(function($query) {
                           $query->whereNull('arquivo')
                                 ->orWhere('validade', '<', now());
                       })
                       ->count();
    }

    protected function getProximaExpiracao()
    {
        $documento = Documento::where('fornecedor_id', $this->fornecedor->id)
                            ->whereNotNull('arquivo')
                            ->where('validade', '>', now())
                            ->orderBy('validade')
                            ->first();

        return $documento ? "Próximo vencimento em " . $documento->validade->format('d/m/Y') : 'Nenhum documento próximo do vencimento';
    }

    protected function getDocumentosPendentes()
    {
        return Documento::where('fornecedor_id', $this->fornecedor->id)
                       ->where(function($query) {
                           $query->whereNull('arquivo')
                                 ->orWhere('validade', '<', now());
                       })
                       ->get()
                       ->map(function($doc) {
                           $doc->dias_para_vencer = $doc->validade ? $doc->validade->diffInDays(now()) : null;
                           return $doc;
                       });
    }

    protected function getNotificacoesRecentes()
    {
        return Notificacao::where('fornecedor_id', $this->fornecedor->id)
                         ->orderBy('created_at', 'desc')
                         ->limit(5)
                         ->get();
    }

    protected function getDocumentosRecentes()
    {
        return Documento::where('fornecedor_id', $this->fornecedor->id)
                       ->whereNotNull('arquivo')
                       ->orderBy('updated_at', 'desc')
                       ->limit(5)
                       ->get()
                       ->map(function($doc) {
                           $doc->status_class = $this->getStatusClass($doc);
                           return $doc;
                       });
    }

    protected function getStatusClass($documento)
    {
        if (!$documento->arquivo) {
            return 'danger';
        }

        if ($documento->validade < now()) {
            return 'danger';
        }

        if ($documento->validade->diffInDays(now()) <= 30) {
            return 'warning';
        }

        return 'success';
    }

    protected function getCategoriaComDocumentos()
    {
        $categorias = [
            ['id' => 1, 'nome' => 'Documentos Societários'],
            ['id' => 2, 'nome' => 'Documentos Fiscais'],
            ['id' => 3, 'nome' => 'Certidões'],
            ['id' => 4, 'nome' => 'Documentos Técnicos'],
            ['id' => 5, 'nome' => 'Outros']
        ];

        foreach ($categorias as &$categoria) {
            $categoria['documentos'] = Documento::where('fornecedor_id', $this->fornecedor->id)
                                              ->where('categoria_id', $categoria['id'])
                                              ->get()
                                              ->map(function($doc) {
                                                  $doc->status_class = $this->getStatusClass($doc);
                                                  return $doc;
                                              });
        }

        return $categorias;
    }

    public function loginForm()
    {
        if ($this->session->get('fornecedor_id')) {
            return redirect('/portal');
        }
        return $this->view->render('portal/login');
    }

    public function login()
    {
        $cnpj = request()->post('cnpj');
        $senha = request()->post('senha');
        $lembrar = request()->post('lembrar');

        $fornecedor = Fornecedor::where('cnpj', $cnpj)->first();

        if (!$fornecedor || !password_verify($senha, $fornecedor->senha)) {
            return redirect()->back()->with('error', 'CNPJ ou senha inválidos');
        }

        $this->session->put('fornecedor_id', $fornecedor->id);
        
        if ($lembrar) {
            $this->session->put('remember_token', $fornecedor->remember_token);
        }

        return redirect('/portal');
    }

    public function logout()
    {
        $this->session->forget('fornecedor_id');
        $this->session->forget('remember_token');
        return redirect('/portal/login');
    }

    public function dados()
    {
        return $this->view->render('portal/dados', [
            'currentPage' => 'dados'
        ]);
    }

    public function atualizarDados()
    {
        $data = request()->only([
            'razao_social',
            'nome_fantasia',
            'inscricao_estadual',
            'inscricao_municipal',
            'cep',
            'logradouro',
            'numero',
            'complemento',
            'bairro',
            'cidade',
            'estado',
            'telefone',
            'email',
            'contato'
        ]);

        $this->fornecedor->update($data);

        // Atualiza senha se fornecida
        $senhaAtual = request()->post('senha_atual');
        $novaSenha = request()->post('nova_senha');

        if ($senhaAtual && $novaSenha) {
            if (!password_verify($senhaAtual, $this->fornecedor->senha)) {
                return redirect()->back()->with('error', 'Senha atual incorreta');
            }

            $this->fornecedor->senha = password_hash($novaSenha, PASSWORD_DEFAULT);
            $this->fornecedor->save();
        }

        return redirect()->back()->with('success', 'Dados atualizados com sucesso');
    }

    public function notificacoes()
    {
        $notificacoes = Notificacao::where('fornecedor_id', $this->fornecedor->id)
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(10);

        return $this->view->render('portal/notificacoes', [
            'currentPage' => 'notificacoes',
            'notificacoes' => $notificacoes
        ]);
    }

    public function marcarNotificacaoComoLida($id)
    {
        $notificacao = Notificacao::where('fornecedor_id', $this->fornecedor->id)
                                 ->where('id', $id)
                                 ->first();

        if ($notificacao) {
            $notificacao->marcarComoLida();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function marcarTodasNotificacoesComoLidas()
    {
        Notificacao::where('fornecedor_id', $this->fornecedor->id)
                   ->where('lida', false)
                   ->update(['lida' => true]);

        return response()->json(['success' => true]);
    }
}
