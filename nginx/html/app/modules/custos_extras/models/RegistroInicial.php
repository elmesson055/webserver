<?php
namespace App\Modules\CustosExtras\Models;

use App\Core\Model;
use App\Core\Database\Database;

class RegistroInicial extends Model {
    protected $table = 'custos_extras_registros_iniciais';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cliente_id',
        'tipo_custo_id',
        'valor',
        'data_registro',
        'descricao',
        'status_id',
        'documento_id'
    ];

    protected $validationRules = [
        'cliente_id' => 'required|exists:clientes,id',
        'tipo_custo_id' => 'required|exists:tipos_custos,id',
        'valor' => 'required|numeric|min:0',
        'data_registro' => 'required|date',
        'descricao' => 'max:1000',
        'status_id' => 'required|exists:status,id',
        'documento_id' => 'nullable|exists:documentos,id'
    ];

    public function cliente() {
        return $this->belongsTo('App\Modules\Cadastros\Models\Cliente', 'cliente_id');
    }

    public function tipoCusto() {
        return $this->belongsTo('App\Modules\Tipos\Models\TipoCusto', 'tipo_custo_id');
    }

    public function status() {
        return $this->belongsTo('App\Modules\Status\Models\Status', 'status_id');
    }

    public function documento() {
        return $this->belongsTo('App\Modules\Documentos\Models\Documento', 'documento_id');
    }

    public function beforeSave() {
        // Additional logic before saving
    }

    public function afterFind() {
        // Additional logic after retrieving
    }

    public static function findByTerm($term, $limit = 10) {
        $db = Database::getInstance();
        
        $sql = "SELECT id, descricao, valor, data_registro
                FROM custos_extras_registros_iniciais
                WHERE (descricao LIKE :term)
                ORDER BY data_registro DESC
                LIMIT :limit";
        
        $params = [
            'term' => "%{$term}%",
            'limit' => $limit
        ];
        
        return $db->select($sql, $params);
    }
}
