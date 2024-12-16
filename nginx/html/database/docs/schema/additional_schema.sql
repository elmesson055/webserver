{{ ... }}

-- Módulo Fornecedores

-- Tabela principal de fornecedores
CREATE TABLE fornecedores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    razao_social VARCHAR(200) NOT NULL,
    nome_fantasia VARCHAR(200),
    cnpj VARCHAR(14) NOT NULL UNIQUE,
    inscricao_estadual VARCHAR(20),
    inscricao_municipal VARCHAR(20),
    tipo_empresa VARCHAR(50) NOT NULL,
    porte VARCHAR(20) NOT NULL,
    ramo_atividade VARCHAR(100) NOT NULL,
    website VARCHAR(255),
    email_comercial VARCHAR(255) NOT NULL,
    telefone_principal VARCHAR(20) NOT NULL,
    telefone_secundario VARCHAR(20),
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    rating DECIMAL(2,1),
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_status (status),
    INDEX idx_rating (rating)
);

-- Tabela de endereços de fornecedores
CREATE TABLE fornecedores_enderecos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fornecedor_id INT NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    cep VARCHAR(8) NOT NULL,
    logradouro VARCHAR(200) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    complemento VARCHAR(100),
    bairro VARCHAR(100) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    principal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_fornecedor_principal (fornecedor_id, principal)
);

-- Tabela de contatos de fornecedores
CREATE TABLE fornecedores_contatos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fornecedor_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    cargo VARCHAR(100) NOT NULL,
    departamento VARCHAR(100),
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    celular VARCHAR(20),
    principal BOOLEAN DEFAULT FALSE,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_fornecedor_principal (fornecedor_id, principal)
);

-- Tabela de documentos de fornecedores
CREATE TABLE fornecedores_documentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fornecedor_id INT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    numero VARCHAR(50),
    data_emissao DATE NOT NULL,
    data_validade DATE,
    arquivo_path VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_fornecedor_tipo (fornecedor_id, tipo),
    INDEX idx_validade (data_validade),
    INDEX idx_status (status)
);

-- Tabela de contratos com fornecedores
CREATE TABLE fornecedores_contratos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fornecedor_id INT NOT NULL,
    numero_contrato VARCHAR(50) NOT NULL UNIQUE,
    tipo_contrato VARCHAR(50) NOT NULL,
    objeto TEXT NOT NULL,
    valor DECIMAL(15,2) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    renovacao_automatica BOOLEAN DEFAULT FALSE,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    arquivo_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_fornecedor_status (fornecedor_id, status),
    INDEX idx_data_fim (data_fim)
);

-- Tabela de avaliações de fornecedores
CREATE TABLE fornecedores_avaliacoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fornecedor_id INT NOT NULL,
    avaliador_id INT NOT NULL,
    periodo VARCHAR(7) NOT NULL,
    qualidade INT NOT NULL CHECK (qualidade BETWEEN 0 AND 10),
    prazo INT NOT NULL CHECK (prazo BETWEEN 0 AND 10),
    preco INT NOT NULL CHECK (preco BETWEEN 0 AND 10),
    atendimento INT NOT NULL CHECK (atendimento BETWEEN 0 AND 10),
    media DECIMAL(2,1) NOT NULL,
    comentarios TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id),
    FOREIGN KEY (avaliador_id) REFERENCES usuarios(id),
    UNIQUE KEY unique_avaliacao (fornecedor_id, periodo),
    INDEX idx_fornecedor_periodo (fornecedor_id, periodo)
);

-- Tabela de categorias de fornecedores
CREATE TABLE fornecedores_categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de relacionamento fornecedores-categorias
CREATE TABLE fornecedores_categorias_rel (
    fornecedor_id INT NOT NULL,
    categoria_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    PRIMARY KEY (fornecedor_id, categoria_id),
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id),
    FOREIGN KEY (categoria_id) REFERENCES fornecedores_categorias(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id)
);

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    salt VARCHAR(32) NOT NULL,
    status VARCHAR(20) DEFAULT 'ativo',
    ultimo_login TIMESTAMP NULL,
    tentativas_login INT DEFAULT 0,
    bloqueado_ate TIMESTAMP NULL,
    mfa_ativo BOOLEAN DEFAULT FALSE,
    mfa_secret VARCHAR(32),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_status (status)
);

-- Tabela de Permissões
CREATE TABLE permissoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    modulo VARCHAR(50) NOT NULL,
    acoes JSON NOT NULL,
    recursos JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_modulo (modulo)
);

-- Tabela de Grupos
CREATE TABLE grupos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    permissoes JSON NOT NULL,
    hierarquia INT NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_hierarquia (hierarquia),
    INDEX idx_ativo (ativo)
);

-- Tabela de Sessões
CREATE TABLE sessoes (
    id VARCHAR(64) PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    refresh_token VARCHAR(255),
    ip VARCHAR(45) NOT NULL,
    user_agent TEXT,
    dados JSON,
    expira_em TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_token (token),
    INDEX idx_refresh_token (refresh_token),
    INDEX idx_expira_em (expira_em)
);

-- Tabela de Auditoria
CREATE TABLE auditoria (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    acao VARCHAR(50) NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    recurso_tipo VARCHAR(50) NOT NULL,
    recurso_id VARCHAR(50) NOT NULL,
    dados_antigos JSON,
    dados_novos JSON,
    ip VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_usuario_acao (usuario_id, acao),
    INDEX idx_modulo_recurso (modulo, recurso_tipo, recurso_id)
);

