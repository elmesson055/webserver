<?php
require_once __DIR__ . '/../app/init.php';

echo "<h2>Teste de Fornecedor</h2>";
echo "<pre>";

try {
    // Inserir um fornecedor de teste
    $stmt = $db->prepare("INSERT INTO Fornecedor (nome, cnpj, telefone, email, observacao) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        'Fornecedor Teste',
        '12345678901234',
        '11999887766',
        'teste@teste.com',
        'Observação de teste'
    ]);
    
    $fornecedor_id = $db->lastInsertId();
    echo "Fornecedor inserido com ID: $fornecedor_id\n\n";

    // Buscar o fornecedor
    $stmt = $db->prepare("SELECT * FROM Fornecedor WHERE id = ?");
    $stmt->execute([$fornecedor_id]);
    $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "Dados do fornecedor:\n";
    echo "Nome: " . $fornecedor['nome'] . "\n";
    echo "CNPJ: " . $fornecedor['cnpj'] . "\n";
    echo "Telefone (banco): " . $fornecedor['telefone'] . "\n";
    
    // Testar a função de formatação
    require_once __DIR__ . '/../public/fornecedores.php';
    echo "Telefone (formatado): " . formatarTelefone($fornecedor['telefone']) . "\n";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}

echo "</pre>";
