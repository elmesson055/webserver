<?php
namespace App\Modules\Auditoria\Controllers;

use App\Core\Controller;
use App\Modules\Auditoria\Models\Alteracao;
use App\Modules\Auditoria\Controllers\VersaoController;

class AlteracaoController extends Controller {
    public function registrar($modelo, $registroId, $campo, $valorAntigo, $valorNovo) {
        // Registrar a alteração
        $this->registrarAlteracao($modelo, $registroId, $campo, $valorAntigo, $valorNovo);
        
        // Registrar a nova versão
        $dados = [$campo => $valorNovo]; // Apenas o campo alterado
        $versaoController = new VersaoController();
        $versaoController->registrar($modelo, $registroId, $dados);
    }

    public function registrarAlteracao($modelo, $registroId, $campo, $valorAntigo, $valorNovo) {
        $alteracao = new Alteracao();
        $alteracao->usuario_id = $this->auth->user()->id; // ID do usuário autenticado
        $alteracao->modelo = $modelo;
        $alteracao->registro_id = $registroId;
        $alteracao->campo = $campo;
        $alteracao->valor_antigo = $valorAntigo;
        $alteracao->valor_novo = $valorNovo;
        $alteracao->data_hora = now(); // Método para obter a data/hora atual
        $alteracao->save();
    }
}
