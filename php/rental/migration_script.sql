-- ----------------------------------------------------------------------------
-- MySQL Workbench Migration
-- Migrated Schemata: tarefaCopy
-- Source Schemata: tarefa
-- Created: Wed Feb  2 21:44:15 2022
-- Workbench Version: 8.0.26
-- ----------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- Schema tarefaCopy
-- ----------------------------------------------------------------------------
DROP SCHEMA IF EXISTS `tarefaCopy` ;
CREATE SCHEMA IF NOT EXISTS `tarefaCopy` ;

-- ----------------------------------------------------------------------------
-- Table tarefaCopy.aluga
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tarefaCopy`.`aluga` (
  `id_aluguer` INT NOT NULL AUTO_INCREMENT,
  `data_inicio` DATETIME NOT NULL,
  `data_fin` DATETIME NULL DEFAULT NULL,
  `prezo_total` DECIMAL(6,2) NULL DEFAULT NULL,
  `devolto` VARCHAR(45) CHARACTER SET 'utf8mb4' NULL DEFAULT NULL,
  `fk_id_produto` INT NULL DEFAULT NULL,
  `cod_cliente` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id_aluguer`),
  INDEX `fk_id_produto_idx` (`fk_id_produto` ASC) VISIBLE,
  CONSTRAINT `fk_id_produto`
    FOREIGN KEY (`fk_id_produto`)
    REFERENCES `tarefaCopy`.`produto` (`id_produto`)
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------------------------------------------------------
-- Table tarefaCopy.comentarios
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tarefaCopy`.`comentarios` (
  `id_comentario` INT NOT NULL AUTO_INCREMENT,
  `fk_nome_usuario` VARCHAR(50) NULL DEFAULT NULL,
  `comentario` VARCHAR(150) NULL DEFAULT NULL,
  `data` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id_comentario`),
  INDEX `fk_comentarios_1_idx` (`fk_nome_usuario` ASC) VISIBLE,
  CONSTRAINT `fk_nome_usuario`
    FOREIGN KEY (`fk_nome_usuario`)
    REFERENCES `tarefaCopy`.`usuarios` (`nome`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------------------------------------------------------
-- Table tarefaCopy.produto
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tarefaCopy`.`produto` (
  `id_produto` INT NOT NULL,
  `nome` VARCHAR(45) NULL DEFAULT NULL,
  `descricion` VARCHAR(200) NULL DEFAULT NULL,
  `familia` VARCHAR(45) NULL DEFAULT NULL,
  `imaxe` VARCHAR(45) NULL DEFAULT NULL,
  `prezo` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id_produto`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

-- ----------------------------------------------------------------------------
-- Table tarefaCopy.usuarios
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tarefaCopy`.`usuarios` (
  `nome` VARCHAR(50) NOT NULL,
  `contrasinal` VARCHAR(100) NOT NULL,
  `nomeCompleto` VARCHAR(150) NULL DEFAULT NULL,
  `email` VARCHAR(45) NULL DEFAULT NULL,
  `data` DATETIME NULL DEFAULT NULL,
  `rol` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`nome`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;
SET FOREIGN_KEY_CHECKS = 1;
