<?php
namespace App\Modules\Notificacoes\Models;

use App\Core\Model;

class RegrasNotificacao extends Model {
    protected $table = 'regras_notificacao';
    protected $fillable = ['usuario_id', 'tipo_evento', 'mensagem', 'status'];
}
