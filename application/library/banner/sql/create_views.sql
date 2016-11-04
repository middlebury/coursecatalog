-- ---------------------------------------------------------

--
-- A view for the most recent version of each course
--

CREATE OR REPLACE VIEW `scbcrse_recent`  AS
SELECT
	crse1.*,
	IF (SCREQIV_SUBJ_CODE_EQIV IS NULL, 0, 1) AS has_alternates
FROM
	SCBCRSE AS crse1

	-- 'Outer self exclusion join' to fetch only the most recent SCBCRSE row.
	LEFT JOIN SCBCRSE AS crse2
		ON (crse1.SCBCRSE_SUBJ_CODE = crse2.SCBCRSE_SUBJ_CODE
			AND crse1.SCBCRSE_CRSE_NUMB = crse2.SCBCRSE_CRSE_NUMB
			-- If crse2 is effective after crse1, a join will be successfull and crse2 non-null.
			-- On the latest crse1, crse2 will be null.
			AND crse1.SCBCRSE_EFF_TERM < crse2.SCBCRSE_EFF_TERM)
	LEFT JOIN SCREQIV
		ON (crse1.SCBCRSE_SUBJ_CODE = SCREQIV_SUBJ_CODE
			AND crse1.SCBCRSE_CRSE_NUMB = SCREQIV_CRSE_NUMB)

WHERE

	-- Clause for the 'outer self exclusion join'
	crse2.SCBCRSE_SUBJ_CODE IS NULL

GROUP BY crse1.SCBCRSE_SUBJ_CODE , crse1.SCBCRSE_CRSE_NUMB
ORDER BY crse1.SCBCRSE_SUBJ_CODE , crse1.SCBCRSE_CRSE_NUMB;

-- ---------------------------------------------------------

--
-- A view for the most recent version of each course, with descriptions
--

CREATE OR REPLACE VIEW `scbcrse_scbdesc_recent`  AS
SELECT
	scbcrse_recent.*,
	desc1.SCBDESC_TERM_CODE_EFF,
	desc1.SCBDESC_TEXT_NARRATIVE
FROM
	scbcrse_recent

	LEFT JOIN SCBDESC AS desc1
		ON (SCBCRSE_SUBJ_CODE = desc1.SCBDESC_SUBJ_CODE
			AND SCBCRSE_CRSE_NUMB = desc1.SCBDESC_CRSE_NUMB
			AND (desc1.SCBDESC_TERM_CODE_END IS NULL OR SCBCRSE_EFF_TERM < desc1.SCBDESC_TERM_CODE_END))

	-- 'Outer self exclusion join' to fetch only the most recent SCBDESC row if multiple match.
	LEFT JOIN SCBDESC AS desc2
		ON (SCBCRSE_SUBJ_CODE = desc2.SCBDESC_SUBJ_CODE
			AND SCBCRSE_CRSE_NUMB = desc2.SCBDESC_CRSE_NUMB
			AND (desc2.SCBDESC_TERM_CODE_END IS NULL OR SCBCRSE_EFF_TERM < desc2.SCBDESC_TERM_CODE_END)
			-- If desc2 is effective after desc1, a join will be successfull and desc2 non-null.
			-- On the latest desc1, desc2 will be null.
			AND desc1.SCBDESC_TERM_CODE_EFF < desc2.SCBDESC_TERM_CODE_EFF)
WHERE
	-- Clause for the 'outer self exclusion join'
	desc2.SCBDESC_SUBJ_CODE IS NULL;

-- ---------------------------------------------------------

--
-- A view joining the sections and courses tables together.
--

CREATE OR REPLACE VIEW `ssbsect_scbcrse`  AS
SELECT
	SSBSECT.*,
	CONCAT(crse.SCBCRSE_TITLE, IF(SSBSECT_CRSE_TITLE IS NOT NULL, CONCAT('\n', SSBSECT_CRSE_TITLE), '')) AS section_title,
	crse.*
