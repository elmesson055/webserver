-- Estruturas para Integrações e Controles Específicos

-- Controle ANVISA (RDC 319)
CREATE TABLE produtos_controlados (
    id_produto INT PRIMARY KEY,
    numero_registro VARCHAR(50),
    classe_produto VARCHAR(50),
    principio_ativo VARCHAR(100),
    restricoes TEXT,
    temperatura_minima DECIMAL(5,2),
    temperatura_maxima DECIMAL(5,2),
    umidade_minima DECIMAL(5,2),
    umidade_maxima DECIMAL(5,2),
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto)
);

-- Rastreabilidade ANVISA
CREATE TABLE rastreabilidade_produtos (
    id_rastreabilidade INT PRIMARY KEY AUTO_INCREMENT,
    id_produto INT,
    id_lote INT,
    id_nota_entrada INT,
    id_nota_saida INT,
    ius VARCHAR(100), -- Identificador Único de Série
    iuc VARCHAR(100), -- Identificador Único de Carga
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto),
    FOREIGN KEY (id_lote) REFERENCES lotes(id_lote),
    FOREIGN KEY (id_nota_entrada) REFERENCES notas_fiscais(id_nota),
    FOREIGN KEY (id_nota_saida) REFERENCES notas_fiscais(id_nota)
);

-- Documentos de Transporte
CREATE TABLE documentos_transporte (
    id_documento INT PRIMARY KEY AUTO_INCREMENT,
    tipo_documento ENUM('CTE', 'MDFE', 'DACTE', 'CIOT') NOT NULL,
    numero_documento VARCHAR(50) NOT NULL,
    serie VARCHAR(10),
    chave_acesso VARCHAR(44),
    xml_documento TEXT,
    data_emissao DATETIME NOT NULL,
    status ENUM('EMITIDO', 'AUTORIZADO', 'CANCELADO', 'REJEITADO') DEFAULT 'EMITIDO'
);

-- Veículos
CREATE TABLE veiculos (
    id_veiculo INT PRIMARY KEY AUTO_INCREMENT,
    placa VARCHAR(8) NOT NULL,
    tipo_veiculo VARCHAR(50),
    capacidade_kg DECIMAL(10,2),
    capacidade_m3 DECIMAL(10,2),
    controle_temperatura BOOLEAN DEFAULT false,
    temperatura_minima DECIMAL(5,2),
    temperatura_maxima DECIMAL(5,2),
    rntrc VARCHAR(20),
    status ENUM('DISPONIVEL', 'EM_TRANSITO', 'MANUTENCAO') DEFAULT 'DISPONIVEL'
);

-- Monitoramento de Temperatura em Trânsito
CREATE TABLE temperatura_transito (
    id_registro INT PRIMARY KEY AUTO_INCREMENT,
    id_veiculo INT,
    id_documento INT,
    temperatura DECIMAL(5,2),
    umidade DECIMAL(5,2),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('NORMAL', 'ALERTA', 'CRITICO') DEFAULT 'NORMAL',
    FOREIGN KEY (id_veiculo) REFERENCES veiculos(id_veiculo),
    FOREIGN KEY (id_documento) REFERENCES documentos_transporte(id_documento)
);

-- Cross Docking
CREATE TABLE cross_docking (
    id_cross_docking INT PRIMARY KEY AUTO_INCREMENT,
    id_nota_entrada INT,
    id_pedido_saida INT,
    data_entrada TIMESTAMP,
    previsao_saida TIMESTAMP,
    status ENUM('AGENDADO', 'RECEBIDO', 'SEPARADO', 'EXPEDIDO', 'CANCELADO') DEFAULT 'AGENDADO',
    prioridade INT DEFAULT 0,
    dock_entrada VARCHAR(10),
    dock_saida VARCHAR(10),
    FOREIGN KEY (id_nota_entrada) REFERENCES notas_fiscais(id_nota),
    FOREIGN KEY (id_pedido_saida) REFERENCES pedidos_separacao(id_pedido)
);

-- Agendamento de Docas
CREATE TABLE agendamento_docas (
    id_agendamento INT PRIMARY KEY AUTO_INCREMENT,
    tipo_operacao ENUM('RECEBIMENTO', 'EXPEDICAO') NOT NULL,
    data_agendamento DATETIME NOT NULL,
    id_transportadora INT,
    placa_veiculo VARCHAR(8),
    doca VARCHAR(10),
    status ENUM('AGENDADO', 'CHECKIN', 'EM_OPERACAO', 'FINALIZADO', 'CANCELADO') DEFAULT 'AGENDADO',
    tempo_estimado INT, -- em minutos
    id_nota INT,
    id_pedido INT,
    FOREIGN KEY (id_nota) REFERENCES notas_fiscais(id_nota),
    FOREIGN KEY (id_pedido) REFERENCES pedidos_separacao(id_pedido)
);

-- Procedures

DELIMITER //

