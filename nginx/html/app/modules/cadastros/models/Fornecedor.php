<?php

namespace App\Modules\Cadastros\Models;

use App\Core\Model;
use App\Core\Traits\LoggableTrait;
use App\Core\Traits\ExportableTrait;
use App\Core\Traits\ValidatableTrait;

class Fornecedor extends Model
{
    use LoggableTrait;
    use ExportableTrait;
    use ValidatableTrait;

    protected $table = 'fornecedores';

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'telefone',
        'email',
        'contato',
        'observacoes',
        'situacao',
        'status',
        'dias_atraso',
        'valor_em_aberto',
        'status_financeiro',
        'saldo',
        'limite_credito'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->initializeLoggable();
        $this->initializeValidatable();
    }

    /**
     * Validações personalizadas para Fornecedor
     */
    public function validate()
    {
        $this->clearValidationErrors();

        // Validações básicas
        if (empty($this->razao_social)) {
            $this->addError('razao_social', 'Razão Social é obrigatória');
        }

        if (empty($this->cnpj)) {
            $this->addError('cnpj', 'CNPJ é obrigatório');
        } else {
            // Valida CNPJ
            if ($this->validateCnpj($this->cnpj)) {
                // Se CNPJ é válido, consulta situação
                $situacao = $this->validateCnpjStatus($this->cnpj);
                if ($situacao && $situacao['situacao'] !== 'ATIVA') {
                    $this->addError('cnpj', 'CNPJ está inativo na Receita Federal');
                }
            }
        }

        if (!empty($this->cep)) {
            // Valida e preenche endereço via CEP
            $endereco = $this->validateCep($this->cep);
            if ($endereco) {
                $this->logradouro = $endereco['logradouro'];
                $this->bairro = $endereco['bairro'];
                $this->cidade = $endereco['cidade'];
                $this->estado = $endereco['estado'];
            }
        }

        if (empty($this->email)) {
            $this->addError('email', 'Email é obrigatório');
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'Email inválido');
        }

        return !$this->hasValidationErrors();
    }

    /**
     * Verifica duplicidade de CNPJ
     */
    public function checkDuplicate()
    {
        if (empty($this->cnpj)) {
            return false;
        }

        $query = "SELECT id FROM {$this->table} WHERE cnpj = ? AND id != ?";
        $result = $this->db->query($query, [$this->cnpj, $this->id ?? 0]);

        return !empty($result);
    }

    /**
     * Sobrescreve o método save para incluir validações
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->checkDuplicate()) {
            $this->addError('cnpj', 'CNPJ já cadastrado');
            return false;
        }

        return parent::save();
    }

    /**
     * Define as colunas para exportação
     */
    public function getExportColumns()
    {
        return [
            'ID' => 'id',
            'Razão Social' => 'razao_social',
            'Nome Fantasia' => 'nome_fantasia',
            'CNPJ' => 'cnpj',
            'Inscrição Estadual' => 'inscricao_estadual',
            'CEP' => 'cep',
            'Endereço' => function($row) {
                return "{$row->logradouro}, {$row->numero} {$row->complemento}";
            },
            'Bairro' => 'bairro',
            'Cidade/UF' => function($row) {
                return "{$row->cidade}/{$row->estado}";
            },
            'Telefone' => 'telefone',
            'Email' => 'email',
            'Contato' => 'contato',
            'Situação' => 'situacao'
        ];
    }

    // Relacionamentos
    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class);
    }

    public function dadosBancarios()
    {
        return $this->hasMany(DadosBancarios::class);
    }

    public function movimentacoesFinanceiras()
    {
        return $this->hasMany(MovimentacaoFinanceira::class);
    }

    // Métodos
    public function atualizarStatus()
    {
        $documentosVencidos = $this->documentos()
            ->whereNotNull('validade')
            ->where('validade', '<', now())
            ->count();

        $documentosPendentes = $this->documentos()
            ->whereNull('arquivo')
            ->count();

        if ($documentosVencidos > 0) {
            $this->status = 'Suspenso';
        } elseif ($documentosPendentes > 0) {
            $this->status = 'Pendente';
        } else {
            $this->status = 'Ativo';
        }

        $this->save();
    }

    public function getDadosBancariosPrincipal()
    {
        return $this->dadosBancarios()
            ->where('principal', true)
            ->first();
    }

    public function atualizarStatusFinanceiro()
    {
        $movimentacoes = $this->movimentacoesFinanceiras()
            ->where('status', 'PENDENTE')
            ->where('data_vencimento', '<', date('Y-m-d'))
            ->get();

        $diasAtraso = 0;
        $valorEmAberto = 0;

        foreach ($movimentacoes as $mov) {
            $diasAtraso = max($diasAtraso, $mov->getDiasAtraso());
            $valorEmAberto += $mov->valor;
        }

        $this->dias_atraso = $diasAtraso;
        $this->valor_em_aberto = $valorEmAberto;

        // Define o status financeiro com base nos dias de atraso
        if ($diasAtraso === 0) {
            $this->status_financeiro = 'REGULAR';
        } elseif ($diasAtraso <= 30) {
            $this->status_financeiro = 'PENDENTE';
        } elseif ($diasAtraso <= 90) {
            $this->status_financeiro = 'IRREGULAR';
        } else {
            $this->status_financeiro = 'BLOQUEADO';
        }

        $this->save();
    }

    public function getStatusFinanceiroFormatado()
    {
        $classes = [
            'REGULAR' => 'success',
            'PENDENTE' => 'warning',
            'IRREGULAR' => 'danger',
            'BLOQUEADO' => 'dark'
        ];

        return '<span class="badge bg-' . ($classes[$this->status_financeiro] ?? 'secondary') . '">' . 
               $this->status_financeiro . '</span>';
    }

    public function getSaldoFormatado()
    {
        return 'R$ ' . number_format($this->saldo, 2, ',', '.');
    }

    public function getValorEmAbertoFormatado()
    {
        return 'R$ ' . number_format($this->valor_em_aberto, 2, ',', '.');
    }

    public function getLimiteCreditoFormatado()
    {
        return 'R$ ' . number_format($this->limite_credito, 2, ',', '.');
    }

    public function getLimiteDisponivel()
    {
        return max(0, $this->limite_credito - $this->valor_em_aberto);
    }

    public function getLimiteDisponivelFormatado()
    {
        return 'R$ ' . number_format($this->getLimiteDisponivel(), 2, ',', '.');
    }

    public function getPorcentagemLimiteUtilizado()
    {
        if ($this->limite_credito <= 0) {
            return 100;
        }
        return min(100, ($this->valor_em_aberto / $this->limite_credito) * 100);
    }
}