FROM
	SSBSECT

	LEFT JOIN SCBCRSE AS crse
		ON (SSBSECT_SUBJ_CODE = crse.SCBCRSE_SUBJ_CODE
			AND SSBSECT_CRSE_NUMB = crse.SCBCRSE_CRSE_NUMB
			AND crse.SCBCRSE_EFF_TERM <= SSBSECT_TERM_CODE)

	-- Outer self exclusion join to fetch only the most recent SCBCRSE row that has a EFF_TERM less than the sections
	LEFT JOIN SCBCRSE AS crse2
		ON (SSBSECT_SUBJ_CODE = crse2.SCBCRSE_SUBJ_CODE
			AND SSBSECT_CRSE_NUMB = crse2.SCBCRSE_CRSE_NUMB
			AND crse2.SCBCRSE_EFF_TERM <= SSBSECT_TERM_CODE
			AND crse.SCBCRSE_EFF_TERM < crse2.SCBCRSE_EFF_TERM )

WHERE

	-- Clause for the outer self exclusion join
	crse2.SCBCRSE_SUBJ_CODE IS NULL;

-- ---------------------------------------------------------

--
-- A view joining the sections, courses, and course-description tables together.
--

CREATE OR REPLACE VIEW `ssbsect_scbcrse_scbdesc`  AS
SELECT
	ssbsect_scbcrse.*,
	desc1.SCBDESC_TERM_CODE_EFF,
	desc1.SCBDESC_TEXT_NARRATIVE
FROM
	ssbsect_scbcrse

	LEFT JOIN SCBDESC AS desc1
		ON (SSBSECT_SUBJ_CODE = desc1.SCBDESC_SUBJ_CODE
			AND SSBSECT_CRSE_NUMB = desc1.SCBDESC_CRSE_NUMB
			AND SSBSECT_TERM_CODE >= desc1.SCBDESC_TERM_CODE_EFF
			AND (desc1.SCBDESC_TERM_CODE_END IS NULL OR SSBSECT_TERM_CODE < desc1.SCBDESC_TERM_CODE_END))

	-- 'Outer self exclusion join' to fetch only the most recent SCBDESC row if multiple match.
	LEFT JOIN SCBDESC AS desc2
		ON (SSBSECT_SUBJ_CODE = desc2.SCBDESC_SUBJ_CODE
			AND SSBSECT_CRSE_NUMB = desc2.SCBDESC_CRSE_NUMB
			AND SSBSECT_TERM_CODE >= desc2.SCBDESC_TERM_CODE_EFF
			AND (desc2.SCBDESC_TERM_CODE_END IS NULL OR SSBSECT_TERM_CODE < desc2.SCBDESC_TERM_CODE_END)
			-- If desc2 is effective after desc1, a join will be successfull and desc2 non-null.
			-- On the latest desc1, desc2 will be null.
			AND desc1.SCBDESC_TERM_CODE_EFF < desc2.SCBDESC_TERM_CODE_EFF)
WHERE
	-- Clause for the 'outer self exclusion join'
	desc2.SCBDESC_SUBJ_CODE IS NULL;



-- ---------------------------------------------------------

--
-- This view allows fetching of the most recent equivalencies available for a course
--
CREATE OR REPLACE VIEW screqiv_recent AS
SELECT
	equiv1.*
FROM
	SCREQIV AS equiv1

	-- 'Outer self exclusion join' to fetch only the most recent SCREQIV row.
	LEFT JOIN SCREQIV AS equiv2
		ON (equiv1.SCREQIV_SUBJ_CODE = equiv2.SCREQIV_SUBJ_CODE
			AND equiv1.SCREQIV_CRSE_NUMB = equiv2.SCREQIV_CRSE_NUMB
			-- Check for matching equivalents OR NULL equivalents which may be used to clear out old values.
			AND ((equiv1.SCREQIV_SUBJ_CODE_EQIV = equiv2.SCREQIV_SUBJ_CODE_EQIV
					AND equiv1.SCREQIV_CRSE_NUMB_EQIV = equiv2.SCREQIV_CRSE_NUMB_EQIV)
				OR (equiv2.SCREQIV_SUBJ_CODE_EQIV IS NULL))
			AND (equiv1.SCREQIV_START_TERM = equiv2.SCREQIV_START_TERM
				OR equiv2.SCREQIV_START_TERM IS NULL)
			-- If equiv2 is effective after equiv1, a join will be successfull and equiv2 non-null.
			-- On the latest equiv1, equiv2 will be null.
			AND equiv1.SCREQIV_EFF_TERM < equiv2.SCREQIV_EFF_TERM)

