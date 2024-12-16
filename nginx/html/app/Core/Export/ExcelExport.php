<?php

namespace App\Core\Export;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelExport extends BaseExport
{
    private $spreadsheet;
    private $sheet;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
    }

    public function download($filename = null)
    {
        if ($filename) {
            $this->filename = $filename;
        }

        if (!$this->filename) {
            $this->filename = 'export_' . date('Y-m-d_His') . '.xlsx';
        }

        $this->generate();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function save($path)
    {
        $this->generate();
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($path);
    }

    private function generate()
    {
        // Set title if exists
        if ($this->title) {
            $this->sheet->setCellValue('A1', $this->title);
            $this->sheet->mergeCells('A1:' . $this->getLastColumn() . '1');
            $this->styleTitle();
            $startRow = 3;
        } else {
            $startRow = 1;
        }

        // Set headers
        $col = 'A';
        foreach ($this->columns as $header) {
            $this->sheet->setCellValue($col . $startRow, $header);
            $this->sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $this->styleHeaders($startRow);

        // Set data
        $row = $startRow + 1;
        foreach ($this->data as $item) {
            $col = 'A';
            foreach ($this->columns as $field => $header) {
                $value = is_object($item) ? $item->{$field} : $item[$field];
                $this->sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }

        $this->styleData($startRow + 1, $row - 1);
    }

    private function styleTitle()
    {
        $this->sheet->getStyle('A1:' . $this->getLastColumn() . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2EFDA']
            ]
        ]);
        $this->sheet->getRowDimension(1)->setRowHeight(30);
    }

    private function styleHeaders($row)
    {
        $this->sheet->getStyle('A' . $row . ':' . $this->getLastColumn() . $row)->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F2F2']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);
    }

    private function styleData($startRow, $endRow)
    {
        $this->sheet->getStyle('A' . $startRow . ':' . $this->getLastColumn() . $endRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);
    }

    private function getLastColumn()
    {
        return chr(64 + count($this->columns));
    }
}
