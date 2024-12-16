<?php
namespace App\Modules\Notificacoes\Controllers;

use App\Core\Controller;
use App\Modules\Notificacoes\Models\RegrasNotificacao;

class RegrasNotificacaoController extends Controller {
    public function index() {
        $regras = RegrasNotificacao::where('usuario_id', $this->auth->user()->id)->get();
        return $this->view('notificacoes/views/regras', ['regras' => $regras]);
    }

    public function criar() {
        if ($this->request->isPost()) {
            $tipoEvento = $this->request->get('tipo_evento');
            $mensagem = $this->request->get('mensagem');
            $status = $this->request->get('status', 'ativa'); // Padrão para ativa

            $regra = new RegrasNotificacao();
            $regra->usuario_id = $this->auth->user()->id;
            $regra->tipo_evento = $tipoEvento;
            $regra->mensagem = $mensagem;
            $regra->status = $status;
            $regra->save();

            // Redirecionar ou retornar resposta
            return $this->redirect('/notificacoes/regras');
        }
        return $this->view('notificacoes/views/criar');
    }
}
