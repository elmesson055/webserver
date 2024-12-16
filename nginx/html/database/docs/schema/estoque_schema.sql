-- Estrutura do WMS e Controle de Lotes

-- Tabela de Armazéns
CREATE TABLE armazens (
    id_armazem INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    endereco TEXT,
    area_total DECIMAL(10,2),
    ativo BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de Áreas do Armazém
CREATE TABLE areas_armazem (
    id_area INT PRIMARY KEY AUTO_INCREMENT,
    id_armazem INT NOT NULL,
    nome VARCHAR(50) NOT NULL,
    tipo ENUM('RECEBIMENTO', 'ARMAZENAGEM', 'PICKING', 'EXPEDICAO') NOT NULL,
    capacidade_max INT,
    FOREIGN KEY (id_armazem) REFERENCES armazens(id_armazem)
);

-- Tabela de Endereços (Localizações)
CREATE TABLE enderecos (
    id_endereco INT PRIMARY KEY AUTO_INCREMENT,
    id_area INT NOT NULL,
    rua VARCHAR(10),
    prateleira VARCHAR(10),
    nivel VARCHAR(10),
    posicao VARCHAR(10),
    capacidade_kg DECIMAL(10,2),
    capacidade_m3 DECIMAL(10,2),
    ocupado BOOLEAN DEFAULT false,
    FOREIGN KEY (id_area) REFERENCES areas_armazem(id_area),
    UNIQUE KEY endereco_completo (id_area, rua, prateleira, nivel, posicao)
);

-- Tabela de Lotes
CREATE TABLE lotes (
    id_lote INT PRIMARY KEY AUTO_INCREMENT,
    id_produto INT NOT NULL,
    numero_lote VARCHAR(50) NOT NULL,
    data_fabricacao DATE NOT NULL,
    data_validade DATE,
    quantidade_inicial DECIMAL(15,3) NOT NULL,
    quantidade_atual DECIMAL(15,3) NOT NULL,
    status ENUM('QUARENTENA', 'DISPONIVEL', 'BLOQUEADO', 'VENCIDO') DEFAULT 'QUARENTENA',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto),
    UNIQUE KEY lote_produto (id_produto, numero_lote)
);

-- Tabela de Movimentações de Lote
CREATE TABLE movimentacoes_lote (
    id_movimentacao INT PRIMARY KEY AUTO_INCREMENT,
    id_lote INT NOT NULL,
    tipo_movimento ENUM('ENTRADA', 'SAIDA', 'TRANSFERENCIA', 'AJUSTE') NOT NULL,
    quantidade DECIMAL(15,3) NOT NULL,
    id_endereco_origem INT,
    id_endereco_destino INT,
    id_pedido INT,
    motivo TEXT,
    data_movimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT NOT NULL,
    FOREIGN KEY (id_lote) REFERENCES lotes(id_lote),
    FOREIGN KEY (id_endereco_origem) REFERENCES enderecos(id_endereco),
    FOREIGN KEY (id_endereco_destino) REFERENCES enderecos(id_endereco)
);

-- Tabela de Inventário
CREATE TABLE inventarios (
    id_inventario INT PRIMARY KEY AUTO_INCREMENT,
    tipo ENUM('GERAL', 'PARCIAL', 'CICLICO') NOT NULL,
    status ENUM('PLANEJADO', 'EM_ANDAMENTO', 'FINALIZADO', 'CANCELADO') DEFAULT 'PLANEJADO',
    data_inicio TIMESTAMP,
    data_fim TIMESTAMP,
    usuario_responsavel INT NOT NULL,
    observacoes TEXT
);

