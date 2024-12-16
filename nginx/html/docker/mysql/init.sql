-- Criar banco de dados se não existir
CREATE DATABASE IF NOT EXISTS custo_extras;
USE custo_extras;

-- Criar tabelas necessárias
CREATE TABLE IF NOT EXISTS notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    mensagem TEXT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pendente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notificacao_regras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    tipo_evento VARCHAR(100) NOT NULL,
    condicoes JSON,
    acoes JSON,
    status BOOLEAN DEFAULT TRUE,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notificacao_destinatarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notificacao_id INT NOT NULL,
    usuario_id INT NOT NULL,
    lida BOOLEAN DEFAULT FALSE,
    data_leitura TIMESTAMP NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (notificacao_id) REFERENCES notificacoes(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notificacao_historicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    notificacao_id INT NOT NULL,
    acao VARCHAR(100) NOT NULL,
    descricao TEXT,
    usuario_id INT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (notificacao_id) REFERENCES notificacoes(id)
) ENGINE=InnoDB;

-- Criar índices para melhor performance
CREATE INDEX idx_notificacoes_status ON notificacoes(status);
CREATE INDEX idx_notificacoes_tipo ON notificacoes(tipo);
CREATE INDEX idx_notificacao_regras_tipo_evento ON notificacao_regras(tipo_evento);
CREATE INDEX idx_notificacao_destinatarios_usuario ON notificacao_destinatarios(usuario_id);
CREATE INDEX idx_notificacao_destinatarios_lida ON notificacao_destinatarios(lida);

-- Inserir dados iniciais se necessário
INSERT INTO notificacao_regras (nome, tipo_evento, condicoes, acoes, status)
VALUES 
('Nova Aprovação Pendente', 'novo_custo_extra', 
'{"tipo": "aprovacao", "status": "pendente"}',
'{"notificar": ["aprovadores"], "email": true, "push": true}',
true);

-- Criar usuário para a aplicação
CREATE USER IF NOT EXISTS 'app_user'@'%' IDENTIFIED BY 'app_password';
GRANT ALL PRIVILEGES ON custo_extras.* TO 'app_user'@'%';
FLUSH PRIVILEGES;