-- Tabela de Usuários-Grupos (Relacionamento N:N)
CREATE TABLE usuarios_grupos (
    usuario_id INT NOT NULL,
    grupo_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    PRIMARY KEY (usuario_id, grupo_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (grupo_id) REFERENCES grupos(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id)
);

-- Módulo Cadastros

-- Tabela de Países
CREATE TABLE cadastros_paises (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    codigo_iso VARCHAR(2) NOT NULL UNIQUE,
    codigo_iso3 VARCHAR(3) NOT NULL UNIQUE,
    codigo_bacen INT UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Estados
CREATE TABLE cadastros_estados (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    uf VARCHAR(2) NOT NULL UNIQUE,
    codigo_ibge INT NOT NULL UNIQUE,
    pais_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (pais_id) REFERENCES cadastros_paises(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Cidades
CREATE TABLE cadastros_cidades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    estado_id INT NOT NULL,
    codigo_ibge INT NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (estado_id) REFERENCES cadastros_estados(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_estado (estado_id)
);

-- Tabela de Bancos
CREATE TABLE cadastros_bancos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo_bacen VARCHAR(3) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    nome_reduzido VARCHAR(50),
    ispb VARCHAR(8) UNIQUE,
    compe VARCHAR(3) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Tipos de Logradouro
CREATE TABLE cadastros_tipos_logradouro (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE,
    abreviacao VARCHAR(10) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Tipos de Telefone
CREATE TABLE cadastros_tipos_telefone (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE,
    mascara VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Tipos de Documento
CREATE TABLE cadastros_tipos_documento (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE,
    mascara VARCHAR(50),
    validacao VARCHAR(255),
    obrigatorio BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Módulo Financeiro

-- Tabela de Contas Bancárias
CREATE TABLE financeiro_contas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    banco_id INT NOT NULL,
    tipo_conta VARCHAR(20) NOT NULL,
    agencia VARCHAR(10) NOT NULL,
    conta VARCHAR(20) NOT NULL,
    digito VARCHAR(2) NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    saldo_inicial DECIMAL(15,2) NOT NULL DEFAULT 0,
    saldo_atual DECIMAL(15,2) NOT NULL DEFAULT 0,
    data_abertura DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'ativa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (banco_id) REFERENCES bancos(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_status (status)
);

-- Tabela de Movimentações Financeiras
CREATE TABLE financeiro_movimentacoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    conta_id INT NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    valor DECIMAL(15,2) NOT NULL,
    data_movimento DATE NOT NULL,
    descricao TEXT NOT NULL,
    categoria_id INT NOT NULL,
    documento VARCHAR(50),
    conciliado BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (conta_id) REFERENCES financeiro_contas(id),
    FOREIGN KEY (categoria_id) REFERENCES financeiro_categorias(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_conta_data (conta_id, data_movimento),
    INDEX idx_categoria (categoria_id)
);

-- Tabela de Contas a Pagar
CREATE TABLE financeiro_contas_pagar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fornecedor_id INT NOT NULL,
    tipo_documento VARCHAR(50) NOT NULL,
    numero_documento VARCHAR(50) NOT NULL,
    valor_total DECIMAL(15,2) NOT NULL,
    data_emissao DATE NOT NULL,
    data_vencimento DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    forma_pagamento VARCHAR(50),
    conta_id INT,
    categoria_id INT NOT NULL,
    observacoes TEXT,
    anexo_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id),
    FOREIGN KEY (conta_id) REFERENCES financeiro_contas(id),
    FOREIGN KEY (categoria_id) REFERENCES financeiro_categorias(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_fornecedor (fornecedor_id),
    INDEX idx_vencimento (data_vencimento),
    INDEX idx_status (status)
);

-- Tabela de Contas a Receber
CREATE TABLE financeiro_contas_receber (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    tipo_documento VARCHAR(50) NOT NULL,
    numero_documento VARCHAR(50) NOT NULL,
    valor_total DECIMAL(15,2) NOT NULL,
    data_emissao DATE NOT NULL,
    data_vencimento DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    forma_recebimento VARCHAR(50),
    conta_id INT,
    categoria_id INT NOT NULL,
    observacoes TEXT,
    anexo_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (conta_id) REFERENCES financeiro_contas(id),
    FOREIGN KEY (categoria_id) REFERENCES financeiro_categorias(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_cliente (cliente_id),
    INDEX idx_vencimento (data_vencimento),
    INDEX idx_status (status)
);

-- Tabela de Categorias Financeiras
CREATE TABLE financeiro_categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    categoria_pai_id INT,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (categoria_pai_id) REFERENCES financeiro_categorias(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    UNIQUE KEY unique_nome_tipo (nome, tipo),
    INDEX idx_tipo (tipo)
);

-- Tabela de Centros de Custo
CREATE TABLE financeiro_centros_custo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    responsavel_id INT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_ativo (ativo)
);

-- Tabela de Rateios
CREATE TABLE financeiro_rateios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_documento VARCHAR(20) NOT NULL,
    documento_id INT NOT NULL,
    centro_custo_id INT NOT NULL,
    percentual DECIMAL(5,2) NOT NULL,
    valor DECIMAL(15,2) NOT NULL,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (centro_custo_id) REFERENCES financeiro_centros_custo(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    INDEX idx_documento (tipo_documento, documento_id)
);

-- Tabela de Conciliação Bancária
CREATE TABLE financeiro_conciliacao (
    id INT PRIMARY KEY AUTO_INCREMENT,
    conta_id INT NOT NULL,
    data_conciliacao DATE NOT NULL,
    saldo_extrato DECIMAL(15,2) NOT NULL,
    saldo_sistema DECIMAL(15,2) NOT NULL,
    diferenca DECIMAL(15,2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (conta_id) REFERENCES financeiro_contas(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    UNIQUE KEY unique_conta_data (conta_id, data_conciliacao),
    INDEX idx_status (status)
);

-- Procedures do Módulo Financeiro

-- Procedure de Fechamento Mensal
DELIMITER //
CREATE PROCEDURE sp_fechamento_mensal(
    IN p_ano INT,
    IN p_mes INT,
    IN p_usuario_id INT
)
BEGIN
    DECLARE v_data_inicio DATE;
    DECLARE v_data_fim DATE;
    DECLARE v_saldo_anterior DECIMAL(15,2);
    DECLARE v_total_receitas DECIMAL(15,2);
    DECLARE v_total_despesas DECIMAL(15,2);
    DECLARE v_resultado DECIMAL(15,2);
    
    -- Define período
    SET v_data_inicio = DATE(CONCAT(p_ano, '-', LPAD(p_mes, 2, '0'), '-01'));
    SET v_data_fim = LAST_DAY(v_data_inicio);
    
    -- Verifica se já existe fechamento para o período
    IF EXISTS (SELECT 1 FROM financeiro_fechamentos 
               WHERE ano = p_ano AND mes = p_mes) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Já existe fechamento para este período';
    END IF;
    
    -- Calcula saldo anterior
    SELECT COALESCE(saldo_final, 0)
    INTO v_saldo_anterior
    FROM financeiro_fechamentos
    WHERE (ano < p_ano) OR (ano = p_ano AND mes < p_mes)
    ORDER BY ano DESC, mes DESC
    LIMIT 1;
    
    -- Calcula totais do período
    SELECT 
        COALESCE(SUM(CASE WHEN tipo IN ('RECEITA', 'RECEBIMENTO') THEN valor ELSE 0 END), 0),
        COALESCE(SUM(CASE WHEN tipo IN ('DESPESA', 'PAGAMENTO') THEN valor ELSE 0 END), 0)
    INTO v_total_receitas, v_total_despesas
    FROM financeiro_movimentacoes
    WHERE DATE(data_movimento) BETWEEN v_data_inicio AND v_data_fim;
    
    -- Calcula resultado
    SET v_resultado = v_saldo_anterior + v_total_receitas - v_total_despesas;
    
    -- Registra fechamento
    INSERT INTO financeiro_fechamentos (
        ano,
        mes,
        data_inicio,
        data_fim,
        saldo_anterior,
        total_receitas,
        total_despesas,
        saldo_final,
        status,
        fechado_por,
        fechado_em
    ) VALUES (
        p_ano,
        p_mes,
        v_data_inicio,
        v_data_fim,
        v_saldo_anterior,
        v_total_receitas,
        v_total_despesas,
        v_resultado,
        'FECHADO',
        p_usuario_id,
        CURRENT_TIMESTAMP
    );
    
    -- Registra log
    INSERT INTO logs_sistema (
        tipo,
        descricao,
        usuario_id,
        data_hora
    ) VALUES (
        'FECHAMENTO_MENSAL',
        CONCAT('Fechamento mensal realizado: ', p_mes, '/', p_ano),
        p_usuario_id,
        CURRENT_TIMESTAMP
    );
END;
//
DELIMITER ;

-- Procedure de Conciliação Bancária
DELIMITER //
CREATE PROCEDURE sp_conciliacao_bancaria(
    IN p_conta_id INT,
    IN p_data_inicio DATE,
    IN p_data_fim DATE,
    IN p_usuario_id INT
)
BEGIN
    DECLARE v_saldo_banco DECIMAL(15,2);
    DECLARE v_saldo_sistema DECIMAL(15,2);
    DECLARE v_diferenca DECIMAL(15,2);
    
    -- Calcula saldo do sistema
    SELECT COALESCE(saldo_atual, 0)
    INTO v_saldo_sistema
    FROM financeiro_contas
    WHERE id = p_conta_id;
    
    -- Inicia processo de conciliação
    INSERT INTO financeiro_conciliacao (
        conta_id,
        data_inicio,
        data_fim,
        saldo_banco,
        saldo_sistema,
        diferenca,
        status,
        conciliado_por,
        data_conciliacao
    ) VALUES (
        p_conta_id,
        p_data_inicio,
        p_data_fim,
        v_saldo_banco,
        v_saldo_sistema,
        v_saldo_banco - v_saldo_sistema,
        'PENDENTE',
        p_usuario_id,
        CURRENT_TIMESTAMP
    );
    
    -- Marca movimentações como conciliadas
    UPDATE financeiro_movimentacoes
    SET conciliado = TRUE,
        data_conciliacao = CURRENT_TIMESTAMP,
        conciliado_por = p_usuario_id
    WHERE conta_id = p_conta_id
    AND DATE(data_movimento) BETWEEN p_data_inicio AND p_data_fim
    AND conciliado = FALSE;
    
    -- Registra log
    INSERT INTO logs_sistema (
        tipo,
        descricao,
        usuario_id,
        data_hora
    ) VALUES (
        'CONCILIACAO_BANCARIA',
        CONCAT('Conciliação bancária realizada para conta ', p_conta_id),
        p_usuario_id,
        CURRENT_TIMESTAMP
    );
END;
//
DELIMITER ;

-- Procedure de Fluxo de Caixa Projetado
DELIMITER //
CREATE PROCEDURE sp_fluxo_caixa_projetado(
    IN p_data_inicio DATE,
    IN p_data_fim DATE,
    IN p_conta_id INT
)
BEGIN
    -- Saldo inicial
    SELECT saldo_atual
    FROM financeiro_contas
    WHERE id = p_conta_id;
    
    -- Receitas previstas
    SELECT 
        data_vencimento,
        SUM(valor_total) as total_receitas,
        COUNT(*) as qtd_receitas
    FROM financeiro_contas_receber
    WHERE status = 'PENDENTE'
    AND data_vencimento BETWEEN p_data_inicio AND p_data_fim
    AND (p_conta_id IS NULL OR conta_id = p_conta_id)
    GROUP BY data_vencimento
    ORDER BY data_vencimento;
    
    -- Despesas previstas
    SELECT 
        data_vencimento,
        SUM(valor_total) as total_despesas,
        COUNT(*) as qtd_despesas
    FROM financeiro_contas_pagar
    WHERE status = 'PENDENTE'
    AND data_vencimento BETWEEN p_data_inicio AND p_data_fim
    AND (p_conta_id IS NULL OR conta_id = p_conta_id)
    GROUP BY data_vencimento
    ORDER BY data_vencimento;
    
    -- Saldo projetado por dia
    WITH RECURSIVE dates AS (
        SELECT p_data_inicio as date
        UNION ALL
        SELECT date + INTERVAL 1 DAY
        FROM dates
        WHERE date < p_data_fim
    ),
    receitas AS (
        SELECT 
            data_vencimento as data,
            SUM(valor_total) as total
        FROM financeiro_contas_receber
        WHERE status = 'PENDENTE'
        AND data_vencimento BETWEEN p_data_inicio AND p_data_fim
        AND (p_conta_id IS NULL OR conta_id = p_conta_id)
        GROUP BY data_vencimento
    ),
    despesas AS (
        SELECT 
            data_vencimento as data,
            SUM(valor_total) as total
        FROM financeiro_contas_pagar
        WHERE status = 'PENDENTE'
        AND data_vencimento BETWEEN p_data_inicio AND p_data_fim
        AND (p_conta_id IS NULL OR conta_id = p_conta_id)
        GROUP BY data_vencimento
    )
    SELECT 
        d.date,
        COALESCE(r.total, 0) as receitas,
        COALESCE(dp.total, 0) as despesas,
        COALESCE(r.total, 0) - COALESCE(dp.total, 0) as resultado_dia,
        SUM(COALESCE(r.total, 0) - COALESCE(dp.total, 0)) OVER (ORDER BY d.date) as saldo_projetado
    FROM dates d
    LEFT JOIN receitas r ON d.date = r.data
    LEFT JOIN despesas dp ON d.date = dp.data
    ORDER BY d.date;
END;
//
DELIMITER ;

-- Triggers do Módulo Financeiro

-- Trigger para atualização de saldo após movimentação
DELIMITER //
CREATE TRIGGER trg_after_insert_movimentacao
AFTER INSERT ON financeiro_movimentacoes
FOR EACH ROW
BEGIN
    DECLARE v_tipo_movimento VARCHAR(20);
    DECLARE v_valor_final DECIMAL(15,2);
    
    -- Define se é crédito ou débito
    SET v_tipo_movimento = NEW.tipo;
    
    -- Calcula o valor final baseado no tipo
    IF v_tipo_movimento IN ('RECEITA', 'RECEBIMENTO') THEN
        SET v_valor_final = NEW.valor;
    ELSE
        SET v_valor_final = -NEW.valor;
    END IF;
    
    -- Atualiza o saldo da conta
    UPDATE financeiro_contas 
    SET saldo_atual = saldo_atual + v_valor_final,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = NEW.conta_id;
    
    -- Registra no histórico de saldo
    INSERT INTO financeiro_historico_saldo (
        conta_id,
        movimentacao_id,
        saldo_anterior,
        valor_movimento,
        saldo_atual,
        tipo_movimento,
        data_movimento
    ) SELECT 
        NEW.conta_id,
        NEW.id,
        saldo_atual - v_valor_final,
        v_valor_final,
        saldo_atual,
        v_tipo_movimento,
        CURRENT_TIMESTAMP
    FROM financeiro_contas
    WHERE id = NEW.conta_id;
END;
//
DELIMITER ;

-- Trigger para notificações de vencimento
DELIMITER //
CREATE TRIGGER trg_after_insert_conta_pagar
AFTER INSERT ON financeiro_contas_pagar
FOR EACH ROW
BEGIN
    -- Cria notificação para 5 dias antes do vencimento
    INSERT INTO notificacoes (
        tipo,
        titulo,
        mensagem,
        data_envio,
        status,
        destinatario_id,
        referencia_tipo,
        referencia_id
    ) VALUES (
        'VENCIMENTO_CONTA',
        'Conta a Pagar - Vencimento Próximo',
        CONCAT('A conta ', NEW.numero_documento, ' vencerá em 5 dias. Valor: R$ ', NEW.valor_total),
        DATE_SUB(NEW.data_vencimento, INTERVAL 5 DAY),
        'PENDENTE',
        NEW.created_by,
        'CONTA_PAGAR',
        NEW.id
    );
    
    -- Cria notificação para o dia do vencimento
    INSERT INTO notificacoes (
        tipo,
        titulo,
        mensagem,
        data_envio,
        status,
        destinatario_id,
        referencia_tipo,
        referencia_id
    ) VALUES (
        'VENCIMENTO_CONTA',
        'Conta a Pagar - Vence Hoje',
        CONCAT('A conta ', NEW.numero_documento, ' vence hoje. Valor: R$ ', NEW.valor_total),
        NEW.data_vencimento,
        'PENDENTE',
        NEW.created_by,
        'CONTA_PAGAR',
        NEW.id
    );
END;
//
DELIMITER ;

-- Trigger para cálculo automático de juros e multas
DELIMITER //
CREATE TRIGGER trg_before_update_conta_pagar
BEFORE UPDATE ON financeiro_contas_pagar
FOR EACH ROW
BEGIN
    DECLARE dias_atraso INT;
    DECLARE valor_juros DECIMAL(15,2);
    DECLARE valor_multa DECIMAL(15,2);
    
    -- Só calcula juros se estiver sendo paga e em atraso
    IF NEW.status = 'PAGO' AND OLD.status != 'PAGO' AND NEW.data_pagamento > OLD.data_vencimento THEN
        -- Calcula dias em atraso
        SET dias_atraso = DATEDIFF(NEW.data_pagamento, OLD.data_vencimento);
        
        -- Calcula multa (2% fixo)
        SET valor_multa = OLD.valor_total * 0.02;
        
        -- Calcula juros (1% ao mês, proporcional aos dias)
        SET valor_juros = OLD.valor_total * (0.01 * dias_atraso / 30);
        
        -- Atualiza valores
        SET NEW.valor_multa = valor_multa;
        SET NEW.valor_juros = valor_juros;
        SET NEW.valor_total = OLD.valor_total + valor_multa + valor_juros;
        SET NEW.dias_atraso = dias_atraso;
    END IF;
END;
//
DELIMITER ;

-- Trigger para atualização de status do fornecedor
DELIMITER //
CREATE TRIGGER trg_after_update_conta_pagar
AFTER UPDATE ON financeiro_contas_pagar
FOR EACH ROW
BEGIN
    DECLARE total_em_atraso DECIMAL(15,2);
    DECLARE maior_atraso INT;
    
    -- Se o status mudou para pago em atraso
    IF NEW.status = 'PAGO' AND OLD.status != 'PAGO' AND NEW.dias_atraso > 0 THEN
        -- Calcula total em atraso e maior atraso do fornecedor
        SELECT 
            COALESCE(SUM(valor_total), 0),
            COALESCE(MAX(dias_atraso), 0)
        INTO total_em_atraso, maior_atraso
        FROM financeiro_contas_pagar
        WHERE fornecedor_id = NEW.fornecedor_id
        AND status = 'PENDENTE'
        AND data_vencimento < CURRENT_DATE;
        
        -- Atualiza status do fornecedor
        UPDATE fornecedores
        SET status_financeiro = 
            CASE 
                WHEN total_em_atraso > 0 AND maior_atraso > 90 THEN 'INADIMPLENTE'
                WHEN total_em_atraso > 0 AND maior_atraso > 30 THEN 'IRREGULAR'
                WHEN maior_atraso > 0 THEN 'REGULAR_COM_RESTRICAO'
                ELSE 'REGULAR'
            END,
            valor_em_aberto = total_em_atraso,
            dias_atraso = maior_atraso,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = NEW.fornecedor_id;
    END IF;
END;
//
DELIMITER ;

-- Trigger para validação de limite de crédito
DELIMITER //
CREATE TRIGGER trg_before_insert_conta_pagar
BEFORE INSERT ON financeiro_contas_pagar
FOR EACH ROW
BEGIN
    DECLARE v_limite_credito DECIMAL(15,2);
    DECLARE v_valor_em_aberto DECIMAL(15,2);
    DECLARE v_novo_total DECIMAL(15,2);
    
    -- Obtém limite de crédito do fornecedor
    SELECT limite_credito, valor_em_aberto
    INTO v_limite_credito, v_valor_em_aberto
    FROM fornecedores
    WHERE id = NEW.fornecedor_id;
    
    -- Calcula novo total
    SET v_novo_total = v_valor_em_aberto + NEW.valor_total;
    
    -- Verifica se ultrapassa limite
    IF v_novo_total > v_limite_credito THEN
        -- Registra tentativa de ultrapassar limite
        INSERT INTO logs_sistema (
            tipo,
            descricao,
            usuario_id,
            data_hora
        ) VALUES (
            'LIMITE_CREDITO_EXCEDIDO',
            CONCAT('Tentativa de lançamento acima do limite. Fornecedor: ', NEW.fornecedor_id, 
                   '. Valor: ', NEW.valor_total,
                   '. Limite disponível: ', v_limite_credito - v_valor_em_aberto),
            NEW.created_by,
            CURRENT_TIMESTAMP
        );
        
        -- Gera erro
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Limite de crédito excedido para este fornecedor';
    END IF;
    
    -- Se tudo ok, atualiza valor em aberto do fornecedor
    UPDATE fornecedores
    SET valor_em_aberto = v_novo_total,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = NEW.fornecedor_id;
END;
//
DELIMITER ;

-- Procedure para análise de crédito
DELIMITER //
CREATE PROCEDURE sp_analise_credito(
    IN p_fornecedor_id INT,
    IN p_valor_solicitado DECIMAL(15,2),
    OUT p_resultado VARCHAR(20),
    OUT p_mensagem VARCHAR(255)
)
BEGIN
    DECLARE v_limite_atual DECIMAL(15,2);
    DECLARE v_valor_em_aberto DECIMAL(15,2);
    DECLARE v_dias_atraso INT;
    DECLARE v_qtd_atrasos INT;
    DECLARE v_score DECIMAL(5,2);
    
    -- Busca informações do fornecedor
    SELECT 
        limite_credito,
        valor_em_aberto,
        dias_atraso,
        (SELECT COUNT(*) 
         FROM financeiro_contas_pagar 
         WHERE fornecedor_id = p_fornecedor_id 
         AND dias_atraso > 0
         AND data_pagamento >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
        ) as qtd_atrasos
    INTO v_limite_atual, v_valor_em_aberto, v_dias_atraso, v_qtd_atrasos
    FROM fornecedores
    WHERE id = p_fornecedor_id;
    
    -- Calcula score baseado em diversos fatores
    SET v_score = 100 - (
        -- Reduz score baseado em dias de atraso atual
        CASE 
            WHEN v_dias_atraso > 90 THEN 50
            WHEN v_dias_atraso > 30 THEN 30
            WHEN v_dias_atraso > 0 THEN 15
            ELSE 0
        END +
        -- Reduz score baseado em quantidade de atrasos nos últimos 6 meses
        (v_qtd_atrasos * 5) +
        -- Reduz score baseado em utilização do limite
        ((v_valor_em_aberto / v_limite_atual) * 20)
    );
    
    -- Define resultado baseado no score e limite disponível
    IF v_score < 30 THEN
        SET p_resultado = 'REPROVADO';
        SET p_mensagem = 'Score muito baixo devido a histórico de atrasos';
    ELSEIF (v_valor_em_aberto + p_valor_solicitado) > v_limite_atual THEN
        SET p_resultado = 'REPROVADO';
        SET p_mensagem = 'Valor solicitado ultrapassa limite de crédito disponível';
    ELSEIF v_score < 60 THEN
        SET p_resultado = 'PENDENTE';
        SET p_mensagem = 'Necessária análise manual devido a score intermediário';
    ELSE
        SET p_resultado = 'APROVADO';
        SET p_mensagem = 'Crédito aprovado automaticamente';
    END IF;
    
    -- Registra análise
    INSERT INTO financeiro_analise_credito (
        fornecedor_id,
        valor_solicitado,
        score,
        resultado,
        mensagem,
        data_analise
    ) VALUES (
        p_fornecedor_id,
        p_valor_solicitado,
        v_score,
        p_resultado,
        p_mensagem,
        CURRENT_TIMESTAMP
    );
END;
//
DELIMITER ;

-- Tabela para histórico de análises de crédito
CREATE TABLE financeiro_analise_credito (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fornecedor_id INT NOT NULL,
    valor_solicitado DECIMAL(15,2) NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    resultado VARCHAR(20) NOT NULL,
    mensagem VARCHAR(255) NOT NULL,
    data_analise TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id),
    INDEX idx_fornecedor_data (fornecedor_id, data_analise)
);

-- Módulo Logs

-- Tabela de Logs do Sistema
CREATE TABLE logs_sistema (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(50) NOT NULL,
    origem VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    dados JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    INDEX idx_tipo (tipo),
    INDEX idx_origem (origem),
    INDEX idx_created_at (created_at)
);

-- Tabela de Logs de Acesso
CREATE TABLE logs_acesso (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    tipo VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    detalhes JSON,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_tipo (tipo),
    INDEX idx_created_at (created_at)
);

-- Tabela de Logs de Auditoria
CREATE TABLE logs_auditoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tabela VARCHAR(100) NOT NULL,
    registro_id INT NOT NULL,
    acao VARCHAR(20) NOT NULL,
    dados_anteriores JSON,
    dados_novos JSON,
    usuario_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_tabela_registro (tabela, registro_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_created_at (created_at)
);

-- Tabela de Logs da API
CREATE TABLE logs_api (
    id INT PRIMARY KEY AUTO_INCREMENT,
    metodo VARCHAR(10) NOT NULL,
    endpoint VARCHAR(255) NOT NULL,
    parametros JSON,
    resposta JSON,
    status_code INT NOT NULL,
    tempo_resposta INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_endpoint (endpoint),
    INDEX idx_status_code (status_code),
    INDEX idx_created_at (created_at)
);

-- Tabela de Configurações de Logs
CREATE TABLE logs_configuracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_log VARCHAR(50) NOT NULL,
    retencao_dias INT NOT NULL,
    nivel_log VARCHAR(20) NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    UNIQUE KEY unique_tipo_log (tipo_log)
);

-- Tabela de Arquivamento de Logs
CREATE TABLE logs_arquivamento (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_log VARCHAR(50) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE NOT NULL,
    total_registros INT NOT NULL,
    arquivo_path VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    INDEX idx_tipo_data (tipo_log, data_inicio, data_fim),
    INDEX idx_status (status)
);

-- Módulo Clientes

-- Tabela principal de clientes
CREATE TABLE clientes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_pessoa CHAR(1) NOT NULL,
    nome_razao_social VARCHAR(200) NOT NULL,
    nome_fantasia VARCHAR(200),
    cpf_cnpj VARCHAR(14) NOT NULL UNIQUE,
    rg_inscricao_estadual VARCHAR(20),
    inscricao_municipal VARCHAR(20),
    data_nascimento_fundacao DATE,
    email_principal VARCHAR(255) NOT NULL,
    telefone_principal VARCHAR(20) NOT NULL,
    telefone_secundario VARCHAR(20),
    observacoes TEXT,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_status (status),
    INDEX idx_tipo_pessoa (tipo_pessoa)
);

-- Tabela de endereços dos clientes
CREATE TABLE clientes_enderecos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    tipo_endereco VARCHAR(50) NOT NULL,
    cep VARCHAR(8) NOT NULL,
    logradouro VARCHAR(200) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    complemento VARCHAR(100),
    bairro VARCHAR(100) NOT NULL,
    cidade_id INT NOT NULL,
    principal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (cidade_id) REFERENCES cadastros_cidades(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_cliente (cliente_id)
);

-- Tabela de contatos dos clientes
CREATE TABLE clientes_contatos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    cargo VARCHAR(100),
    departamento VARCHAR(100),
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    celular VARCHAR(20),
    principal BOOLEAN DEFAULT FALSE,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_cliente (cliente_id)
);

-- Tabela de documentos dos clientes
CREATE TABLE clientes_documentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    tipo_documento_id INT NOT NULL,
    numero_documento VARCHAR(50) NOT NULL,
    data_emissao DATE,
    data_validade DATE,
    orgao_emissor VARCHAR(100),
    arquivo_path VARCHAR(255),
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (tipo_documento_id) REFERENCES cadastros_tipos_documento(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_cliente (cliente_id),
    INDEX idx_tipo_documento (tipo_documento_id)
);

-- Tabela de histórico de interações com clientes
CREATE TABLE clientes_historico (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    tipo_interacao VARCHAR(50) NOT NULL,
    descricao TEXT NOT NULL,
    data_interacao DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    INDEX idx_cliente (cliente_id),
    INDEX idx_data_interacao (data_interacao)
);

-- Módulo Custos Extras

-- Tabela de Custos Extras
CREATE TABLE custos_extras (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_custo_id INT NOT NULL,
    cliente_id INT NOT NULL,
    valor DECIMAL(15,2) NOT NULL,
    data_referencia DATE NOT NULL,
    data_vencimento DATE NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    observacoes TEXT,
    anexo_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (tipo_custo_id) REFERENCES tipos_custos(id),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_tipo_custo (tipo_custo_id),
    INDEX idx_cliente (cliente_id),
    INDEX idx_status (status),
    INDEX idx_data_vencimento (data_vencimento)
);

-- Tabela de Rateios de Custos Extras
CREATE TABLE custos_extras_rateios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    custo_extra_id INT NOT NULL,
    centro_custo_id INT NOT NULL,
    percentual DECIMAL(5,2) NOT NULL,
    valor DECIMAL(15,2) NOT NULL,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (custo_extra_id) REFERENCES custos_extras(id),
    FOREIGN KEY (centro_custo_id) REFERENCES financeiro_centros_custo(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    INDEX idx_custo_extra (custo_extra_id)
);

-- Tabela de Histórico de Status dos Custos Extras
CREATE TABLE custos_extras_historico (
    id INT PRIMARY KEY AUTO_INCREMENT,
    custo_extra_id INT NOT NULL,
    status_anterior VARCHAR(20) NOT NULL,
    status_novo VARCHAR(20) NOT NULL,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (custo_extra_id) REFERENCES custos_extras(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    INDEX idx_custo_extra (custo_extra_id)
);

-- Tabela de Aprovações de Custos Extras
CREATE TABLE custos_extras_aprovacoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    custo_extra_id INT NOT NULL,
    nivel_aprovacao INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    observacoes TEXT,
    data_aprovacao DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (custo_extra_id) REFERENCES custos_extras(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_custo_extra (custo_extra_id),
    INDEX idx_status (status)
);

-- Módulo Documentos

-- Tabela de Categorias de Documentos
CREATE TABLE documentos_categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    categoria_pai_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (categoria_pai_id) REFERENCES documentos_categorias(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Documentos
CREATE TABLE documentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    categoria_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    arquivo_path VARCHAR(255) NOT NULL,
    tipo_arquivo VARCHAR(50) NOT NULL,
    tamanho_bytes BIGINT NOT NULL,
    tags JSON,
    versao VARCHAR(20) NOT NULL DEFAULT '1.0',
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    data_validade DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (categoria_id) REFERENCES documentos_categorias(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_categoria (categoria_id),
    INDEX idx_status (status),
    FULLTEXT INDEX idx_busca (titulo, descricao)
);

-- Tabela de Versões de Documentos
CREATE TABLE documentos_versoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    documento_id INT NOT NULL,
    versao VARCHAR(20) NOT NULL,
    arquivo_path VARCHAR(255) NOT NULL,
    tamanho_bytes BIGINT NOT NULL,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (documento_id) REFERENCES documentos(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    INDEX idx_documento (documento_id)
);

-- Tabela de Compartilhamento de Documentos
CREATE TABLE documentos_compartilhamentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    documento_id INT NOT NULL,
    usuario_id INT,
    grupo_id INT,
    permissao VARCHAR(20) NOT NULL,
    data_inicio DATE NOT NULL,
    data_fim DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (documento_id) REFERENCES documentos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (grupo_id) REFERENCES usuarios_grupos(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_documento (documento_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_grupo (grupo_id)
);

-- Tabela de Histórico de Acesso aos Documentos
CREATE TABLE documentos_acessos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    documento_id INT NOT NULL,
    usuario_id INT NOT NULL,
    tipo_acesso VARCHAR(20) NOT NULL,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (documento_id) REFERENCES documentos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_documento (documento_id),
    INDEX idx_usuario (usuario_id)
);

-- Módulo Layouts

-- Tabela de Layouts
CREATE TABLE layouts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    tipo VARCHAR(50) NOT NULL,
    configuracoes JSON NOT NULL,
    padrao BOOLEAN DEFAULT FALSE,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_tipo (tipo),
    INDEX idx_status (status)
);

-- Tabela de Componentes dos Layouts
CREATE TABLE layouts_componentes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    layout_id INT NOT NULL,
    tipo_componente VARCHAR(50) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    configuracoes JSON NOT NULL,
    posicao INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (layout_id) REFERENCES layouts(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_layout (layout_id)
);

-- Tabela de Layouts por Usuário
CREATE TABLE layouts_usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    layout_id INT NOT NULL,
    configuracoes JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (layout_id) REFERENCES layouts(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    UNIQUE KEY unique_usuario_layout (usuario_id, layout_id)
);

-- Tabela de Temas
CREATE TABLE layouts_temas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    cores JSON NOT NULL,
    fontes JSON NOT NULL,
    padrao BOOLEAN DEFAULT FALSE,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_status (status)
);

-- Tabela de Preferências de Tema por Usuário
CREATE TABLE layouts_temas_usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    tema_id INT NOT NULL,
    configuracoes JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (tema_id) REFERENCES layouts_temas(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    UNIQUE KEY unique_usuario_tema (usuario_id, tema_id)
);

-- Módulo Portal

-- Tabela de Páginas do Portal
CREATE TABLE portal_paginas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    conteudo TEXT NOT NULL,
    meta_descricao VARCHAR(255),
    meta_palavras_chave VARCHAR(255),
    status VARCHAR(20) NOT NULL DEFAULT 'rascunho',
    data_publicacao DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    FULLTEXT INDEX idx_busca (titulo, conteudo)
);

-- Tabela de Menus do Portal
CREATE TABLE portal_menus (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    posicao VARCHAR(50) NOT NULL,
    ordem INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_posicao (posicao),
    INDEX idx_status (status)
);

-- Tabela de Itens de Menu
CREATE TABLE portal_menu_itens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    menu_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    target VARCHAR(20) DEFAULT '_self',
    item_pai_id INT,
    ordem INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (menu_id) REFERENCES portal_menus(id),
    FOREIGN KEY (item_pai_id) REFERENCES portal_menu_itens(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_menu (menu_id),
    INDEX idx_status (status)
);

-- Tabela de Banners
CREATE TABLE portal_banners (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    imagem_path VARCHAR(255) NOT NULL,
    url VARCHAR(255),
    data_inicio DATE NOT NULL,
    data_fim DATE,
    ordem INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_status (status),
    INDEX idx_datas (data_inicio, data_fim)
);

-- Tabela de Widgets do Portal
CREATE TABLE portal_widgets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    configuracoes JSON NOT NULL,
    posicao VARCHAR(50) NOT NULL,
    ordem INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_tipo (tipo),
    INDEX idx_status (status)
);

-- Módulo Status Emissão

-- Tabela de Status de Emissão
CREATE TABLE status_emissao (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    cor VARCHAR(7) NOT NULL,
    icone VARCHAR(50),
    ordem INT NOT NULL,
    permite_edicao BOOLEAN DEFAULT FALSE,
    permite_exclusao BOOLEAN DEFAULT FALSE,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_status (status)
);

-- Tabela de Transições de Status
CREATE TABLE status_emissao_transicoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    status_origem_id INT NOT NULL,
    status_destino_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    requer_aprovacao BOOLEAN DEFAULT FALSE,
    requer_observacao BOOLEAN DEFAULT FALSE,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (status_origem_id) REFERENCES status_emissao(id),
    FOREIGN KEY (status_destino_id) REFERENCES status_emissao(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    UNIQUE KEY unique_transicao (status_origem_id, status_destino_id),
    INDEX idx_status (status)
);

-- Tabela de Histórico de Status
CREATE TABLE status_emissao_historico (
    id INT PRIMARY KEY AUTO_INCREMENT,
    entidade_tipo VARCHAR(50) NOT NULL,
    entidade_id INT NOT NULL,
    status_anterior_id INT,
    status_novo_id INT NOT NULL,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (status_anterior_id) REFERENCES status_emissao(id),
    FOREIGN KEY (status_novo_id) REFERENCES status_emissao(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    INDEX idx_entidade (entidade_tipo, entidade_id)
);

-- Módulo Tipos Custos

-- Tabela de Tipos de Custos
CREATE TABLE tipos_custos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    categoria_id INT,
    requer_aprovacao BOOLEAN DEFAULT FALSE,
    permite_rateio BOOLEAN DEFAULT FALSE,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (categoria_id) REFERENCES tipos_custos_categorias(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_categoria (categoria_id),
    INDEX idx_status (status)
);

-- Tabela de Categorias de Tipos de Custos
CREATE TABLE tipos_custos_categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    categoria_pai_id INT,
    ordem INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (categoria_pai_id) REFERENCES tipos_custos_categorias(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_status (status)
);

-- Tabela de Campos Personalizados de Tipos de Custos
CREATE TABLE tipos_custos_campos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_custo_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    tipo_campo VARCHAR(50) NOT NULL,
    obrigatorio BOOLEAN DEFAULT FALSE,
    configuracoes JSON,
    ordem INT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (tipo_custo_id) REFERENCES tipos_custos(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_tipo_custo (tipo_custo_id),
    INDEX idx_status (status)
);

-- Módulo Auditoria

-- Tabela de Trilha de Auditoria
CREATE TABLE auditoria_trilha (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255),
    entidade_tipo VARCHAR(50) NOT NULL,
    entidade_id INT NOT NULL,
    acao VARCHAR(20) NOT NULL,
    dados_anteriores JSON,
    dados_novos JSON,
    observacoes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_entidade (entidade_tipo, entidade_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_created_at (created_at)
);

-- Tabela de Sessões de Usuário
CREATE TABLE auditoria_sessoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255),
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME,
    status VARCHAR(20) NOT NULL DEFAULT 'ativa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_token (token),
    INDEX idx_status (status)
);

