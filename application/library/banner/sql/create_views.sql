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
	
	-- Exclude non-active status codes.
	AND crse1.SCBCRSE_CSTA_CODE NOT IN (
		'C', 'I', 'P', 'T', 'X'
	)
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
-- This view allows matching of courses that are equivalent to a course that is 
-- in turn equivalent to another course.
--	

CREATE OR REPLACE VIEW screqiv_2way AS
SELECT
	equiv1.`SCREQIV_SUBJ_CODE`,
	equiv1.`SCREQIV_CRSE_NUMB`,
	equiv1.`SCREQIV_EFF_TERM`,
	equiv1.`SCREQIV_SUBJ_CODE_EQIV`,
	equiv1.`SCREQIV_CRSE_NUMB_EQIV`,
	equiv2.`SCREQIV_SUBJ_CODE` AS equiv2_subj_code,
	equiv2.`SCREQIV_CRSE_NUMB` AS equiv2_crse_numb,
	equiv2.`SCREQIV_EFF_TERM` AS equiv2_eff_term,
	equiv2.`SCREQIV_SUBJ_CODE_EQIV` AS equiv2_subj_code_equiv,
	equiv2.`SCREQIV_CRSE_NUMB_EQIV` AS equiv2_crse_numb_equiv
FROM 
	SCREQIV as equiv1
	LEFT JOIN SCREQIV AS equiv2 
		ON ((equiv1.SCREQIV_SUBJ_CODE_EQIV = equiv2.SCREQIV_SUBJ_CODE
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
