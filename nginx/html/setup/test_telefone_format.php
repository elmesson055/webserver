<?php
require_once __DIR__ . '/../app/init.php';

echo "<h2>Teste de Formatação de Telefone</h2>";
echo "<pre>";

// Função para formatar o telefone
function formatarTelefone($telefone) {
    // Remove tudo que não for número
    $telefone = preg_replace("/[^0-9]/", "", $telefone);
    
    // Formata de acordo com o tamanho
    $len = strlen($telefone);
    if ($len == 11) {
        // Celular com DDD
        return "(" . substr($telefone, 0, 2) . ") " . substr($telefone, 2, 5) . "-" . substr($telefone, 7);
    } elseif ($len == 10) {
        // Telefone fixo com DDD
        return "(" . substr($telefone, 0, 2) . ") " . substr($telefone, 2, 4) . "-" . substr($telefone, 6);
    }
    return $telefone; // Retorna sem formatação se não se encaixar nos padrões
}

try {
    // Buscar todos os fornecedores
    $stmt = $db->query("SELECT id, nome, telefone FROM Fornecedor");
    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Total de fornecedores: " . count($fornecedores) . "\n\n";

    foreach ($fornecedores as $fornecedor) {
        echo "Fornecedor: " . $fornecedor['nome'] . "\n";
        echo "Telefone (banco): " . $fornecedor['telefone'] . "\n";
        echo "Telefone (formatado): " . formatarTelefone($fornecedor['telefone']) . "\n\n";
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}

echo "</pre>";