-- Tabela de Contagens de Inventário
CREATE TABLE contagens_inventario (
    id_contagem INT PRIMARY KEY AUTO_INCREMENT,
    id_inventario INT NOT NULL,
    id_lote INT NOT NULL,
    id_endereco INT NOT NULL,
    quantidade_sistema DECIMAL(15,3) NOT NULL,
    quantidade_contada DECIMAL(15,3),
    divergencia DECIMAL(15,3),
    status ENUM('PENDENTE', 'CONTADO', 'RECONTAGEM', 'AJUSTADO') DEFAULT 'PENDENTE',
    data_contagem TIMESTAMP,
    usuario_contagem INT,
    FOREIGN KEY (id_inventario) REFERENCES inventarios(id_inventario),
    FOREIGN KEY (id_lote) REFERENCES lotes(id_lote),
    FOREIGN KEY (id_endereco) REFERENCES enderecos(id_endereco)
);

-- Views para o WMS

-- View de Ocupação de Endereços
CREATE VIEW vw_ocupacao_enderecos AS
SELECT 
    a.nome AS armazem,
    ar.nome AS area,
    e.rua,
    e.prateleira,
    e.nivel,
    e.posicao,
    e.ocupado,
    COALESCE(COUNT(DISTINCT ml.id_lote), 0) as qtd_lotes,
    COALESCE(SUM(l.quantidade_atual), 0) as quantidade_total
FROM enderecos e
JOIN areas_armazem ar ON e.id_area = ar.id_area
JOIN armazens a ON ar.id_armazem = a.id_armazem
LEFT JOIN movimentacoes_lote ml ON e.id_endereco = ml.id_endereco_destino
LEFT JOIN lotes l ON ml.id_lote = l.id_lote
GROUP BY a.id_armazem, ar.id_area, e.id_endereco;

-- View de Status dos Lotes
CREATE VIEW vw_status_lotes AS
SELECT 
    l.id_lote,
    l.numero_lote,
    p.nome AS produto,
    l.data_fabricacao,
    l.data_validade,
    l.quantidade_inicial,
    l.quantidade_atual,
    l.status,
    e.rua,
    e.prateleira,
    e.nivel,
    e.posicao,
    a.nome AS armazem
FROM lotes l
JOIN produtos p ON l.id_produto = p.id_produto
LEFT JOIN movimentacoes_lote ml ON l.id_lote = ml.id_lote
LEFT JOIN enderecos e ON ml.id_endereco_destino = e.id_endereco
LEFT JOIN areas_armazem ar ON e.id_area = ar.id_area
LEFT JOIN armazens a ON ar.id_armazem = a.id_armazem
WHERE ml.id_movimentacao = (
    SELECT MAX(id_movimentacao)
    FROM movimentacoes_lote
    WHERE id_lote = l.id_lote
);

-- Procedures para Gestão de Lotes e WMS

DELIMITER //

-- Procedure para Entrada de Lote
CREATE PROCEDURE sp_entrada_lote(
    IN p_id_produto INT,
    IN p_numero_lote VARCHAR(50),
    IN p_data_fabricacao DATE,
    IN p_data_validade DATE,
    IN p_quantidade DECIMAL(15,3),
    IN p_id_endereco INT,
    IN p_usuario_id INT
)
BEGIN
    DECLARE v_id_lote INT;
    
    START TRANSACTION;
    
    -- Insere o lote
    INSERT INTO lotes (
        id_produto, 
        numero_lote, 
        data_fabricacao, 
        data_validade, 
        quantidade_inicial, 
        quantidade_atual
    ) VALUES (
        p_id_produto,
        p_numero_lote,
        p_data_fabricacao,
        p_data_validade,
        p_quantidade,
        p_quantidade
    );
    
    SET v_id_lote = LAST_INSERT_ID();
    
    -- Registra a movimentação
    INSERT INTO movimentacoes_lote (
        id_lote,
        tipo_movimento,
        quantidade,
        id_endereco_destino,
        usuario_id
    ) VALUES (
        v_id_lote,
        'ENTRADA',
        p_quantidade,
        p_id_endereco,
        p_usuario_id
    );
    
    -- Atualiza o status do endereço
    UPDATE enderecos 
    SET ocupado = true 
    WHERE id_endereco = p_id_endereco;
    
    COMMIT;
END //

