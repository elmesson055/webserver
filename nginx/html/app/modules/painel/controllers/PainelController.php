<?php
namespace App\Modules\Painel\Controllers;

use App\Core\Controller;
use App\Modules\CustosExtras\Models\RegistroInicial;
use App\Modules\Cadastros\Models\Atividade;
use App\Modules\Cadastros\Models\Status;

class PainelController extends Controller {
    public function index() {
        // Total de Custos
        $totalCustos = RegistroInicial::sum('valor');

        // Total de Receitas (ajustado conforme necessário)
        $totalReceitas = RegistroInicial::where('tipo_custo_id', 1)->sum('valor'); // Ajustar conforme o tipo de receita

        // Saldo Atual
        $saldo = $totalReceitas - $totalCustos;

        // Aprovações Pendentes
        $custosPendentes = RegistroInicial::where('status_id', 2)->count(); // Status 2 para pendente

        // Atividades Recentes
        $atividadesRecentes = Atividade::orderBy('data_hora', 'desc')->limit(5)->get();

        // Dados para o gráfico de status
        $statusData = RegistroInicial::selectRaw('status_id, COUNT(*) as total')
            ->groupBy('status_id')
            ->pluck('total', 'status_id');

        $statusLabels = $statusData->keys()->map(function($statusId) {
            return Status::find($statusId)->nome; // Ajuste conforme a estrutura do seu modelo
        });

        $statusDistribution = [];
        foreach ($statusData as $statusId => $total) {
            $statusDistribution[] = [
                'label' => $statusLabels[$statusId],
                'value' => $total,
            ];
        }

        return $this->view('painel/views/dashboard', [
            'totalCustos' => $totalCustos,
            'totalReceitas' => $totalReceitas,
            'saldo' => $saldo,
            'custosPendentes' => $custosPendentes,
            'atividadesRecentes' => $atividadesRecentes,
            'statusLabels' => $statusLabels,
            'statusData' => $statusData,
            'statusDistribution' => $statusDistribution
        ]);
    }
}
