<?php

namespace App\Modules\Financeiro\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Modules\Financeiro\Models\DadosBancarios;
use App\Modules\Financeiro\Models\MovimentacaoFinanceira;
use App\Modules\Cadastros\Models\Fornecedor;

class FinanceiroController extends Controller
{
    public function dadosBancarios()
    {
        $fornecedor = $this->getFornecedorAutenticado();
        $dadosBancarios = DadosBancarios::where('fornecedor_id', $fornecedor->id)
            ->orderBy('principal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('financeiro.dados_bancarios', [
            'dadosBancarios' => $dadosBancarios,
            'bancos' => DadosBancarios::getBancos(),
            'tiposConta' => DadosBancarios::getTiposConta(),
            'tiposPix' => DadosBancarios::getTiposPix()
        ]);
    }

    public function salvarDadosBancarios()
    {
        $fornecedor = $this->getFornecedorAutenticado();
        $dados = new DadosBancarios($_POST);
        $dados->fornecedor_id = $fornecedor->id;

        if ($dados->save()) {
            return Response::json([
                'success' => true,
                'message' => 'Dados bancários salvos com sucesso'
            ]);
        }

        return Response::json([
            'success' => false,
            'errors' => $dados->getValidationErrors()
        ], 422);
    }

    public function excluirDadosBancarios($id)
    {
        $fornecedor = $this->getFornecedorAutenticado();
        $dados = DadosBancarios::where('fornecedor_id', $fornecedor->id)
            ->where('id', $id)
            ->first();

        if (!$dados) {
            return Response::json([
                'success' => false,
                'message' => 'Dados bancários não encontrados'
            ], 404);
        }

        $dados->delete();

        return Response::json([
            'success' => true,
            'message' => 'Dados bancários excluídos com sucesso'
        ]);
    }

    public function movimentacoes()
    {
        $fornecedor = $this->getFornecedorAutenticado();
        
        $filtros = [
            'status' => $_GET['status'] ?? null,
            'tipo' => $_GET['tipo'] ?? null,
            'data_inicio' => $_GET['data_inicio'] ?? null,
            'data_fim' => $_GET['data_fim'] ?? null
        ];

        $query = MovimentacaoFinanceira::where('fornecedor_id', $fornecedor->id);

        if ($filtros['status']) {
            $query->where('status', $filtros['status']);
        }

        if ($filtros['tipo']) {
            $query->where('tipo', $filtros['tipo']);
        }

        if ($filtros['data_inicio']) {
            $query->where('data_vencimento', '>=', $filtros['data_inicio']);
        }

        if ($filtros['data_fim']) {
            $query->where('data_vencimento', '<=', $filtros['data_fim']);
        }

        $movimentacoes = $query->orderBy('data_vencimento', 'desc')
            ->paginate(20);

        return view('financeiro.movimentacoes', [
            'movimentacoes' => $movimentacoes,
            'filtros' => $filtros,
            'tipos' => MovimentacaoFinanceira::getTipos(),
            'status' => MovimentacaoFinanceira::getStatus()
        ]);
    }

    public function detalhesMovimentacao($id)
    {
        $fornecedor = $this->getFornecedorAutenticado();
        $movimentacao = MovimentacaoFinanceira::where('fornecedor_id', $fornecedor->id)
            ->where('id', $id)
            ->first();

        if (!$movimentacao) {
            return Response::json([
                'success' => false,
                'message' => 'Movimentação não encontrada'
            ], 404);
        }

        return Response::json([
            'success' => true,
            'data' => $movimentacao
        ]);
    }

    public function uploadComprovante($id)
    {
        $fornecedor = $this->getFornecedorAutenticado();
        $movimentacao = MovimentacaoFinanceira::where('fornecedor_id', $fornecedor->id)
            ->where('id', $id)
            ->first();

        if (!$movimentacao) {
            return Response::json([
                'success' => false,
                'message' => 'Movimentação não encontrada'
            ], 404);
        }

        if (!isset($_FILES['comprovante'])) {
            return Response::json([
                'success' => false,
                'message' => 'Nenhum arquivo enviado'
            ], 422);
        }

        $file = $_FILES['comprovante'];
        $path = 'uploads/comprovantes/' . date('Y/m');
        $filename = uniqid() . '_' . $file['name'];

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $path . '/' . $filename)) {
            $movimentacao->comprovante = $path . '/' . $filename;
            $movimentacao->save();

            return Response::json([
                'success' => true,
                'message' => 'Comprovante enviado com sucesso'
            ]);
        }

        return Response::json([
            'success' => false,
            'message' => 'Erro ao enviar comprovante'
        ], 500);
    }

    protected function getFornecedorAutenticado()
    {
        return Fornecedor::find($_SESSION['fornecedor_id']);
    }
}
