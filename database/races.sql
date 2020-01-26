USE SOSGAME;

CREATE TABLE IF NOT EXISTS RACES(
	`id` TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	`id_start_position` INT UNSIGNED NOT NULL,
	`name` CHAR(63) NOT NULL,
	`description` TEXT NOT NULL,
	`icon` CHAR(63) NOT NULL
) ENGINE = MYISAM;

INSERT INTO RACES VALUES
(null, 1, "Ludzie", "Uchodźcy z powierzchni. Ostani bastion ludzkości. Chcą zebrać siły py pokonać zło z powierzchni.", ""),
(null, 1, "Badacze", "Jeden z oddziałów ludzkich ktory wcześniej przybył do podziemi. Są to grupy samotników mający na celu ekspoloracje ruin.", ""),
(null, 2, "Górnicy", "Rdzenni mieszkańcy podziemi. Często uważani za baśniowych krasnoludów. Ich niski wzrost spowodowany jest jednak długotrwałym przebywaniem w podziemiach od setek lat, a ich muskulatura wynika z ciężkej pracy w kopalniach.", ""),
(null, 3, "Starożytni", "Strażnicy prastarej kultury. Nieumarli emanujący chęcią mordu.", "");
