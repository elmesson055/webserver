<?php
namespace App\Modules\Notificacoes\Models;

use App\Core\Model;

class Notificacao extends Model {
    protected $table = 'notificacoes';
    protected $fillable = ['usuario_id', 'mensagem', 'status', 'data_hora'];
}
