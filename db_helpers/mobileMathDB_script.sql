-- MySQL Script generated by MySQL Workbench
-- Sun May 21 10:03:02 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mobileMath
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mobileMath
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mobileMath` DEFAULT CHARACTER SET utf8 ;
USE `mobileMath` ;

-- -----------------------------------------------------
-- Table `mobileMath`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mobileMath`.`users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `completions` INT NOT NULL,
  `u_name` VARCHAR(45) NOT NULL,
  `add_rnd` INT NOT NULL,
  `sub_rnd` INT NOT NULL,
  `mul_rnd` INT NOT NULL,
  `div_rnd` INT NOT NULL,
  `totem` VARCHAR(60) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`user_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mobileMath`.`prizes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mobileMath`.`prizes` (
  `prize_id` INT NOT NULL AUTO_INCREMENT,
  `prize_name` VARCHAR(45) NOT NULL,
  `prize_img` VARCHAR(60) NOT NULL,
  `prize_desc` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`prize_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mobileMath`.`user_has_prizes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mobileMath`.`user_has_prizes` (
  `user_prize_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `prize_id` INT NOT NULL,
  PRIMARY KEY (`user_prize_id`),
  INDEX `fk_user_has_prizes_users_idx` (`user_id` ASC),
  INDEX `fk_user_has_prizes_table21_idx` (`prize_id` ASC),
  CONSTRAINT `fk_user_has_prizes_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `mobileMath`.`users` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_prizes_table21`
    FOREIGN KEY (`prize_id`)
    REFERENCES `mobileMath`.`prizes` (`prize_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
