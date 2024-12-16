<?php

namespace App\Core\Export;

abstract class BaseExport
{
    protected $data;
    protected $columns;
    protected $title;
    protected $filename;

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setColumns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    abstract public function download($filename = null);
    abstract public function save($path);
}
