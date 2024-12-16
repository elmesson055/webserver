<?php

namespace App\Helpers;

class Messages {
    private static $messages = null;

    /**
     * Carrega as mensagens do arquivo de configuração
     */
    private static function loadMessages() {
        if (self::$messages === null) {
            self::$messages = require CONFIG_DIR . '/messages.php';
        }
    }

    /**
     * Obtém uma mensagem pelo seu caminho
     * 
     * @param string $path Caminho da mensagem (ex: 'errors.404.message')
     * @param array $params Parâmetros para substituição
     * @return string
     */
    public static function get($path, $params = []) {
        self::loadMessages();
        
        $keys = explode('.', $path);
        $message = self::$messages;
        
        foreach ($keys as $key) {
            if (!isset($message[$key])) {
                return $path; // Retorna o caminho se a mensagem não for encontrada
            }
            $message = $message[$key];
        }
        
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $message = str_replace('{' . $key . '}', $value, $message);
            }
        }
        
        return $message;
    }

    /**
     * Obtém uma mensagem de erro
     * 
     * @param string $code Código do erro (403, 404, 500, db)
     * @param string $part Parte da mensagem (title, heading, message, etc)
     * @return string
     */
    public static function error($code, $part) {
        return self::get("errors.{$code}.{$part}");
    }

    /**
     * Obtém uma mensagem de autenticação
     * 
     * @param string $key Chave da mensagem
     * @return string
     */
    public static function auth($key) {
        return self::get("auth.{$key}");
    }

    /**
     * Obtém uma mensagem de validação
     * 
     * @param string $key Chave da mensagem
     * @param array $params Parâmetros para substituição
     * @return string
     */
    public static function validation($key, $params = []) {
        return self::get("validation.{$key}", $params);
    }

    /**
     * Obtém uma mensagem de sucesso
     * 
     * @param string $key Chave da mensagem
     * @param array $params Parâmetros para substituição
     * @return string
     */
    public static function success($key, $params = []) {
        return self::get("success.{$key}", $params);
    }

    /**
     * Obtém o texto de um botão
     * 
     * @param string $key Chave do botão
     * @return string
     */
    public static function button($key) {
        return self::get("buttons.{$key}");
    }
}
