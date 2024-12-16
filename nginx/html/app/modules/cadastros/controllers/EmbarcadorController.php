<?php

namespace App\Modules\Cadastros\Controllers;

use App\Core\Controller\BaseController;
use App\Modules\Cadastros\Embarcador;
use App\Core\Http\Request;
use App\Core\Http\Response;

class EmbarcadorController extends BaseController {
    protected $embarcador;

    public function __construct() {
        parent::__construct();
        $this->embarcador = new Embarcador();
    }

    /**
     * Lista embarcadores
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {
        try {
            $filtros = $request->getQueryParams();
            $embarcadores = $this->embarcador->buscar($filtros['termo'] ?? '');

            return $this->view('cadastros.embarcadores.index', [
                'embarcadores' => $embarcadores,
                'filtros' => $filtros
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Exibe formulário de criação
     * @return Response
     */
    public function create() {
        return $this->view('cadastros.embarcadores.form');
    }

    /**
     * Salva novo embarcador
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {
        try {
            $data = $request->getPostParams();
            
            // Valida dados
            $validation = $this->embarcador->validate($data);
            if (!$validation['valid']) {
                return $this->error($validation['errors']);
            }

            // Adiciona usuário responsável
            $data['created_by'] = $this->auth->user()->id;
            
            // Salva embarcador
            $embarcador = $this->embarcador->create($data);

            // Log da ação
            $this->log->info('Embarcador criado', [
                'embarcador_id' => $embarcador->id,
                'user_id' => $this->auth->user()->id
            ]);

            return $this->success('Embarcador cadastrado com sucesso', [
                'redirect' => '/cadastros/embarcadores'
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Exibe formulário de edição
     * @param int $id
     * @return Response
     */
    public function edit($id) {
        try {
            $embarcador = $this->embarcador->find($id);
            if (!$embarcador) {
                throw new \Exception('Embarcador não encontrado');
            }

            return $this->view('cadastros.embarcadores.form', [
                'embarcador' => $embarcador
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Atualiza embarcador
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id) {
        try {
            $embarcador = $this->embarcador->find($id);
            if (!$embarcador) {
                throw new \Exception('Embarcador não encontrado');
            }

            $data = $request->getPostParams();
            
            // Valida dados
            $validation = $this->embarcador->validate($data);
            if (!$validation['valid']) {
                return $this->error($validation['errors']);
            }

            // Adiciona usuário que atualizou
            $data['updated_by'] = $this->auth->user()->id;
            
            // Atualiza embarcador
            $embarcador->update($data);

            // Log da ação
            $this->log->info('Embarcador atualizado', [
                'embarcador_id' => $embarcador->id,
                'user_id' => $this->auth->user()->id
            ]);

            return $this->success('Embarcador atualizado com sucesso', [
                'redirect' => '/cadastros/embarcadores'
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Remove embarcador
     * @param int $id
     * @return Response
     */
    public function destroy($id) {
        try {
            $embarcador = $this->embarcador->find($id);
            if (!$embarcador) {
                throw new \Exception('Embarcador não encontrado');
            }

            // Soft delete
            $embarcador->update(['status' => 0]);

            // Log da ação
            $this->log->info('Embarcador removido', [
                'embarcador_id' => $embarcador->id,
                'user_id' => $this->auth->user()->id
            ]);

            return $this->success('Embarcador removido com sucesso');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Gera relatório de embarcadores
     * @param Request $request
     * @return Response
     */
    public function relatorio(Request $request) {
        try {
            $filtros = $request->getQueryParams();
            $dados = $this->embarcador->relatorio($filtros);

            // Gera PDF ou Excel conforme solicitado
            $formato = $filtros['formato'] ?? 'pdf';
            if ($formato === 'excel') {
                return $this->excel('Relatório de Embarcadores', $dados);
            }

            return $this->pdf('cadastros.embarcadores.relatorio', [
                'dados' => $dados,
                'filtros' => $filtros
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
