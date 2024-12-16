<?php

namespace App\Modules\Cadastros;

use App\Core\Database\Model;
use App\Core\Validation\Validator;

class Embarcador extends Model {
    protected $table = 'embarcadores';
    protected $primaryKey = 'id';
    protected $fillable = [
        'razao_social',
        'cnpj',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'telefone',
        'email',
        'contato',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $rules = [
        'razao_social' => 'required|max:255',
        'cnpj' => 'required|cnpj|unique:embarcadores',
        'endereco' => 'required|max:255',
        'cidade' => 'required|max:100',
        'estado' => 'required|max:2',
        'cep' => 'required|formato_cep',
        'email' => 'required|email|max:255'
    ];

    /**
     * Valida os dados do embarcador
     * @param array $data
     * @return array
     */
    public function validate($data) {
        $validator = new Validator($data, $this->rules);
        return $validator->validate();
    }

    /**
     * Retorna embarcadores ativos
     * @return array
     */
    public function getAtivos() {
        return $this->where('status', 1)->get();
    }

    /**
     * Busca embarcadores por termo
     * @param string $termo
     * @return array
     */
    public function buscar($termo) {
        return $this->where('razao_social', 'LIKE', "%{$termo}%")
                    ->orWhere('cnpj', 'LIKE', "%{$termo}%")
                    ->get();
    }

    /**
     * Retorna dados para relatório
     * @param array $filtros
     * @return array
     */
    public function relatorio($filtros = []) {
        $query = $this->select([
            'embarcadores.*',
            'users.name as usuario_cadastro'
        ])
        ->leftJoin('users', 'users.id', '=', 'embarcadores.created_by');

        if (!empty($filtros['status'])) {
            $query->where('embarcadores.status', $filtros['status']);
        }

        if (!empty($filtros['data_inicio'])) {
            $query->where('embarcadores.created_at', '>=', $filtros['data_inicio']);
        }

        if (!empty($filtros['data_fim'])) {
            $query->where('embarcadores.created_at', '<=', $filtros['data_fim']);
        }

        return $query->get();
    }
}