-- Tabela de Eventos de Segurança
CREATE TABLE auditoria_eventos_seguranca (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    tipo_evento VARCHAR(50) NOT NULL,
    descricao TEXT NOT NULL,
    severidade VARCHAR(20) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_tipo_evento (tipo_evento),
    INDEX idx_severidade (severidade)
);

-- Tabela de Configurações de Auditoria
CREATE TABLE auditoria_configuracoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    entidade_tipo VARCHAR(50) NOT NULL,
    acoes_monitoradas JSON NOT NULL,
    retencao_dias INT NOT NULL,
    nivel_detalhe VARCHAR(20) NOT NULL DEFAULT 'normal',
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    UNIQUE KEY unique_entidade_tipo (entidade_tipo)
);

-- Módulo Configurações

-- Tabela de Configurações do Sistema
CREATE TABLE configuracoes_sistema (
    id INT PRIMARY KEY AUTO_INCREMENT,
    chave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    descricao TEXT,
    grupo VARCHAR(50) NOT NULL,
    requer_reinicio BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_grupo (grupo)
);

-- Tabela de Configurações de Email
CREATE TABLE configuracoes_email (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(50) NOT NULL UNIQUE,
    servidor_smtp VARCHAR(255) NOT NULL,
    porta INT NOT NULL,
    usuario VARCHAR(255),
    senha VARCHAR(255),
    ssl_tls BOOLEAN DEFAULT TRUE,
    email_from VARCHAR(255) NOT NULL,
    nome_from VARCHAR(255) NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Templates de Email
CREATE TABLE configuracoes_email_templates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(100) NOT NULL UNIQUE,
    assunto VARCHAR(255) NOT NULL,
    corpo TEXT NOT NULL,
    variaveis JSON,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Configurações de Módulos
CREATE TABLE configuracoes_modulos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    modulo VARCHAR(50) NOT NULL UNIQUE,
    configuracoes JSON NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Parâmetros do Sistema
CREATE TABLE configuracoes_parametros (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(100) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    valor TEXT NOT NULL,
    tipo_valor VARCHAR(50) NOT NULL,
    descricao TEXT,
    editavel BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Módulo Integrações

-- Tabela de Serviços de Integração
CREATE TABLE integracoes_servicos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    tipo VARCHAR(50) NOT NULL,
    configuracoes JSON NOT NULL,
    credenciais JSON,
    url_base VARCHAR(255),
    headers JSON,
    timeout INT DEFAULT 30,
    retry_attempts INT DEFAULT 3,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_tipo (tipo),
    INDEX idx_status (status)
);

-- Tabela de Endpoints de Integração
CREATE TABLE integracoes_endpoints (
    id INT PRIMARY KEY AUTO_INCREMENT,
    servico_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    metodo VARCHAR(10) NOT NULL,
    path VARCHAR(255) NOT NULL,
    parametros JSON,
    headers JSON,
    body_template TEXT,
    response_template TEXT,
    timeout INT,
    retry_attempts INT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (servico_id) REFERENCES integracoes_servicos(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_servico (servico_id)
);

-- Tabela de Logs de Integração
CREATE TABLE integracoes_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    servico_id INT NOT NULL,
    endpoint_id INT,
    tipo_operacao VARCHAR(50) NOT NULL,
    request_data JSON,
    response_data JSON,
    status_code INT,
    sucesso BOOLEAN NOT NULL,
    erro_mensagem TEXT,
    tempo_resposta INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (servico_id) REFERENCES integracoes_servicos(id),
    FOREIGN KEY (endpoint_id) REFERENCES integracoes_endpoints(id),
    INDEX idx_servico (servico_id),
    INDEX idx_endpoint (endpoint_id),
    INDEX idx_created_at (created_at)
);

-- Tabela de Webhooks
CREATE TABLE integracoes_webhooks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    servico_id INT NOT NULL,
    url VARCHAR(255) NOT NULL,
    eventos JSON NOT NULL,
    secret_token VARCHAR(255),
    headers JSON,
    ativo BOOLEAN DEFAULT TRUE,
    ultima_execucao DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (servico_id) REFERENCES integracoes_servicos(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_servico (servico_id)
);

-- Tabela de Filas de Integração
CREATE TABLE integracoes_filas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    servico_id INT NOT NULL,
    endpoint_id INT,
    tipo_operacao VARCHAR(50) NOT NULL,
    prioridade INT DEFAULT 0,
    dados JSON NOT NULL,
    tentativas INT DEFAULT 0,
    max_tentativas INT DEFAULT 3,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    erro_mensagem TEXT,
    agendado_para DATETIME,
    processado_em DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (servico_id) REFERENCES integracoes_servicos(id),
    FOREIGN KEY (endpoint_id) REFERENCES integracoes_endpoints(id),
    INDEX idx_servico (servico_id),
    INDEX idx_status (status),
    INDEX idx_agendado (agendado_para)
);

