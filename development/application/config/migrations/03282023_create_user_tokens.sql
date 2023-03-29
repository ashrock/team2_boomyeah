CREATE TABLE `user_tokens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NULL,
  `token` VARCHAR(255) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_tokens_user_id_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_user_tokens_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;