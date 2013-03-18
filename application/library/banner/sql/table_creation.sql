-- --------------------------------------------------------

--
-- Table structure for table `catalog_term`
--

CREATE TABLE IF NOT EXISTS `catalog_term` (
  `catalog_id` varchar(10) NOT NULL,
  `term_code` varchar(6) NOT NULL COMMENT 'Maps to STVTERM.STVTERM_CODE',
  `term_display_label` varchar(4) NOT NULL COMMENT 'The label such as ''F'', ''S'', ''W'', ''L'', etc used to build a section display name.',
  PRIMARY KEY  (`catalog_id`,`term_code`),
  KEY `catalog_id` (`catalog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table maps term_code patterns to a given catalog.';

-- --------------------------------------------------------

--
-- Table structure for table `catalog_term_inactive`
--

CREATE TABLE IF NOT EXISTS `catalog_term_inactive` (
  `term_code` varchar(6) NOT NULL COMMENT 'Maps to STVTERM.STVTERM_CODE',
  PRIMARY KEY  (`term_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Terms that should be ignored even with sections';


-- --------------------------------------------------------

--
-- Table structure for table `catalog_term_match`
--

CREATE TABLE IF NOT EXISTS `catalog_term_match` (
  `catalog_id` varchar(10) NOT NULL,
  `term_code_match` varchar(10) NOT NULL,
  `term_display_label` varchar(4) NOT NULL COMMENT 'The label such as ''F'', ''S'', ''W'', ''L'', etc used to build a section display name.',
  PRIMARY KEY  (`catalog_id`,`term_code_match`),
  KEY `catalog_id` (`catalog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='This table maps term_code patterns to a given catalog.';

-- --------------------------------------------------------

--
-- Table structure for table `course_catalog`
--

CREATE TABLE IF NOT EXISTS `course_catalog` (
  `catalog_id` varchar(10) NOT NULL COMMENT 'An identifier for the catalog.',
  `catalog_title` varchar(100) NOT NULL COMMENT 'A title for the catalog, will be displayed in drop-down menus and headings.',
  `current_term` varchar(6) default NULL,
  PRIMARY KEY  (`catalog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='A listing of catalogs, each of which may be made up several ';

-- --------------------------------------------------------

--
-- Table structure for table `course_catalog_college`
--

CREATE TABLE IF NOT EXISTS `course_catalog_college` (
  `catalog_id` varchar(10) NOT NULL COMMENT 'The identifier of the catalog',
  `coll_code` char(2) NOT NULL COMMENT 'The college code from STVCOLL_CODE',
  KEY `catalog_id` (`catalog_id`),
  KEY `coll_code` (`coll_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='A join-table that maps colleges from stvcoll to catalogs in ';

-- --------------------------------------------------------

--
-- Table structure for table `GORINTG`
--

CREATE TABLE IF NOT EXISTS `GORINTG` (
  `GORINTG_CODE` varchar(5) NOT NULL default '' COMMENT 'INTEGRATION CODE: User Defined Integration Code that is used with external partner.',
  `GORINTG_DESC` varchar(30) NOT NULL default '' COMMENT 'Description: User Description of the Integration Code.',
  `GORINTG_INTP_CODE` varchar(5) NOT NULL default '' COMMENT 'INTEGRATION PARTNER SYSTEM CODE: Code defined on GTVINTP that associates with the User Integration Code.',
  `GORINTG_USER_ID` varchar(30) NOT NULL default '' COMMENT 'USER ID: The unique identification of the user.',
  `GORINTG_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'ACTIVITY DATE: The date that the information for the row was inserted or updated in the GORINTG table.',
  `GORINTG_DATA_ORIGIN` varchar(30) default NULL COMMENT 'DATA ORIGIN: Source system that created or updated the row',
  PRIMARY KEY  (`GORINTG_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Integration Partner System Rule Table.';

-- --------------------------------------------------------

--
-- Table structure for table `GTVDUNT`
--

CREATE TABLE IF NOT EXISTS `GTVDUNT` (
  `GTVDUNT_CODE` varchar(4) NOT NULL default '' COMMENT 'Duration Unit Code: The Duration Unit Code',
  `GTVDUNT_DESC` varchar(30) NOT NULL default '' COMMENT 'Description: Description of the duration unit code',
  `GTVDUNT_NUMBER_OF_DAYS` decimal(7,2) NOT NULL default '0.00' COMMENT 'Number of Days per duration unit: Represents the number of days that one duration unit would equate to (ie 1 week equals 7 days)',
  `GTVDUNT_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'Activity Date: Date this record entered or last updated',
  `GTVDUNT_USER_ID` varchar(30) NOT NULL default '' COMMENT 'User ID: The username of the person who entered or last updated this record',
  `GTVDUNT_VR_MSG_NO` decimal(6,0) default NULL COMMENT 'Voice Response Message Number',
  PRIMARY KEY  (`GTVDUNT_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `GTVINSM`
--

CREATE TABLE IF NOT EXISTS `GTVINSM` (
  `GTVINSM_CODE` varchar(5) NOT NULL default '' COMMENT 'Instructional Method Code: The Instructional Method Code',
  `GTVINSM_DESC` varchar(30) NOT NULL default '' COMMENT 'Description: Description of the instructional method code',
  `GTVINSM_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'Activity Date: Date this record entered or last updated',
  `GTVINSM_USER_ID` varchar(30) NOT NULL default '' COMMENT 'User ID: The username of the person who entered or last updated this record',
  `GTVINSM_VR_MSG_NO` decimal(6,0) default NULL COMMENT 'Voice Response Message Number',
  PRIMARY KEY  (`GTVINSM_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Instructional Method Code  Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `GTVINTP`
--

CREATE TABLE IF NOT EXISTS `GTVINTP` (
  `GTVINTP_CODE` varchar(5) NOT NULL default '' COMMENT 'PARTNER SYSTEM CODE: Used to define an external Integration Partner System. Valid Codes are ''WEBCT'' for WebCT Integration or ''BB'' for Blackboard Integration.',
  `GTVINTP_DESC` varchar(30) NOT NULL default '' COMMENT 'DESCRIPTION: Description of external Integration Partner System Code. For example ''WebCT Campus Edition / Vista'' or ''Blackboard''.',
  `GTVINTP_USER_ID` varchar(30) NOT NULL default '' COMMENT 'USER ID: The unique identification of the user.',
  `GTVINTP_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'ACTIVITY DATE: The date that the information for the row was inserted or updated in the GTVINTP table.',
  `GTVINTP_DATA_ORIGIN` varchar(30) default NULL COMMENT 'DATA ORIGIN: Source system that created or updated the row',
  PRIMARY KEY  (`GTVINTP_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Integration Partner System Code Validation Table.';

-- --------------------------------------------------------

--
-- Table structure for table `GTVMTYP`
--

CREATE TABLE IF NOT EXISTS `GTVMTYP` (
  `GTVMTYP_CODE` varchar(4) NOT NULL default '' COMMENT 'Meeting Type Code: The Meeting Type Code',
  `GTVMTYP_DESC` varchar(30) NOT NULL default '' COMMENT 'Description: Description of the Meeting Type code',
  `GTVMTYP_SYS_REQ_IND` char(1) NOT NULL default '' COMMENT 'System Required Indicator: Indicates whether or not this record is required to exist on the database',
  `GTVMTYP_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'Activty Date: Date this record entered or last updated',
  `GTVMTYP_USER_ID` varchar(30) NOT NULL default '' COMMENT 'User ID: The username of the person who entered or last updated this record',
  `GTVMTYP_VR_MSG_NO` decimal(6,0) default NULL COMMENT 'Voice Response Message Number',
  PRIMARY KEY  (`GTVMTYP_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Meeting Type Code validation table';

-- --------------------------------------------------------

--
-- Table structure for table `GTVSCHS`
--

CREATE TABLE IF NOT EXISTS `GTVSCHS` (
  `GTVSCHS_CODE` char(3) NOT NULL default '' COMMENT 'Code for Schedule Status.',
  `GTVSCHS_DESC` varchar(30) NOT NULL default '' COMMENT 'Description of Schedule Status code.',
  `GTVSCHS_SYSTEM_REQ_IND` char(1) NOT NULL default '' COMMENT 'A Y in this column indicates that the row is delivered by SCT and required for the system.',
  `GTVSCHS_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the date a record was created or updated.',
  PRIMARY KEY  (`GTVSCHS_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Validation entries for Schedule Status Codes.';

-- --------------------------------------------------------

--
-- Table structure for table `SCBCRSE`
--

CREATE TABLE IF NOT EXISTS `SCBCRSE` (
  `SCBCRSE_SUBJ_CODE` varchar(4) NOT NULL default '' COMMENT 'This field defines the subject area of the course.',
  `SCBCRSE_CRSE_NUMB` varchar(5) NOT NULL default '' COMMENT 'This field defines the course number associated with the subject for the        course.',
  `SCBCRSE_EFF_TERM` varchar(6) default NULL COMMENT 'This field identifies the term this version of the course becomes effective.',
  `SCBCRSE_COLL_CODE` char(2) default NULL COMMENT 'This field is used to specify the college which offers the course.',
  `SCBCRSE_DIVS_CODE` varchar(4) default NULL COMMENT 'This field can be used to specify the division which offers the course, if      desired.',
  `SCBCRSE_DEPT_CODE` varchar(4) default NULL COMMENT 'This field is used to specify the department which offers the course, if        desired.',
  `SCBCRSE_CSTA_CODE` char(1) default NULL COMMENT 'Course Status Code. It is a required field. It can be used to prevent creation of sections of the course based on the Active/Inactive flag associated with the status code.',
  `SCBCRSE_TITLE` varchar(30) default NULL COMMENT 'This field is used to specify the title of the course which will be in effect   for the effective term. It is a free format field.',
  `SCBCRSE_CIPC_CODE` varchar(6) default NULL COMMENT 'This field can be used to record the CIP code of the course.  Normally, CIP     course codes are used to identify the primary subject matter of a course.',
  `SCBCRSE_CREDIT_HR_IND` char(2) default NULL COMMENT 'This field defines whether the course can be offered for variable credit.       OR/TO is used to specify the relationship between low and high credit hours.    values.  An OR/TO value must exist to enter Credit Hours High.',
  `SCBCRSE_CREDIT_HR_LOW` decimal(7,3) default NULL COMMENT 'This field specifies the minimum number of credits for which a course may       be offered.  Credit hour values will control the number of credit hours for     which sections can be scheduled and for which students may register.',
  `SCBCRSE_CREDIT_HR_HIGH` decimal(7,3) default NULL COMMENT 'This field is used either to define a second valid credit hour value or to      define the high credit hour value within a range.  A value may only be entered  if the OR/TO field is complete and value must be > Credit Hours Low.',
  `SCBCRSE_LEC_HR_IND` char(2) default NULL COMMENT 'This field defines whether the course can be offered for variable lecture hours.  OR/TO is used to specify the relationship between the low and high lecture hour values.  An OR/TO value must exist to enter Lecture Hours High.',
  `SCBCRSE_LEC_HR_LOW` decimal(7,3) default NULL COMMENT 'This field is used to specify the minimum number of lecture hours for which a course may be offered.',
  `SCBCRSE_LEC_HR_HIGH` decimal(7,3) default NULL COMMENT 'This field is used either to define a second valid lecture hour value or to define the high lecture hour value within a range.  A value may only be entered if the OR/TO field is complete and must be > Lecture Hours Low.',
  `SCBCRSE_LAB_HR_IND` char(2) default NULL COMMENT 'This field defines whether the course can be offered for variable lab hours.  OR/TO is used to specify the relationship between the low and high lab hour values.  An OR/TO value must exist to enter Lab Hours High.',
  `SCBCRSE_LAB_HR_LOW` decimal(7,3) default NULL COMMENT 'This field is used to specify the minimum number of lab hours for which a course may be offered.',
  `SCBCRSE_LAB_HR_HIGH` decimal(7,3) default NULL COMMENT 'This field is used either to define a second valid lab hour value or to define the high lab hour value within a range.  A value may only be entered if the OR/TO field is complete and this field value must be > Lab Hours Low.',
  `SCBCRSE_OTH_HR_IND` char(2) default NULL COMMENT 'This field defines whether the course can be offered for variable other hours.  OR/TO is used to specify the relationship between the low and high other hour values.  An OR/TO value must exist to enter Other Hours High.',
  `SCBCRSE_OTH_HR_LOW` decimal(7,3) default NULL COMMENT 'This field is used to specify the minimum number of other hours for which the course can be offered. Other hours is defined by the institution.',
  `SCBCRSE_OTH_HR_HIGH` decimal(7,3) default NULL COMMENT 'This field is used to define a second valid other hour value or to define the high other hours value within a range.  A value may only be entered if the OR/TO field is complete and this field value must be > Other Hours Low.',
  `SCBCRSE_BILL_HR_IND` char(2) default NULL COMMENT 'This field defines whether the course can be billed for variable credit.        OR/TO is used to specify the relationship between the low and high billing      hour values.  An OR/TO value must exist to enter Billing Hours High.',
  `SCBCRSE_BILL_HR_LOW` decimal(7,3) default NULL COMMENT 'This field is used to specify the minimum number of credits for which the       course can be billed based on defined assessment rules.  Billing hours will     default to the values defined for credit hours, but may be changed.',
  `SCBCRSE_BILL_HR_HIGH` decimal(7,3) default NULL COMMENT 'This field defines a second valid billing hours value or to define the high     billing hours value within a range.  A value may only be entered if the         OR/TO  field is complete and this field value must be > Billing Hours Low.',
  `SCBCRSE_APRV_CODE` char(1) default NULL COMMENT 'This field is used to record the type of requirements a course is approved to   fulfill.',
  `SCBCRSE_REPEAT_LIMIT` decimal(2,0) default NULL COMMENT 'This field is used to record the maximum number of times the course may be      repeated by a student.  Zero (0) will default.  Repeat Limit is used in the     repeat check process in Academic History.',
  `SCBCRSE_PWAV_CODE` char(1) default NULL COMMENT 'This field is used to record the type of authorization a student must have in   order to waive a pre-requisite requirement.  It is informational only.',
  `SCBCRSE_TUIW_IND` char(1) default NULL COMMENT 'This field specifies whether the course is exempt from tuit. and fees defined   on Reg. Fees Process Control Form - SFARGFE.  If "Y", all rules on SFARGFE      with "Y" in Override field ignored.  If "N", rules on SFARGFE apply.',
  `SCBCRSE_ADD_FEES_IND` char(1) default NULL COMMENT 'This field is used to record that additional fees, outside of the SFARGFE       assessment rules, are charged for the course.  Additional course fees are       defined in Fee Code Block of Course Detail Form - SCADETL. Info. only.',
  `SCBCRSE_ACTIVITY_DATE` date default NULL COMMENT 'This field specifies the date the record was created or updated.',
  `SCBCRSE_CONT_HR_LOW` decimal(7,3) default NULL COMMENT 'This column contains the sum of low lecture, lab and others hours columns. Contact hours divided by 10 are the continuing education units for a continuing ed course. This calculated value is stored in the credit hour field when the ceu_ind = Y',
  `SCBCRSE_CONT_HR_IND` char(2) default NULL COMMENT 'This column contains the literal TO or OR to define the relationship between the low and high contact hours',
  `SCBCRSE_CONT_HR_HIGH` decimal(7,3) default NULL COMMENT 'This column contains the sum of high lecture, lab and others hours columns. It functions the same as the cont_hr_low column. A value may only be entered if the OR/TO field is entered and must be > contact hours low',
  `SCBCRSE_CEU_IND` char(1) default NULL,
  `SCBCRSE_REPS_CODE` char(2) default NULL COMMENT 'Repeat status code used for reporting purposes.',
  `SCBCRSE_MAX_RPT_UNITS` decimal(9,3) default NULL COMMENT 'Maximum number of credits permitted.',
  `SCBCRSE_CAPP_PREREQ_TEST_IND` char(1) NOT NULL default '' COMMENT 'Indicates whether CAPP areas or existing prerequisite and test score restrictions are in effect for the course for the effective term',
  `SCBCRSE_DUNT_CODE` varchar(4) default NULL COMMENT 'Duration Unit code indicates the type of duration the course is offered for',
  `SCBCRSE_NUMBER_OF_UNITS` decimal(7,2) default NULL COMMENT 'Duration number of units indicates the total number of times the duration unit extends',
  `SCBCRSE_DATA_ORIGIN` varchar(30) default NULL COMMENT 'DATA SOURCE: Source system that created or updated the row',
  `SCBCRSE_USER_ID` varchar(30) default NULL COMMENT 'USER ID: User who inserted or last update the data',
  UNIQUE KEY `SCBCRSE_KEY_INDEX` (`SCBCRSE_SUBJ_CODE`,`SCBCRSE_CRSE_NUMB`,`SCBCRSE_EFF_TERM`),
  KEY `SCBCRSE_COLL_CODE` (`SCBCRSE_COLL_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Course General Information Base Table';


-- --------------------------------------------------------

--
-- Table structure for table `SCBDESC`
--

CREATE TABLE IF NOT EXISTS `SCBDESC` (
  `SCBDESC_SUBJ_CODE` varchar(4) NOT NULL COMMENT 'Subject code.  This field indicates the subject code of the course.',
  `SCBDESC_CRSE_NUMB` varchar(5) NOT NULL COMMENT 'Course number.  This field indicatesd the course number of the course.',
  `SCBDESC_TERM_CODE_EFF` varchar(6) NOT NULL COMMENT 'Effective Term.  This field identifies the term this version of the course becomes effective.',
  `SCBDESC_ACTIVITY_DATE` date NOT NULL COMMENT 'ACTIVITY DATE: The date that the information for the row was inserted or updated in the SCBDESC table.',
  `SCBDESC_USER_ID` varchar(30) NOT NULL COMMENT 'USER IDENTIFICATION: The unique identification of the user who changed the record.',
  `SCBDESC_TEXT_NARRATIVE` mediumtext COMMENT 'Course descriptive text is maintained here.',
  `SCBDESC_TERM_CODE_END` varchar(6) default NULL COMMENT 'End Term.  Identifies the term that the course description effective term ends',
  PRIMARY KEY  (`SCBDESC_SUBJ_CODE`,`SCBDESC_CRSE_NUMB`,`SCBDESC_TERM_CODE_EFF`),
  FULLTEXT KEY `SCBDESC_TEXT_NARRATIVE` (`SCBDESC_TEXT_NARRATIVE`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Course Catalog Description Narrative Text Table';

-- --------------------------------------------------------

--
-- Table structure for table `SCRATTR`
--

CREATE TABLE IF NOT EXISTS `SCRATTR` (
  `SCRATTR_SUBJ_CODE` varchar(4) NOT NULL COMMENT 'This field defines the subject area of the course',
  `SCRATTR_CRSE_NUMB` varchar(5) NOT NULL COMMENT 'This field defines the course number associated with the subject for the course',
  `SCRATTR_EFF_TERM` varchar(6) NOT NULL COMMENT 'This field identifies the term this version of the course attributes become effective',
  `SCRATTR_ATTR_CODE` varchar(4) default NULL COMMENT 'This field defines the attribute code of the course',
  `SCRATTR_ACTIVITY_DATE` date NOT NULL COMMENT 'This field identifies the date the record was created or updated',
  UNIQUE KEY `SCRATTR_SUBJ_CODE` (`SCRATTR_SUBJ_CODE`,`SCRATTR_CRSE_NUMB`,`SCRATTR_EFF_TERM`,`SCRATTR_ATTR_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Course Attribute Repeating Table';

-- --------------------------------------------------------

--
-- Table structure for table `SCREQIV`
--

CREATE TABLE IF NOT EXISTS `SCREQIV` (
  `SCREQIV_SUBJ_CODE` varchar(4) NOT NULL COMMENT 'This field defines the subject area of the course.',
  `SCREQIV_CRSE_NUMB` varchar(5) NOT NULL COMMENT 'This field defines the course number associated with the subject for the        course.',
  `SCREQIV_EFF_TERM` varchar(6) NOT NULL COMMENT 'This field identifies the term this version of the course equivalents becomes   effective.',
  `SCREQIV_SUBJ_CODE_EQIV` varchar(4) default NULL COMMENT 'This field defines an equivalent course subject code for the master course.     Defining equivalents for a course is optional.  An unlimited number of          equivalents can be authorized for a course.',
  `SCREQIV_CRSE_NUMB_EQIV` varchar(5) default NULL COMMENT 'This field defines the course number of the equivalent course.  Course          numbers are not validated against valid course versions.',
  `SCREQIV_START_TERM` varchar(6) default NULL COMMENT 'This field specifies the start term of the course version which is              equivalent to the course entered in the Key Block.',
  `SCREQIV_END_TERM` varchar(6) default NULL COMMENT 'This field is used to specify the end term of the course version which is       equivalent to the course entered in the Key Block.',
  `SCREQIV_ACTIVITY_DATE` date NOT NULL COMMENT 'This field specifies the most current date record was created or updated.',
  UNIQUE KEY `SCREQIV_SUBJ_CODE` (`SCREQIV_SUBJ_CODE`,`SCREQIV_CRSE_NUMB`,`SCREQIV_EFF_TERM`,`SCREQIV_SUBJ_CODE_EQIV`,`SCREQIV_CRSE_NUMB_EQIV`,`SCREQIV_START_TERM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Equivalent Course Repeating Table';

-- --------------------------------------------------------

--
-- Table structure for table `SCRLEVL`
--

CREATE TABLE IF NOT EXISTS `SCRLEVL` (
  `SCRLEVL_SUBJ_CODE` varchar(4) NOT NULL COMMENT 'This field defines the subject area of the course.',
  `SCRLEVL_CRSE_NUMB` varchar(5) NOT NULL COMMENT 'This field defines the course number associated with the subject for the course.',
  `SCRLEVL_EFF_TERM` varchar(6) NOT NULL COMMENT 'This field identifies the term this version of the course level becomes effective.',
  `SCRLEVL_LEVL_CODE` varchar(2) NOT NULL COMMENT 'This field is used to authorize the level for which a course may be offered.    Each course must be authorized to be offered for at least one level.  A         course can be authorized for an unlimited number of levels.',
  `SCRLEVL_ACTIVITY_DATE` date NOT NULL COMMENT 'This field specifies the most current date record was created or updated.',
  PRIMARY KEY  (`SCRLEVL_SUBJ_CODE`,`SCRLEVL_CRSE_NUMB`,`SCRLEVL_EFF_TERM`,`SCRLEVL_LEVL_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Course Level Repeating Table';


-- --------------------------------------------------------

--
-- Table structure for table `SIRASGN`
--

CREATE TABLE IF NOT EXISTS `SIRASGN` (
  `SIRASGN_TERM_CODE` varchar(6) NOT NULL default '' COMMENT 'Term of the faculty member assignment',
  `SIRASGN_CRN` varchar(5) NOT NULL default '' COMMENT 'The course reference of the course that the instructor was assigned to',
  `SIRASGN_PIDM` decimal(8,0) NOT NULL default '0' COMMENT 'The Pidm of the faculty member',
  `SIRASGN_CATEGORY` char(2) NOT NULL default '' COMMENT 'The session indicator associated with the assignment',
  `SIRASGN_PERCENT_RESPONSE` decimal(3,0) NOT NULL default '0' COMMENT 'Faculty members percentage of responsibility to the assignment',
  `SIRASGN_WORKLOAD_ADJUST` decimal(9,0) default NULL COMMENT 'Faculty Adjustied Workload for instructional assignment.',
  `SIRASGN_PERCENT_SESS` decimal(3,0) NOT NULL default '0' COMMENT 'Faculty session percentage of responsibility of instructional assignment.',
  `SIRASGN_PRIMARY_IND` char(1) default NULL COMMENT 'The primary instructor of the course',
  `SIRASGN_OVER_RIDE` char(1) default NULL COMMENT 'Override Indicator.',
  `SIRASGN_POSITION` decimal(8,0) default NULL COMMENT 'Faculty Position.',
  `SIRASGN_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'Activity date',
  `SIRASGN_FCNT_CODE` char(2) default NULL COMMENT 'The contract type that the instructional assignment is associated with',
  `SIRASGN_POSN` varchar(6) default NULL COMMENT 'This field is the Position number for the faculty assignment.  It is used to tie the faculty member''s assignment to a position defined in the BANNER Human Resources system',
  `SIRASGN_SUFF` char(2) default NULL COMMENT 'This field is the Position number suffix.  It is used to tie the faculty member''s assignment to a position defined in the BANNER Human Resources System',
  `SIRASGN_ASTY_CODE` varchar(4) default NULL COMMENT 'Faculty Assignment Type Code',
  `SIRASGN_DATA_ORIGIN` varchar(30) default NULL COMMENT 'DATA SOURCE: Source system that created or updated the row',
  `SIRASGN_USER_ID` varchar(30) default NULL COMMENT 'USER ID: User who inserted or last update the data',
  PRIMARY KEY  (`SIRASGN_TERM_CODE`,`SIRASGN_CRN`,`SIRASGN_PIDM`,`SIRASGN_CATEGORY`),
  UNIQUE KEY `SIRASGN_KEY_INDEX2` (`SIRASGN_PIDM`,`SIRASGN_TERM_CODE`,`SIRASGN_CRN`,`SIRASGN_CATEGORY`),
  KEY `SIRASGN_CRN` (`SIRASGN_CRN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Faculty Member Instructional Assignment  Repeating Table';

-- --------------------------------------------------------

--
-- Table structure for table `SSBDESC`
--

CREATE TABLE IF NOT EXISTS `SSBDESC` (
  `SSBDESC_TERM_CODE` varchar(6) NOT NULL COMMENT 'Term Code.  This field identifies the term for which you are creating descriptive text for a course section.',
  `SSBDESC_CRN` varchar(5) NOT NULL COMMENT 'This field identifies the course Reference Number for which you are creating descriptive text.',
  `SSBDESC_TEXT_NARRATIVE` text NOT NULL COMMENT 'Course section descriptive text is maintained here.',
  `SSBDESC_ACTIVITY_DATE` date NOT NULL COMMENT 'ACTIVITY DATE: The date that the information for the row was inserted or updated in the SSBDESC table.',
  `SSBDESC_USER_ID` varchar(30) NOT NULL COMMENT 'USER IDENTIFICATION: The unique identification of the user who changed the record.',
  PRIMARY KEY  (`SSBDESC_TERM_CODE`,`SSBDESC_CRN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Section Description Table';

-- --------------------------------------------------------

--
-- Table structure for table `SSBSECT`
--

CREATE TABLE IF NOT EXISTS `SSBSECT` (
  `SSBSECT_TERM_CODE` varchar(6) NOT NULL default '' COMMENT 'This field is not displayed on the form (page 0).  It defines the Term code for the course section information.  It is derived from the Key Block Term.',
  `SSBSECT_CRN` varchar(5) NOT NULL default '' COMMENT 'This field is not displayed on the form (page 0).  It will display the Course Reference Number (CRN) assigned to this course section when it was initially added.',
  `SSBSECT_PTRM_CODE` char(3) default NULL COMMENT 'This field is used to specify the part-of-term in which the section is offered.  The value entered here must be valid as defined for the term on the Term Control Form - SOATERM.  The default is "1".',
  `SSBSECT_SUBJ_CODE` varchar(4) NOT NULL default '' COMMENT 'This field is required in order to add a section.  In addition, the combina- tion of Subject, Course Number and Key Block Term must identify a valid course version as maintained in the Catalog Module.',
  `SSBSECT_CRSE_NUMB` varchar(5) NOT NULL default '' COMMENT '"This field is used to the determine the course number in the  Key Block with a Term and Subject  to identify a valid course version from the Catalog Module.  If you try to schedule a section for a term restricted on the Schedule Restrictions Form - SCARR',
  `SSBSECT_SEQ_NUMB` char(3) NOT NULL default '' COMMENT 'This field identifies the section number of a course.  A Section number can only be used once to identify a Subject/Course Number combination in a term, however, multiple sections of a course can share a 0 section number.',
  `SSBSECT_SSTS_CODE` char(1) NOT NULL default '' COMMENT 'This field maintains the status of a section.  The ''Allow Registration'' switch on the Section Status Validation Form - STVSSTS determines whether the status entered will prevent or allow registration for this section.',
  `SSBSECT_SCHD_CODE` char(3) NOT NULL default '' COMMENT 'This field identifies the instructional type of the section being scheduled.  It is a required field on an add.  Use the LIST FIELD VALUES key to display the Schedule Type Validation Form - STVSCHD.',
  `SSBSECT_CAMP_CODE` char(3) NOT NULL default '' COMMENT 'This field defines the campus on which the section is scheduled.  If you attempt to schedule a course for a campus which is restricted on the Course Schedule Restrictions Form - SCASRES, an error will occur.',
  `SSBSECT_CRSE_TITLE` varchar(30) default NULL COMMENT 'This field will display the title of the course as defined on the Basic Course Information Form - SCACRSE.',
  `SSBSECT_CREDIT_HRS` decimal(7,3) default NULL COMMENT 'This field can be used to restrict a section to a single credit hr. value when the course was defined with variable credit hours. on the Basic Course Information Form - SCACRSE.  This value must fall between the variable values.',
  `SSBSECT_BILL_HRS` decimal(7,3) default NULL COMMENT 'This field can be used to restrict a section to a single billing hour value when the course was defined with variable billing hours on the Basic Course Information Form - SCACRSE.  This value must fall between the variable values.',
  `SSBSECT_GMOD_CODE` char(1) default NULL COMMENT 'This field can be used to specify one grading mode for this section from the grading mode(s) defined for the course on the Basic Course Information Form - SCACRSE.  This field is optional.',
  `SSBSECT_SAPR_CODE` char(2) default NULL COMMENT 'This field can be used to specify the type of special approval a student must have to register.  The Special Approval Severity switch on the Term Control Form - SOATERM determines how this is checked at registration.',
  `SSBSECT_SESS_CODE` char(1) default NULL COMMENT 'This field can be used to identify the session in which the section is scheduled.  This is an optional field.  Use the LIST FIELD VALUES key to display the Session Code Validation Form - STVSESS.',
  `SSBSECT_LINK_IDENT` char(2) default NULL COMMENT 'This field can be used to link other sections of the same course which must be taken concurrently.  Enter a code in this field, then enter same code in Link connector field for linked section on Schedule Detail Form - SSADETL.',
  `SSBSECT_PRNT_IND` char(1) default NULL COMMENT 'This field is used to specify whether the section should be printed in the Schedule Report - SSRSECT.  "Y" - Yes, print section is the default value.',
  `SSBSECT_GRADABLE_IND` char(1) default NULL COMMENT 'This field is used to specify whether or not the section is gradable.  It is a required field and "Y" - Yes, Gradable, is the default value.',
  `SSBSECT_TUIW_IND` char(1) default NULL COMMENT 'This field can be used to specify that this section be exempt from the assessment of tuition and fees as defined on the Registration Fees Process Control Form - SFARGFE (See Field Usage Notes).',
  `SSBSECT_REG_ONEUP` decimal(4,0) NOT NULL default '0' COMMENT 'This field is not displayed on the form (page 0).  It displays the count associated with the last student registered for the course section.',
  `SSBSECT_PRIOR_ENRL` decimal(4,0) NOT NULL default '0' COMMENT 'This field is system maintained.  When the Section Roll Process, SSRROLL, is used to create section entries based on a previous term222s sections, the Prior enrollment field is set to the enrollment from the prior term.',
  `SSBSECT_PROJ_ENRL` decimal(4,0) NOT NULL default '0' COMMENT 'This field is used to record the anticipated enrollment in the section for the term.  It is an optional field which defaults to "0" when a section is added, and it controls no system processing.',
  `SSBSECT_MAX_ENRL` decimal(4,0) NOT NULL default '0' COMMENT 'This field is used to set the maximum enrollment for a section.  When the Capacity severity switch is set to ''W'' or ''F'' on the Term Control Form - SOATERM, the system issues warnings when enrollment exceeds this number.',
  `SSBSECT_ENRL` decimal(4,0) NOT NULL default '0' COMMENT 'This field is system maintained.  It displays a running total of enrollments in the section which have a course status with a ''Count in Enroll'' flag of ''Y'' on the Registration Status Code Validation Form - STVRSTS.',
  `SSBSECT_SEATS_AVAIL` decimal(4,0) NOT NULL default '0' COMMENT 'This field is system maintained.  It will display a running total of remaining avaialable seats in the section by subtracting the Actual enrollment field from the Maximum enrollment field defined for this section.',
  `SSBSECT_TOT_CREDIT_HRS` decimal(9,3) default NULL COMMENT 'This field is system maintained.  It will display a running total of all enrolled credit hours in the section.',
  `SSBSECT_CENSUS_ENRL` decimal(4,0) default NULL COMMENT 'This field is system maintained.  It will display the running total of students registered for the section prior to the census date defined for the part of term.',
  `SSBSECT_CENSUS_ENRL_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field is defined in the Census field in the Base Part of Term Block on the Term Control Form - SOATERM, and will default to this field based on the Key Block Term and Part/Term field in the Section Block of this form.',
  `SSBSECT_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field specifies the most current date record was created or updated.',
  `SSBSECT_PTRM_START_DATE` date default NULL COMMENT 'This field is defined in the Start field in the Base Part of Term Block on the Term Control Form - SOATERM, and will default to this field based on the Key Block Term and Part/Term field in the Section Block of this form.',
  `SSBSECT_PTRM_END_DATE` date default NULL COMMENT 'This field is defined in the End field in the Base Part of Term Block on the Term Control Form - SOATERM, and will default to this field based on the Key Block Term and Part/Term field in the Section Block of this form.',
  `SSBSECT_PTRM_WEEKS` decimal(3,0) default NULL COMMENT 'This field is defined in the ''Wks'' field in the Base Part of Term Block on the Term Control Form - SOATERM, and will default to this field based on the Key Block Term and Part/Term field in the Section Block of this form.',
  `SSBSECT_RESERVED_IND` char(1) default NULL COMMENT 'This field indicates whether reserved seats are defined for the section',
  `SSBSECT_WAIT_CAPACITY` decimal(4,0) default NULL COMMENT 'This field is used to set the maximum waitlist enrollment for a section',
  `SSBSECT_WAIT_COUNT` decimal(4,0) default NULL COMMENT 'This field is system maintained.  It displays a running total of enrollments in the section which have course status with a waitlist flag set to Y on the Registration Status Code Validation Form - STVRSTS',
  `SSBSECT_WAIT_AVAIL` decimal(4,0) default NULL COMMENT 'This field is system maintained.  It displays a running total of remaining available waitlist seats by subtracting the waitlist actual enrollment from the maximum waitlist enrollment for the section',
  `SSBSECT_LEC_HR` decimal(9,3) default NULL COMMENT 'This field is used to specify the lecture hours specific to this section.',
  `SSBSECT_LAB_HR` decimal(9,3) default NULL COMMENT 'This field is used to specify the lab hours specific to this section.',
  `SSBSECT_OTH_HR` decimal(9,3) default NULL COMMENT 'This field is used to specify the other hours specific to this section.  Other hours is defined by the institution.',
  `SSBSECT_CONT_HR` decimal(9,3) default NULL COMMENT 'This field is the column that contains the sum of low lecture, lab and others hours.  Contact hours divided by 10 are the continuing education units for a continuing education section.  This calculated value is stored in the credit hour field',
  `SSBSECT_ACCT_CODE` char(2) default NULL COMMENT 'Accounting Method Code',
  `SSBSECT_ACCL_CODE` char(2) default NULL COMMENT 'This field can be used to specify the Academic Calendar Type associated with a section.',
  `SSBSECT_CENSUS_2_DATE` date default NULL COMMENT 'The census two date for the term and academic calendar year',
  `SSBSECT_ENRL_CUT_OFF_DATE` date default NULL COMMENT 'The last date on which students may enroll for the academic calendar type and registration status',
  `SSBSECT_ACAD_CUT_OFF_DATE` date default NULL COMMENT 'The last date when student may process a course without it appearing on their academic history information',
  `SSBSECT_DROP_CUT_OFF_DATE` date default NULL COMMENT 'The last date on which students may drop a course without assessing a penalty',
  `SSBSECT_CENSUS_2_ENRL` decimal(4,0) default NULL COMMENT 'The census two enrollment',
  `SSBSECT_VOICE_AVAIL` char(1) NOT NULL default '' COMMENT 'Indicates whether a section is available to Web and Voice Response Telephone Registration.  "N" denotes course is not available, "Y" denotes course is available.',
  `SSBSECT_CAPP_PREREQ_TEST_IND` char(1) NOT NULL default '' COMMENT 'Indicates whether CAPP areas or existing prerequisite and test score restrictions are in effect for the section.',
  `SSBSECT_GSCH_NAME` varchar(10) default NULL,
  `SSBSECT_BEST_OF_COMP` decimal(3,0) default NULL COMMENT 'SECTION BEST OF COMPONENTS: This field indicates the number of child components to be used in subset best of calculations when calculating a section score.',
  `SSBSECT_SUBSET_OF_COMP` decimal(3,0) default NULL COMMENT 'SECTION SUBSET OF COMPONENTS: This field indicates the number of child components to be used in subset calculations when calculating a section score.',
  `SSBSECT_INSM_CODE` varchar(5) default NULL COMMENT 'Instructional Method Code. The instructional method code assigned to the section',
  `SSBSECT_REG_FROM_DATE` date default NULL COMMENT 'Registration From Date.  The first date that registration will be open to the learner for this section',
  `SSBSECT_REG_TO_DATE` date default NULL COMMENT 'Registration To Date.  The last date that registrations will be accepted for this section.',
  `SSBSECT_LEARNER_REGSTART_FDATE` date default NULL COMMENT 'Learner Registration Start Date.  When registering for the course, the first date that the learner can start taking the course',
  `SSBSECT_LEARNER_REGSTART_TDATE` date default NULL COMMENT 'Learner Registration To Date.  When registering for the course, the last date that the learner can start taking the course',
  `SSBSECT_DUNT_CODE` varchar(4) default NULL COMMENT 'Duration Unit Code. The duration unit code assigned to the section',
  `SSBSECT_NUMBER_OF_UNITS` decimal(7,2) default NULL COMMENT 'Duration Number of Units.  The number of units the student will be given to complete the course.',
  `SSBSECT_NUMBER_OF_EXTENSIONS` decimal(3,0) NOT NULL default '0' COMMENT 'Number of Extensions.  The maximum number of extensions permitted for this section.',
  `SSBSECT_DATA_ORIGIN` varchar(30) default NULL COMMENT 'DATA ORIGIN: Source system that created or updated the row',
  `SSBSECT_USER_ID` varchar(30) default NULL COMMENT 'USER ID: User who inserted or last update the data',
  `SSBSECT_INTG_CDE` varchar(5) default NULL COMMENT 'INTEGRATION PARTNER SYSTEM CODE: Code defined on GORINTG that associates with the User Integration Code.',
  `SSBSECT_fulltext` text NOT NULL COMMENT 'A dynamicly generated column for full-text searching.',
  PRIMARY KEY  (`SSBSECT_TERM_CODE`,`SSBSECT_CRN`),
  UNIQUE KEY `SSBSECT_INDEX_SUBJ` (`SSBSECT_SUBJ_CODE`,`SSBSECT_CRSE_NUMB`,`SSBSECT_TERM_CODE`,`SSBSECT_CRN`),
  KEY `SSBSECT_GSCH_INDEX` (`SSBSECT_GSCH_NAME`),
  KEY `SSBSECT_PTRM_DATE_INDEX` (`SSBSECT_PTRM_START_DATE`),
  KEY `SSBSECT_DATE_INDEX` (`SSBSECT_REG_FROM_DATE`,`SSBSECT_REG_TO_DATE`,`SSBSECT_TERM_CODE`),
  FULLTEXT KEY `SSBSECT_fulltext_index` (`SSBSECT_fulltext`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Section General Information Base Table';

-- --------------------------------------------------------

--
-- Table structure for table `SSBXLST`
--

CREATE TABLE IF NOT EXISTS `SSBXLST` (
  `SSBXLST_TERM_CODE` varchar(6) NOT NULL COMMENT 'Cross List Section Term Code.',
  `SSBXLST_XLST_GROUP` varchar(2) NOT NULL COMMENT 'Cross List Group Identifier.',
  `SSBXLST_DESC` varchar(30) default NULL COMMENT 'Cross List Group Identifier.',
  `SSBXLST_MAX_ENRL` int(4) NOT NULL COMMENT 'Maxmum Cross List Enrollment.',
  `SSBXLST_ENRL` int(4) NOT NULL COMMENT 'Corss List Section Enrollment.',
  `SSBXLST_SEATS_AVAIL` int(4) NOT NULL COMMENT 'Cross List Section Available Seats.',
  `SSBXLST_ACTIVITY_DATE` date NOT NULL COMMENT 'This field identifies the most recent date a record was created or updated.',
  PRIMARY KEY  (`SSBXLST_TERM_CODE`,`SSBXLST_XLST_GROUP`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cross List Enrollment Information Base Table';

-- --------------------------------------------------------

--
-- Table structure for table `SSRATTR`
--

CREATE TABLE IF NOT EXISTS `SSRATTR` (
  `SSRATTR_TERM_CODE` varchar(6) NOT NULL default '' COMMENT 'This field defines the term for which you are creating a section attribute code.',
  `SSRATTR_CRN` varchar(5) NOT NULL default '' COMMENT 'This field defines the course reference number for which you are creating a section attribute code.',
  `SSRATTR_ATTR_CODE` varchar(4) NOT NULL default '' COMMENT 'This field defines the attribute code of the section.',
  `SSRATTR_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field contains the most current date the record was added or changed.',
  PRIMARY KEY  (`SSRATTR_TERM_CODE`,`SSRATTR_CRN`,`SSRATTR_ATTR_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Degree Program Attribute Repeating Table';

-- --------------------------------------------------------

--
-- Table structure for table `SSRMEET`
--

CREATE TABLE IF NOT EXISTS `SSRMEET` (
  `SSRMEET_TERM_CODE` varchar(6) NOT NULL default '' COMMENT 'This field is not displayed on the form (page 0).  It defines the term for which you are creating meeting times for the course section.  It is based on the Key Block Term.',
  `SSRMEET_CRN` varchar(5) NOT NULL default '' COMMENT 'This field is not displayed on the form (page 0).  It defines the Course Reference Number for the course section for which you are creating meeting times',
  `SSRMEET_DAYS_CODE` char(1) default NULL COMMENT 'This field defines the Day code for which the Key Block section will be scheduled.  It is a required field to enter a meeting time record.',
  `SSRMEET_DAY_NUMBER` decimal(1,0) default NULL COMMENT 'This field is not displayed on the form (page 0).  It defines the day number as defined on the STVDAYS Validation Form',
  `SSRMEET_BEGIN_TIME` varchar(4) default NULL COMMENT 'This field defines the Begin Time of the course section being scheduled.  It is a required field and is in the format HHMM using military times.  The SSRSECT (Schedule of Classes) converts this time to standard times.',
  `SSRMEET_END_TIME` varchar(4) default NULL COMMENT 'This field defines the End Time of the course section being scheduled.  It is a required field and is in the format HHMM using military times.  The SSRSECT (Schedule of Classes) converts this time to standard times.',
  `SSRMEET_BLDG_CODE` varchar(6) default NULL COMMENT 'This field defines the Building where the course section will be scheduled.  It is not required when scheduling course section meeting times.  It is required when scheduling course section meeting rooms.',
  `SSRMEET_ROOM_CODE` varchar(10) default NULL COMMENT 'This field defines the Room where the course section will be scheduled.  It is not required when scheduling course section meeting times.  It is required when scheduling a course section meeting building.',
  `SSRMEET_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field specifies the most current date record was created or updated.',
  `SSRMEET_START_DATE` date NOT NULL default '0000-00-00' COMMENT 'Section Meeting Start Date.',
  `SSRMEET_END_DATE` date NOT NULL default '0000-00-00' COMMENT 'Section End Date.',
  `SSRMEET_CATAGORY` char(2) default NULL COMMENT 'Section Indicator.',
  `SSRMEET_SUN_DAY` char(1) default NULL COMMENT 'Section Meeting Time Sunday Indicator.',
  `SSRMEET_MON_DAY` char(1) default NULL COMMENT 'Section Meeting Time Monday Indicator.',
  `SSRMEET_TUE_DAY` char(1) default NULL COMMENT 'Section Meeting Time Tuesday Indicator.',
  `SSRMEET_WED_DAY` char(1) default NULL COMMENT 'Section Meeting Time Wednesday Indicator.',
  `SSRMEET_THU_DAY` char(1) default NULL COMMENT 'Section Meeting Time Thrusday Indicator.',
  `SSRMEET_FRI_DAY` char(1) default NULL COMMENT 'Section Meeting Time Friday Indicator.',
  `SSRMEET_SAT_DAY` char(1) default NULL COMMENT 'Section Meeting Time Saturday Indicator.',
  `SSRMEET_SCHD_CODE` char(3) default NULL COMMENT 'Section Schedule Type.',
  `SSRMEET_OVER_RIDE` char(1) default NULL COMMENT 'Section Time Conflict Override Indicator.',
  `SSRMEET_CREDIT_HR_SESS` decimal(7,3) default NULL COMMENT 'The session credit hours',
  `SSRMEET_MEET_NO` decimal(4,0) default NULL COMMENT 'Total Section Meeting Number which is system generated.',
  `SSRMEET_HRS_WEEK` decimal(5,2) default NULL COMMENT 'Section Metting Hours per Week.',
  `SSRMEET_FUNC_CODE` varchar(12) default NULL COMMENT 'Function code assigned to an event',
  `SSRMEET_COMT_CODE` varchar(6) default NULL COMMENT 'Committee/Service Type code.',
  `SSRMEET_SCHS_CODE` char(3) default NULL COMMENT 'Schedule Status Code for use with Scheduling Tool Interface .',
  `SSRMEET_MTYP_CODE` varchar(4) default NULL COMMENT 'Meeting Type Code. The meeting type code assigned to this meeting time of the section',
  `SSRMEET_DATA_ORIGIN` varchar(30) default NULL COMMENT 'DATA SOURCE: Source system that created or updated the row',
  `SSRMEET_USER_ID` varchar(30) default NULL COMMENT 'USER ID: User who inserted or last update the data',
  KEY `SSRMEET_KEY_INDEX` (`SSRMEET_TERM_CODE`,`SSRMEET_CRN`,`SSRMEET_BEGIN_TIME`,`SSRMEET_MON_DAY`,`SSRMEET_TUE_DAY`,`SSRMEET_WED_DAY`,`SSRMEET_THU_DAY`,`SSRMEET_FRI_DAY`,`SSRMEET_SAT_DAY`,`SSRMEET_SUN_DAY`),
  KEY `SSRMEET_KEY_INDEX2` (`SSRMEET_BLDG_CODE`,`SSRMEET_ROOM_CODE`,`SSRMEET_BEGIN_TIME`,`SSRMEET_END_TIME`,`SSRMEET_MON_DAY`,`SSRMEET_TUE_DAY`,`SSRMEET_WED_DAY`,`SSRMEET_THU_DAY`,`SSRMEET_FRI_DAY`,`SSRMEET_SAT_DAY`,`SSRMEET_SUN_DAY`,`SSRMEET_START_DATE`,`SSRMEET_END_DATE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Section Meeting Times Repeating Table';

-- --------------------------------------------------------

--
-- Table structure for table `SSRXLST`
--

CREATE TABLE IF NOT EXISTS `SSRXLST` (
  `SSRXLST_TERM_CODE` varchar(6) NOT NULL COMMENT 'Cross List Section Term.',
  `SSRXLST_XLST_GROUP` varchar(2) NOT NULL COMMENT 'Cross List Group Identifier Number.',
  `SSRXLST_CRN` varchar(5) NOT NULL COMMENT 'Corss List Section CRN.',
  `SSRXLST_ACTIVITY_DATE` date NOT NULL COMMENT 'This field identifies the most recent date a record was created or updated.',
  PRIMARY KEY  (`SSRXLST_TERM_CODE`,`SSRXLST_XLST_GROUP`,`SSRXLST_CRN`),
  KEY `SSRXLST_TERM_CODE` (`SSRXLST_TERM_CODE`,`SSRXLST_CRN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cross List Section Repeating Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVACYR`
--

CREATE TABLE IF NOT EXISTS `STVACYR` (
  `STVACYR_CODE` varchar(4) NOT NULL default '' COMMENT 'Identifies the abbreviation for the beginning/ ending periods for academic year referenced in the General Student, Academic History, Degree Audit Modules. Format CCYY (e.g. 1995-1996 coded 1996).',
  `STVACYR_DESC` varchar(30) default NULL COMMENT 'This field specifies the academic year associated with the academic year code.',
  `STVACYR_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most current date a record was created or updated.',
  `STVACYR_SYSREQ_IND` char(1) default NULL COMMENT 'The system required indicator',
  PRIMARY KEY  (`STVACYR_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Academic Year Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVAPRV`
--

CREATE TABLE IF NOT EXISTS `STVAPRV` (
  `STVAPRV_CODE` char(1) NOT NULL default '' COMMENT 'This field indicates the catalog approval code referenced on the Basic Course Information Form (SCACRSE).',
  `STVAPRV_DESC` varchar(30) NOT NULL default '' COMMENT 'This field specifies the type of approval (e.g. dean"s, departmental, etc.)     associated with the approval code.',
  `STVAPRV_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most current date a record was created or updated.',
  PRIMARY KEY  (`STVAPRV_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Catalog Approval Code Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVASTY`
--

CREATE TABLE IF NOT EXISTS `STVASTY` (
  `STVASTY_CODE` varchar(4) NOT NULL default '' COMMENT 'Assignment Type Code',
  `STVASTY_DESC` varchar(30) NOT NULL default '' COMMENT 'Description of Code',
  `STVASTY_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'The Activity Date',
  PRIMARY KEY  (`STVASTY_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Assignment Type Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVATTR`
--

CREATE TABLE IF NOT EXISTS `STVATTR` (
  `STVATTR_CODE` varchar(4) NOT NULL default '' COMMENT 'Attribute code which defines degree requirements that may be either required by a degree program or satisfied by passing a course',
  `STVATTR_DESC` varchar(30) NOT NULL default '' COMMENT 'Description for the attribute code',
  `STVATTR_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'Description for the attribute code',
  PRIMARY KEY  (`STVATTR_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Degree Program Attribute Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVBLDG`
--

CREATE TABLE IF NOT EXISTS `STVBLDG` (
  `STVBLDG_CODE` varchar(6) NOT NULL default '' COMMENT 'This field identifies the building code referenced in the Class Schedule and Registration Modules.',
  `STVBLDG_DESC` varchar(30) NOT NULL default '' COMMENT 'This field specifies the building associated with the building code.',
  `STVBLDG_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated .',
  `STVBLDG_VR_MSG_NO` decimal(6,0) default NULL COMMENT 'The Voice Response message number assigned to the recorded message that describes the building code.',
  PRIMARY KEY  (`STVBLDG_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Building Code Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVCAMP`
--

CREATE TABLE IF NOT EXISTS `STVCAMP` (
  `STVCAMP_CODE` char(3) NOT NULL default '' COMMENT 'STVCAMP_DICD_CODE: District Identifier Code validated by form GTVDICD.',
  `STVCAMP_DESC` varchar(30) default NULL COMMENT 'This field defines the institution"s campus associated with the campus code.',
  `STVCAMP_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated.',
  `STVCAMP_DICD_CODE` char(3) default NULL COMMENT 'District Identifier Code validated by HR form PTVDICD.',
  PRIMARY KEY  (`STVCAMP_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Campus Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVCIPC`
--

CREATE TABLE IF NOT EXISTS `STVCIPC` (
  `STVCIPC_CODE` varchar(6) NOT NULL default '' COMMENT 'This field identifies the Classification of Instructional Programs (CIP) code   assigned an area of study as referenced in the Degree Program Code (SDAPROG)    and the Basic Course Info. Form (SCACRSE) and by STVMAJR.',
  `STVCIPC_DESC` varchar(30) NOT NULL default '' COMMENT 'This field specifies the area of study associated with the CIP code.  CIP       codes are used in Integrated Postsecondary Education Data System (IPEDS)        reporting.',
  `STVCIPC_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated.',
  `STVCIPC_CIPC_A_IND` char(1) default NULL COMMENT 'CIPC A indicator. Y causes the IPEDS Completions Report to process this CIPC code on the A Report',
  `STVCIPC_CIPC_B_IND` char(1) default NULL COMMENT 'CIPC B indicator. Y causes the IPEDS Completion Report to process the CIPC code on the B report.',
  `STVCIPC_CIPC_C_IND` char(1) default NULL COMMENT 'CIPC C indicator. Y causes the IPEDS Completion Report to process the CIPC code on the C report.',
  `STVCIPC_SP04_PROGRAM_CDE` varchar(5) default NULL COMMENT 'Code for California MIS process for field SP04.',
  PRIMARY KEY  (`STVCIPC_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='CIP Code Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVCOLL`
--

CREATE TABLE IF NOT EXISTS `STVCOLL` (
  `STVCOLL_CODE` char(2) NOT NULL default '' COMMENT 'This field identifies the college code referenced in the Catalog, Class Schedule, Recruiting, Admissions, General Student, Registration, Academic History and Degree Audit Modules. Reqd value: 00 - No College Designated.',
  `STVCOLL_DESC` varchar(30) NOT NULL default '' COMMENT 'This field specifies the college or school (i.e. **Banner"s** highest administrative unit) associated with the college code.',
  `STVCOLL_ADDR_STREET_LINE1` varchar(30) default NULL COMMENT 'This field is not currently in use.',
  `STVCOLL_ADDR_STREET_LINE2` varchar(30) default NULL COMMENT 'This field is not currently in use.',
  `STVCOLL_ADDR_STREET_LINE3` varchar(30) default NULL COMMENT 'This field is not currently in use.',
  `STVCOLL_ADDR_CITY` varchar(20) default NULL COMMENT 'This field is not currently in use.',
  `STVCOLL_ADDR_STATE` char(2) default NULL COMMENT 'This field is not currently in use.',
  `STVCOLL_ADDR_COUNTRY` varchar(28) default NULL COMMENT 'This field is not currently in use.',
  `STVCOLL_ADDR_ZIP_CODE` varchar(10) default NULL COMMENT 'This field is not currently in use.',
  `STVCOLL_ACTIVITY_DATE` date default NULL COMMENT 'This field identifies the most recent date a record was created or updated.',
  `STVCOLL_SYSTEM_REQ_IND` char(1) default NULL COMMENT 'System Required Indicator',
  `STVCOLL_VR_MSG_NO` decimal(6,0) default NULL COMMENT 'The Voice Response message number assigned to the recorded message that describes the college code.',
  `STVCOLL_STATSCAN_CDE3` varchar(6) default NULL COMMENT 'Statistics Canadian reporting institution specific code.',
  `STVCOLL_DICD_CODE` char(3) default NULL COMMENT 'MIS DISTRICT/DIVISION CODE: This field indicates equivalent district or division associated with an Institution.',
  PRIMARY KEY  (`STVCOLL_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='College Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVCOMT`
--

CREATE TABLE IF NOT EXISTS `STVCOMT` (
  `STVCOMT_CODE` varchar(6) NOT NULL default '' COMMENT 'Committee type code.',
  `STVCOMT_DESC` varchar(30) NOT NULL default '' COMMENT 'Committee type description.',
  `STVCOMT_TRANS_PRINT` char(1) default NULL COMMENT 'This field indicates whether the committee type will appear on transcript.',
  `STVCOMT_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most current date a record was created or changed.',
  PRIMARY KEY  (`STVCOMT_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Committee Type Code Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVCSTA`
--

CREATE TABLE IF NOT EXISTS `STVCSTA` (
  `STVCSTA_CODE` char(1) NOT NULL default '' COMMENT 'This field identifies the course status code referenced on the Basic Course Information Form (SCACRSE).',
  `STVCSTA_DESC` varchar(30) NOT NULL default '' COMMENT 'This field specifies the free-format course status associated with the status code.',
  `STVCSTA_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated.',
  `STVCSTA_ACTIVE_IND` char(1) NOT NULL default '' COMMENT 'Course Status Code Active Indicator.',
  PRIMARY KEY  (`STVCSTA_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Course Status Code Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVDEPT`
--

CREATE TABLE IF NOT EXISTS `STVDEPT` (
  `STVDEPT_CODE` varchar(4) NOT NULL default '' COMMENT 'This field identifies the department code referenced in the Catalog, Recruiting, Admissions, and Acad.  Hist.  Modules.  Required value: 0000 - Dept.  Not Declared.',
  `STVDEPT_DESC` varchar(30) NOT NULL default '' COMMENT 'This field specifies the department associated with the department code.',
  `STVDEPT_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated.',
  `STVDEPT_SYSTEM_REQ_IND` char(1) default NULL COMMENT 'System Required Indicator',
  `STVDEPT_VR_MSG_NO` decimal(6,0) default NULL COMMENT 'The Voice Response message number assigned to the recorded message that describes the department code.',
  PRIMARY KEY  (`STVDEPT_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Department Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVDIVS`
--

CREATE TABLE IF NOT EXISTS `STVDIVS` (
  `STVDIVS_CODE` varchar(4) NOT NULL default '' COMMENT 'This field identifies the division code referenced on the Basic Course Info.    Form (SCACRSE).  Reqd value:  0000 - Division Not Declared.',
  `STVDIVS_DESC` varchar(30) NOT NULL default '' COMMENT 'This field specifies the division associated with the division code.',
  `STVDIVS_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated.',
  PRIMARY KEY  (`STVDIVS_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `STVFCNT`
--

CREATE TABLE IF NOT EXISTS `STVFCNT` (
  `STVFCNT_CODE` char(2) NOT NULL default '' COMMENT 'Faculty member contract type code',
  `STVFCNT_DESC` varchar(30) NOT NULL default '' COMMENT 'Description of faculty member contract type code',
  `STVFCNT_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'The activity date',
  PRIMARY KEY  (`STVFCNT_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Faculty Contract Code Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVLEVL`
--

CREATE TABLE IF NOT EXISTS `STVLEVL` (
  `STVLEVL_CODE` varchar(2) NOT NULL COMMENT 'This field identifies the student level code referenced in the Catalog, Recruiting, Admissions, Gen Student, Registration, and Acad Hist Modules. Required value: 00 - Level Not Declared.',
  `STVLEVL_DESC` varchar(30) NOT NULL COMMENT 'This field specifies the student level (e.g. undergraduate, graduate, professional) associated with the student level code.',
  `STVLEVL_ACTIVITY_DATE` date NOT NULL COMMENT 'This field identifies the most recent date a record was created or updated.',
  `STVLEVL_ACAD_IND` varchar(1) default NULL COMMENT 'This field is not currently in use.',
  `STVLEVL_CEU_IND` varchar(1) NOT NULL COMMENT 'Continuing Education Indicator.',
  `STVLEVL_SYSTEM_REQ_IND` varchar(1) default NULL COMMENT 'System Required Indicator',
  `STVLEVL_VR_MSG_NO` int(6) default NULL COMMENT 'The Voice Response message number assigned to the recorded message that describes the student level.',
  `STVLEVL_EDI_EQUIV` varchar(2) default NULL COMMENT 'EDI Level Code',
  PRIMARY KEY  (`STVLEVL_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Student Level Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVMEET`
--

CREATE TABLE IF NOT EXISTS `STVMEET` (
  `STVMEET_CODE` varchar(2) NOT NULL COMMENT 'Meeting Time Code',
  `STVMEET_MON_DAY` varchar(1) default NULL COMMENT 'Monday Indicator.',
  `STVMEET_TUE_DAY` varchar(1) default NULL COMMENT 'Tuesday Indicator.',
  `STVMEET_WED_DAY` varchar(1) default NULL COMMENT 'Wednesday Indicator.',
  `STVMEET_THU_DAY` varchar(1) default NULL COMMENT 'Thrusday Indicator.',
  `STVMEET_FRI_DAY` varchar(1) default NULL COMMENT 'Friday Indicator.',
  `STVMEET_SAT_DAY` varchar(1) default NULL COMMENT 'Saturday Indicator.',
  `STVMEET_SUN_DAY` varchar(1) default NULL COMMENT 'Sunday Indicator.',
  `STVMEET_BEGIN_TIME` varchar(4) default NULL COMMENT 'Begin Time.',
  `STVMEET_END_TIME` varchar(4) default NULL COMMENT 'End Time.',
  `STVMEET_ACTIVITY_DATE` date NOT NULL COMMENT 'This field identifies the most recent date a record was created or updated.',
  PRIMARY KEY  (`STVMEET_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Meeting Time Code Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVPWAV`
--

CREATE TABLE IF NOT EXISTS `STVPWAV` (
  `STVPWAV_CODE` char(1) NOT NULL default '' COMMENT 'This field identifies the prerequisite waiver code referenced on the Basic      Course Information Form (SCACRSE).',
  `STVPWAV_DESC` varchar(30) NOT NULL default '' COMMENT 'This field specifies the prerequisite waiver requirement/approval source        associated with the pre-req waiver code.',
  `STVPWAV_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated.',
  PRIMARY KEY  (`STVPWAV_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Pre-Requisite Waiver Code Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVREPS`
--

CREATE TABLE IF NOT EXISTS `STVREPS` (
  `STVREPS_CODE` char(2) NOT NULL default '' COMMENT 'Repeat status code used for reporting purposes.',
  `STVREPS_DESC` varchar(30) NOT NULL default '' COMMENT 'Description of repeat status.',
  `STVREPS_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated.',
  PRIMARY KEY  (`STVREPS_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Repeat Status Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVSCHD`
--

CREATE TABLE IF NOT EXISTS `STVSCHD` (
  `STVSCHD_CODE` char(3) NOT NULL default '' COMMENT 'This field identifies the schedule type code referenced in the Catalog, Class   Schedule and Registration Modules.',
  `STVSCHD_DESC` varchar(30) NOT NULL default '' COMMENT 'This field specifies the schedule type (e.g. lecture, lab, self-paced)          associated with the schedule type code.',
  `STVSCHD_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated.',
  `STVSCHD_INSTRUCT_METHOD` char(2) default NULL COMMENT 'The Instructional Method code used to relate Schedule Type and Instructional Method',
  `STVSCHD_COOP_IND` char(1) NOT NULL default '' COMMENT 'The Coop Assignment Allowed Indicator.',
  `STVSCHD_AUTO_SCHEDULER_IND` char(1) NOT NULL default '' COMMENT 'Indicator to identify whether schedule type is used by scheduling tool.  Values are Y or N.',
  `STVSCHD_INSM_CODE` varchar(5) default NULL COMMENT 'Instructional Method code:  This field indicates how the course is delivered to the learner.  Examples are Web based, mixed media, face to face',
  `STVSCHD_VR_MSG_NO` decimal(6,0) default '0' COMMENT 'Voice Response Message Number',
  PRIMARY KEY  (`STVSCHD_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `STVSUBJ`
--

CREATE TABLE IF NOT EXISTS `STVSUBJ` (
  `STVSUBJ_CODE` varchar(4) NOT NULL default '' COMMENT 'This field identifies the subject code referenced in the Catalog, Registration and Acad.  Hist.  Modules.',
  `STVSUBJ_DESC` varchar(30) default NULL COMMENT 'This field specifies the subject associated with the subject code.',
  `STVSUBJ_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated.  ',
  `STVSUBJ_VR_MSG_NO` decimal(6,0) default NULL COMMENT 'The Voice Response message number assigned to the recorded message that describes the subject code.',
  `STVSUBJ_DISP_WEB_IND` char(1) NOT NULL default '' COMMENT 'Web registration indicator',
  PRIMARY KEY  (`STVSUBJ_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Subject Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVTERM`
--

CREATE TABLE IF NOT EXISTS `STVTERM` (
  `STVTERM_CODE` varchar(6) NOT NULL default '' COMMENT 'This field identifies the term code referenced in the Catalog, Recruiting, Admissions, Gen. Student, Registration, Student Billing and Acad. Hist. Modules. Reqd. value: 999999 - End of Time.',
  `STVTERM_DESC` varchar(30) NOT NULL default '' COMMENT 'This field specifies the term associated with the term code. The term is identified by the academic year and term number and is formatted YYYYTT.',
  `STVTERM_START_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the term start date and is formatted DD-MON-YY.',
  `STVTERM_END_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the term end date and is fomatted DD-MON-YY.',
  `STVTERM_FA_PROC_YR` varchar(4) default NULL COMMENT 'This field identifies the financial aid processing start and end years (e.g. The financial aid processing year 1988 - 1989 is formatted 8889.).',
  `STVTERM_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'This field identifies the most recent date a record was created or updated.',
  `STVTERM_FA_TERM` char(1) default NULL COMMENT 'This field identifies the financial aid award term.',
  `STVTERM_FA_PERIOD` decimal(2,0) default NULL COMMENT 'This field identifies the financial aid award beginning period.',
  `STVTERM_FA_END_PERIOD` decimal(2,0) default NULL COMMENT 'This field identifies the financial aid award ending period.',
  `STVTERM_ACYR_CODE` varchar(4) NOT NULL default '' COMMENT 'This field is not currently in use.',
  `STVTERM_HOUSING_START_DATE` date NOT NULL default '0000-00-00' COMMENT 'Housing Start Date.',
  `STVTERM_HOUSING_END_DATE` date NOT NULL default '0000-00-00' COMMENT 'Housing End Date.',
  `STVTERM_SYSTEM_REQ_IND` char(1) default NULL COMMENT 'System Required Indicator',
  `STVTERM_TRMT_CODE` char(1) default NULL COMMENT 'Term type for this term. Will default from SHBCGPA_TRMT_CODE.',
  PRIMARY KEY  (`STVTERM_CODE`),
  UNIQUE KEY `STVTERM_ACYR_INDEX` (`STVTERM_ACYR_CODE`,`STVTERM_CODE`),
  KEY `STVTERM_AIDY_INDEX` (`STVTERM_FA_PROC_YR`,`STVTERM_CODE`),
  KEY `STVTERM_END_DATE_INDEX` (`STVTERM_END_DATE`),
  KEY `STVTERM_START_DATE_INDEX` (`STVTERM_START_DATE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Term Code Validation Table';

-- --------------------------------------------------------

--
-- Table structure for table `STVTRMT`
--

CREATE TABLE IF NOT EXISTS `STVTRMT` (
  `STVTRMT_CODE` char(1) NOT NULL default '' COMMENT 'Type of term, eg.  2 - semester, 4 - quarter.',
  `STVTRMT_DESC` varchar(30) NOT NULL default '' COMMENT 'Specifies the type of term associated with term type code.',
  `STVTRMT_ACTIVITY_DATE` date NOT NULL default '0000-00-00' COMMENT 'Most recent date record was created or updated.',
  PRIMARY KEY  (`STVTRMT_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `SYVINST`
--

CREATE TABLE IF NOT EXISTS `SYVINST` (
  `SYVINST_TERM_CODE` varchar(6) NOT NULL default '' COMMENT 'Term of the faculty member assignment',
  `SYVINST_CRN` varchar(5) NOT NULL default '' COMMENT 'The course reference of the course that the instructor was assigned to',
  `SYVINST_PIDM` decimal(8,0) NOT NULL default '0' COMMENT 'The Pidm of the faculty member',
  `SYVINST_LAST_NAME` varchar(60) NOT NULL default '' COMMENT 'This field defines the last name of person.',
  `SYVINST_FIRST_NAME` varchar(15) default NULL COMMENT 'This field identifies the first name of person.',
  `WEB_ID` varchar(100) NOT NULL COMMENT 'This field is used in web applications to identify individuals rather than using their PIDMs.',
  PRIMARY KEY  (`SYVINST_TERM_CODE`,`SYVINST_CRN`,`SYVINST_PIDM`),
  KEY `SYVINST_PIDM` (`SYVINST_PIDM`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `catalog_term_match`
--
ALTER TABLE `catalog_term_match`
  ADD CONSTRAINT `catalog_term_match_ibfk_1` FOREIGN KEY (`catalog_id`) REFERENCES `course_catalog` (`catalog_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `course_catalog_college`
--
ALTER TABLE `course_catalog_college`
  ADD CONSTRAINT `course_catalog_college_ibfk_1` FOREIGN KEY (`catalog_id`) REFERENCES `course_catalog` (`catalog_id`) ON DELETE CASCADE ON UPDATE CASCADE;
