<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;
use PDOException;

class DashboardController extends Controller
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance();
    }

    public function index()
    {
        try {
            // Total de custos extras
            $stmt = $this->db->prepare("SELECT COALESCE(SUM(valor), 0) as total FROM custos_extras");
            $stmt->execute();
            $totalCustos = $stmt->fetch()['total'];

            // Registros pendentes
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM custos_extras WHERE status = 'pendente'");
            $stmt->execute();
            $totalPendentes = $stmt->fetch()['total'];

            // Em aprovação
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM custos_extras WHERE status = 'em_aprovacao'");
            $stmt->execute();
            $totalEmAprovacao = $stmt->fetch()['total'];

            // Aprovados
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM custos_extras WHERE status = 'aprovado'");
            $stmt->execute();
            $totalAprovados = $stmt->fetch()['total'];

            // Dados para o gráfico de custos por mês
            $stmt = $this->db->prepare("
                SELECT 
                    DATE_FORMAT(data_registro, '%Y-%m') as mes,
                    SUM(valor) as total
                FROM custos_extras
                WHERE data_registro >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(data_registro, '%Y-%m')
                ORDER BY mes ASC
            ");
            $stmt->execute();
            $custosPorMes = $stmt->fetchAll();

            // Dados para o gráfico de status
            $stmt = $this->db->prepare("
                SELECT 
                    status,
                    COUNT(*) as total
                FROM custos_extras
                GROUP BY status
            ");
            $stmt->execute();
            $custosPorStatus = $stmt->fetchAll();

            // Prepara os dados para os gráficos em formato JSON
            $dadosGraficos = [
                'custosPorMes' => $custosPorMes,
                'custosPorStatus' => $custosPorStatus
            ];

            // Carrega a view com os dados
            $this->view('dashboard/index', [
                'pageTitle' => 'Dashboard',
                'totalCustos' => number_format($totalCustos, 2, ',', '.'),
                'totalPendentes' => $totalPendentes,
                'totalEmAprovacao' => $totalEmAprovacao,
                'totalAprovados' => $totalAprovados,
                'dadosGraficos' => json_encode($dadosGraficos)
            ]);

        } catch (PDOException $e) {
            error_log("Erro no dashboard: " . $e->getMessage());
            $_SESSION['message'] = "Erro ao carregar os dados do dashboard.";
            $_SESSION['message_type'] = "danger";
            
            // Carrega a view com dados vazios
            $this->view('dashboard/index', [
                'pageTitle' => 'Dashboard',
                'totalCustos' => '0,00',
                'totalPendentes' => 0,
                'totalEmAprovacao' => 0,
                'totalAprovados' => 0,
                'dadosGraficos' => json_encode([
                    'custosPorMes' => [],
                    'custosPorStatus' => []
                ])
            ]);
        }
    }
}
