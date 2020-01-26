USE SOSGAME;

CREATE TABLE IF NOT EXISTS ITEMS(
	`id` SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`id_typeitem` TINYINT UNSIGNED NOT NULL,
	`id_rank` TINYINT UNSIGNED NOT NULL,
	`id_stats` INT UNSIGNED NOT NULL,
	`lvl_demand` SMALLINT UNSIGNED NOT NULL,
	`name` CHAR(63) NOT NULL,
	`description` VARCHAR(255) NOT NULL,
	`cost` MEDIUMINT UNSIGNED NOT NULL,
	`coins` SMALLINT UNSIGNED NOT NULL,
	`stack` SMALLINT UNSIGNED NOT NULL,
	`icon` CHAR(63) NOT NULL
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS TYPES_OF_ITEMS(
	`id` TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`name` CHAR(63) NOT NULL
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS RANKS_OF_ITEMS(
	`id` TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`name` CHAR(63) NOT NULL
) ENGINE = MYISAM;

INSERT INTO RANKS_OF_ITEMS VALUES
(1, "Zwykły"),
(2, "Rzadki"),
(3, "Starożytny"),
(4, "Artefakt");

INSERT INTO TYPES_OF_ITEMS VALUES
(1, "Neutralny"),
(2, "Questowy"),
(3, "Jedzenie"),
(4, "Hełm"),
(5, "Górna część uzbrojeni"),
(6, "Dolna cześć uzbrojenia"),
(7, "Buty"),
(8, "Amulet"),
(9, "Pierścień"),
(10, "Tarcza"),
(11, "Broń jednoręczna"),
(12, "Broń dwuręczna"),
(13, "Broń magiczna");
(14, "Broń dystansowa"),
(15, "Naboje"),