-- Módulo Notificações

-- Tabela de Tipos de Notificação
CREATE TABLE notificacoes_tipos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(100) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    template TEXT NOT NULL,
    variaveis JSON,
    canais JSON NOT NULL,
    prioridade INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Notificações
CREATE TABLE notificacoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    mensagem TEXT NOT NULL,
    dados JSON,
    lida BOOLEAN DEFAULT FALSE,
    lida_em DATETIME,
    arquivada BOOLEAN DEFAULT FALSE,
    arquivada_em DATETIME,
    prioridade INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_id) REFERENCES notificacoes_tipos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_lida (lida),
    INDEX idx_arquivada (arquivada),
    INDEX idx_created_at (created_at)
);

-- Tabela de Preferências de Notificação
CREATE TABLE notificacoes_preferencias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    tipo_id INT NOT NULL,
    canais JSON NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (tipo_id) REFERENCES notificacoes_tipos(id),
    UNIQUE KEY unique_usuario_tipo (usuario_id, tipo_id)
);

-- Tabela de Envios de Notificação
CREATE TABLE notificacoes_envios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    notificacao_id INT NOT NULL,
    canal VARCHAR(50) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    tentativas INT DEFAULT 0,
    erro_mensagem TEXT,
    enviado_em DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (notificacao_id) REFERENCES notificacoes(id),
    INDEX idx_notificacao (notificacao_id),
    INDEX idx_status (status)
);

