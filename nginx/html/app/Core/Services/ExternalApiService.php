<?php

namespace App\Core\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ExternalApiService
{
    private $client;
    private $timeout;
    private $cache;

    public function __construct($timeout = 10)
    {
        $this->client = new Client();
        $this->timeout = $timeout;
    }

    /**
     * Consulta CEP via ViaCEP
     */
    public function consultarCep($cep)
    {
        try {
            $cep = preg_replace('/[^0-9]/', '', $cep);
            $response = $this->client->get("https://viacep.com.br/ws/{$cep}/json/", [
                'timeout' => $this->timeout
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['erro'])) {
                return [
                    'success' => false,
                    'message' => 'CEP não encontrado'
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'cep' => $data['cep'],
                    'logradouro' => $data['logradouro'],
                    'bairro' => $data['bairro'],
                    'cidade' => $data['localidade'],
                    'estado' => $data['uf']
                ]
            ];
        } catch (RequestException $e) {
            return [
                'success' => false,
                'message' => 'Erro ao consultar CEP: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validação sintática de CNPJ
     */
    public function validarCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) != 14) {
            return [
                'success' => false,
                'message' => 'CNPJ deve ter 14 dígitos'
            ];
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return [
                'success' => false,
                'message' => 'CNPJ inválido'
            ];
        }

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return [
                'success' => false,
                'message' => 'CNPJ inválido'
            ];
        }

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;
        if ($cnpj[13] != ($resto < 2 ? 0 : 11 - $resto)) {
            return [
                'success' => false,
                'message' => 'CNPJ inválido'
            ];
        }

        return [
            'success' => true,
            'message' => 'CNPJ válido'
        ];
    }

    /**
     * Consulta situação do CNPJ na Receita Federal
     * Nota: Esta é uma implementação mock, pois a consulta real requer certificado digital
     */
    public function consultarCnpj($cnpj)
    {
        $validacao = $this->validarCnpj($cnpj);
        if (!$validacao['success']) {
            return $validacao;
        }

        // Mock da consulta
        return [
            'success' => true,
            'data' => [
                'cnpj' => $cnpj,
                'situacao' => 'ATIVA',
                'data_situacao' => date('Y-m-d'),
                'motivo_situacao' => null,
                'situacao_especial' => null,
                'data_situacao_especial' => null
            ]
        ];
    }
}
