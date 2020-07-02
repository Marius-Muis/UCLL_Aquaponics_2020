/* Queries written by Jonna; altered a bit by Marius in preperation for upload to MySQL server. */
CREATE TABLE `h2opouwf_aquaponics`.`system` ( `id` INT NOT NULL AUTO_INCREMENT ,
 `name` VARCHAR(255) NOT NULL , PRIMARY KEY(`id`));

INSERT INTO `system` (`id`, `name`) VALUES (NULL, 'team6');
SELECT * FROM `system`;

CREATE TABLE `h2opouwf_aquaponics`.`readings` ( `id` INT NOT NULL AUTO_INCREMENT , `water_level` DECIMAL(6,2) NOT NULL , 
`temperature` DECIMAL(6,2) NOT NULL , `ph` DECIMAL(6,2) NOT NULL , `dis_oxygen` DECIMAL(6,2) NOT NULL , `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `system_id` INT NOT NULL , PRIMARY KEY (`id`));

ALTER TABLE `readings` ADD FOREIGN KEY (`system_id`) REFERENCES `system`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `readings` (`id`, `water_level`, `temperature`, `ph`, `dis_oxygen`, `time`, `system_id`) VALUES (NULL, '130', '16', '7', '10', '2020-04-21 11:22:16', '1');
INSERT INTO `readings` (`id`, `water_level`, `temperature`, `ph`, `dis_oxygen`, `time`, `system_id`) VALUES (NULL, '136', '16', '6.7', '10', '2020-04-21 11:30:49', '1');
INSERT INTO `readings` (`id`, `water_level`, `temperature`, `ph`, `dis_oxygen`, `time`, `system_id`) VALUES (NULL, '136', '16', '6.7', '10', current_timestamp(), '1');

SELECT * FROM `readings` WHERE system_id = 1;
SELECT * FROM `readings` WHERE system_id = 1 AND time BETWEEN '2020-04-21 11:10:25' AND '2020-04-21 11:30:00';
SELECT water_level, ph, dis_oxygen, time  FROM readings WHERE system_id = 1 ORDER BY time DESC;
SELECT * FROM `readings` WHERE system_id = 1 AND time BETWEEN '2020-04-21 11:10:25' AND '2020-04-21 11:30:00';
SELECT * FROM `readings` WHERE system_id = 1 AND time > date_sub(now(), interval 1 week);
SELECT * FROM `readings` WHERE system_id = 1 AND time > date_sub(now(), interval 1 day);
SELECT * FROM `readings` WHERE system_id = 1 AND time > date_sub(now(), interval 1 month);
SELECT MAX(water_level) FROM readings WHERE system_id = 1;  