-- Módulo Painel

-- Tabela de Dashboards
CREATE TABLE painel_dashboards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    layout JSON NOT NULL,
    compartilhado BOOLEAN DEFAULT FALSE,
    padrao BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Widgets
CREATE TABLE painel_widgets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(50) NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    configuracoes JSON NOT NULL,
    atualizacao_automatica BOOLEAN DEFAULT TRUE,
    intervalo_atualizacao INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_tipo (tipo)
);

-- Tabela de Widgets por Dashboard
CREATE TABLE painel_dashboard_widgets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    dashboard_id INT NOT NULL,
    widget_id INT NOT NULL,
    posicao JSON NOT NULL,
    configuracoes JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (dashboard_id) REFERENCES painel_dashboards(id),
    FOREIGN KEY (widget_id) REFERENCES painel_widgets(id),
    UNIQUE KEY unique_dashboard_widget (dashboard_id, widget_id)
);

-- Tabela de Preferências de Dashboard por Usuário
CREATE TABLE painel_preferencias_usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    dashboard_id INT NOT NULL,
    configuracoes JSON,
    dashboard_padrao BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (dashboard_id) REFERENCES painel_dashboards(id),
    UNIQUE KEY unique_usuario_dashboard (usuario_id, dashboard_id)
);

-- Tabela de Cache de Dados de Widget
CREATE TABLE painel_widget_cache (
    id INT PRIMARY KEY AUTO_INCREMENT,
    widget_id INT NOT NULL,
    dados JSON NOT NULL,
    valido_ate DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (widget_id) REFERENCES painel_widgets(id),
    INDEX idx_widget (widget_id),
    INDEX idx_validade (valido_ate)
);

