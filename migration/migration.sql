CREATE SCHEMA `oscillate` ;

CREATE TABLE `oscillate`.`companies` (
                                         `id` INT NOT NULL AUTO_INCREMENT,
                                         `name` VARCHAR(75) NULL,
                                         `created_at` DATETIME NULL,
                                         `updated_at` DATETIME NULL,
                                         PRIMARY KEY (`id`),
                                         UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE);

CREATE TABLE `oscillate`.`stock_prices` (
                                            `id` INT NOT NULL AUTO_INCREMENT,
                                            `company_id` INT NULL,
                                            `price` DECIMAL(10,2) NULL,
                                            `date` DATE NULL,
                                            `created_at` DATETIME NULL,
                                            `updated_at` DATETIME NULL,
                                            PRIMARY KEY (`id`),
                                            INDEX `fk_company_id_idx` (`company_id` ASC) VISIBLE,
                                            CONSTRAINT `fk_company_id`
                                                FOREIGN KEY (`company_id`)
                                                    REFERENCES `oscillate`.`companies` (`id`)
                                                    ON DELETE NO ACTION
                                                    ON UPDATE NO ACTION);
