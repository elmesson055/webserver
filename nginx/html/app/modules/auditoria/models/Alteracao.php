<?php
namespace App\Modules\Auditoria\Models;

use App\Core\Model;

class Alteracao extends Model {
    protected $table = 'alteracoes';
    protected $fillable = ['usuario_id', 'modelo', 'registro_id', 'campo', 'valor_antigo', 'valor_novo', 'data_hora'];
}
