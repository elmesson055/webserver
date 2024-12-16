<?php

namespace App\Core\Traits;

use App\Core\Export\ExcelExport;
use App\Core\Export\PdfExport;

trait ExportableTrait
{
    /**
     * Export data to Excel
     *
     * @param array|null $data Data to export
     * @param array|null $columns Column definitions
     * @param string|null $title Report title
     * @param string|null $filename Output filename
     * @return void
     */
    public function exportToExcel($data = null, $columns = null, $title = null, $filename = null)
    {
        $data = $data ?? $this->all();
        $columns = $columns ?? $this->getExportColumns();
        $title = $title ?? $this->getExportTitle();
        $filename = $filename ?? $this->getExportFilename('xlsx');

        $export = new ExcelExport();
        $export->setData($data)
               ->setColumns($columns)
               ->setTitle($title);

        return $export->download($filename);
    }

    /**
     * Export data to PDF
     *
     * @param array|null $data Data to export
     * @param array|null $columns Column definitions
     * @param string|null $title Report title
     * @param string|null $filename Output filename
     * @return void
     */
    public function exportToPdf($data = null, $columns = null, $title = null, $filename = null)
    {
        $data = $data ?? $this->all();
        $columns = $columns ?? $this->getExportColumns();
        $title = $title ?? $this->getExportTitle();
        $filename = $filename ?? $this->getExportFilename('pdf');

        $export = new PdfExport();
        $export->setData($data)
               ->setColumns($columns)
               ->setTitle($title)
               ->addHeader($title)
               ->addFooter('Gerado em ' . date('d/m/Y H:i:s'));

        return $export->download($filename);
    }

    /**
     * Get default export columns
     *
     * @return array
     */
    protected function getExportColumns()
    {
        return $this->fillable ? array_combine($this->fillable, $this->fillable) : [];
    }

    /**
     * Get default export title
     *
     * @return string
     */
    protected function getExportTitle()
    {
        return 'Relatório de ' . class_basename($this);
    }

    /**
     * Get default export filename
     *
     * @param string $extension File extension without dot
     * @return string
     */
    protected function getExportFilename($extension)
    {
        $basename = strtolower(class_basename($this));
        return $basename . '_' . date('Y-m-d_His') . '.' . $extension;
    }
}
