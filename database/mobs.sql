USE SOSGAME;

CREATE TABLE IF NOT EXISTS MOBS(
	`id` MEDIUMINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`id_type_mob` TINYINT UNSIGNED NOT NULL,
	`id_hero` INT UNSIGNED NOT NULL
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS TYPES_OF_MOBS(
	`id` TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`name` CHAR(63) NOT NULL
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS DROPS_FROM_MOBS(
	`id_mob` MEDIUMINT UNSIGNED NOT NULL,
	`id_item` SMALLINT UNSIGNED NOT NULL,
	`amount` TINYINT UNSIGNED NOT NULL,
	`difficult` MEDIUMINT UNSIGNED NOT NULL, /***1/difficult***/

	PRIMARY KEY(`id_mob`, `id_item`)
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS GROUPS_OF_MOBS(
	`id` MEDIUMINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`id_position` INT UNSIGNED NOT NULL,
	`relations` ENUM('neutral', 'aggressive') NOT NULL
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS MOBS_IN_GROUPS(
	`id_group` MEDIUMINT UNSIGNED NOT NULL,
	`id_mob` MEDIUMINT UNSIGNED NOT NULL,
	`index` TINYINT UNSIGNED NOT NULL,

	PRIMARY KEY(`id_group`, `index`)
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS GROUP_OF_MOBS_ELIMINATED(
	`id_server` TINYINT UNSIGNED NOT NULL,
	`id_group` MEDIUMINT UNSIGNED NOT NULL,
	`end_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	PRIMARY KEY(`id_server`, `id_group`)
);

CREATE TABLE IF NOT EXISTS MOBS_DIES(
	`id_server` TINYINT UNSIGNED NOT NULL,
	`id_group` MEDIUMINT UNSIGNED NOT NULL,
	`id_mob` MEDIUMINT UNSIGNED NOT NULL,
	`id_won_general` INT UNSIGNED NOT NULL,
	`end_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	PRIMARY KEY(`id_server`, `id_mob`)
);

INSERT INTO TYPES_OF_MOBS VALUES (null, ""), (null, "Boss");
