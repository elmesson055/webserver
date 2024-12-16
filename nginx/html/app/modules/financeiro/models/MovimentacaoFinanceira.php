<?php

namespace App\Modules\Financeiro\Models;

use App\Core\Model;
use App\Core\Traits\ValidatableTrait;
use App\Core\Traits\LoggableTrait;
use App\Modules\Cadastros\Models\Fornecedor;

class MovimentacaoFinanceira extends Model
{
    use ValidatableTrait, LoggableTrait;

    protected $table = 'movimentacoes_financeiras';

    protected $fillable = [
        'fornecedor_id',
        'dados_bancarios_id',
        'tipo',
        'valor',
        'data_vencimento',
        'data_pagamento',
        'status',
        'forma_pagamento',
        'numero_documento',
        'descricao',
        'observacoes',
        'comprovante',
        'nota_fiscal'
    ];

    protected $casts = [
        'valor' => 'float',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function dadosBancarios()
    {
        return $this->belongsTo(DadosBancarios::class);
    }

    public function validate()
    {
        $this->clearValidationErrors();

        if (empty($this->fornecedor_id)) {
            $this->addError('fornecedor_id', 'Fornecedor é obrigatório');
        }

        if (empty($this->tipo)) {
            $this->addError('tipo', 'Tipo de movimentação é obrigatório');
        }

        if (empty($this->valor) || $this->valor <= 0) {
            $this->addError('valor', 'Valor deve ser maior que zero');
        }

        if (empty($this->data_vencimento)) {
            $this->addError('data_vencimento', 'Data de vencimento é obrigatória');
        }

        if ($this->status === 'PAGO' && empty($this->data_pagamento)) {
            $this->addError('data_pagamento', 'Data de pagamento é obrigatória para status PAGO');
        }

        if ($this->status === 'PAGO' && empty($this->forma_pagamento)) {
            $this->addError('forma_pagamento', 'Forma de pagamento é obrigatória para status PAGO');
        }

        return !$this->hasValidationErrors();
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        // Se for um pagamento
        if ($this->status === 'PAGO' && empty($this->data_pagamento)) {
            $this->data_pagamento = date('Y-m-d');
        }

        return parent::save();
    }

    public static function getTipos()
    {
        return [
            'PAGAMENTO' => 'Pagamento',
            'ADIANTAMENTO' => 'Adiantamento',
            'REEMBOLSO' => 'Reembolso',
            'ESTORNO' => 'Estorno'
        ];
    }

    public static function getStatus()
    {
        return [
            'PENDENTE' => 'Pendente',
            'APROVADO' => 'Aprovado',
            'PAGO' => 'Pago',
            'CANCELADO' => 'Cancelado'
        ];
    }

    public static function getFormasPagamento()
    {
        return [
            'PIX' => 'PIX',
            'TED' => 'TED',
            'BOLETO' => 'Boleto',
            'CHEQUE' => 'Cheque'
        ];
    }

    public function getValorFormatado()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    public function getDataVencimentoFormatada()
    {
        return $this->data_vencimento ? $this->data_vencimento->format('d/m/Y') : '';
    }

    public function getDataPagamentoFormatada()
    {
        return $this->data_pagamento ? $this->data_pagamento->format('d/m/Y') : '';
    }

    public function isAtrasado()
    {
        if ($this->status !== 'PAGO' && $this->data_vencimento) {
            return $this->data_vencimento < date('Y-m-d');
        }
        return false;
    }

    public function getDiasAtraso()
    {
        if ($this->isAtrasado()) {
            return $this->data_vencimento->diffInDays(now());
        }
        return 0;
    }
}
