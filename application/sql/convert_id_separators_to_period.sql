UPDATE user_catalog SET catalog_id_keyword = REPLACE(catalog_id_keyword, '/', '.');
UPDATE user_savedcourses SET course_id_keyword = REPLACE(course_id_keyword, '/', '.');
UPDATE user_schedules SET term_id_keyword = REPLACE(term_id_keyword, '/', '.');
UPDATE user_schedule_offerings SET offering_id_keyword = REPLACE(offering_id_keyword, '/', '.');
UPDATE archive_configurations SET catalog_id = REPLACE(catalog_id, '/', '.');
UPDATE archive_configuration_revisions SET json_data=REPLACE(json_data, 'topic\\/subject\\/', 'topic.subject.');
UPDATE archive_configuration_revisions SET json_data=REPLACE(json_data, 'topic\\/department\\/', 'topic.department.');
