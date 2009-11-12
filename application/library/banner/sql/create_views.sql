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
	
	LEFT JOIN SCBCRSE AS crse ON (SSBSECT_SUBJ_CODE = crse.SCBCRSE_SUBJ_CODE AND SSBSECT_CRSE_NUMB = crse.SCBCRSE_CRSE_NUMB AND crse.SCBCRSE_EFF_TERM <= SSBSECT_TERM_CODE)
	
	-- Outer self exclusion join to fetch only the most recent SCBCRSE row that has a EFF_TERM less than the sections
	LEFT JOIN SCBCRSE AS crse2 ON (SSBSECT_SUBJ_CODE = crse2.SCBCRSE_SUBJ_CODE AND SSBSECT_CRSE_NUMB = crse2.SCBCRSE_CRSE_NUMB AND crse2.SCBCRSE_EFF_TERM <= SSBSECT_TERM_CODE AND crse.SCBCRSE_EFF_TERM < crse2.SCBCRSE_EFF_TERM )

WHERE

	-- Clause for the outer self exclusion join
	crse2.SCBCRSE_SUBJ_CODE IS NULL