CREATE TABLE `afranco_courses_banner`.`section_fulltext` (
`section_fulltext_term_code` VARCHAR( 6 ) NOT NULL COMMENT 'Term code for the section',
`section_fulltext_crn` VARCHAR( 5 ) NOT NULL COMMENT 'Course Reference Number (CRN) for the section.',
`section_fulltext_text` TEXT NOT NULL COMMENT 'A concatenated string of all parts to be searched: titles, descriptions, etc.',
PRIMARY KEY ( `section_fulltext_term_code` , `section_fulltext_crn` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'A full-text index for keyword searching on Sections.';
