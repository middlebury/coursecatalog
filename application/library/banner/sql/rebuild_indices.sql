-- FULLTEXT indices will bloat over time and aren't included in the default
-- OPTIMIZE TABLE commands. Drop and rebuild.
ALTER TABLE `SCBDESC` DROP INDEX IF EXISTS `SCBDESC_TEXT_NARRATIVE`;
ALTER TABLE `SCBDESC` ADD FULLTEXT `SCBDESC_TEXT_NARRATIVE` (`SCBDESC_TEXT_NARRATIVE`);
ALTER TABLE `SSBSECT` DROP INDEX IF EXISTS `SSBSECT_fulltext_index`;
ALTER TABLE `SSBSECT` ADD FULLTEXT `SSBSECT_fulltext_index` (`SSBSECT_fulltext`);

-- Run OPTIMIZE TABLE on all tables.
OPTIMIZE TABLE `GORINTG`;
OPTIMIZE TABLE `GTVDUNT`;
OPTIMIZE TABLE `GTVINSM`;
OPTIMIZE TABLE `GTVINTP`;
OPTIMIZE TABLE `GTVMTYP`;
OPTIMIZE TABLE `GTVSCHS`;
OPTIMIZE TABLE `SCBCRSE`;
OPTIMIZE TABLE `SCBDESC`;
OPTIMIZE TABLE `SCRATTR`;
OPTIMIZE TABLE `SCREQIV`;
OPTIMIZE TABLE `SCRLEVL`;
OPTIMIZE TABLE `SIRASGN`;
OPTIMIZE TABLE `SOBPTRM`;
OPTIMIZE TABLE `SSBDESC`;
OPTIMIZE TABLE `SSBSECT`;
OPTIMIZE TABLE `SSBXLST`;
OPTIMIZE TABLE `SSRATTR`;
OPTIMIZE TABLE `SSRBLCK`;
OPTIMIZE TABLE `SSRMEET`;
OPTIMIZE TABLE `SSRXLST`;
OPTIMIZE TABLE `STVACYR`;
OPTIMIZE TABLE `STVAPRV`;
OPTIMIZE TABLE `STVASTY`;
OPTIMIZE TABLE `STVATTR`;
OPTIMIZE TABLE `STVBLCK`;
OPTIMIZE TABLE `STVBLDG`;
OPTIMIZE TABLE `STVCAMP`;
OPTIMIZE TABLE `STVCIPC`;
OPTIMIZE TABLE `STVCOLL`;
OPTIMIZE TABLE `STVCOMT`;
OPTIMIZE TABLE `STVCSTA`;
OPTIMIZE TABLE `STVDEPT`;
OPTIMIZE TABLE `STVDIVS`;
OPTIMIZE TABLE `STVFCNT`;
OPTIMIZE TABLE `STVLEVL`;
OPTIMIZE TABLE `STVMEET`;
OPTIMIZE TABLE `STVPTRM`;
OPTIMIZE TABLE `STVPWAV`;
OPTIMIZE TABLE `STVREPS`;
OPTIMIZE TABLE `STVSCHD`;
OPTIMIZE TABLE `STVSUBJ`;
OPTIMIZE TABLE `STVTERM`;
OPTIMIZE TABLE `STVTRMT`;
OPTIMIZE TABLE `instructors`;
OPTIMIZE TABLE `antirequisites`;
OPTIMIZE TABLE `archive_configuration_revisions`;
OPTIMIZE TABLE `archive_configurations`;
OPTIMIZE TABLE `archive_export_progress`;
OPTIMIZE TABLE `archive_jobs`;
OPTIMIZE TABLE `catalog_campus`;
OPTIMIZE TABLE `catalog_term`;
OPTIMIZE TABLE `catalog_term_inactive`;
OPTIMIZE TABLE `catalog_term_match`;
OPTIMIZE TABLE `course_catalog`;
OPTIMIZE TABLE `course_catalog_college`;
OPTIMIZE TABLE `scbcrse_recent`;
OPTIMIZE TABLE `scbcrse_scbdesc_recent`;
OPTIMIZE TABLE `scrattr_recent`;
OPTIMIZE TABLE `screqiv_2way`;
OPTIMIZE TABLE `screqiv_current`;
OPTIMIZE TABLE `screqiv_recent`;
OPTIMIZE TABLE `scrlevl_recent`;
OPTIMIZE TABLE `ssbsect_scbcrse`;
OPTIMIZE TABLE `ssbsect_scbcrse_scbdesc`;
OPTIMIZE TABLE `user_catalog`;
OPTIMIZE TABLE `user_savedcourses`;
OPTIMIZE TABLE `user_schedule_offerings`;
OPTIMIZE TABLE `user_schedules`;
