-- Migrate the SYVINST table data to a new `instructors` table.

CREATE TABLE IF NOT EXISTS `instructors` (
  `PIDM` decimal(8,0) NOT NULL default '0' COMMENT 'The Pidm of the faculty member',
  `WEB_ID` varchar(100) NULL COMMENT 'This field is used in web applications to identify individuals rather than using their PIDMs.',
  `FIRST_NAME` varchar(4000) default NULL COMMENT 'This field identifies the first name of person.',
  `LAST_NAME` varchar(240) NOT NULL default '' COMMENT 'This field defines the last name of person.',
  PRIMARY KEY  (`PIDM`),
  KEY `WEB_ID` (`WEB_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `instructors`
(`PIDM`, `WEB_ID`, `FIRST_NAME`, `LAST_NAME`)
SELECT
    DISTINCT(`SYVINST_PIDM`),
    `WEB_ID`,
    `SYVINST_FIRST_NAME`,
    `SYVINST_LAST_NAME`
FROM
    `SYVINST`;

DROP TABLE IF EXISTS `SYVINST`;
