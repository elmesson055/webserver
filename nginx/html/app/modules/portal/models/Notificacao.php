<?php

namespace App\Modules\Portal\Models;

use App\Core\Model;
use App\Modules\Cadastros\Models\Fornecedor;

class Notificacao extends Model
{
    protected $table = 'notificacoes';

    protected $fillable = [
        'fornecedor_id',
        'titulo',
        'mensagem',
        'lida',
        'tipo'
    ];

    protected $casts = [
        'lida' => 'boolean'
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function marcarComoLida()
    {
        $this->lida = true;
        $this->save();
    }
}
