-- -----------------------------------------------------
-- Schema custo_extras
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `custo_extras` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `custo_extras`;

-- -----------------------------------------------------
-- Table `fornecedores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `razao_social` VARCHAR(255) NOT NULL,
  `nome_fantasia` VARCHAR(255) NULL,
  `cnpj` VARCHAR(14) NOT NULL,
  `inscricao_estadual` VARCHAR(20) NULL,
  `inscricao_municipal` VARCHAR(20) NULL,
  `email` VARCHAR(255) NOT NULL,
  `telefone` VARCHAR(20) NULL,
  `celular` VARCHAR(20) NULL,
  `cep` VARCHAR(8) NOT NULL,
  `logradouro` VARCHAR(255) NOT NULL,
  `numero` VARCHAR(10) NOT NULL,
  `complemento` VARCHAR(100) NULL,
  `bairro` VARCHAR(100) NOT NULL,
  `cidade` VARCHAR(100) NOT NULL,
  `estado` CHAR(2) NOT NULL,
  `contato` VARCHAR(100) NULL,
  `observacoes` TEXT NULL,
  `situacao` ENUM('ATIVO', 'INATIVO', 'BLOQUEADO', 'PENDENTE') NOT NULL DEFAULT 'PENDENTE',
  `status` ENUM('REGULAR', 'IRREGULAR') NOT NULL DEFAULT 'REGULAR',
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL,
  `saldo` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `limite_credito` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `status_financeiro` ENUM('REGULAR', 'PENDENTE', 'IRREGULAR', 'BLOQUEADO') NOT NULL DEFAULT 'REGULAR',
  `ultima_movimentacao` DATE NULL,
  `dias_atraso` INT NOT NULL DEFAULT 0,
  `valor_em_aberto` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uk_fornecedores_cnpj` (`cnpj` ASC),
  UNIQUE INDEX `uk_fornecedores_email` (`email` ASC),
  INDEX `idx_fornecedores_situacao` (`situacao` ASC),
  INDEX `idx_fornecedores_status` (`status` ASC),
  INDEX `idx_fornecedores_status_financeiro` (`status_financeiro` ASC),
  INDEX `idx_fornecedores_dias_atraso` (`dias_atraso` ASC)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `documentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `documentos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fornecedor_id` BIGINT UNSIGNED NOT NULL,
  `categoria` VARCHAR(50) NOT NULL,
  `tipo` VARCHAR(50) NOT NULL,
  `nome` VARCHAR(255) NOT NULL,
  `descricao` TEXT NULL,
  `arquivo` VARCHAR(255) NOT NULL,
  `mime_type` VARCHAR(100) NOT NULL,
  `tamanho` BIGINT NOT NULL,
  `data_upload` DATE NOT NULL,
  `data_validade` DATE NULL,
  `status` ENUM('PENDENTE', 'APROVADO', 'REJEITADO', 'VENCIDO') NOT NULL DEFAULT 'PENDENTE',
  `observacoes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_documentos_fornecedor_idx` (`fornecedor_id` ASC),
  INDEX `idx_documentos_categoria` (`categoria` ASC),
  INDEX `idx_documentos_status` (`status` ASC),
  INDEX `idx_documentos_data_validade` (`data_validade` ASC),
  CONSTRAINT `fk_documentos_fornecedor`
    FOREIGN KEY (`fornecedor_id`)
    REFERENCES `fornecedores` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `notificacoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notificacoes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fornecedor_id` BIGINT UNSIGNED NOT NULL,
  `tipo` VARCHAR(50) NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `mensagem` TEXT NOT NULL,
  `lida` BOOLEAN NOT NULL DEFAULT FALSE,
  `data_leitura` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_notificacoes_fornecedor_idx` (`fornecedor_id` ASC),
  INDEX `idx_notificacoes_lida` (`lida` ASC),
  INDEX `idx_notificacoes_tipo` (`tipo` ASC),
  CONSTRAINT `fk_notificacoes_fornecedor`
    FOREIGN KEY (`fornecedor_id`)
    REFERENCES `fornecedores` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `dados_bancarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dados_bancarios` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fornecedor_id` BIGINT UNSIGNED NOT NULL,
  `banco` VARCHAR(3) NOT NULL,
  `agencia` VARCHAR(10) NOT NULL,
  `conta` VARCHAR(20) NOT NULL,
  `tipo_conta` ENUM('CORRENTE', 'POUPANCA') NOT NULL,
  `titular` VARCHAR(255) NOT NULL,
  `cpf_cnpj_titular` VARCHAR(14) NOT NULL,
  `pix_tipo` ENUM('CPF', 'CNPJ', 'EMAIL', 'TELEFONE', 'ALEATORIA') NULL,
  `pix_chave` VARCHAR(255) NULL,
  `principal` BOOLEAN NOT NULL DEFAULT FALSE,
  `status` ENUM('ATIVO', 'INATIVO') NOT NULL DEFAULT 'ATIVO',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_dados_bancarios_fornecedor_idx` (`fornecedor_id` ASC),
  INDEX `idx_dados_bancarios_principal` (`principal` ASC),
  INDEX `idx_dados_bancarios_status` (`status` ASC),
  CONSTRAINT `fk_dados_bancarios_fornecedor`
    FOREIGN KEY (`fornecedor_id`)
    REFERENCES `fornecedores` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `movimentacoes_financeiras`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movimentacoes_financeiras` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fornecedor_id` BIGINT UNSIGNED NOT NULL,
  `dados_bancarios_id` BIGINT UNSIGNED NULL,
  `tipo` ENUM('PAGAMENTO', 'ADIANTAMENTO', 'REEMBOLSO', 'ESTORNO') NOT NULL,
  `valor` DECIMAL(15,2) NOT NULL,
  `data_vencimento` DATE NOT NULL,
  `data_pagamento` DATE NULL,
  `status` ENUM('PENDENTE', 'APROVADO', 'PAGO', 'CANCELADO') NOT NULL DEFAULT 'PENDENTE',
  `forma_pagamento` ENUM('PIX', 'TED', 'BOLETO', 'CHEQUE') NULL,
  `numero_documento` VARCHAR(50) NULL,
  `descricao` VARCHAR(255) NOT NULL,
  `observacoes` TEXT NULL,
  `comprovante` VARCHAR(255) NULL,
  `nota_fiscal` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_movimentacoes_fornecedor_idx` (`fornecedor_id` ASC),
  INDEX `fk_movimentacoes_dados_bancarios_idx` (`dados_bancarios_id` ASC),
  INDEX `idx_movimentacoes_tipo` (`tipo` ASC),
  INDEX `idx_movimentacoes_status` (`status` ASC),
  INDEX `idx_movimentacoes_data_vencimento` (`data_vencimento` ASC),
  INDEX `idx_movimentacoes_data_pagamento` (`data_pagamento` ASC),
  CONSTRAINT `fk_movimentacoes_fornecedor`
    FOREIGN KEY (`fornecedor_id`)
    REFERENCES `fornecedores` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_movimentacoes_dados_bancarios`
    FOREIGN KEY (`dados_bancarios_id`)
    REFERENCES `dados_bancarios` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE = InnoDB;
