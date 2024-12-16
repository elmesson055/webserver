<?php

namespace App\Models;

use App\Core\Model;

class Embarcador extends Model {
    protected $table = 'embarcadores';
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
        'status'
    ];

    public function getSearchFields() {
        return [
            'razao_social',
            'cnpj',
            'cidade',
            'estado'
        ];
    }

    public function validateCreate($data) {
        $errors = [];

        // Validação de CNPJ
        if (!$this->validaCNPJ($data['cnpj'])) {
            $errors['cnpj'] = 'CNPJ inválido';
        }

        // Validação de campos obrigatórios
        $required = ['razao_social', 'cnpj', 'endereco', 'cidade', 'estado'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[$field] = 'Campo obrigatório';
            }
        }

        // Validação de email
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }

        return $errors;
    }

    private function validaCNPJ($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        if (strlen($cnpj) != 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}
