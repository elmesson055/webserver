<?php
namespace App\Modules\CustosExtras\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Modules\CustosExtras\Models\RegistroInicial;
use App\Core\Exceptions\ValidationException;
use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RegistroInicialController extends Controller {
    public function index() {
        $this->checkPermission('custos_extras.visualizar');
        
        $search = $this->request->get('search', '');
        $page = $this->request->get('page', 1);
        $perPage = 20;
        
        $query = RegistroInicial::query()
            ->orderBy('data_registro', 'DESC');
            
        if (!empty($search)) {
            $query->where('descricao', 'LIKE', "%{$search}%");
        }
        
        $total = $query->count();
        $registros = $query->limit($perPage)
                             ->offset(($page - 1) * $perPage)
                             ->get();
        
        return $this->view('custos_extras/views/registro_inicial/index', [
            'registros' => $registros,
            'search' => $search,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total
        ]);
    }
    
    public function create() {
        $this->checkPermission('custos_extras.criar');
        
        $registro = new RegistroInicial();
        
        return $this->view('custos_extras/views/registro_inicial/form', [
            'registro' => $registro,
            'title' => 'Novo Registro Inicial'
        ]);
    }
    
    public function edit($id) {
        $this->checkPermission('custos_extras.editar');
        
        $registro = RegistroInicial::findOrFail($id);
        
        return $this->view('custos_extras/views/registro_inicial/form', [
            'registro' => $registro,
            'title' => 'Editar Registro Inicial'
        ]);
    }
    
    public function store() {
        $this->checkPermission('custos_extras.criar');
        
        try {
            $registro = new RegistroInicial();
            $registro->fill($this->request->all());
            $registro->save();
            
            $this->flash->success('Registro inicial cadastrado com sucesso!');
            return $this->redirect('/custos-extras/registro-inicial');
            
        } catch (ValidationException $e) {
            $this->flash->error($e->getMessage());
            return $this->redirect('/custos-extras/registro-inicial/create')
                       ->withInput()
                       ->withErrors($e->getErrors());
        }
    }
    
    public function update($id) {
        $this->checkPermission('custos_extras.editar');
        
        try {
            $registro = RegistroInicial::findOrFail($id);
            $registro->fill($this->request->all());
            $registro->save();
            
            $this->flash->success('Registro inicial atualizado com sucesso!');
            return $this->redirect('/custos-extras/registro-inicial');
            
        } catch (ValidationException $e) {
            $this->flash->error($e->getMessage());
            return $this->redirect("/custos-extras/registro-inicial/{$id}/edit")
                       ->withInput()
                       ->withErrors($e->getErrors());
        }
    }
    
    public function delete($id) {
        $this->checkPermission('custos_extras.excluir');
        
        try {
            $registro = RegistroInicial::findOrFail($id);
            $registro->delete();
            
            $this->flash->success('Registro inicial excluído com sucesso!');
            return $this->json(['success' => true]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Não foi possível excluir o registro inicial.'
            ], 400);
        }
    }
    
    public function search() {
        $this->checkPermission('custos_extras.visualizar');
        
        $term = $this->request->get('term', '');
        $registros = RegistroInicial::findByTerm($term);
        
        return $this->json($registros);
    }
    
    public function exportPdf() {
        $this->checkPermission('custos_extras.visualizar');

        $registros = RegistroInicial::all();

        // Criar novo PDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Configurar título
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Relatório de Registros Iniciais', 0, 1, 'C');
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
        $pdf->Output('registros_iniciais.pdf', 'D');
    }
    
    public function exportExcel() {
        $this->checkPermission('custos_extras.visualizar');

        $registros = RegistroInicial::all();

        // Criar nova planilha
        $spreadsheet = new Spreadsheet();
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
        header('Content-Disposition: attachment;filename="registros_iniciais.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
