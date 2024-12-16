<?php

namespace App\Modules\Portal\Models;

use App\Core\Model;
use App\Modules\Cadastros\Models\Fornecedor;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = [
        'fornecedor_id',
        'categoria_id',
        'nome',
        'arquivo',
        'validade',
        'observacoes',
        'status'
    ];

    protected $dates = [
        'validade',
        'created_at',
        'updated_at'
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function getStatus()
    {
        if (!$this->arquivo) {
            return 'Pendente';
        }

        if ($this->validade < now()) {
            return 'Vencido';
        }

        if ($this->validade->diffInDays(now()) <= 30) {
            return 'A vencer';
        }

        return 'Válido';
    }
}