-- Módulo Relatórios

-- Tabela de Relatórios
CREATE TABLE relatorios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(100) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    tipo VARCHAR(50) NOT NULL,
    template TEXT,
    parametros JSON,
    formato_saida VARCHAR(20) NOT NULL,
    agendamento JSON,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_tipo (tipo)
);

-- Tabela de Execuções de Relatório
CREATE TABLE relatorios_execucoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    relatorio_id INT NOT NULL,
    usuario_id INT NOT NULL,
    parametros_utilizados JSON,
    status VARCHAR(20) NOT NULL DEFAULT 'pendente',
    arquivo_gerado VARCHAR(255),
    erro_mensagem TEXT,
    tempo_execucao INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (relatorio_id) REFERENCES relatorios(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_relatorio (relatorio_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_status (status)
);

-- Tabela de Agendamentos de Relatório
CREATE TABLE relatorios_agendamentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    relatorio_id INT NOT NULL,
    usuario_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    parametros JSON,
    frequencia VARCHAR(50) NOT NULL,
    configuracao_frequencia JSON NOT NULL,
    destinatarios JSON,
    ativo BOOLEAN DEFAULT TRUE,
    ultima_execucao DATETIME,
    proxima_execucao DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (relatorio_id) REFERENCES relatorios(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    INDEX idx_relatorio (relatorio_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_proxima_execucao (proxima_execucao)
);

-- Tabela de Favoritos de Relatório
CREATE TABLE relatorios_favoritos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    relatorio_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (relatorio_id) REFERENCES relatorios(id),
    UNIQUE KEY unique_usuario_relatorio (usuario_id, relatorio_id)
);

-- Módulo Segurança

-- Tabela de Perfis
CREATE TABLE seguranca_perfis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    permissoes JSON NOT NULL,
    padrao BOOLEAN DEFAULT FALSE,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Usuários por Perfil
CREATE TABLE seguranca_usuarios_perfis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    perfil_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (perfil_id) REFERENCES seguranca_perfis(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    UNIQUE KEY unique_usuario_perfil (usuario_id, perfil_id)
);

-- Tabela de Políticas de Senha
CREATE TABLE seguranca_politicas_senha (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    tamanho_minimo INT NOT NULL DEFAULT 8,
    tamanho_maximo INT NOT NULL DEFAULT 32,
    requer_maiusculas BOOLEAN DEFAULT TRUE,
    requer_minusculas BOOLEAN DEFAULT TRUE,
    requer_numeros BOOLEAN DEFAULT TRUE,
    requer_especiais BOOLEAN DEFAULT TRUE,
    validade_dias INT DEFAULT 90,
    historico_senhas INT DEFAULT 5,
    tentativas_maximas INT DEFAULT 5,
    tempo_bloqueio INT DEFAULT 30,
    padrao BOOLEAN DEFAULT FALSE,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id)
);

-- Tabela de Tokens de Acesso
CREATE TABLE seguranca_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    tipo VARCHAR(50) NOT NULL,
    valido_ate DATETIME NOT NULL,
    revogado BOOLEAN DEFAULT FALSE,
    revogado_em DATETIME,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_token (token),
    INDEX idx_validade (valido_ate)
);

-- Tabela de Histórico de Senhas
CREATE TABLE seguranca_historico_senhas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_usuario (usuario_id)
);

-- Tabela de Tentativas de Login
CREATE TABLE seguranca_tentativas_login (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    ip_address VARCHAR(45) NOT NULL,
    user_agent VARCHAR(255),
    sucesso BOOLEAN NOT NULL,
    motivo_falha VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_ip (ip_address),
    INDEX idx_created_at (created_at)
);

-- Tabela de Permissões Customizadas
CREATE TABLE seguranca_permissoes_customizadas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    recurso VARCHAR(100) NOT NULL,
    permissoes JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    updated_by INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (created_by) REFERENCES usuarios(id),
    FOREIGN KEY (updated_by) REFERENCES usuarios(id),
    UNIQUE KEY unique_usuario_recurso (usuario_id, recurso)
);

-- Procedures para Relatórios Financeiros
DELIMITER //

