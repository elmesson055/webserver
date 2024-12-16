<?php
require_once 'init.php';
require_once 'auth.php';
require_once '../lib/fpdf/fpdf.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Relatório de Custos', 0, 1, 'C');
        $this->Ln(5);
        
        // Cabeçalho da tabela
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(240, 240, 240);
        
        $header = array(
            'Nº Rastreio',
            'Data',
            'Cliente',
            'Tipo Carga',
            'Tipo Custo',
            'Status Op.',
            'Romaneio',
            'Nº Ref.',
            'NFe',
            'Qtd',
            'Valor Total',
            'Valor Apr.',
            'Data Apr.',
            'Nº Ocor.',
            'Status',
            'Doc.',
            'CTe',
            'Data CTe',
            'Data Pag.'
        );
        $w = array(25, 20, 30, 20, 20, 20, 20, 20, 20, 15, 20, 20, 20, 20, 20, 15, 20, 20, 20);
        
        foreach($header as $i => $h) {
            $this->Cell($w[$i], 7, utf8_decode($h), 1, 0, 'C', true);
        }
        $this->Ln();
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Criar novo PDF
$pdf = new PDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);

// Consulta SQL para obter os dados
$sql = "SELECT c.*, 
        cl.name as client_name,
        ct.name as cargo_type_name,
        cost.name as cost_type_name,
        os.name as operational_status_name,
        s.name as status_name,
        dt.name as document_type_name
        FROM costs c
        LEFT JOIN clients cl ON c.client_id = cl.id
        LEFT JOIN cargo_types ct ON c.cargo_type_id = ct.id
        LEFT JOIN cost_types cost ON c.cost_type_id = cost.id
        LEFT JOIN operational_status os ON c.operational_status_id = os.id
        LEFT JOIN status s ON c.status_id = s.id
        LEFT JOIN document_types dt ON c.document_type_id = dt.id
        ORDER BY c.date DESC";

$stmt = $db->query($sql);

// Larguras das colunas
$w = array(25, 20, 30, 20, 20, 20, 20, 20, 20, 15, 20, 20, 20, 20, 20, 15, 20, 20, 20);

// Imprimir linhas de dados
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Verificar se precisa adicionar nova página
    if ($pdf->GetY() > 180) {
        $pdf->AddPage();
    }
    
    $pdf->Cell($w[0], 6, utf8_decode($row['tracking_number']), 1, 0, 'L');
    $pdf->Cell($w[1], 6, date('d/m/Y', strtotime($row['date'])), 1, 0, 'C');
    $pdf->Cell($w[2], 6, utf8_decode(substr($row['client_name'], 0, 30)), 1, 0, 'L');
    $pdf->Cell($w[3], 6, utf8_decode(substr($row['cargo_type_name'], 0, 15)), 1, 0, 'L');
    $pdf->Cell($w[4], 6, utf8_decode(substr($row['cost_type_name'], 0, 15)), 1, 0, 'L');
    $pdf->Cell($w[5], 6, utf8_decode(substr($row['operational_status_name'], 0, 15)), 1, 0, 'L');
    $pdf->Cell($w[6], 6, utf8_decode($row['romaneio']), 1, 0, 'L');
    $pdf->Cell($w[7], 6, utf8_decode($row['reference_number']), 1, 0, 'L');
    $pdf->Cell($w[8], 6, utf8_decode($row['nfe_number']), 1, 0, 'L');
    $pdf->Cell($w[9], 6, utf8_decode($row['quantity']), 1, 0, 'R');
    $pdf->Cell($w[10], 6, 'R$ ' . number_format($row['total_value'], 2, ',', '.'), 1, 0, 'R');
    $pdf->Cell($w[11], 6, 'R$ ' . number_format($row['approved_value'], 2, ',', '.'), 1, 0, 'R');
    $pdf->Cell($w[12], 6, $row['approval_date'] ? date('d/m/Y', strtotime($row['approval_date'])) : '', 1, 0, 'C');
    $pdf->Cell($w[13], 6, utf8_decode($row['occurrence_number']), 1, 0, 'L');
    $pdf->Cell($w[14], 6, utf8_decode(substr($row['status_name'], 0, 15)), 1, 0, 'L');
    $pdf->Cell($w[15], 6, utf8_decode(substr($row['document_type_name'], 0, 8)), 1, 0, 'L');
    $pdf->Cell($w[16], 6, utf8_decode($row['cte_number']), 1, 0, 'L');
    $pdf->Cell($w[17], 6, $row['cte_emission_date'] ? date('d/m/Y', strtotime($row['cte_emission_date'])) : '', 1, 0, 'C');
    $pdf->Cell($w[18], 6, $row['payment_date'] ? date('d/m/Y', strtotime($row['payment_date'])) : '', 1, 0, 'C');
    $pdf->Ln();
}

// Headers para download do arquivo
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename=relatorio_custos_' . date('Y-m-d') . '.pdf');

// Saída do PDF
$pdf->Output('I', 'relatorio_custos_' . date('Y-m-d') . '.pdf');