-- Procedure para Transferência de Lote
CREATE PROCEDURE sp_transferir_lote(
    IN p_id_lote INT,
    IN p_quantidade DECIMAL(15,3),
    IN p_id_endereco_origem INT,
    IN p_id_endereco_destino INT,
    IN p_usuario_id INT
)
BEGIN
    DECLARE v_qtd_atual DECIMAL(15,3);
    
    START TRANSACTION;
    
    -- Verifica quantidade disponível
    SELECT quantidade_atual INTO v_qtd_atual
    FROM lotes WHERE id_lote = p_id_lote;
    
    IF v_qtd_atual < p_quantidade THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Quantidade insuficiente no lote';
    END IF;
    
    -- Registra a movimentação
    INSERT INTO movimentacoes_lote (
        id_lote,
        tipo_movimento,
        quantidade,
        id_endereco_origem,
        id_endereco_destino,
        usuario_id
    ) VALUES (
        p_id_lote,
        'TRANSFERENCIA',
        p_quantidade,
        p_id_endereco_origem,
        p_id_endereco_destino,
        p_usuario_id
    );
    
    -- Atualiza os endereços
    UPDATE enderecos 
    SET ocupado = false 
    WHERE id_endereco = p_id_endereco_origem;
    
    UPDATE enderecos 
    SET ocupado = true 
    WHERE id_endereco = p_id_endereco_destino;
    
    COMMIT;
END //

-- Procedure para Iniciar Inventário
CREATE PROCEDURE sp_iniciar_inventario(
    IN p_tipo ENUM('GERAL', 'PARCIAL', 'CICLICO'),
    IN p_usuario_responsavel INT,
    IN p_id_area INT
)
BEGIN
    DECLARE v_id_inventario INT;
    
    START TRANSACTION;
    
    -- Cria o inventário
    INSERT INTO inventarios (
        tipo,
        status,
        data_inicio,
        usuario_responsavel
    ) VALUES (
        p_tipo,
        'EM_ANDAMENTO',
        CURRENT_TIMESTAMP,
        p_usuario_responsavel
    );
    
    SET v_id_inventario = LAST_INSERT_ID();
    
    -- Gera as contagens necessárias
    INSERT INTO contagens_inventario (
        id_inventario,
        id_lote,
        id_endereco,
        quantidade_sistema
    )
    SELECT 
        v_id_inventario,
        l.id_lote,
        ml.id_endereco_destino,
        l.quantidade_atual
    FROM lotes l
    JOIN movimentacoes_lote ml ON l.id_lote = ml.id_lote
    JOIN enderecos e ON ml.id_endereco_destino = e.id_endereco
    WHERE e.id_area = p_id_area
    AND ml.id_movimentacao = (
        SELECT MAX(id_movimentacao)
        FROM movimentacoes_lote
        WHERE id_lote = l.id_lote
    );
    
    COMMIT;
END //

DELIMITER ;

-- Triggers para Controle de Lotes

DELIMITER //

-- Trigger para Validar Data de Validade
CREATE TRIGGER tr_validar_validade_lote
BEFORE INSERT ON lotes
FOR EACH ROW
BEGIN
    IF NEW.data_validade <= NEW.data_fabricacao THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Data de validade deve ser posterior à data de fabricação';
    END IF;
END //

-- Trigger para Atualizar Status do Lote
CREATE TRIGGER tr_atualizar_status_lote
BEFORE UPDATE ON lotes
FOR EACH ROW
BEGIN
    -- Verifica se o lote venceu
    IF NEW.data_validade <= CURRENT_DATE THEN
        SET NEW.status = 'VENCIDO';
    END IF;
    
    -- Verifica se a quantidade zerou
    IF NEW.quantidade_atual = 0 THEN
        SET NEW.status = 'BLOQUEADO';
    END IF;
END //

DELIMITER ;

-- Índices para Otimização

CREATE INDEX idx_lotes_status ON lotes(status);
CREATE INDEX idx_lotes_validade ON lotes(data_validade);
CREATE INDEX idx_movimentacoes_data ON movimentacoes_lote(data_movimento);
CREATE INDEX idx_enderecos_ocupacao ON enderecos(ocupado);
CREATE INDEX idx_contagens_status ON contagens_inventario(status);
