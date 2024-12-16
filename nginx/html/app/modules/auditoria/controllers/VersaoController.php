<?php
namespace App\Modules\Auditoria\Controllers;

use App\Core\Controller;
use App\Modules\Auditoria\Models\Versao;

class VersaoController extends Controller {
    public function registrar($modelo, $registroId, $dados) {
        $versao = new Versao();
        $versao->modelo = $modelo;
        $versao->registro_id = $registroId;
        $versao->dados = json_encode($dados); // Armazenar dados como JSON
        $versao->data_hora = now(); // Método para obter a data/hora atual
        $versao->save();
    }

    public function restaurarVersao($modelo, $registroId, $versaoId) {
        $versao = Versao::where('modelo', $modelo)
                        ->where('registro_id', $registroId)
                        ->where('id', $versaoId)
                        ->first();

        if ($versao) {
            // Lógica para restaurar os dados
            $dados = json_decode($versao->dados, true);
            // Atualizar o registro original com os dados restaurados
            $registro = $modelo::find($registroId);
            if ($registro) {
                $registro->update($dados);
            }
        }
    }
}
