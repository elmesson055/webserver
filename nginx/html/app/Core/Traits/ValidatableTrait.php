<?php

namespace App\Core\Traits;

use App\Core\Services\ExternalApiService;

trait ValidatableTrait
{
    protected $externalApi;
    protected $validationErrors = [];

    public function initializeValidatable()
    {
        $this->externalApi = new ExternalApiService();
    }

    /**
     * Adiciona um erro de validação
     */
    protected function addError($field, $message)
    {
        if (!isset($this->validationErrors[$field])) {
            $this->validationErrors[$field] = [];
        }
        $this->validationErrors[$field][] = $message;
    }

    /**
     * Retorna todos os erros de validação
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    /**
     * Limpa os erros de validação
     */
    public function clearValidationErrors()
    {
        $this->validationErrors = [];
    }

    /**
     * Verifica se existem erros de validação
     */
    public function hasValidationErrors()
    {
        return !empty($this->validationErrors);
    }

    /**
     * Valida um CEP usando ViaCEP
     */
    public function validateCep($cep, $field = 'cep')
    {
        $result = $this->externalApi->consultarCep($cep);
        if (!$result['success']) {
            $this->addError($field, $result['message']);
            return false;
        }
        return $result['data'];
    }

    /**
     * Valida um CNPJ
     */
    public function validateCnpj($cnpj, $field = 'cnpj')
    {
        $result = $this->externalApi->validarCnpj($cnpj);
        if (!$result['success']) {
            $this->addError($field, $result['message']);
            return false;
        }
        return true;
    }

    /**
     * Consulta situação do CNPJ na Receita
     */
    public function validateCnpjStatus($cnpj, $field = 'cnpj')
    {
        $result = $this->externalApi->consultarCnpj($cnpj);
        if (!$result['success']) {
            $this->addError($field, $result['message']);
            return false;
        }
        return $result['data'];
    }
}
