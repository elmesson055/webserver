<?php

namespace App\Modules\Financeiro\Models;

use App\Core\Model;
use App\Core\Traits\ValidatableTrait;
use App\Modules\Cadastros\Models\Fornecedor;

class DadosBancarios extends Model
{
    use ValidatableTrait;

    protected $table = 'dados_bancarios';

    protected $fillable = [
        'fornecedor_id',
        'banco',
        'agencia',
        'conta',
        'tipo_conta',
        'titular',
        'cpf_cnpj_titular',
        'pix_tipo',
        'pix_chave',
        'principal',
        'status'
    ];

    protected $casts = [
        'principal' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function validate()
    {
        $this->clearValidationErrors();

        // Validações básicas
        if (empty($this->banco)) {
            $this->addError('banco', 'Banco é obrigatório');
        }

        if (empty($this->agencia)) {
            $this->addError('agencia', 'Agência é obrigatória');
        }

        if (empty($this->conta)) {
            $this->addError('conta', 'Conta é obrigatória');
        }

        if (empty($this->tipo_conta)) {
            $this->addError('tipo_conta', 'Tipo de conta é obrigatório');
        }

        if (empty($this->titular)) {
            $this->addError('titular', 'Titular é obrigatório');
        }

        if (empty($this->cpf_cnpj_titular)) {
            $this->addError('cpf_cnpj_titular', 'CPF/CNPJ do titular é obrigatório');
        }

        // Valida PIX se informado
        if (!empty($this->pix_tipo)) {
            if (empty($this->pix_chave)) {
                $this->addError('pix_chave', 'Chave PIX é obrigatória quando tipo é informado');
            } else {
                $this->validatePixKey();
            }
        }

        return !$this->hasValidationErrors();
    }

    protected function validatePixKey()
    {
        switch ($this->pix_tipo) {
            case 'CPF':
                if (!$this->validateCpf($this->pix_chave)) {
                    $this->addError('pix_chave', 'CPF inválido');
                }
                break;
            case 'CNPJ':
                if (!$this->validateCnpj($this->pix_chave)) {
                    $this->addError('pix_chave', 'CNPJ inválido');
                }
                break;
            case 'EMAIL':
                if (!filter_var($this->pix_chave, FILTER_VALIDATE_EMAIL)) {
                    $this->addError('pix_chave', 'Email inválido');
                }
                break;
            case 'TELEFONE':
                if (!preg_match('/^\+[1-9][0-9]\d{10,11}$/', $this->pix_chave)) {
                    $this->addError('pix_chave', 'Telefone inválido');
                }
                break;
            case 'ALEATORIA':
                if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $this->pix_chave)) {
                    $this->addError('pix_chave', 'Chave aleatória inválida');
                }
                break;
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        // Se esta conta está sendo marcada como principal
        if ($this->principal) {
            // Remove o flag principal de outras contas do fornecedor
            $this->db->update(
                $this->table,
                ['principal' => false],
                ['fornecedor_id' => $this->fornecedor_id, 'id != ?' => $this->id]
            );
        }

        return parent::save();
    }

    public static function getBancos()
    {
        return [
            '001' => 'Banco do Brasil',
            '033' => 'Santander',
            '104' => 'Caixa Econômica Federal',
            '237' => 'Bradesco',
            '341' => 'Itaú',
            '756' => 'Sicoob',
            // Adicione outros bancos conforme necessário
        ];
    }

    public static function getTiposConta()
    {
        return [
            'CORRENTE' => 'Conta Corrente',
            'POUPANCA' => 'Conta Poupança'
        ];
    }

    public static function getTiposPix()
    {
        return [
            'CPF' => 'CPF',
            'CNPJ' => 'CNPJ',
            'EMAIL' => 'E-mail',
            'TELEFONE' => 'Telefone',
            'ALEATORIA' => 'Chave Aleatória'
        ];
    }
}
