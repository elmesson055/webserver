<?php
namespace App\Modules\Usuarios\Models;

class Usuario {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function listarUsuarios($filtros = []) {
        $sql = "SELECT u.*, f.nome as funcao_nome 
                FROM usuarios u 
                LEFT JOIN funcoes f ON u.funcao_id = f.id 
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filtros['busca'])) {
            $sql .= " AND (u.nome LIKE ? OR u.email LIKE ?)";
            $busca = "%" . $filtros['busca'] . "%";
            $params[] = $busca;
            $params[] = $busca;
        }
        
        if (!empty($filtros['status'])) {
            $sql .= " AND u.status = ?";
            $params[] = $filtros['status'];
        }
        
        $sql .= " ORDER BY u.nome ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function obterUsuario($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function criarUsuario($dados) {
        $sql = "INSERT INTO usuarios (nome, sobrenome, email, senha, funcao_id, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
                
        $stmt = $this->db->prepare($sql);
        $senha_hash = password_hash($dados['senha'], PASSWORD_DEFAULT);
        
        return $stmt->execute([
            $dados['nome'],
            $dados['sobrenome'],
            $dados['email'],
            $senha_hash,
            $dados['funcao_id'],
            'ativo'
        ]);
    }
    
    public function atualizarUsuario($id, $dados) {
        $sql = "UPDATE usuarios SET 
                nome = ?, 
                sobrenome = ?, 
                email = ?, 
                funcao_id = ?, 
                status = ?,
                updated_at = NOW()
                WHERE id = ?";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['sobrenome'],
            $dados['email'],
            $dados['funcao_id'],
            $dados['status'],
            $id
        ]);
    }
    
    public function alterarSenha($id, $nova_senha) {
        $sql = "UPDATE usuarios SET senha = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        return $stmt->execute([$senha_hash, $id]);
    }
    
    public function excluirUsuario($id) {
        // Soft delete - apenas marca como inativo
        $sql = "UPDATE usuarios SET status = 'inativo', updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function listarFuncoes() {
        $stmt = $this->db->prepare("SELECT id, nome FROM funcoes WHERE status = 'ativo'");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