WHERE

	-- Clause for the 'outer self exclusion join'
	equiv2.SCREQIV_SUBJ_CODE IS NULL

	-- Ignore rows just used to clear out old values.
	AND equiv1.SCREQIV_SUBJ_CODE_EQIV IS NOT NULL

GROUP BY equiv1.SCREQIV_SUBJ_CODE , equiv1.SCREQIV_CRSE_NUMB, equiv1.SCREQIV_SUBJ_CODE_EQIV,  equiv1.SCREQIV_CRSE_NUMB_EQIV
ORDER BY equiv1.SCREQIV_SUBJ_CODE , equiv1.SCREQIV_CRSE_NUMB, equiv1.SCREQIV_SUBJ_CODE_EQIV, equiv1.SCREQIV_CRSE_NUMB_EQIV;

-- ---------------------------------------------------------

--
-- This view allows fetching current equivalencies for a course, excluding those that
-- have expired.
--
-- In our Banner database the 'Beginning of Time' term (000000) and 'End of Time term (999999)
-- have valid, but arbitrary start/end dates (~2000-01-01). The only way to distiguish
-- them from real terms is to look for their null value in STVTERM_TRMT_CODE.
--
DROP VIEW IF EXISTS screqiv_current;
DROP TABLE IF EXISTS screqiv_current;
CREATE TABLE screqiv_current
SELECT
	screqiv_recent.*
FROM
	screqiv_recent
	LEFT JOIN STVTERM as start_term ON (SCREQIV_START_TERM = start_term.STVTERM_CODE)
	LEFT JOIN STVTERM as end_term ON (SCREQIV_END_TERM = end_term.STVTERM_CODE)
WHERE
	(start_term.STVTERM_TRMT_CODE IS NULL OR start_term.STVTERM_START_DATE < NOW())
	AND (end_term.STVTERM_TRMT_CODE IS NULL OR end_term.STVTERM_END_DATE > NOW());

-- ---------------------------------------------------------

--
-- This view allows matching of courses that are equivalent to a course that is
-- in turn equivalent to another course.
--
-- Give three courses, A, B, C, they may be marked equivalent in 4 columns as any of
--  1   2  3   4
--  A = B, A = C
--  A = B, B = C
--	A = B, C = B
--  A = B, C = A

CREATE OR REPLACE VIEW screqiv_2way AS
SELECT
	equiv1.`SCREQIV_SUBJ_CODE` AS subj_code_1,
	equiv1.`SCREQIV_CRSE_NUMB` AS crse_numb_1,
	equiv1.`SCREQIV_EFF_TERM` AS eff_term_a,
	equiv1.`SCREQIV_SUBJ_CODE_EQIV` AS subj_code_2,
	equiv1.`SCREQIV_CRSE_NUMB_EQIV` AS crse_numb_2,
	equiv2.`SCREQIV_SUBJ_CODE` AS subj_code_3,
	equiv2.`SCREQIV_CRSE_NUMB` AS crse_numb_3,
	equiv2.`SCREQIV_EFF_TERM` AS eff_term_b,
	equiv2.`SCREQIV_SUBJ_CODE_EQIV` AS subj_code_4,
	equiv2.`SCREQIV_CRSE_NUMB_EQIV` AS crse_numb_4
FROM
	screqiv_current as equiv1
	LEFT JOIN screqiv_current AS equiv2
		ON ((equiv1.SCREQIV_SUBJ_CODE = equiv2.SCREQIV_SUBJ_CODE
				AND equiv1.SCREQIV_CRSE_NUMB = equiv2.SCREQIV_CRSE_NUMB)
			OR (equiv1.SCREQIV_SUBJ_CODE = equiv2.SCREQIV_SUBJ_CODE_EQIV
				AND equiv1.SCREQIV_CRSE_NUMB = equiv2.SCREQIV_CRSE_NUMB_EQIV)
			OR (equiv1.SCREQIV_SUBJ_CODE_EQIV = equiv2.SCREQIV_SUBJ_CODE
				AND equiv1.SCREQIV_CRSE_NUMB_EQIV = equiv2.SCREQIV_CRSE_NUMB)
			OR (equiv1.SCREQIV_SUBJ_CODE_EQIV = equiv2.SCREQIV_SUBJ_CODE_EQIV
				AND equiv1.SCREQIV_CRSE_NUMB_EQIV = equiv2.SCREQIV_CRSE_NUMB_EQIV));