-- Procedure para Validação ANVISA
CREATE PROCEDURE sp_validar_produto_controlado(
    IN p_id_produto INT,
    IN p_temperatura DECIMAL(5,2),
    IN p_umidade DECIMAL(5,2)
)
BEGIN
    DECLARE v_temp_min DECIMAL(5,2);
    DECLARE v_temp_max DECIMAL(5,2);
    DECLARE v_umid_min DECIMAL(5,2);
    DECLARE v_umid_max DECIMAL(5,2);
    
    -- Busca parâmetros de controle
    SELECT 
        temperatura_minima,
        temperatura_maxima,
        umidade_minima,
        umidade_maxima
    INTO 
        v_temp_min,
        v_temp_max,
        v_umid_min,
        v_umid_max
    FROM produtos_controlados
    WHERE id_produto = p_id_produto;
    
    -- Valida temperatura
    IF p_temperatura < v_temp_min OR p_temperatura > v_temp_max THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Temperatura fora dos limites permitidos pela ANVISA';
    END IF;
    
    -- Valida umidade
    IF p_umidade < v_umid_min OR p_umidade > v_umid_max THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Umidade fora dos limites permitidos pela ANVISA';
    END IF;
END //

-- Procedure para Cross Docking
CREATE PROCEDURE sp_processar_cross_docking(
    IN p_id_nota_entrada INT,
    IN p_id_pedido_saida INT
)
BEGIN
    DECLARE v_doca_entrada VARCHAR(10);
    DECLARE v_doca_saida VARCHAR(10);
    
    START TRANSACTION;
    
    -- Busca docas disponíveis
    SELECT MIN(doca) INTO v_doca_entrada
    FROM agendamento_docas
    WHERE status = 'AGENDADO'
    AND tipo_operacao = 'RECEBIMENTO'
    AND data_agendamento = CURRENT_DATE;
    
    SELECT MIN(doca) INTO v_doca_saida
    FROM agendamento_docas
    WHERE status = 'AGENDADO'
    AND tipo_operacao = 'EXPEDICAO'
    AND data_agendamento = CURRENT_DATE;
    
    -- Registra operação de cross docking
    INSERT INTO cross_docking (
        id_nota_entrada,
        id_pedido_saida,
        data_entrada,
        previsao_saida,
        dock_entrada,
        dock_saida,
        status
    ) VALUES (
        p_id_nota_entrada,
        p_id_pedido_saida,
        CURRENT_TIMESTAMP,
        DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 2 HOUR),
        v_doca_entrada,
        v_doca_saida,
        'RECEBIDO'
    );
    
    -- Atualiza status do pedido
    UPDATE pedidos_separacao
    SET tipo_pedido = 'CROSS_DOCKING',
        status = 'EM_SEPARACAO'
    WHERE id_pedido = p_id_pedido_saida;
    
    COMMIT;
END //

-- Procedure para Monitoramento de Temperatura em Trânsito
CREATE PROCEDURE sp_registrar_temperatura_transito(
    IN p_id_veiculo INT,
    IN p_id_documento INT,
    IN p_temperatura DECIMAL(5,2),
    IN p_umidade DECIMAL(5,2),
    IN p_latitude DECIMAL(10,8),
    IN p_longitude DECIMAL(11,8)
)
BEGIN
    DECLARE v_temp_min DECIMAL(5,2);
    DECLARE v_temp_max DECIMAL(5,2);
    DECLARE v_status VARCHAR(10);
    
    -- Busca limites de temperatura do veículo
    SELECT 
        temperatura_minima,
        temperatura_maxima
    INTO v_temp_min, v_temp_max
    FROM veiculos
    WHERE id_veiculo = p_id_veiculo;
    
    -- Define status
    SET v_status = CASE
        WHEN p_temperatura < v_temp_min OR p_temperatura > v_temp_max THEN 'CRITICO'
        WHEN p_temperatura < (v_temp_min + 1) OR p_temperatura > (v_temp_max - 1) THEN 'ALERTA'
        ELSE 'NORMAL'
    END;
    
    -- Registra temperatura
    INSERT INTO temperatura_transito (
        id_veiculo,
        id_documento,
        temperatura,
        umidade,
        latitude,
        longitude,
        status
    ) VALUES (
        p_id_veiculo,
        p_id_documento,
        p_temperatura,
        p_umidade,
        p_latitude,
        p_longitude,
        v_status
    );
    
    -- Se crítico, gera alerta
    IF v_status = 'CRITICO' THEN
        -- Aqui você pode adicionar sua lógica de notificação
        -- Por exemplo, enviar e-mail, SMS, etc.
        NULL;
    END IF;
END //

DELIMITER ;

-- Índices

CREATE INDEX idx_rastreabilidade_ius ON rastreabilidade_produtos(ius);
CREATE INDEX idx_rastreabilidade_iuc ON rastreabilidade_produtos(iuc);
CREATE INDEX idx_documentos_chave ON documentos_transporte(chave_acesso);
CREATE INDEX idx_temperatura_transito ON temperatura_transito(data_registro, status);
CREATE INDEX idx_agendamento_data ON agendamento_docas(data_agendamento, status);
CREATE INDEX idx_cross_docking_status ON cross_docking(status, prioridade);
