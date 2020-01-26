USE SOSGAME;

CREATE TABLE IF NOT EXISTS GENERALS(
	`id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`id_hero` INT UNSIGNED NOT NULL,
	`id_position` INT UNSIGNED NOT NULL,
	`id_outfit` SMALLINT UNSIGNED NOT NULL,
	`name` VARCHAR(63) NOT NULL,
	`learnpoints` SMALLINT UNSIGNED NOT NULL,
	`team_size` TINYINT UNSIGNED NOT NULL,
	`eq_max_size` TINYINT UNSIGNED NOT NULL,
	`max_amount_heroes` TINYINT UNSIGNED NOT NULL,
	`cash` INT UNSIGNED NOT NULL
);

CREATE TABLE IF NOT EXISTS GENERALS_PRESETS(
	`id_character` SMALLINT UNSIGNED NOT NULL,
	`id_race` TINYINT UNSIGNED NOT NULL,
	`team_size` TINYINT UNSIGNED NOT NULL,

	PRIMARY KEY(`id_character`, `id_race`)
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS GENERALS_DIES(
	`id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`end_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS EQ_GENERALS_OF_ITEMS(
	`id_owner` INT UNSIGNED NOT NULL,
	`id_subject` SMALLINT UNSIGNED NOT NULL,
	`index` SMALLINT UNSIGNED NOT NULL,
	`amount` SMALLINT UNSIGNED NOT NULL,

	PRIMARY KEY(`id_owner`, `index`)
);

CREATE TABLE IF NOT EXISTS TEAMS(
	`id_owner` INT NOT NULL,
	`id_subject` INT NOT NULL,
	`index` TINYINT NOT NULL,
	`is_temp` BOOLEAN NOT NULL,

	PRIMARY KEY (`id_owner`, `id_subject`, `index`)
);

CREATE TABLE IF NOT EXISTS EQ_GENERALS_OF_HEROES(
	`id` INT UNSIGNED NOT NULL,
	`id_hero` INT UNSIGNED NOT NULL,
	`is_temp` BOOLEAN NOT NULL,

	 PRIMARY KEY(`id`, `id_hero`)
);

CREATE TABLE IF NOT EXISTS FRIENDS_LIST(
	`id` INT UNSIGNED NOT NULL,
	`id_general` INT UNSIGNED NOT NULL,

	PRIMARY KEY(`id`, `id_general`)
);

CREATE TABLE IF NOT EXISTS ENEMIES_LIST(
	`id` INT UNSIGNED NOT NULL,
	`id_general` INT UNSIGNED NOT NULL,

	PRIMARY KEY(`id`, `id_general`)
);

CREATE TABLE IF NOT EXISTS BATTLES(
	`id_team` INT UNSIGNED NOT NULL,
	`id_group` INT UNSIGNED NOT NULL,
	`type` ENUM('PVP', 'PVM') NOT NULL,
	`who_win` INT UNSIGNED,
	`end_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

	PRIMARY KEY(`id_team`, `id_group`)
);

INSERT INTO GENERALS_PRESETS VALUES (1, 1, 3), (2, 2, 3);