-- Procedure para relatório de posição financeira
CREATE PROCEDURE sp_relatorio_posicao_financeira(
    IN p_data DATE,
    IN p_conta_id INT
)
BEGIN
    -- Tabela temporária para cache do relatório
    CREATE TEMPORARY TABLE IF NOT EXISTS tmp_posicao_financeira (
        conta_id INT,
        conta VARCHAR(100),
        saldo_anterior DECIMAL(15,2),
        creditos_dia DECIMAL(15,2),
        debitos_dia DECIMAL(15,2),
        saldo_atual DECIMAL(15,2),
        qtd_pendente_conciliacao INT,
        data_relatorio DATE
    );
    
    -- Limpa dados anteriores
    DELETE FROM tmp_posicao_financeira WHERE data_relatorio = p_data;
    
    -- Insere dados atualizados
    INSERT INTO tmp_posicao_financeira
    SELECT 
        c.id,
        c.descricao as conta,
        (SELECT COALESCE(SUM(CASE WHEN tipo = 'CREDITO' THEN valor ELSE 0 END), 0)
         FROM financeiro_movimentacoes m 
         WHERE m.conta_id = c.id 
         AND m.tipo = 'CREDITO' 
         AND DATE(m.data_movimento) < p_data) as saldo_anterior,
        COALESCE(creditos.total, 0) as creditos_dia,
        COALESCE(debitos.total, 0) as debitos_dia,
        c.saldo_atual,
        COALESCE(pendentes.total, 0) as qtd_pendente_conciliacao,
        p_data as data_relatorio
    FROM financeiro_contas c
    LEFT JOIN (
        SELECT conta_id, SUM(valor) as total
        FROM financeiro_movimentacoes
        WHERE tipo = 'CREDITO' 
        AND DATE(data_movimento) = p_data
        GROUP BY conta_id
    ) creditos ON creditos.conta_id = c.id
    LEFT JOIN (
        SELECT conta_id, SUM(valor) as total
        FROM financeiro_movimentacoes
        WHERE tipo = 'DEBITO' 
        AND DATE(data_movimento) = p_data
        GROUP BY conta_id
    ) debitos ON debitos.conta_id = c.id
    LEFT JOIN (
        SELECT conta_id, COUNT(*) as total
        FROM financeiro_movimentacoes
        WHERE conciliado = 0
        GROUP BY conta_id
    ) pendentes ON pendentes.conta_id = c.id
    WHERE (p_conta_id IS NULL OR c.id = p_conta_id)
    AND c.status = 'ATIVO';
    
    -- Retorna resultado
    SELECT * FROM tmp_posicao_financeira
    WHERE data_relatorio = p_data
    ORDER BY conta;
END;
//

-- Procedure para relatório de aging list
CREATE PROCEDURE sp_relatorio_aging_list(
    IN p_data_base DATE,
    IN p_fornecedor_id INT
)
BEGIN
    WITH faixas_atraso AS (
        SELECT 
            cp.fornecedor_id,
            f.razao_social,
            SUM(CASE 
                WHEN DATEDIFF(p_data_base, cp.data_vencimento) <= 0 THEN cp.valor_total
                ELSE 0
            END) as a_vencer,
            SUM(CASE 
                WHEN DATEDIFF(p_data_base, cp.data_vencimento) BETWEEN 1 AND 30 THEN cp.valor_total
                ELSE 0
            END) as ate_30_dias,
            SUM(CASE 
                WHEN DATEDIFF(p_data_base, cp.data_vencimento) BETWEEN 31 AND 60 THEN cp.valor_total
                ELSE 0
            END) as ate_60_dias,
            SUM(CASE 
                WHEN DATEDIFF(p_data_base, cp.data_vencimento) BETWEEN 61 AND 90 THEN cp.valor_total
                ELSE 0
            END) as ate_90_dias,
            SUM(CASE 
                WHEN DATEDIFF(p_data_base, cp.data_vencimento) > 90 THEN cp.valor_total
                ELSE 0
            END) as acima_90_dias
        FROM financeiro_contas_pagar cp
        JOIN fornecedores f ON f.id = cp.fornecedor_id
        WHERE cp.status = 'PENDENTE'
        AND (p_fornecedor_id IS NULL OR cp.fornecedor_id = p_fornecedor_id)
        GROUP BY cp.fornecedor_id, f.razao_social
    )
    SELECT 
        fornecedor_id,
        razao_social,
        a_vencer,
        ate_30_dias,
        ate_60_dias,
        ate_90_dias,
        acima_90_dias,
        (ate_30_dias + ate_60_dias + ate_90_dias + acima_90_dias) as total_vencido,
        (a_vencer + ate_30_dias + ate_60_dias + ate_90_dias + acima_90_dias) as total_geral
    FROM faixas_atraso
    ORDER BY total_vencido DESC;
END;
//

-- Procedure para atualização de estatísticas
CREATE PROCEDURE sp_atualizar_estatisticas()
BEGIN
    -- Atualiza estatísticas das tabelas principais
    ANALYZE TABLE financeiro_movimentacoes;
    ANALYZE TABLE financeiro_contas_pagar;
    ANALYZE TABLE financeiro_contas_receber;
    ANALYZE TABLE financeiro_analise_credito;
    
    -- Registra execução
    INSERT INTO logs_sistema (
        tipo,
        descricao,
        data_hora
    ) VALUES (
        'MANUTENCAO',
        'Atualização de estatísticas do módulo financeiro',
        CURRENT_TIMESTAMP
    );
END;
//

-- Procedure para limpeza de cache
CREATE PROCEDURE sp_limpar_cache_financeiro()
BEGIN
    -- Remove dados temporários antigos
    DROP TEMPORARY TABLE IF EXISTS tmp_posicao_financeira;
    DROP TEMPORARY TABLE IF EXISTS tmp_fluxo_caixa;
    
    -- Registra limpeza
    INSERT INTO logs_sistema (
        tipo,
        descricao,
        data_hora
    ) VALUES (
        'MANUTENCAO',
        'Limpeza de cache do módulo financeiro',
        CURRENT_TIMESTAMP
    );
END;
//

-- Event para atualização diária de estatísticas
CREATE EVENT IF NOT EXISTS evt_atualizar_estatisticas
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_DATE + INTERVAL 1 DAY + INTERVAL 1 HOUR
DO
    CALL sp_atualizar_estatisticas();
//

-- Event para limpeza de cache
CREATE EVENT IF NOT EXISTS evt_limpar_cache
ON SCHEDULE EVERY 6 HOUR
STARTS CURRENT_TIMESTAMP + INTERVAL 1 HOUR
DO
    CALL sp_limpar_cache_financeiro();
//

DELIMITER ;

-- Views para Relatórios Financeiros

-- View de Posição Financeira por Conta
CREATE OR REPLACE VIEW vw_posicao_financeira AS
SELECT 
    c.id as conta_id,
    c.descricao as conta,
    c.tipo_conta,
    c.saldo_atual,
    (SELECT COUNT(*) 
     FROM financeiro_movimentacoes m 
     WHERE m.conta_id = c.id 
     AND m.conciliado = 0) as qtd_pendente_conciliacao,
    (SELECT COALESCE(SUM(CASE WHEN tipo = 'CREDITO' THEN valor ELSE 0 END), 0)
     FROM financeiro_movimentacoes m 
     WHERE m.conta_id = c.id 
     AND m.tipo = 'CREDITO' 
     AND DATE(m.data_movimento) = CURRENT_DATE) as creditos_hoje,
    (SELECT COALESCE(SUM(CASE WHEN tipo = 'DEBITO' THEN valor ELSE 0 END), 0)
     FROM financeiro_movimentacoes m 
     WHERE m.conta_id = c.id 
     AND m.tipo = 'DEBITO' 
     AND DATE(m.data_movimento) = CURRENT_DATE) as debitos_hoje
FROM financeiro_contas c
WHERE c.status = 'ATIVO';

-- View de Contas a Pagar por Vencimento
CREATE OR REPLACE VIEW vw_contas_pagar_vencimento AS
SELECT 
    DATE(cp.data_vencimento) as data_vencimento,
    COUNT(*) as qtd_titulos,
    SUM(cp.valor_total) as valor_total,
    SUM(CASE 
        WHEN cp.data_vencimento < CURRENT_DATE THEN cp.valor_total 
        ELSE 0 
    END) as valor_vencido,
    COUNT(CASE 
        WHEN cp.data_vencimento < CURRENT_DATE THEN 1 
    END) as qtd_vencidos
FROM financeiro_contas_pagar cp
WHERE cp.status = 'PENDENTE'
GROUP BY DATE(cp.data_vencimento)
ORDER BY data_vencimento;

-- View de Análise de Crédito por Fornecedor
CREATE OR REPLACE VIEW vw_analise_credito_fornecedor AS
SELECT 
    f.id as fornecedor_id,
    f.razao_social,
    f.limite_credito,
    f.valor_em_aberto,
    (f.limite_credito - f.valor_em_aberto) as limite_disponivel,
    f.dias_atraso,
    (SELECT COUNT(*) 
     FROM financeiro_contas_pagar cp 
     WHERE cp.fornecedor_id = f.id 
     AND cp.status = 'PENDENTE'
     AND cp.data_vencimento < CURRENT_DATE) as titulos_pendentes,
    (SELECT MAX(score) 
     FROM financeiro_analise_credito ac 
     WHERE ac.fornecedor_id = f.id) as ultimo_score,
    (SELECT resultado 
     FROM financeiro_analise_credito ac 
     WHERE ac.fornecedor_id = f.id 
     ORDER BY data_analise DESC LIMIT 1) as ultima_analise
FROM fornecedores f;

