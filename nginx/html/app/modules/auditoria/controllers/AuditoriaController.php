<?php
namespace App\Modules\Auditoria\Controllers;

use App\Core\Controller;
use App\Modules\Auditoria\Models\Auditoria;

class AuditoriaController extends Controller {
    public function relatorio() {
        $query = Auditoria::query();

        // Filtragem por usuário
        if ($this->request->has('usuario')) {
            $usuario = $this->request->get('usuario');
            $query->where('usuario_id', 'LIKE', "%{$usuario}%");
        }

        // Filtragem por data
        if ($this->request->has('data_inicio')) {
            $dataInicio = $this->request->get('data_inicio');
            $query->where('data_hora', '>=', $dataInicio);
        }
        if ($this->request->has('data_fim')) {
            $dataFim = $this->request->get('data_fim');
            $query->where('data_hora', '<=', $dataFim);
        }

        $auditorias = $query->orderBy('data_hora', 'desc')->get();
        return $this->view('auditoria/views/relatorio', ['auditorias' => $auditorias]);
    }

    public function registrarAtividade($acao, $descricao) {
        $auditoria = new Auditoria();
        $auditoria->usuario_id = $this->auth->user()->id; // ID do usuário autenticado
        $auditoria->acao = $acao;
        $auditoria->descricao = $descricao;
        $auditoria->data_hora = now(); // Método para obter a data/hora atual
        $auditoria->save();
    }
}
