<?php
namespace App\Modules\Auditoria\Models;

use App\Core\Model;

class Versao extends Model {
    protected $table = 'versoes';
    protected $fillable = ['modelo', 'registro_id', 'dados', 'data_hora'];
}
