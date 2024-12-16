-- Estruturas complementares do WMS

-- Controle de Depositantes
CREATE TABLE depositantes (
    id_depositante INT PRIMARY KEY AUTO_INCREMENT,
    razao_social VARCHAR(200) NOT NULL,
    cnpj VARCHAR(14) UNIQUE NOT NULL,
    inscricao_estadual VARCHAR(20),
    regime_tributario ENUM('SIMPLES', 'LUCRO_PRESUMIDO', 'LUCRO_REAL'),
    email_contato VARCHAR(100),
    telefone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contratos de Armazenagem
CREATE TABLE contratos_armazenagem (
    id_contrato INT PRIMARY KEY AUTO_INCREMENT,
    id_depositante INT NOT NULL,
    numero_contrato VARCHAR(50) UNIQUE NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    valor_posicao_palete DECIMAL(10,2),
    valor_movimentacao DECIMAL(10,2),
    valor_minimo_mensal DECIMAL(10,2),
    temperatura_minima DECIMAL(5,2),
    temperatura_maxima DECIMAL(5,2),
    controle_temperatura BOOLEAN DEFAULT false,
    status ENUM('ATIVO', 'SUSPENSO', 'ENCERRADO') DEFAULT 'ATIVO',
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Tabela de Preços por Serviço
CREATE TABLE precos_servicos (
    id_preco INT PRIMARY KEY AUTO_INCREMENT,
    id_contrato INT NOT NULL,
    tipo_servico ENUM('ARMAZENAGEM', 'MOVIMENTACAO', 'PICKING', 'EMBALAGEM', 'CROSS_DOCKING') NOT NULL,
    unidade_medida ENUM('PALETE', 'VOLUME', 'PESO', 'HORA') NOT NULL,
    valor_unitario DECIMAL(10,2) NOT NULL,
    data_vigencia_inicio DATE NOT NULL,
    data_vigencia_fim DATE,
    FOREIGN KEY (id_contrato) REFERENCES contratos_armazenagem(id_contrato)
);

-- Controle de Paletes
CREATE TABLE paletes (
    id_palete INT PRIMARY KEY AUTO_INCREMENT,
    codigo_barras VARCHAR(50) UNIQUE NOT NULL,
    tipo ENUM('PBR', 'CHEP', 'DESCARTAVEL', 'OUTROS') NOT NULL,
    status ENUM('DISPONIVEL', 'EM_USO', 'MANUTENCAO', 'DESCARTADO') DEFAULT 'DISPONIVEL',
    id_depositante INT,
    data_ultima_inspecao DATE,
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Movimentação de Paletes
CREATE TABLE movimentacao_paletes (
    id_movimentacao INT PRIMARY KEY AUTO_INCREMENT,
    id_palete INT NOT NULL,
    tipo_movimento ENUM('ENTRADA', 'SAIDA', 'MANUTENCAO', 'DESCARTE') NOT NULL,
    id_depositante_origem INT,
    id_depositante_destino INT,
    data_movimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT NOT NULL,
    FOREIGN KEY (id_palete) REFERENCES paletes(id_palete),
    FOREIGN KEY (id_depositante_origem) REFERENCES depositantes(id_depositante),
    FOREIGN KEY (id_depositante_destino) REFERENCES depositantes(id_depositante)
);

-- Controle de Temperatura
CREATE TABLE registros_temperatura (
    id_registro INT PRIMARY KEY AUTO_INCREMENT,
    id_area INT NOT NULL,
    temperatura DECIMAL(5,2) NOT NULL,
    umidade DECIMAL(5,2),
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('NORMAL', 'ALERTA', 'CRITICO') DEFAULT 'NORMAL',
    FOREIGN KEY (id_area) REFERENCES areas_armazem(id_area)
);

-- Notas Fiscais
CREATE TABLE notas_fiscais (
    id_nota INT PRIMARY KEY AUTO_INCREMENT,
    id_depositante INT NOT NULL,
    numero_nota VARCHAR(50) NOT NULL,
    serie VARCHAR(10),
    chave_nfe VARCHAR(44) UNIQUE,
    xml_nfe TEXT,
    data_emissao DATETIME NOT NULL,
    data_entrada DATETIME,
    valor_total DECIMAL(15,2),
    status ENUM('AGUARDANDO', 'RECEBIDA', 'PROCESSADA', 'DIVERGENTE', 'CANCELADA') DEFAULT 'AGUARDANDO',
    tipo_operacao ENUM('ENTRADA', 'SAIDA', 'DEVOLUCAO') NOT NULL,
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Itens da Nota Fiscal
CREATE TABLE itens_nota_fiscal (
    id_item INT PRIMARY KEY AUTO_INCREMENT,
    id_nota INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade DECIMAL(15,3) NOT NULL,
    valor_unitario DECIMAL(15,4) NOT NULL,
    valor_total DECIMAL(15,2) NOT NULL,
    numero_lote VARCHAR(50),
    data_validade DATE,
    FOREIGN KEY (id_nota) REFERENCES notas_fiscais(id_nota),
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto)
);

-- Pedidos de Separação
CREATE TABLE pedidos_separacao (
    id_pedido INT PRIMARY KEY AUTO_INCREMENT,
    id_depositante INT NOT NULL,
    numero_pedido VARCHAR(50) NOT NULL,
    tipo_pedido ENUM('NORMAL', 'CROSS_DOCKING', 'URGENTE') DEFAULT 'NORMAL',
    prioridade INT DEFAULT 0,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_limite_separacao DATETIME,
    status ENUM('AGUARDANDO', 'EM_SEPARACAO', 'SEPARADO', 'CONFERIDO', 'EXPEDIDO', 'CANCELADO') DEFAULT 'AGUARDANDO',
    id_nota_fiscal_saida INT,
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante),
    FOREIGN KEY (id_nota_fiscal_saida) REFERENCES notas_fiscais(id_nota)
);

-- Itens do Pedido de Separação
CREATE TABLE itens_pedido_separacao (
    id_item INT PRIMARY KEY AUTO_INCREMENT,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade_solicitada DECIMAL(15,3) NOT NULL,
    quantidade_separada DECIMAL(15,3) DEFAULT 0,
    regra_separacao ENUM('FEFO', 'FIFO', 'LIFO') DEFAULT 'FEFO',
    status ENUM('PENDENTE', 'EM_SEPARACAO', 'SEPARADO', 'CANCELADO') DEFAULT 'PENDENTE',
    FOREIGN KEY (id_pedido) REFERENCES pedidos_separacao(id_pedido),
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto)
);

-- Logística Reversa
CREATE TABLE devolucoes (
    id_devolucao INT PRIMARY KEY AUTO_INCREMENT,
    id_nota_fiscal_origem INT,
    id_depositante INT NOT NULL,
    motivo_devolucao TEXT NOT NULL,
    data_solicitacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('SOLICITADA', 'AUTORIZADA', 'RECEBIDA', 'PROCESSADA', 'RECUSADA') DEFAULT 'SOLICITADA',
    FOREIGN KEY (id_nota_fiscal_origem) REFERENCES notas_fiscais(id_nota),
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Itens da Devolução
CREATE TABLE itens_devolucao (
    id_item INT PRIMARY KEY AUTO_INCREMENT,
    id_devolucao INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade DECIMAL(15,3) NOT NULL,
    condicao ENUM('NOVO', 'AVARIADO', 'VENCIDO') NOT NULL,
    observacao TEXT,
    FOREIGN KEY (id_devolucao) REFERENCES devolucoes(id_devolucao),
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto)
);

-- Faturamento
CREATE TABLE faturas (
    id_fatura INT PRIMARY KEY AUTO_INCREMENT,
    id_depositante INT NOT NULL,
    numero_fatura VARCHAR(50) UNIQUE NOT NULL,
    data_emissao DATE NOT NULL,
    data_vencimento DATE NOT NULL,
    periodo_inicio DATE NOT NULL,
    periodo_fim DATE NOT NULL,
    valor_total DECIMAL(15,2) NOT NULL,
    status ENUM('EMITIDA', 'PAGA', 'VENCIDA', 'CANCELADA') DEFAULT 'EMITIDA',
    FOREIGN KEY (id_depositante) REFERENCES depositantes(id_depositante)
);

-- Itens da Fatura
CREATE TABLE itens_fatura (
    id_item INT PRIMARY KEY AUTO_INCREMENT,
    id_fatura INT NOT NULL,
    tipo_servico ENUM('ARMAZENAGEM', 'MOVIMENTACAO', 'PICKING', 'EMBALAGEM', 'CROSS_DOCKING') NOT NULL,
    quantidade DECIMAL(15,3) NOT NULL,
    valor_unitario DECIMAL(10,2) NOT NULL,
    valor_total DECIMAL(15,2) NOT NULL,
    descricao TEXT,
    FOREIGN KEY (id_fatura) REFERENCES faturas(id_fatura)
);

-- Fotos de Recebimento
CREATE TABLE fotos_recebimento (
    id_foto INT PRIMARY KEY AUTO_INCREMENT,
    id_nota INT NOT NULL,
    caminho_arquivo VARCHAR(255) NOT NULL,
    tipo_foto ENUM('CARGA', 'AVARIA', 'DOCUMENTO', 'OUTROS') NOT NULL,
    data_foto TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT NOT NULL,
    FOREIGN KEY (id_nota) REFERENCES notas_fiscais(id_nota)
);

-- Blacklist
CREATE TABLE blacklist (
    id_blacklist INT PRIMARY KEY AUTO_INCREMENT,
    tipo_documento ENUM('CPF', 'CNPJ', 'PLACA') NOT NULL,
    numero_documento VARCHAR(50) NOT NULL,
    motivo TEXT NOT NULL,
    data_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ATIVO', 'INATIVO') DEFAULT 'ATIVO',
    usuario_inclusao INT NOT NULL,
    UNIQUE KEY unique_documento (tipo_documento, numero_documento)
);

-- Procedures

DELIMITER //

-- Procedure para Recebimento de Nota Fiscal
CREATE PROCEDURE sp_receber_nota_fiscal(
    IN p_id_nota INT,
    IN p_usuario_id INT,
    IN p_temperatura DECIMAL(5,2)
)
BEGIN
    DECLARE v_id_depositante INT;
    DECLARE v_controle_temperatura BOOLEAN;
    DECLARE v_temp_min DECIMAL(5,2);
    DECLARE v_temp_max DECIMAL(5,2);
    
    -- Busca informações do contrato
    SELECT n.id_depositante, c.controle_temperatura, c.temperatura_minima, c.temperatura_maxima
    INTO v_id_depositante, v_controle_temperatura, v_temp_min, v_temp_max
    FROM notas_fiscais n
    JOIN contratos_armazenagem c ON n.id_depositante = c.id_depositante
    WHERE n.id_nota = p_id_nota
    AND c.status = 'ATIVO';
    
    -- Valida temperatura se necessário
    IF v_controle_temperatura AND (p_temperatura < v_temp_min OR p_temperatura > v_temp_max) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Temperatura fora dos limites contratuais';
    END IF;
    
    -- Atualiza status da nota
    UPDATE notas_fiscais 
    SET status = 'RECEBIDA',
        data_entrada = CURRENT_TIMESTAMP
    WHERE id_nota = p_id_nota;
    
    -- Registra temperatura
    IF v_controle_temperatura THEN
        INSERT INTO registros_temperatura (
            id_area,
            temperatura,
            status
        ) VALUES (
            (SELECT id_area FROM areas_armazem WHERE tipo = 'RECEBIMENTO' LIMIT 1),
            p_temperatura,
            CASE 
                WHEN p_temperatura < v_temp_min OR p_temperatura > v_temp_max THEN 'CRITICO'
                WHEN p_temperatura < (v_temp_min + 1) OR p_temperatura > (v_temp_max - 1) THEN 'ALERTA'
                ELSE 'NORMAL'
            END
        );
    END IF;
END //

-- Procedure para Cross Docking
CREATE PROCEDURE sp_cross_docking(
    IN p_id_nota_entrada INT,
    IN p_id_pedido_saida INT,
    IN p_usuario_id INT
)
BEGIN
    DECLARE v_status_nota VARCHAR(20);
    
    -- Verifica status da nota
    SELECT status INTO v_status_nota
    FROM notas_fiscais
    WHERE id_nota = p_id_nota_entrada;
    
    IF v_status_nota != 'RECEBIDA' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Nota fiscal não está recebida';
    END IF;
    
    -- Atualiza pedido para cross docking
    UPDATE pedidos_separacao
    SET tipo_pedido = 'CROSS_DOCKING',
        status = 'EM_SEPARACAO'
    WHERE id_pedido = p_id_pedido_saida;
    
    -- Vincula itens da nota aos itens do pedido
    INSERT INTO itens_pedido_separacao (
        id_pedido,
        id_produto,
        quantidade_solicitada,
        regra_separacao
    )
    SELECT 
        p_id_pedido_saida,
        i.id_produto,
        i.quantidade,
        'FIFO'
    FROM itens_nota_fiscal i
    WHERE i.id_nota = p_id_nota_entrada;
END //

-- Procedure para Gerar Fatura
CREATE PROCEDURE sp_gerar_fatura(
    IN p_id_depositante INT,
    IN p_periodo_inicio DATE,
    IN p_periodo_fim DATE
)
BEGIN
    DECLARE v_id_fatura INT;
    DECLARE v_valor_total DECIMAL(15,2) DEFAULT 0;
    
    START TRANSACTION;
    
    -- Cria fatura
    INSERT INTO faturas (
        id_depositante,
        numero_fatura,
        data_emissao,
        data_vencimento,
        periodo_inicio,
        periodo_fim,
        valor_total
    ) VALUES (
        p_id_depositante,
        CONCAT('FAT', DATE_FORMAT(CURRENT_DATE, '%Y%m'), LPAD(p_id_depositante, 5, '0')),
        CURRENT_DATE,
        DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY),
        p_periodo_inicio,
        p_periodo_fim,
        0
    );
    
    SET v_id_fatura = LAST_INSERT_ID();
    
    -- Insere serviços de armazenagem
    INSERT INTO itens_fatura (
        id_fatura,
        tipo_servico,
        quantidade,
        valor_unitario,
        valor_total,
        descricao
    )
    SELECT 
        v_id_fatura,
        'ARMAZENAGEM',
        COUNT(DISTINCT l.id_lote),
        c.valor_posicao_palete,
        COUNT(DISTINCT l.id_lote) * c.valor_posicao_palete,
        'Armazenagem de paletes'
    FROM lotes l
    JOIN movimentacoes_lote ml ON l.id_lote = ml.id_lote
    JOIN contratos_armazenagem c ON l.id_depositante = c.id_depositante
    WHERE l.id_depositante = p_id_depositante
    AND DATE(ml.data_movimento) BETWEEN p_periodo_inicio AND p_periodo_fim
    GROUP BY c.valor_posicao_palete;
    
    -- Atualiza valor total
    UPDATE faturas
    SET valor_total = (
        SELECT SUM(valor_total)
        FROM itens_fatura
        WHERE id_fatura = v_id_fatura
    )
    WHERE id_fatura = v_id_fatura;
    
    COMMIT;
END //

DELIMITER ;

-- Índices

CREATE INDEX idx_notas_chave ON notas_fiscais(chave_nfe);
CREATE INDEX idx_notas_data ON notas_fiscais(data_emissao);
CREATE INDEX idx_pedidos_status ON pedidos_separacao(status);
CREATE INDEX idx_temperatura ON registros_temperatura(data_registro, status);
CREATE INDEX idx_blacklist_documento ON blacklist(tipo_documento, numero_documento);
CREATE INDEX idx_faturas_periodo ON faturas(periodo_inicio, periodo_fim);
