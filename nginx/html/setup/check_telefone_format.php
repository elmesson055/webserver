<?php
require_once __DIR__ . '/../app/init.php';

echo "<h2>Verificação de Formato de Telefones</h2>";
echo "<pre>";

try {
    // Buscar todos os telefones
    $stmt = $db->query("SELECT id, nome, telefone FROM Fornecedor");
    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Total de fornecedores: " . count($fornecedores) . "\n\n";

    $problematicos = [];
    foreach ($fornecedores as $fornecedor) {
        $telefone = $fornecedor['telefone'];
        $apenasNumeros = preg_replace("/[^0-9]/", "", $telefone);
        
        // Se o telefone atual contém caracteres não numéricos
        if ($telefone !== $apenasNumeros) {
            $problematicos[] = $fornecedor;
            // Atualizar para manter apenas números
            $stmt = $db->prepare("UPDATE Fornecedor SET telefone = ? WHERE id = ?");
            $stmt->execute([$apenasNumeros, $fornecedor['id']]);
            echo "Corrigido telefone do fornecedor {$fornecedor['nome']}: {$telefone} -> {$apenasNumeros}\n";
        }
    }

    if (empty($problematicos)) {
        echo "Todos os telefones já estão no formato correto (apenas números).\n";
    } else {
        echo "\nTotal de telefones corrigidos: " . count($problematicos) . "\n";
    }

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}

echo "</pre>";
