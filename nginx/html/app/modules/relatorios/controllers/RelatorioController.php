<?php
namespace App\Modules\Relatorios\Controllers;

use App\Core\Controller;
use App\Modules\CustosExtras\Models\RegistroInicial;

class RelatorioController extends Controller {
    public function gerar() {
        // Obter campos e filtros do formulário
        $campos = $this->request->get('campos', []);
        $filtros = $this->request->get('filtros', '');

        // Consultar dados com base nos campos e filtros selecionados
        $query = RegistroInicial::query();

        // Aplicar filtros se houver
        if (!empty($filtros)) {
            $query->where('descricao', 'LIKE', "%{$filtros}%");
        }

        // Obter os dados
        $registros = $query->get($campos);

        // Verificar se o formato de exportação foi solicitado
        $formato = $this->request->get('formato', 'pdf'); // Padrão para PDF

        if ($formato === 'pdf') {
            return $this->exportPdf($registros);
        } else if ($formato === 'excel') {
            return $this->exportExcel($registros);
        }
        // Aqui você pode adicionar lógica para outros formatos, como CSV

        // Para simplificação, vamos apenas retornar os dados como JSON
        return $this->json($registros);
    }

    // Adicionar método para exportar dados em PDF
    public function exportPdf($registros) {
        $pdf = new \TCPDF();
        $pdf->AddPage();

        // Configurar título
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Relatório de Registros', 0, 1, 'C');
        $pdf->Ln(5);

        // Configurar cabeçalho da tabela
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(40, 7, 'Descrição', 1);
        $pdf->Cell(30, 7, 'Valor', 1);
        $pdf->Cell(30, 7, 'Data', 1);
        $pdf->Cell(40, 7, 'Status', 1);
        $pdf->Ln();

        // Preencher dados da tabela
        $pdf->SetFont('helvetica', '', 10);
        foreach ($registros as $registro) {
            $pdf->Cell(40, 6, $registro->descricao, 1);
            $pdf->Cell(30, 6, 'R$ ' . number_format($registro->valor, 2, ',', '.'), 1);
            $pdf->Cell(30, 6, date('d/m/Y', strtotime($registro->data_registro)), 1);
            $pdf->Cell(40, 6, $registro->status->nome, 1);
            $pdf->Ln();
        }

        // Saída do PDF
        $pdf->Output('relatorio_registros.pdf', 'D');
    }

    // Adicionar método para exportar dados em Excel
    public function exportExcel($registros) {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Configurar cabeçalho
        $sheet->setCellValue('A1', 'Descrição');
        $sheet->setCellValue('B1', 'Valor');
        $sheet->setCellValue('C1', 'Data');
        $sheet->setCellValue('D1', 'Status');

        // Preencher dados
        $row = 2;
        foreach ($registros as $registro) {
            $sheet->setCellValue('A' . $row, $registro->descricao);
            $sheet->setCellValue('B' . $row, $registro->valor);
            $sheet->setCellValue('C' . $row, date('d/m/Y', strtotime($registro->data_registro)));
            $sheet->setCellValue('D' . $row, $registro->status->nome);
            $row++;
        }

        // Configurar cabeçalhos para download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="relatorio_registros.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
