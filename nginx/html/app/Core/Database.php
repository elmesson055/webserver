<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;
    private $config;

    private function __construct() {
        try {
            error_log("=== Iniciando conexão com o banco de dados ===");
            
            // Carregar configuração
            $database_config = dirname(dirname(dirname(__DIR__))) . '/config/database.php';
            error_log("Carregando configurações do banco: " . $database_config);
            
            if (!file_exists($database_config)) {
                throw new PDOException("Arquivo de configuração não encontrado: " . $database_config);
            }
            
            // Incluir o arquivo e pegar a variável $db_config
            require $database_config;
            if (!isset($db_config) || !is_array($db_config)) {
                throw new PDOException("Configuração do banco de dados inválida");
            }
            $this->config = $db_config;
            
            // Verificar configuração
            $required_fields = ['host', 'dbname', 'username', 'password', 'port', 'charset'];
            foreach ($required_fields as $field) {
                if (!isset($this->config[$field])) {
                    throw new PDOException("Campo de configuração ausente: $field");
                }
                error_log("$field: " . $this->config[$field]);
            }

            // Configura a conexão
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $this->config['host'],
                $this->config['port'],
                $this->config['dbname'],
                $this->config['charset']
            );
            
            error_log("DSN configurada: " . $dsn);

            // Criar conexão com o banco
            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => true
                ]
            );
            
            error_log("Conexão estabelecida com sucesso!");
            
        } catch (PDOException $e) {
            error_log("Erro ao conectar com o banco de dados: " . $e->getMessage());
            throw new PDOException("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function query($sql) {
        return $this->connection->query($sql);
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserializing of the instance
    private function __wakeup() {}
}
