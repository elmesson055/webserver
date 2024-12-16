<?php

namespace Tests\Database;

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $connection;
    private $config;

    protected function setUp(): void
    {
        // Carrega configurações de teste do banco de dados
        $this->config = include __DIR__ . '/../../config/database.php';
    }

    public function testDatabaseConnection()
    {
        try {
            $this->connection = new \PDO(
                "mysql:host={$this->config['host']};dbname={$this->config['database']}",
                $this->config['username'],
                $this->config['password']
            );
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->assertTrue(true, "Conexão com banco de dados estabelecida com sucesso");
        } catch (\PDOException $e) {
            $this->fail("Falha na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public function testDatabaseConfiguration()
    {
        $this->assertNotEmpty($this->config['host'], "Host do banco de dados não pode estar vazio");
        $this->assertNotEmpty($this->config['database'], "Nome do banco de dados não pode estar vazio");
        $this->assertNotEmpty($this->config['username'], "Usuário do banco de dados não pode estar vazio");
        $this->assertNotEmpty($this->config['password'], "Senha do banco de dados não pode estar vazia");
    }
}
