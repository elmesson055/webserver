<?php

namespace App\Modules\Cadastros\Controllers;

use App\Core\Controller;
use App\Core\Services\ExternalApiService;
use App\Modules\Cadastros\Models\Fornecedor;

class FornecedorController extends Controller
{
    protected $fornecedor;
    protected $externalApi;

    public function __construct()
    {
        parent::__construct();
        $this->fornecedor = new Fornecedor();
        $this->externalApi = new ExternalApiService();
    }

    public function index()
    {
        $fornecedores = $this->fornecedor->all();
        return $this->view('fornecedores/index', ['fornecedores' => $fornecedores]);
    }

    public function create()
    {
        return $this->view('fornecedores/form');
    }

    public function edit($id)
    {
        $fornecedor = $this->fornecedor->find($id);
        if (!$fornecedor) {
            $this->flash->error('Fornecedor não encontrado');
            return $this->redirect('fornecedores');
        }
        return $this->view('fornecedores/form', ['fornecedor' => $fornecedor]);
    }

    public function save()
    {
        $data = $this->request->post();
        
        if (isset($data['id']) && !empty($data['id'])) {
            $fornecedor = $this->fornecedor->find($data['id']);
            if (!$fornecedor) {
                return $this->json([
                    'success' => false,
                    'message' => 'Fornecedor não encontrado'
                ]);
            }
            $fornecedor->fill($data);
        } else {
            $fornecedor = new Fornecedor($data);
        }

        if ($fornecedor->save()) {
            return $this->json([
                'success' => true,
                'message' => 'Fornecedor salvo com sucesso'
            ]);
        }

        return $this->json([
            'success' => false,
            'errors' => $fornecedor->getValidationErrors()
        ]);
    }

    public function delete($id)
    {
        $fornecedor = $this->fornecedor->find($id);
        if (!$fornecedor) {
            $this->flash->error('Fornecedor não encontrado');
            return $this->redirect('fornecedores');
        }

        if ($fornecedor->delete()) {
            $this->flash->success('Fornecedor excluído com sucesso');
        } else {
            $this->flash->error('Erro ao excluir fornecedor');
        }

        return $this->redirect('fornecedores');
    }

    public function validateCnpj()
    {
        $cnpj = $this->request->get('cnpj');
        $result = $this->externalApi->consultarCnpj($cnpj);
        return $this->json($result);
    }

    public function consultaCep()
    {
        $cep = $this->request->get('cep');
        $result = $this->externalApi->consultarCep($cep);
        return $this->json($result);
    }

    public function export($format = 'excel')
    {
        $fornecedores = $this->fornecedor->all();
        
        if ($format === 'pdf') {
            return $this->fornecedor->exportToPdf($fornecedores);
        }
        
        return $this->fornecedor->exportToExcel($fornecedores);
    }
}