-- View de Fluxo de Caixa Realizado
CREATE OR REPLACE VIEW vw_fluxo_caixa_realizado AS
WITH movimentos_dia AS (
    SELECT 
        DATE(m.data_movimento) as data,
        m.conta_id,
        SUM(CASE WHEN m.tipo = 'CREDITO' THEN m.valor ELSE 0 END) as creditos,
        SUM(CASE WHEN m.tipo = 'DEBITO' THEN m.valor ELSE 0 END) as debitos,
        COUNT(*) as qtd_movimentos
    FROM financeiro_movimentacoes m
    WHERE m.data_movimento >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
    GROUP BY DATE(m.data_movimento), m.conta_id
)
SELECT 
    md.data,
    c.descricao as conta,
    md.creditos,
    md.debitos,
    (md.creditos - md.debitos) as resultado_dia,
    md.qtd_movimentos,
    SUM(md.creditos - md.debitos) OVER (
        PARTITION BY md.conta_id 
        ORDER BY md.data
        ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
    ) as saldo_acumulado
FROM movimentos_dia md
JOIN financeiro_contas c ON c.id = md.conta_id
ORDER BY md.data, c.descricao;

-- Índices para Otimização

-- Índices para financeiro_movimentacoes
CREATE INDEX idx_movimentacoes_data ON financeiro_movimentacoes(data_movimento);
CREATE INDEX idx_movimentacoes_conta_data ON financeiro_movimentacoes(conta_id, data_movimento);
CREATE INDEX idx_movimentacoes_conciliacao ON financeiro_movimentacoes(conta_id, conciliado, data_movimento);

-- Índices para contas a pagar
CREATE INDEX idx_contas_pagar_vencimento ON financeiro_contas_pagar(data_vencimento, status);
CREATE INDEX idx_contas_pagar_fornecedor ON financeiro_contas_pagar(fornecedor_id, status);
CREATE INDEX idx_contas_pagar_emissao ON financeiro_contas_pagar(data_emissao);

-- Índices para análise de crédito
CREATE INDEX idx_analise_credito_data ON financeiro_analise_credito(fornecedor_id, data_analise);
CREATE INDEX idx_analise_credito_score ON financeiro_analise_credito(fornecedor_id, score);

-- Índices para categorias
CREATE INDEX idx_categorias_pai ON financeiro_categorias(categoria_pai_id);
CREATE INDEX idx_categorias_tipo ON financeiro_categorias(tipo);

{{ ... }}

-- Views e Procedures para Dashboards em Tempo Real
DELIMITER //

-- View materializada para KPIs financeiros
CREATE OR REPLACE VIEW vw_kpis_financeiros AS
SELECT 
    -- Saldo total
    (SELECT COALESCE(SUM(saldo_atual), 0)
     FROM financeiro_contas 
     WHERE status = 'ATIVO') as saldo_total,
    
    -- Contas a pagar hoje
    (SELECT COALESCE(SUM(valor_total), 0)
     FROM financeiro_contas_pagar
     WHERE status = 'PENDENTE'
     AND data_vencimento = CURRENT_DATE) as pagar_hoje,
    
    -- Contas a receber hoje
    (SELECT COALESCE(SUM(valor_total), 0)
     FROM financeiro_contas_receber
     WHERE status = 'PENDENTE'
     AND data_vencimento = CURRENT_DATE) as receber_hoje,
    
    -- Total vencido
    (SELECT COALESCE(SUM(valor_total), 0)
     FROM financeiro_contas_pagar
     WHERE status = 'PENDENTE'
     AND data_vencimento < CURRENT_DATE) as total_vencido,
    
    -- Quantidade de títulos vencidos
    (SELECT COUNT(*)
     FROM financeiro_contas_pagar
     WHERE status = 'PENDENTE'
     AND data_vencimento < CURRENT_DATE) as qtd_titulos_vencidos,
    
    -- Saldo projetado (30 dias)
    (SELECT 
        (SELECT COALESCE(SUM(saldo_atual), 0)
         FROM financeiro_contas 
         WHERE status = 'ATIVO') +
        (SELECT COALESCE(SUM(valor_total), 0)
         FROM financeiro_contas_receber
         WHERE status = 'PENDENTE'
         AND data_vencimento <= DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY)) -
        (SELECT COALESCE(SUM(valor_total), 0)
         FROM financeiro_contas_pagar
         WHERE status = 'PENDENTE'
         AND data_vencimento <= DATE_ADD(CURRENT_DATE, INTERVAL 30 DAY))
    ) as saldo_projetado_30d;

-- View para gráfico de evolução diária
CREATE OR REPLACE VIEW vw_evolucao_diaria AS
WITH RECURSIVE dates AS (
    SELECT CURRENT_DATE - INTERVAL 29 DAY as date
    UNION ALL
    SELECT date + INTERVAL 1 DAY
    FROM dates
    WHERE date < CURRENT_DATE
),
daily_totals AS (
    SELECT 
        d.date,
        COALESCE(SUM(CASE WHEN m.tipo = 'CREDITO' THEN m.valor ELSE -m.valor END), 0) as resultado_dia
    FROM dates d
    LEFT JOIN financeiro_movimentacoes m ON DATE(m.data_movimento) = d.date
    GROUP BY d.date
)
SELECT 
    date,
    resultado_dia,
    SUM(resultado_dia) OVER (ORDER BY date) as saldo_acumulado
FROM daily_totals;

-- View para distribuição de despesas por categoria
CREATE OR REPLACE VIEW vw_distribuicao_despesas AS
WITH categorias_recursivas AS (
    -- Categorias base
    SELECT 
        id,
        nome,
        categoria_pai_id,
        1 as nivel,
        CAST(nome as CHAR(255)) as hierarquia
    FROM financeiro_categorias
    WHERE categoria_pai_id IS NULL
    AND tipo = 'DESPESA'
    
    UNION ALL
    
    -- Subcategorias
    SELECT 
        c.id,
        c.nome,
        c.categoria_pai_id,
        cr.nivel + 1,
        CONCAT(cr.hierarquia, ' > ', c.nome)
    FROM financeiro_categorias c
    JOIN categorias_recursivas cr ON c.categoria_pai_id = cr.id
)
SELECT 
    cr.id,
    cr.hierarquia as categoria,
    cr.nivel,
    COUNT(cp.id) as qtd_lancamentos,
    COALESCE(SUM(cp.valor_total), 0) as valor_total,
    COALESCE(SUM(cp.valor_total) / NULLIF(
        (SELECT SUM(valor_total) 
         FROM financeiro_contas_pagar 
         WHERE status = 'PENDENTE'
         AND data_vencimento >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
        ), 0
    ) * 100, 0) as percentual
FROM categorias_recursivas cr
LEFT JOIN financeiro_contas_pagar cp ON cp.categoria_id = cr.id
    AND cp.status = 'PENDENTE'
    AND cp.data_vencimento >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
GROUP BY cr.id, cr.hierarquia, cr.nivel
HAVING valor_total > 0
ORDER BY valor_total DESC;

-- Procedure para atualizar dashboard em tempo real
CREATE PROCEDURE sp_atualizar_dashboard(
    OUT p_kpis JSON,
    OUT p_evolucao JSON,
    OUT p_distribuicao JSON
)
BEGIN
    -- KPIs principais
    SELECT JSON_OBJECT(
        'saldo_total', saldo_total,
        'pagar_hoje', pagar_hoje,
        'receber_hoje', receber_hoje,
        'total_vencido', total_vencido,
        'qtd_titulos_vencidos', qtd_titulos_vencidos,
        'saldo_projetado_30d', saldo_projetado_30d
    ) INTO p_kpis
    FROM vw_kpis_financeiros;
    
    -- Evolução diária
    SELECT JSON_ARRAYAGG(
        JSON_OBJECT(
            'data', date,
            'resultado', resultado_dia,
            'acumulado', saldo_acumulado
        )
    ) INTO p_evolucao
    FROM vw_evolucao_diaria;
    
    -- Distribuição de despesas
    SELECT JSON_ARRAYAGG(
        JSON_OBJECT(
            'categoria', categoria,
            'valor', valor_total,
            'percentual', percentual,
            'qtd_lancamentos', qtd_lancamentos
        )
    ) INTO p_distribuicao
    FROM vw_distribuicao_despesas;
END;
//

-- Procedure para notificar alterações significativas
CREATE PROCEDURE sp_monitorar_alteracoes()
BEGIN
    DECLARE v_saldo_anterior DECIMAL(15,2);
    DECLARE v_saldo_atual DECIMAL(15,2);
    DECLARE v_variacao DECIMAL(15,2);
    
    -- Obtém saldo anterior (cache)
    SELECT saldo_total INTO v_saldo_anterior
    FROM vw_kpis_financeiros;
    
    -- Aguarda 1 minuto
    DO SLEEP(60);
    
    -- Obtém saldo atual
    SELECT saldo_total INTO v_saldo_atual
    FROM vw_kpis_financeiros;
    
    -- Calcula variação
    SET v_variacao = ABS((v_saldo_atual - v_saldo_anterior) / v_saldo_anterior * 100);
    
    -- Se variação > 10%, registra alerta
    IF v_variacao > 10 THEN
        INSERT INTO logs_sistema (
            tipo,
            descricao,
            data_hora
        ) VALUES (
            'ALERTA',
            CONCAT('Variação significativa de saldo detectada: ', 
                   ROUND(v_variacao, 2), '%. ',
                   'Anterior: ', v_saldo_anterior,
                   ', Atual: ', v_saldo_atual),
            CURRENT_TIMESTAMP
        );
    END IF;
END;
//

-- Event para monitoramento contínuo
CREATE EVENT IF NOT EXISTS evt_monitorar_alteracoes
ON SCHEDULE EVERY 1 MINUTE
STARTS CURRENT_TIMESTAMP
DO
    CALL sp_monitorar_alteracoes();
//

DELIMITER ;

{{ ... }}
