<?php

namespace App\Core\Export;

use TCPDF;

class PdfExport extends BaseExport
{
    private $pdf;
    private $orientation = 'P';
    private $pageSize = 'A4';

    public function __construct()
    {
        $this->pdf = new TCPDF($this->orientation, 'mm', $this->pageSize, true, 'UTF-8');
        $this->setupPdf();
    }

    public function setOrientation($orientation)
    {
        $this->orientation = $orientation;
        return $this;
    }

    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    public function download($filename = null)
    {
        if ($filename) {
            $this->filename = $filename;
        }

        if (!$this->filename) {
            $this->filename = 'export_' . date('Y-m-d_His') . '.pdf';
        }

        $this->generate();
        $this->pdf->Output($this->filename, 'D');
    }

    public function save($path)
    {
        $this->generate();
        $this->pdf->Output($path, 'F');
    }

    private function setupPdf()
    {
        $this->pdf->SetCreator('Sistema');
        $this->pdf->SetAuthor('Sistema');
        $this->pdf->SetMargins(15, 15, 15);
        $this->pdf->SetAutoPageBreak(true, 15);
        $this->pdf->setImageScale(1.25);
        $this->pdf->SetFont('helvetica', '', 10);
    }

    private function generate()
    {
        $this->pdf->AddPage();

        if ($this->title) {
            $this->pdf->SetFont('helvetica', 'B', 16);
            $this->pdf->Cell(0, 10, $this->title, 0, 1, 'C');
            $this->pdf->Ln(5);
        }

        // Calculate widths
        $totalWidth = $this->pdf->getPageWidth() - 30; // 30 = margins (15 each side)
        $colWidth = $totalWidth / count($this->columns);

        // Headers
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->SetFillColor(242, 242, 242);
        foreach ($this->columns as $header) {
            $this->pdf->Cell($colWidth, 7, $header, 1, 0, 'C', true);
        }
        $this->pdf->Ln();

        // Data
        $this->pdf->SetFont('helvetica', '', 10);
        $this->pdf->SetFillColor(255, 255, 255);

        foreach ($this->data as $item) {
            foreach ($this->columns as $field => $header) {
                $value = is_object($item) ? $item->{$field} : $item[$field];
                // Limit text length to avoid overflow
                $value = strlen($value) > 30 ? substr($value, 0, 27) . '...' : $value;
                $this->pdf->Cell($colWidth, 6, $value, 1, 0, 'L');
            }
            $this->pdf->Ln();
        }
    }

    public function addHeader($text)
    {
        $this->pdf->setHeaderData('', 0, $text, '');
        return $this;
    }

    public function addFooter($text)
    {
        $this->pdf->setFooterData(array(0,0,0), array(0,0,0));
        $this->pdf->setFooterFont(Array('helvetica', '', 8));
        $this->pdf->setFooterMargin(10);
        return $this;
    }
}
