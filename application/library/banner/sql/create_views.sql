-- ---------------------------------------------------------

--
-- A view for the most recent version of each course
--

CREATE OR REPLACE ALGORITHM=TEMPTABLE VIEW `scbcrse_recent`  AS 
SELECT 
	crse1.*
FROM 
	SCBCRSE AS crse1
	
	-- 'Outer self exclusion join' to fetch only the most recent SCBCRSE row.
	LEFT JOIN SCBCRSE AS crse2 
		ON (crse1.SCBCRSE_SUBJ_CODE = crse2.SCBCRSE_SUBJ_CODE 
			AND crse1.SCBCRSE_CRSE_NUMB = crse2.SCBCRSE_CRSE_NUMB 
			-- If crse2 is effective after crse1, a join will be successfull and crse2 non-null.
			-- On the latest crse1, crse2 will be null.
			AND crse1.SCBCRSE_EFF_TERM < crse2.SCBCRSE_EFF_TERM)
	
WHERE
	
	-- Clause for the 'outer self exclusion join'
	crse2.SCBCRSE_SUBJ_CODE IS NULL
	
	-- Exclude non-active status codes.
	AND crse1.SCBCRSE_CSTA_CODE NOT IN (
		'C', 'I', 'P', 'T', 'X'
	)
ORDER BY crse1.SCBCRSE_SUBJ_CODE , crse1.SCBCRSE_CRSE_NUMB;

-- ---------------------------------------------------------

--
-- A view for the most recent version of each course, with descriptions
--

CREATE OR REPLACE ALGORITHM=TEMPTABLE VIEW `scbcrse_scbdesc_recent`  AS 
SELECT 
	scbcrse_recent.*,
	desc1.SCBDESC_TERM_CODE_EFF,
	desc1.SCBDESC_TEXT_NARRATIVE
FROM 
	scbcrse_recent
	
	LEFT JOIN SCBDESC AS desc1 
		ON (SCBCRSE_SUBJ_CODE = desc1.SCBDESC_SUBJ_CODE 
			AND SCBCRSE_CRSE_NUMB = desc1.SCBDESC_CRSE_NUMB 
			AND SCBCRSE_EFF_TERM >= desc1.SCBDESC_TERM_CODE_EFF 
			AND (desc1.SCBDESC_TERM_CODE_END IS NULL OR SCBCRSE_EFF_TERM < desc1.SCBDESC_TERM_CODE_END))
	
	-- 'Outer self exclusion join' to fetch only the most recent SCBDESC row if multiple match.
	LEFT JOIN SCBDESC AS desc2 
		ON (SCBCRSE_SUBJ_CODE = desc2.SCBDESC_SUBJ_CODE 
			AND SCBCRSE_CRSE_NUMB = desc2.SCBDESC_CRSE_NUMB 
			AND SCBCRSE_EFF_TERM >= desc2.SCBDESC_TERM_CODE_EFF 
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

CREATE OR REPLACE ALGORITHM=TEMPTABLE VIEW `ssbsect_scbcrse`  AS 
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

CREATE OR REPLACE ALGORITHM=TEMPTABLE VIEW `ssbsect_scbcrse_scbdesc`  AS 
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