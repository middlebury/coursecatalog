ALTER TABLE `catalog_term_inactive` ADD `catalog_id` VARCHAR(10) NULL DEFAULT NULL FIRST;
ALTER TABLE `catalog_term_inactive` DROP PRIMARY KEY;

UPDATE `catalog_term_inactive` SET `catalog_id` = 'MCUG' WHERE term_code LIKE '%10';
UPDATE `catalog_term_inactive` SET `catalog_id` = 'MCUG' WHERE term_code LIKE '%20';
UPDATE `catalog_term_inactive` SET `catalog_id` = 'MCUG' WHERE term_code LIKE '%65';
UPDATE `catalog_term_inactive` SET `catalog_id` = 'MCUG' WHERE term_code LIKE '%90';

UPDATE `catalog_term_inactive` SET `catalog_id` = 'SE' WHERE term_code LIKE '%50';

UPDATE `catalog_term_inactive` SET `catalog_id` = 'MCLS' WHERE term_code LIKE '%60';

UPDATE `catalog_term_inactive` SET `catalog_id` = 'BLSE' WHERE term_code LIKE '%70';

UPDATE `catalog_term_inactive` SET `catalog_id` = 'MIIS' WHERE term_code LIKE '%28';
UPDATE `catalog_term_inactive` SET `catalog_id` = 'MIIS' WHERE term_code LIKE '%68';
UPDATE `catalog_term_inactive` SET `catalog_id` = 'MIIS' WHERE term_code LIKE '%98';

UPDATE `catalog_term_inactive` SET `catalog_id` = 'MIISLPP' WHERE term_code LIKE '%18';
UPDATE `catalog_term_inactive` SET `catalog_id` = 'MIISLPP' WHERE term_code LIKE '%38';
UPDATE `catalog_term_inactive` SET `catalog_id` = 'MIISLPP' WHERE term_code LIKE '%48';
UPDATE `catalog_term_inactive` SET `catalog_id` = 'MIISLPP' WHERE term_code LIKE '%78';
UPDATE `catalog_term_inactive` SET `catalog_id` = 'MIISLPP' WHERE term_code LIKE '%88';

ALTER TABLE `catalog_term_inactive` CHANGE `catalog_id` `catalog_id` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `catalog_term_inactive` ADD PRIMARY KEY( `catalog_id`, `term_code`);
