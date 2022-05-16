-- Allow WEB_ID to be NULL to address timing issues between Banner record and
-- account creation. There may be a few weeks between a record being created in
-- Banner and a account being created where the WEB_ID is generated.
-- Instructor links will fail without this key, but names can still be displayed
-- next to section records without the id.
ALTER TABLE `SYVINST` CHANGE `WEB_ID` `WEB_ID` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL COMMENT 'This field is used in web applications to identify individuals rather than using their PIDMs.';