-- ---------------------------------------------------------

--
-- This view allows fetching of the most recent attributes available for a course
--
CREATE OR REPLACE VIEW scrattr_recent AS
SELECT
	attr1.*
FROM
	SCRATTR AS attr1

	-- 'Outer self exclusion join' to fetch only the most recent SCRATTR row.
	LEFT JOIN SCRATTR AS attr2
		ON (attr1.SCRATTR_SUBJ_CODE = attr2.SCRATTR_SUBJ_CODE
			AND attr1.SCRATTR_CRSE_NUMB = attr2.SCRATTR_CRSE_NUMB
			-- If attr2 is effective after attr1, a join will be successfull and attr2 non-null.
			-- On the latest attr1, attr2 will be null.
			AND attr1.SCRATTR_EFF_TERM < attr2.SCRATTR_EFF_TERM)

WHERE

	-- Clause for the 'outer self exclusion join'
	attr2.SCRATTR_SUBJ_CODE IS NULL

	-- Exclude null entries that are just for overriding attributes
	AND attr1.SCRATTR_ATTR_CODE IS NOT NULL

GROUP BY attr1.SCRATTR_SUBJ_CODE , attr1.SCRATTR_CRSE_NUMB, attr1.SCRATTR_ATTR_CODE
ORDER BY attr1.SCRATTR_SUBJ_CODE , attr1.SCRATTR_CRSE_NUMB, attr1.SCRATTR_ATTR_CODE;

-- ---------------------------------------------------------

--
-- This view allows fetching of the most recent level available for a course
--
CREATE OR REPLACE VIEW scrlevl_recent AS
SELECT
	levl1.*
FROM
	SCRLEVL AS levl1

	-- 'Outer self exclusion join' to fetch only the most recent SCRLEVL row.
	LEFT JOIN SCRLEVL AS levl2
		ON (levl1.SCRLEVL_SUBJ_CODE = levl2.SCRLEVL_SUBJ_CODE
			AND levl1.SCRLEVL_CRSE_NUMB = levl2.SCRLEVL_CRSE_NUMB
			-- If levl2 is effective after levl1, a join will be successfull and levl2 non-null.
			-- On the latest levl1, levl2 will be null.
			AND levl1.SCRLEVL_EFF_TERM < levl2.SCRLEVL_EFF_TERM)

WHERE

	-- Clause for the 'outer self exclusion join'
	levl2.SCRLEVL_SUBJ_CODE IS NULL

	-- Exclude null entries that are just for overriding levels
	AND levl1.SCRLEVL_LEVL_CODE IS NOT NULL

GROUP BY levl1.SCRLEVL_SUBJ_CODE , levl1.SCRLEVL_CRSE_NUMB, levl1.SCRLEVL_LEVL_CODE
ORDER BY levl1.SCRLEVL_SUBJ_CODE , levl1.SCRLEVL_CRSE_NUMB, levl1.SCRLEVL_LEVL_CODE;

-- ---------------------------------------------------------

--
-- This view allows fetching the campuses at which the offerings in a catalog are held.
--
DROP VIEW IF EXISTS catalog_campus;
DROP TABLE IF EXISTS catalog_campus;
CREATE TABLE catalog_campus
SELECT
	course_catalog_college.catalog_id AS catalog_id,
	STVCAMP_CODE,
	STVCAMP_DESC
FROM
	ssbsect_scbcrse
	INNER JOIN course_catalog_college ON SCBCRSE_COLL_CODE = coll_code
	INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
	INNER JOIN STVCAMP ON SSBSECT_CAMP_CODE = STVCAMP_CODE
WHERE
	course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude
GROUP BY course_catalog_college.catalog_id, STVCAMP_CODE;
