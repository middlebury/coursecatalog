<?php

/*********************************************************
 * Config
 *********************************************************/

$bannerTNS = 
"(DESCRIPTION =
	(ADDRESS_LIST =
		(ADDRESS = (PROTOCOL = TCP)(HOST = server.example.edu)(PORT = 15220))
	)
	(CONNECT_DATA =
		(SERVICE_NAME = SOMETHING.BANNER.EXAMPLE.EDU)
	)
)";
$bannerDSN		= "oci:dbname=".$bannerTNS;
$bannerUser 	= "RO_USER_NAME";
$bannerPassword = "password";

$mysqlDSN		= "mysql:dbname=courses_banner;host=examplemysqlhost";
$mysqlUser		= "testuser";
$mysqlPassword	= "testpassword";

$sendMailOnError = false;
$errorMailFrom	= "admin@example.edu";
$errorMailTo	= array("admin@example.edu", "admin2@example.edu");

/*********************************************************
 * End - Config
 *********************************************************/


function toMySQLDate($date) {
	return date("Y-m-d", strtotime($date));
}

function sendExceptions($exceptions) {
	global $sendMailOnError, $errorMailFrom, $errorMailTo;	
	if(count($exceptions) > 0) {
		$to = implode(", ", $errorMailTo);
		$subject = "COURSE CATALOG: Yo dawg I heard you like errors!";
		$message = "So I put some errors in your database update script so it can error while it updates the database!\n\n";
		
		foreach($exceptions as $exception) {
			$message .= $exception . "\n\n";
		}
		
		$headers = "From: ".$errorMailFrom."\r\n";
		if ($sendMailOnError)
			mail($to, $subject, $message, $headers);
		
		print_r($message);
	}
}

/**
 * Note: I use "TRUNCATE TABLE" instead of "TRUNCATE TABLE" here because versions of
 * MySQL 4.x treat TRUNCATE as a DDL statement and refuse to do it properly during
 * a transaction due to the lock on the table. TRUNCATE is faster than DELETE and
 * you should replace the statements appropriately if you're using a version of
 * MySQL where this works.
 */

$banner; $mysql; $exceptions = array();

try {
	$banner = new PDO($bannerDSN, $bannerUser, $bannerPassword);
	$banner->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$mysql = new PDO($mysqlDSN, $mysqlUser, $mysqlPassword, array(PDO::MYSQL_ATTR_MAX_BUFFER_SIZE => 1024*1024*100));
	$mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	sendExceptions($exceptions);
	die("Failed to connect. ".$e->getMessage()."\n");
}


// GENERAL.GORINTG
 
try {
	print "Updating GORINTG\t";
	$mysql->beginTransaction();
	$tgorintg = $mysql->prepare("TRUNCATE TABLE GORINTG");
	$tgorintg->execute();
	
	$gorintg = $banner->prepare("SELECT * FROM GENERAL.GORINTG");
	$gorintg->execute();
	
	$insert = $mysql->prepare("INSERT INTO GORINTG (GORINTG_CODE, GORINTG_DESC, GORINTG_INTP_CODE, GORINTG_USER_ID, GORINTG_ACTIVITY_DATE, GORINTG_DATA_ORIGIN) VALUES (:GORINTG_CODE, :GORINTG_DESC, :GORINTG_INTP_CODE, :GORINTG_USER_ID, :GORINTG_ACTIVITY_DATE, :GORINTG_DATA_ORIGIN)");
	while($row = $gorintg->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":GORINTG_CODE", $row->GORINTG_CODE);
		$insert->bindValue(":GORINTG_DESC", $row->GORINTG_DESC);
		$insert->bindValue(":GORINTG_INTP_CODE", $row->GORINTG_INTP_CODE);
		$insert->bindValue(":GORINTG_USER_ID", $row->GORINTG_USER_ID);
		$insert->bindValue(":GORINTG_ACTIVITY_DATE", toMySQLDate($row->GORINTG_ACTIVITY_DATE));
		$insert->bindValue(":GORINTG_DATA_ORIGIN", $row->GORINTG_DATA_ORIGIN);
		$insert->execute();
	}
	
	$mysql->commit();
	$gorintg->closeCursor();
	print "...\tUpdated GORINTG\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// GENERAL.GTVDUNT
 
try {
	print "Updating GTVDUNT\t";
	$mysql->beginTransaction();
	$tgtvdunt = $mysql->prepare("TRUNCATE TABLE GTVDUNT");
	$tgtvdunt->execute();
	
	$gtvdunt = $banner->prepare("SELECT * FROM GENERAL.GTVDUNT");
	$gtvdunt->execute();
	
	$insert = $mysql->prepare("INSERT INTO GTVDUNT (GTVDUNT_CODE, GTVDUNT_DESC, GTVDUNT_NUMBER_OF_DAYS, GTVDUNT_ACTIVITY_DATE, GTVDUNT_USER_ID, GTVDUNT_VR_MSG_NO) VALUES (:GTVDUNT_CODE, :GTVDUNT_DESC, :GTVDUNT_NUMBER_OF_DAYS, :GTVDUNT_ACTIVITY_DATE, :GTVDUNT_USER_ID, :GTVDUNT_VR_MSG_NO)");
	while($row = $gtvdunt->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":GTVDUNT_CODE", $row->GTVDUNT_CODE);
		$insert->bindValue(":GTVDUNT_DESC", $row->GTVDUNT_DESC);
		$insert->bindValue(":GTVDUNT_NUMBER_OF_DAYS", $row->GTVDUNT_NUMBER_OF_DAYS);
		$insert->bindValue(":GTVDUNT_ACTIVITY_DATE", toMySQLDate($row->GTVDUNT_ACTIVITY_DATE));
		$insert->bindValue(":GTVDUNT_USER_ID", $row->GTVDUNT_USER_ID);
		$insert->bindValue(":GTVDUNT_VR_MSG_NO", $row->GTVDUNT_VR_MSG_NO);
		$insert->execute();
	}
	
	$mysql->commit();
	$gtvdunt->closeCursor();
	print "...\tUpdated GTVDUNT\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}


// GENERAL.GTVINSM
 
try {
	print "Updating GTVINSM\t";
	$mysql->beginTransaction();
	$tgtvinsm = $mysql->prepare("TRUNCATE TABLE GTVINSM");
	$tgtvinsm->execute();
	
	$gtvinsm = $banner->prepare("SELECT * FROM GENERAL.GTVINSM");
	$gtvinsm->execute();
	
	$insert = $mysql->prepare("INSERT INTO GTVINSM (GTVINSM_CODE, GTVINSM_DESC, GTVINSM_ACTIVITY_DATE, GTVINSM_USER_ID, GTVINSM_VR_MSG_NO) VALUES (:GTVINSM_CODE, :GTVINSM_DESC, :GTVINSM_ACTIVITY_DATE, :GTVINSM_USER_ID, :GTVINSM_VR_MSG_NO)");
	while($row = $gtvinsm->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":GTVINSM_CODE", $row->GTVINSM_CODE);
		$insert->bindValue(":GTVINSM_DESC", $row->GTVINSM_DESC);
		$insert->bindValue(":GTVINSM_ACTIVITY_DATE", toMySQLDate($row->GTVINSM_ACTIVITY_DATE));
		$insert->bindValue(":GTVINSM_USER_ID", $row->GTVINSM_USER_ID);
		$insert->bindValue(":GTVINSM_VR_MSG_NO", $row->GTVINSM_VR_MSG_NO);
		$insert->execute();
	}
	
	$mysql->commit();
	$gtvinsm->closeCursor();
	print "...\tUpdated GTVINSM\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// GENERAL.GTVINTP
 
try {
	print "Updating GTVINTP\t";
	$mysql->beginTransaction();
	$tgtvintp = $mysql->prepare("TRUNCATE TABLE GTVINTP");
	$tgtvintp->execute();
	
	$gtvintp = $banner->prepare("SELECT * FROM GENERAL.GTVINTP");
	$gtvintp->execute();
	
	$insert = $mysql->prepare("INSERT INTO GTVINTP (GTVINTP_CODE, GTVINTP_DESC, GTVINTP_USER_ID, GTVINTP_ACTIVITY_DATE, GTVINTP_DATA_ORIGIN) VALUES (GTVINTP_CODE, GTVINTP_DESC, GTVINTP_USER_ID, GTVINTP_ACTIVITY_DATE, GTVINTP_DATA_ORIGIN)");
	while($row = $gtvintp->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":GTVINTP_CODE", $row->GTVINTP_CODE);
		$insert->bindValue(":GTVINTP_DESC", $row->GTVINTP_DESC);
		$insert->bindValue(":GTVINTP_USER_ID", $row->GTVINTP_USER_ID);
		$insert->bindValue(":GTVINTP_ACTIVITY_DATE", toMySQLDate($row->GTVINTP_ACTIVITY_DATE));
		$insert->bindValue(":GTVINTP_DATA_ORIGIN", $row->GTVINTP_DATA_ORIGIN);
		$insert->execute();
	}
	
	$mysql->commit();
	$gtvintp->closeCursor();
	print "...\tUpdated GTVINTP\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// GENERAL.GTVMTYP
 
try {
	print "Updating GTVMTYP\t";
	$mysql->beginTransaction();
	$tgtvmtyp = $mysql->prepare("TRUNCATE TABLE GTVMTYP");
	$tgtvmtyp->execute();
	
	$gtvmtyp = $banner->prepare("SELECT * FROM GENERAL.GTVMTYP");
	$gtvmtyp->execute();

	$insert = $mysql->prepare("INSERT INTO GTVMTYP (GTVMTYP_CODE, GTVMTYP_DESC, GTVMTYP_SYS_REQ_IND, GTVMTYP_ACTIVITY_DATE, GTVMTYP_USER_ID, GTVMTYP_VR_MSG_NO) VALUES (:GTVMTYP_CODE, :GTVMTYP_DESC, :GTVMTYP_SYS_REQ_IND, :GTVMTYP_ACTIVITY_DATE, :GTVMTYP_USER_ID, :GTVMTYP_VR_MSG_NO)");
	while($row = $gtvmtyp->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":GTVMTYP_CODE", $row->GTVMTYP_CODE);
		$insert->bindValue(":GTVMTYP_DESC", $row->GTVMTYP_DESC);
		$insert->bindValue(":GTVMTYP_SYS_REQ_IND", $row->GTVMTYP_SYS_REQ_IND);
		$insert->bindValue(":GTVMTYP_ACTIVITY_DATE", toMySQLDate($row->GTVMTYP_ACTIVITY_DATE));
		$insert->bindValue(":GTVMTYP_USER_ID", $row->GTVMTYP_USER_ID);
		$insert->bindValue(":GTVMTYP_VR_MSG_NO", $row->GTVMTYP_VR_MSG_NO);
		$insert->execute();
	}
	
	$mysql->commit();
	$gtvmtyp->closeCursor();
	print "...\tUpdated GTVMTYP\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}


// GENERAL.GTVSCHS
 
try {
	print "Updating GTVSCHS\t";
	$mysql->beginTransaction();
	$tgtvschs = $mysql->prepare("TRUNCATE TABLE GTVSCHS");
	$tgtvschs->execute();
	
	$gtvschs = $banner->prepare("SELECT * FROM GENERAL.GTVSCHS");
	$gtvschs->execute();
	
	$insert = $mysql->prepare("INSERT INTO GTVSCHS (GTVSCHS_CODE, GTVSCHS_DESC, GTVSCHS_SYSTEM_REQ_IND, GTVSCHS_ACTIVITY_DATE) VALUES (:GTVSCHS_CODE, :GTVSCHS_DESC, :GTVSCHS_SYSTEM_REQ_IND, :GTVSCHS_ACTIVITY_DATE)");
	while($row = $gtvschs->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":GTVSCHS_CODE", $row->GTVSCHS_CODE);
		$insert->bindValue(":GTVSCHS_DESC", $row->GTVSCHS_DESC);
		$insert->bindValue(":GTVSCHS_SYSTEM_REQ_IND", $row->GTVSCHS_SYSTEM_REQ_IND);
		$insert->bindValue(":GTVSCHS_ACTIVITY_DATE", toMySQLDate($row->GTVSCHS_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$gtvschs->closeCursor();
	print "...\tUpdated GTVSCHS\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.SCBCRSE
 
try {
	print "Updating SCBCRSE\t";
	$mysql->beginTransaction();
	$tscbcrse = $mysql->prepare("TRUNCATE TABLE SCBCRSE");
	$tscbcrse->execute();
	
	$scbcrse = $banner->prepare("SELECT * FROM SATURN.SCBCRSE");
	$scbcrse->execute();
	
	$insert = $mysql->prepare("INSERT INTO SCBCRSE (SCBCRSE_SUBJ_CODE, SCBCRSE_CRSE_NUMB, SCBCRSE_EFF_TERM, SCBCRSE_COLL_CODE, SCBCRSE_DIVS_CODE, SCBCRSE_DEPT_CODE, SCBCRSE_CSTA_CODE, SCBCRSE_TITLE, SCBCRSE_CIPC_CODE, SCBCRSE_CREDIT_HR_IND, SCBCRSE_CREDIT_HR_LOW, SCBCRSE_CREDIT_HR_HIGH, SCBCRSE_LEC_HR_IND, SCBCRSE_LEC_HR_LOW, SCBCRSE_LEC_HR_HIGH, SCBCRSE_LAB_HR_IND, SCBCRSE_LAB_HR_LOW, SCBCRSE_LAB_HR_HIGH, SCBCRSE_OTH_HR_IND, SCBCRSE_OTH_HR_LOW, SCBCRSE_OTH_HR_HIGH, SCBCRSE_BILL_HR_IND, SCBCRSE_BILL_HR_LOW, SCBCRSE_BILL_HR_HIGH, SCBCRSE_APRV_CODE, SCBCRSE_REPEAT_LIMIT, SCBCRSE_PWAV_CODE, SCBCRSE_TUIW_IND, SCBCRSE_ADD_FEES_IND, SCBCRSE_ACTIVITY_DATE, SCBCRSE_CONT_HR_LOW, SCBCRSE_CONT_HR_IND, SCBCRSE_CONT_HR_HIGH, SCBCRSE_CEU_IND, SCBCRSE_REPS_CODE, SCBCRSE_MAX_RPT_UNITS, SCBCRSE_CAPP_PREREQ_TEST_IND, SCBCRSE_DUNT_CODE, SCBCRSE_NUMBER_OF_UNITS, SCBCRSE_DATA_ORIGIN, SCBCRSE_USER_ID) VALUES (:SCBCRSE_SUBJ_CODE, :SCBCRSE_CRSE_NUMB, :SCBCRSE_EFF_TERM,	:SCBCRSE_COLL_CODE,	:SCBCRSE_DIVS_CODE,	:SCBCRSE_DEPT_CODE,	:SCBCRSE_CSTA_CODE,	:SCBCRSE_TITLE,	:SCBCRSE_CIPC_CODE,	:SCBCRSE_CREDIT_HR_IND,	:SCBCRSE_CREDIT_HR_LOW,	:SCBCRSE_CREDIT_HR_HIGH, :SCBCRSE_LEC_HR_IND,:SCBCRSE_LEC_HR_LOW, :SCBCRSE_LEC_HR_HIGH,	:SCBCRSE_LAB_HR_IND, :SCBCRSE_LAB_HR_LOW, :SCBCRSE_LAB_HR_HIGH, :SCBCRSE_OTH_HR_IND, :SCBCRSE_OTH_HR_LOW, :SCBCRSE_OTH_HR_HIGH, :SCBCRSE_BILL_HR_IND, :SCBCRSE_BILL_HR_LOW, :SCBCRSE_BILL_HR_HIGH, :SCBCRSE_APRV_CODE, :SCBCRSE_REPEAT_LIMIT, :SCBCRSE_PWAV_CODE, :SCBCRSE_TUIW_IND, :SCBCRSE_ADD_FEES_IND, :SCBCRSE_ACTIVITY_DATE, :SCBCRSE_CONT_HR_LOW, :SCBCRSE_CONT_HR_IND, :SCBCRSE_CONT_HR_HIGH, :SCBCRSE_CEU_IND, :SCBCRSE_REPS_CODE, :SCBCRSE_MAX_RPT_UNITS, :SCBCRSE_CAPP_PREREQ_TEST_IND, :SCBCRSE_DUNT_CODE, :SCBCRSE_NUMBER_OF_UNITS, :SCBCRSE_DATA_ORIGIN, :SCBCRSE_USER_ID)");
	while($row = $scbcrse->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":SCBCRSE_SUBJ_CODE", $row->SCBCRSE_SUBJ_CODE);
		$insert->bindValue(":SCBCRSE_CRSE_NUMB", $row->SCBCRSE_CRSE_NUMB);
		$insert->bindValue(":SCBCRSE_EFF_TERM", $row->SCBCRSE_EFF_TERM);
		$insert->bindValue(":SCBCRSE_COLL_CODE", $row->SCBCRSE_COLL_CODE);
		$insert->bindValue(":SCBCRSE_DIVS_CODE", $row->SCBCRSE_DIVS_CODE);
		$insert->bindValue(":SCBCRSE_DEPT_CODE", $row->SCBCRSE_DEPT_CODE);
		$insert->bindValue(":SCBCRSE_CSTA_CODE", $row->SCBCRSE_CSTA_CODE);
		$insert->bindValue(":SCBCRSE_TITLE", $row->SCBCRSE_TITLE);
		$insert->bindValue(":SCBCRSE_CIPC_CODE", $row->SCBCRSE_CIPC_CODE);
		$insert->bindValue(":SCBCRSE_CREDIT_HR_IND", $row->SCBCRSE_CREDIT_HR_IND);
		$insert->bindValue(":SCBCRSE_CREDIT_HR_LOW", $row->SCBCRSE_CREDIT_HR_LOW);
		$insert->bindValue(":SCBCRSE_CREDIT_HR_HIGH", $row->SCBCRSE_CREDIT_HR_HIGH);
		$insert->bindValue(":SCBCRSE_LEC_HR_IND", $row->SCBCRSE_LEC_HR_IND);
		$insert->bindValue(":SCBCRSE_LEC_HR_LOW", $row->SCBCRSE_LEC_HR_LOW);
		$insert->bindValue(":SCBCRSE_LEC_HR_HIGH", $row->SCBCRSE_LEC_HR_HIGH);
		$insert->bindValue(":SCBCRSE_LAB_HR_IND", $row->SCBCRSE_LAB_HR_IND);
		$insert->bindValue(":SCBCRSE_LAB_HR_LOW", $row->SCBCRSE_LAB_HR_LOW);
		$insert->bindValue(":SCBCRSE_LAB_HR_HIGH", $row->SCBCRSE_LAB_HR_HIGH);
		$insert->bindValue(":SCBCRSE_OTH_HR_IND", $row->SCBCRSE_OTH_HR_IND);
		$insert->bindValue(":SCBCRSE_OTH_HR_LOW", $row->SCBCRSE_OTH_HR_LOW);
		$insert->bindValue(":SCBCRSE_OTH_HR_HIGH", $row->SCBCRSE_OTH_HR_HIGH);
		$insert->bindValue(":SCBCRSE_BILL_HR_IND", $row->SCBCRSE_BILL_HR_IND);
		$insert->bindValue(":SCBCRSE_BILL_HR_LOW", $row->SCBCRSE_BILL_HR_LOW);
		$insert->bindValue(":SCBCRSE_BILL_HR_HIGH", $row->SCBCRSE_BILL_HR_HIGH);
		$insert->bindValue(":SCBCRSE_APRV_CODE", $row->SCBCRSE_APRV_CODE);
		$insert->bindValue(":SCBCRSE_REPEAT_LIMIT", $row->SCBCRSE_REPEAT_LIMIT);
		$insert->bindValue(":SCBCRSE_PWAV_CODE", $row->SCBCRSE_PWAV_CODE);
		$insert->bindValue(":SCBCRSE_TUIW_IND", $row->SCBCRSE_TUIW_IND);
		$insert->bindValue(":SCBCRSE_ADD_FEES_IND", $row->SCBCRSE_ADD_FEES_IND);
		$insert->bindValue(":SCBCRSE_ACTIVITY_DATE", toMySQLDate($row->SCBCRSE_ACTIVITY_DATE));
		$insert->bindValue(":SCBCRSE_CONT_HR_LOW", $row->SCBCRSE_CONT_HR_LOW);
		$insert->bindValue(":SCBCRSE_CONT_HR_IND", $row->SCBCRSE_CONT_HR_IND);
		$insert->bindValue(":SCBCRSE_CONT_HR_HIGH", $row->SCBCRSE_CONT_HR_HIGH);
		$insert->bindValue(":SCBCRSE_CEU_IND", $row->SCBCRSE_CEU_IND);
		$insert->bindValue(":SCBCRSE_REPS_CODE", $row->SCBCRSE_REPS_CODE);
		$insert->bindValue(":SCBCRSE_MAX_RPT_UNITS", $row->SCBCRSE_MAX_RPT_UNITS);
		$insert->bindValue(":SCBCRSE_CAPP_PREREQ_TEST_IND", $row->SCBCRSE_CAPP_PREREQ_TEST_IND);
		$insert->bindValue(":SCBCRSE_DUNT_CODE", $row->SCBCRSE_DUNT_CODE);
		$insert->bindValue(":SCBCRSE_NUMBER_OF_UNITS", $row->SCBCRSE_NUMBER_OF_UNITS);
		$insert->bindValue(":SCBCRSE_DATA_ORIGIN", $row->SCBCRSE_DATA_ORIGIN);
		$insert->bindValue(":SCBCRSE_USER_ID", $row->SCBCRSE_USER_ID);
		$insert->execute();
	}
	
	$mysql->commit();
	$scbcrse->closeCursor();
	print "...\tUpdated SCBCRSE\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}

// SATURN.SCBDESC
 
try {
	print "Updating SCBDESC\t";
	$mysql->beginTransaction();
	$tSCBDESC = $mysql->prepare("TRUNCATE TABLE SCBDESC");
	$tSCBDESC->execute();
	
	$SCBDESC = $banner->prepare("SELECT SCBDESC_SUBJ_CODE, SCBDESC_CRSE_NUMB, SCBDESC_TERM_CODE_EFF, SCBDESC_ACTIVITY_DATE, SCBDESC_USER_ID, SCBDESC_TEXT_NARRATIVE, SCBDESC_TERM_CODE_END FROM SATURN.SCBDESC");
	$SCBDESC->execute();
	
	$insert = $mysql->prepare("INSERT INTO SCBDESC (SCBDESC_SUBJ_CODE, SCBDESC_CRSE_NUMB, SCBDESC_TERM_CODE_EFF, SCBDESC_ACTIVITY_DATE, SCBDESC_USER_ID, SCBDESC_TEXT_NARRATIVE, SCBDESC_TERM_CODE_END) VALUES (:SCBDESC_SUBJ_CODE, :SCBDESC_CRSE_NUMB, :SCBDESC_TERM_CODE_EFF, :SCBDESC_ACTIVITY_DATE, :SCBDESC_USER_ID, :SCBDESC_TEXT_NARRATIVE, :SCBDESC_TERM_CODE_END)");
	while($row = $SCBDESC->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":SCBDESC_SUBJ_CODE", $row->SCBDESC_SUBJ_CODE);
		$insert->bindValue(":SCBDESC_CRSE_NUMB", $row->SCBDESC_CRSE_NUMB);
		$insert->bindValue(":SCBDESC_TERM_CODE_EFF", $row->SCBDESC_TERM_CODE_EFF);
		$insert->bindValue(":SCBDESC_ACTIVITY_DATE", toMySQLDate($row->SCBDESC_ACTIVITY_DATE));
		$insert->bindValue(":SCBDESC_USER_ID", $row->SCBDESC_USER_ID);
		if (is_null($row->SCBDESC_TEXT_NARRATIVE))
			$insert->bindValue(":SCBDESC_TEXT_NARRATIVE", null);
		else
			$insert->bindValue(":SCBDESC_TEXT_NARRATIVE", stream_get_contents($row->SCBDESC_TEXT_NARRATIVE));
		$insert->bindValue(":SCBDESC_TERM_CODE_END", $row->SCBDESC_TERM_CODE_END);
		$insert->execute();
	}
	
	$mysql->commit();
	$SCBDESC->closeCursor();
	print "...\tUpdated SCBDESC\n";
} catch(Exception $e) {
	print $e->__toString()."\n";
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}

// SATURN.SSBXLST
 
try {
	print "Updating SSBXLST\t";
	$mysql->beginTransaction();
	$tSSBXLST = $mysql->prepare("TRUNCATE TABLE SSBXLST");
	$tSSBXLST->execute();
	
	$SSBXLST = $banner->prepare("SELECT * FROM SATURN.SSBXLST");
	$SSBXLST->execute();
	
	$insert = $mysql->prepare("INSERT INTO SSBXLST (SSBXLST_TERM_CODE, SSBXLST_XLST_GROUP, SSBXLST_DESC, SSBXLST_MAX_ENRL, SSBXLST_ENRL, SSBXLST_SEATS_AVAIL, SSBXLST_ACTIVITY_DATE) VALUES (:SSBXLST_TERM_CODE, :SSBXLST_XLST_GROUP, :SSBXLST_DESC, :SSBXLST_MAX_ENRL, :SSBXLST_ENRL, :SSBXLST_SEATS_AVAIL, :SSBXLST_ACTIVITY_DATE)");
	while($row = $SSBXLST->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":SSBXLST_TERM_CODE", $row->SSBXLST_TERM_CODE);
		$insert->bindValue(":SSBXLST_XLST_GROUP", $row->SSBXLST_XLST_GROUP);
		$insert->bindValue(":SSBXLST_DESC", $row->SSBXLST_DESC);
		$insert->bindValue(":SSBXLST_MAX_ENRL", $row->SSBXLST_MAX_ENRL);
		$insert->bindValue(":SSBXLST_ENRL", $row->SSBXLST_ENRL);
		$insert->bindValue(":SSBXLST_SEATS_AVAIL", $row->SSBXLST_SEATS_AVAIL);
		$insert->bindValue(":SSBXLST_ACTIVITY_DATE", toMySQLDate($row->SSBXLST_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$SSBXLST->closeCursor();
	print "...\tUpdated SSBXLST\n";
} catch(Exception $e) {
	print $e->__toString()."\n";
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}

// SATURN.SSRXLST
 
try {
	print "Updating SSRXLST\t";
	$mysql->beginTransaction();
	$tSSRXLST = $mysql->prepare("TRUNCATE TABLE SSRXLST");
	$tSSRXLST->execute();
	
	$SSRXLST = $banner->prepare("SELECT * FROM SATURN.SSRXLST");
	$SSRXLST->execute();
	
	$insert = $mysql->prepare("INSERT INTO SSRXLST (SSRXLST_TERM_CODE, SSRXLST_CRN, SSRXLST_XLST_GROUP, SSRXLST_ACTIVITY_DATE) VALUES (:SSRXLST_TERM_CODE, :SSRXLST_CRN, :SSRXLST_XLST_GROUP, :SSRXLST_ACTIVITY_DATE)");
	while($row = $SSRXLST->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":SSRXLST_TERM_CODE", $row->SSRXLST_TERM_CODE);
		$insert->bindValue(":SSRXLST_CRN", $row->SSRXLST_CRN);
		$insert->bindValue(":SSRXLST_XLST_GROUP", $row->SSRXLST_XLST_GROUP);
		$insert->bindValue(":SSRXLST_ACTIVITY_DATE", toMySQLDate($row->SSRXLST_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$SSRXLST->closeCursor();
	print "...\tUpdated SSRXLST\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	
// SATURN.SIRASGN
 
try {
	print "Updating SIRASGN\t";
	$mysql->beginTransaction();
	$tsirasgn = $mysql->prepare("TRUNCATE TABLE SIRASGN");
	$tsirasgn->execute();
	
	$sirasgn = $banner->prepare("SELECT * FROM SATURN.SIRASGN");
	$sirasgn->execute();

	$insert = $mysql->prepare("INSERT INTO SIRASGN (SIRASGN_TERM_CODE, SIRASGN_CRN, SIRASGN_PIDM, SIRASGN_CATEGORY, SIRASGN_PERCENT_RESPONSE, SIRASGN_WORKLOAD_ADJUST, SIRASGN_PERCENT_SESS, SIRASGN_PRIMARY_IND, SIRASGN_OVER_RIDE, SIRASGN_POSITION, SIRASGN_ACTIVITY_DATE, SIRASGN_FCNT_CODE, SIRASGN_POSN, SIRASGN_SUFF, SIRASGN_ASTY_CODE, SIRASGN_DATA_ORIGIN, SIRASGN_USER_ID) VALUES (:SIRASGN_TERM_CODE, :SIRASGN_CRN, :SIRASGN_PIDM, :SIRASGN_CATEGORY, :SIRASGN_PERCENT_RESPONSE, :SIRASGN_WORKLOAD_ADJUST, :SIRASGN_PERCENT_SESS, :SIRASGN_PRIMARY_IND, :SIRASGN_OVER_RIDE, :SIRASGN_POSITION, :SIRASGN_ACTIVITY_DATE, :SIRASGN_FCNT_CODE, :SIRASGN_POSN, :SIRASGN_SUFF, :SIRASGN_ASTY_CODE, :SIRASGN_DATA_ORIGIN, :SIRASGN_USER_ID)");
	while($row = $sirasgn->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":SIRASGN_TERM_CODE", $row->SIRASGN_TERM_CODE);
		$insert->bindValue(":SIRASGN_CRN", $row->SIRASGN_CRN);
		$insert->bindValue(":SIRASGN_PIDM", $row->SIRASGN_PIDM);
		$insert->bindValue(":SIRASGN_CATEGORY", $row->SIRASGN_CATEGORY);
		$insert->bindValue(":SIRASGN_PERCENT_RESPONSE", $row->SIRASGN_PERCENT_RESPONSE);
		$insert->bindValue(":SIRASGN_WORKLOAD_ADJUST", $row->SIRASGN_WORKLOAD_ADJUST);
		$insert->bindValue(":SIRASGN_PERCENT_SESS", $row->SIRASGN_PERCENT_SESS);
		$insert->bindValue(":SIRASGN_PRIMARY_IND", $row->SIRASGN_PRIMARY_IND);
		$insert->bindValue(":SIRASGN_OVER_RIDE", $row->SIRASGN_OVER_RIDE);
		$insert->bindValue(":SIRASGN_POSITION", $row->SIRASGN_POSITION);
		$insert->bindValue(":SIRASGN_ACTIVITY_DATE", toMySQLDate($row->SIRASGN_ACTIVITY_DATE));
		$insert->bindValue(":SIRASGN_FCNT_CODE", $row->SIRASGN_FCNT_CODE);
		$insert->bindValue(":SIRASGN_POSN", $row->SIRASGN_POSN);
		$insert->bindValue(":SIRASGN_SUFF", $row->SIRASGN_SUFF);
		$insert->bindValue(":SIRASGN_ASTY_CODE", $row->SIRASGN_ASTY_CODE);
		$insert->bindValue(":SIRASGN_DATA_ORIGIN", $row->SIRASGN_DATA_ORIGIN);
		$insert->bindValue(":SIRASGN_USER_ID", $row->SIRASGN_USER_ID);
		$insert->execute();
	}
	
	$mysql->commit();
	$sirasgn->closeCursor();
	print "...\tUpdated SIRASGN\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}


// SATURN.SSBSECT
 
try {
	print "Updating SSBSECT\t";
	$mysql->beginTransaction();
	$tssbsect = $mysql->prepare("TRUNCATE TABLE SSBSECT");
	$tssbsect->execute();
	
	$ssbsect = $banner->prepare("SELECT * FROM SATURN.SSBSECT");
	$ssbsect->execute();
	
	$insert = $mysql->prepare("INSERT INTO SSBSECT (SSBSECT_TERM_CODE, SSBSECT_CRN, SSBSECT_PTRM_CODE, SSBSECT_SUBJ_CODE, SSBSECT_CRSE_NUMB, SSBSECT_SEQ_NUMB, SSBSECT_SSTS_CODE, SSBSECT_SCHD_CODE, SSBSECT_CAMP_CODE, SSBSECT_CRSE_TITLE, SSBSECT_CREDIT_HRS, SSBSECT_BILL_HRS, SSBSECT_GMOD_CODE, SSBSECT_SAPR_CODE, SSBSECT_SESS_CODE, SSBSECT_LINK_IDENT, SSBSECT_PRNT_IND, SSBSECT_GRADABLE_IND, SSBSECT_TUIW_IND, SSBSECT_REG_ONEUP, SSBSECT_PRIOR_ENRL, SSBSECT_PROJ_ENRL, SSBSECT_MAX_ENRL, SSBSECT_ENRL, SSBSECT_SEATS_AVAIL, SSBSECT_TOT_CREDIT_HRS, SSBSECT_CENSUS_ENRL, SSBSECT_CENSUS_ENRL_DATE, SSBSECT_ACTIVITY_DATE, SSBSECT_PTRM_START_DATE, SSBSECT_PTRM_END_DATE, SSBSECT_PTRM_WEEKS, SSBSECT_RESERVED_IND, SSBSECT_WAIT_CAPACITY, SSBSECT_WAIT_COUNT, SSBSECT_WAIT_AVAIL, SSBSECT_LEC_HR, SSBSECT_LAB_HR, SSBSECT_OTH_HR, SSBSECT_CONT_HR, SSBSECT_ACCT_CODE, SSBSECT_ACCL_CODE, SSBSECT_CENSUS_2_DATE, SSBSECT_ENRL_CUT_OFF_DATE, SSBSECT_ACAD_CUT_OFF_DATE, SSBSECT_DROP_CUT_OFF_DATE, SSBSECT_CENSUS_2_ENRL, SSBSECT_VOICE_AVAIL, SSBSECT_CAPP_PREREQ_TEST_IND, SSBSECT_GSCH_NAME, SSBSECT_BEST_OF_COMP, SSBSECT_SUBSET_OF_COMP, SSBSECT_INSM_CODE, SSBSECT_REG_FROM_DATE, SSBSECT_REG_TO_DATE, SSBSECT_LEARNER_REGSTART_FDATE, SSBSECT_LEARNER_REGSTART_TDATE, SSBSECT_DUNT_CODE, SSBSECT_NUMBER_OF_UNITS, SSBSECT_NUMBER_OF_EXTENSIONS, SSBSECT_DATA_ORIGIN, SSBSECT_USER_ID, SSBSECT_INTG_CDE) VALUES (:SSBSECT_TERM_CODE, :SSBSECT_CRN, :SSBSECT_PTRM_CODE, :SSBSECT_SUBJ_CODE, :SSBSECT_CRSE_NUMB, :SSBSECT_SEQ_NUMB, :SSBSECT_SSTS_CODE, :SSBSECT_SCHD_CODE, :SSBSECT_CAMP_CODE, :SSBSECT_CRSE_TITLE, :SSBSECT_CREDIT_HRS, :SSBSECT_BILL_HRS, :SSBSECT_GMOD_CODE, :SSBSECT_SAPR_CODE, :SSBSECT_SESS_CODE, :SSBSECT_LINK_IDENT, :SSBSECT_PRNT_IND, :SSBSECT_GRADABLE_IND, :SSBSECT_TUIW_IND, :SSBSECT_REG_ONEUP, :SSBSECT_PRIOR_ENRL, :SSBSECT_PROJ_ENRL, :SSBSECT_MAX_ENRL, :SSBSECT_ENRL, :SSBSECT_SEATS_AVAIL, :SSBSECT_TOT_CREDIT_HRS, :SSBSECT_CENSUS_ENRL, :SSBSECT_CENSUS_ENRL_DATE, :SSBSECT_ACTIVITY_DATE, :SSBSECT_PTRM_START_DATE, :SSBSECT_PTRM_END_DATE, :SSBSECT_PTRM_WEEKS, :SSBSECT_RESERVED_IND, :SSBSECT_WAIT_CAPACITY, :SSBSECT_WAIT_COUNT, :SSBSECT_WAIT_AVAIL, :SSBSECT_LEC_HR, :SSBSECT_LAB_HR, :SSBSECT_OTH_HR, :SSBSECT_CONT_HR, :SSBSECT_ACCT_CODE, :SSBSECT_ACCL_CODE, :SSBSECT_CENSUS_2_DATE, :SSBSECT_ENRL_CUT_OFF_DATE, :SSBSECT_ACAD_CUT_OFF_DATE, :SSBSECT_DROP_CUT_OFF_DATE, :SSBSECT_CENSUS_2_ENRL, :SSBSECT_VOICE_AVAIL, :SSBSECT_CAPP_PREREQ_TEST_IND, :SSBSECT_GSCH_NAME, :SSBSECT_BEST_OF_COMP, :SSBSECT_SUBSET_OF_COMP, :SSBSECT_INSM_CODE, :SSBSECT_REG_FROM_DATE, :SSBSECT_REG_TO_DATE, :SSBSECT_LEARNER_REGSTART_FDATE, :SSBSECT_LEARNER_REGSTART_TDATE, :SSBSECT_DUNT_CODE, :SSBSECT_NUMBER_OF_UNITS, :SSBSECT_NUMBER_OF_EXTENSIONS, :SSBSECT_DATA_ORIGIN, :SSBSECT_USER_ID, :SSBSECT_INTG_CDE)");
	while($row = $ssbsect->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":SSBSECT_TERM_CODE", $row->SSBSECT_TERM_CODE);
		$insert->bindValue(":SSBSECT_CRN", $row->SSBSECT_CRN);
		$insert->bindValue(":SSBSECT_PTRM_CODE", $row->SSBSECT_PTRM_CODE);
		$insert->bindValue(":SSBSECT_SUBJ_CODE", $row->SSBSECT_SUBJ_CODE);
		$insert->bindValue(":SSBSECT_CRSE_NUMB", $row->SSBSECT_CRSE_NUMB);
		$insert->bindValue(":SSBSECT_SEQ_NUMB", $row->SSBSECT_SEQ_NUMB);
		$insert->bindValue(":SSBSECT_SSTS_CODE", $row->SSBSECT_SSTS_CODE);
		$insert->bindValue(":SSBSECT_SCHD_CODE", $row->SSBSECT_SCHD_CODE);
		$insert->bindValue(":SSBSECT_CAMP_CODE", $row->SSBSECT_CAMP_CODE);
		$insert->bindValue(":SSBSECT_CRSE_TITLE", $row->SSBSECT_CRSE_TITLE);
		$insert->bindValue(":SSBSECT_CREDIT_HRS", $row->SSBSECT_CREDIT_HRS);
		$insert->bindValue(":SSBSECT_BILL_HRS", $row->SSBSECT_BILL_HRS);
		$insert->bindValue(":SSBSECT_GMOD_CODE", $row->SSBSECT_GMOD_CODE);
		$insert->bindValue(":SSBSECT_SAPR_CODE", $row->SSBSECT_SAPR_CODE);
		$insert->bindValue(":SSBSECT_SESS_CODE", $row->SSBSECT_SESS_CODE);
		$insert->bindValue(":SSBSECT_LINK_IDENT", $row->SSBSECT_LINK_IDENT);
		$insert->bindValue(":SSBSECT_PRNT_IND", $row->SSBSECT_PRNT_IND);
		$insert->bindValue(":SSBSECT_GRADABLE_IND", $row->SSBSECT_GRADABLE_IND);
		$insert->bindValue(":SSBSECT_TUIW_IND", $row->SSBSECT_TUIW_IND);
		$insert->bindValue(":SSBSECT_REG_ONEUP", $row->SSBSECT_REG_ONEUP);
		$insert->bindValue(":SSBSECT_PRIOR_ENRL", $row->SSBSECT_PRIOR_ENRL);
		$insert->bindValue(":SSBSECT_PROJ_ENRL", $row->SSBSECT_PROJ_ENRL);
		$insert->bindValue(":SSBSECT_MAX_ENRL", $row->SSBSECT_MAX_ENRL);
		$insert->bindValue(":SSBSECT_ENRL", $row->SSBSECT_ENRL);
		$insert->bindValue(":SSBSECT_SEATS_AVAIL", $row->SSBSECT_SEATS_AVAIL);
		$insert->bindValue(":SSBSECT_TOT_CREDIT_HRS", $row->SSBSECT_TOT_CREDIT_HRS);
		$insert->bindValue(":SSBSECT_CENSUS_ENRL", $row->SSBSECT_CENSUS_ENRL);
		$insert->bindValue(":SSBSECT_CENSUS_ENRL_DATE", toMySQLDate($row->SSBSECT_CENSUS_ENRL_DATE));
		$insert->bindValue(":SSBSECT_ACTIVITY_DATE", toMySQLDate($row->SSBSECT_ACTIVITY_DATE));
		$insert->bindValue(":SSBSECT_PTRM_START_DATE", toMySQLDate($row->SSBSECT_PTRM_START_DATE));
		$insert->bindValue(":SSBSECT_PTRM_END_DATE", toMySQLDate($row->SSBSECT_PTRM_END_DATE));
		$insert->bindValue(":SSBSECT_PTRM_WEEKS", $row->SSBSECT_PTRM_WEEKS);
		$insert->bindValue(":SSBSECT_RESERVED_IND", $row->SSBSECT_RESERVED_IND);
		$insert->bindValue(":SSBSECT_WAIT_CAPACITY", $row->SSBSECT_WAIT_CAPACITY);
		$insert->bindValue(":SSBSECT_WAIT_COUNT", $row->SSBSECT_WAIT_COUNT);
		$insert->bindValue(":SSBSECT_WAIT_AVAIL", $row->SSBSECT_WAIT_AVAIL);
		$insert->bindValue(":SSBSECT_LEC_HR", $row->SSBSECT_LEC_HR);
		$insert->bindValue(":SSBSECT_LAB_HR", $row->SSBSECT_LAB_HR);
		$insert->bindValue(":SSBSECT_OTH_HR", $row->SSBSECT_OTH_HR);
		$insert->bindValue(":SSBSECT_CONT_HR", $row->SSBSECT_CONT_HR);
		$insert->bindValue(":SSBSECT_ACCT_CODE", $row->SSBSECT_ACCT_CODE);
		$insert->bindValue(":SSBSECT_ACCL_CODE", $row->SSBSECT_ACCL_CODE);
		$insert->bindValue(":SSBSECT_CENSUS_2_DATE", toMySQLDate($row->SSBSECT_CENSUS_2_DATE));
		$insert->bindValue(":SSBSECT_ENRL_CUT_OFF_DATE", toMySQLDate($row->SSBSECT_ENRL_CUT_OFF_DATE));
		$insert->bindValue(":SSBSECT_ACAD_CUT_OFF_DATE", toMySQLDate($row->SSBSECT_ACAD_CUT_OFF_DATE));
		$insert->bindValue(":SSBSECT_DROP_CUT_OFF_DATE", toMySQLDate($row->SSBSECT_DROP_CUT_OFF_DATE));
		$insert->bindValue(":SSBSECT_CENSUS_2_ENRL", $row->SSBSECT_CENSUS_2_ENRL);
		$insert->bindValue(":SSBSECT_VOICE_AVAIL", $row->SSBSECT_VOICE_AVAIL);
		$insert->bindValue(":SSBSECT_CAPP_PREREQ_TEST_IND", $row->SSBSECT_CAPP_PREREQ_TEST_IND);
		$insert->bindValue(":SSBSECT_GSCH_NAME", $row->SSBSECT_GSCH_NAME);
		$insert->bindValue(":SSBSECT_BEST_OF_COMP", $row->SSBSECT_BEST_OF_COMP);
		$insert->bindValue(":SSBSECT_SUBSET_OF_COMP", $row->SSBSECT_SUBSET_OF_COMP);
		$insert->bindValue(":SSBSECT_INSM_CODE", $row->SSBSECT_INSM_CODE);
		$insert->bindValue(":SSBSECT_REG_FROM_DATE", toMySQLDate($row->SSBSECT_REG_FROM_DATE));
		$insert->bindValue(":SSBSECT_REG_TO_DATE", toMySQLDate($row->SSBSECT_REG_TO_DATE));
		$insert->bindValue(":SSBSECT_LEARNER_REGSTART_FDATE", toMySQLDate($row->SSBSECT_LEARNER_REGSTART_FDATE));
		$insert->bindValue(":SSBSECT_LEARNER_REGSTART_TDATE", toMySQLDate($row->SSBSECT_LEARNER_REGSTART_TDATE));
		$insert->bindValue(":SSBSECT_DUNT_CODE", $row->SSBSECT_DUNT_CODE);
		$insert->bindValue(":SSBSECT_NUMBER_OF_UNITS", $row->SSBSECT_NUMBER_OF_UNITS);
		$insert->bindValue(":SSBSECT_NUMBER_OF_EXTENSIONS", $row->SSBSECT_NUMBER_OF_EXTENSIONS);
		$insert->bindValue(":SSBSECT_DATA_ORIGIN", $row->SSBSECT_DATA_ORIGIN);
		$insert->bindValue(":SSBSECT_USER_ID", $row->SSBSECT_USER_ID);
		$insert->bindValue(":SSBSECT_INTG_CDE", $row->SSBSECT_INTG_CDE);
		$insert->execute();
	}
	
	$mysql->commit();
	$ssbsect->closeCursor();
	print "...\tUpdated SSBSECT\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}


// SATURN.SSRATTR
 
try {
	print "Updating SSRATTR\t";
	$mysql->beginTransaction();
	$tssrattr = $mysql->prepare("TRUNCATE TABLE SSRATTR");
	$tssrattr->execute();
	
	$ssrattr = $banner->prepare("SELECT * FROM SATURN.SSRATTR");
	$ssrattr->execute();
	
	$insert = $mysql->prepare("INSERT INTO SSRATTR (SSRATTR_TERM_CODE, SSRATTR_CRN, SSRATTR_ATTR_CODE, SSRATTR_ACTIVITY_DATE) VALUES (:SSRATTR_TERM_CODE, :SSRATTR_CRN, :SSRATTR_ATTR_CODE, :SSRATTR_ACTIVITY_DATE)");
	while($row = $ssrattr->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":SSRATTR_TERM_CODE", $row->SSRATTR_TERM_CODE);
		$insert->bindValue(":SSRATTR_CRN", $row->SSRATTR_CRN);
		$insert->bindValue(":SSRATTR_ATTR_CODE", $row->SSRATTR_ATTR_CODE);
		$insert->bindValue(":SSRATTR_ACTIVITY_DATE", toMySQLDate($row->SSRATTR_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$ssrattr->closeCursor();
	print "...\tUpdated SSRATTR\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}


// SATURN.SSRMEET

try {
	print "Updating SSRMEET\t";
	$mysql->beginTransaction();
	$tssrmeet = $mysql->prepare("TRUNCATE TABLE SSRMEET");
	$tssrmeet->execute();
	
	$ssrmeet = $banner->prepare("SELECT * FROM SATURN.SSRMEET");
	$ssrmeet->execute();

	$insert = $mysql->prepare("INSERT INTO SSRMEET (SSRMEET_TERM_CODE, SSRMEET_CRN, SSRMEET_DAYS_CODE, SSRMEET_DAY_NUMBER, SSRMEET_BEGIN_TIME, SSRMEET_END_TIME, SSRMEET_BLDG_CODE, SSRMEET_ROOM_CODE, SSRMEET_ACTIVITY_DATE, SSRMEET_START_DATE, SSRMEET_END_DATE, SSRMEET_CATAGORY, SSRMEET_SUN_DAY, SSRMEET_MON_DAY, SSRMEET_TUE_DAY, SSRMEET_WED_DAY, SSRMEET_THU_DAY, SSRMEET_FRI_DAY, SSRMEET_SAT_DAY, SSRMEET_SCHD_CODE, SSRMEET_OVER_RIDE, SSRMEET_CREDIT_HR_SESS, SSRMEET_MEET_NO, SSRMEET_HRS_WEEK, SSRMEET_FUNC_CODE, SSRMEET_COMT_CODE, SSRMEET_SCHS_CODE, SSRMEET_MTYP_CODE, SSRMEET_DATA_ORIGIN, SSRMEET_USER_ID) VALUES (:SSRMEET_TERM_CODE, :SSRMEET_CRN, :SSRMEET_DAYS_CODE, :SSRMEET_DAY_NUMBER, :SSRMEET_BEGIN_TIME, :SSRMEET_END_TIME, :SSRMEET_BLDG_CODE, :SSRMEET_ROOM_CODE, :SSRMEET_ACTIVITY_DATE, :SSRMEET_START_DATE, :SSRMEET_END_DATE, :SSRMEET_CATAGORY, :SSRMEET_SUN_DAY, :SSRMEET_MON_DAY, :SSRMEET_TUE_DAY, :SSRMEET_WED_DAY, :SSRMEET_THU_DAY, :SSRMEET_FRI_DAY, :SSRMEET_SAT_DAY, :SSRMEET_SCHD_CODE, :SSRMEET_OVER_RIDE, :SSRMEET_CREDIT_HR_SESS, :SSRMEET_MEET_NO, :SSRMEET_HRS_WEEK, :SSRMEET_FUNC_CODE, :SSRMEET_COMT_CODE, :SSRMEET_SCHS_CODE, :SSRMEET_MTYP_CODE, :SSRMEET_DATA_ORIGIN, :SSRMEET_USER_ID)");
	while($row = $ssrmeet->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":SSRMEET_TERM_CODE", $row->SSRMEET_TERM_CODE);
		$insert->bindValue(":SSRMEET_CRN", $row->SSRMEET_CRN);
		$insert->bindValue(":SSRMEET_DAYS_CODE", $row->SSRMEET_DAYS_CODE);
		$insert->bindValue(":SSRMEET_DAY_NUMBER", $row->SSRMEET_DAY_NUMBER);
		$insert->bindValue(":SSRMEET_BEGIN_TIME", $row->SSRMEET_BEGIN_TIME);
		$insert->bindValue(":SSRMEET_END_TIME", $row->SSRMEET_END_TIME);
		$insert->bindValue(":SSRMEET_BLDG_CODE", $row->SSRMEET_BLDG_CODE);
		$insert->bindValue(":SSRMEET_ROOM_CODE", $row->SSRMEET_ROOM_CODE);
		$insert->bindValue(":SSRMEET_ACTIVITY_DATE", toMySQLDate($row->SSRMEET_ACTIVITY_DATE));
		$insert->bindValue(":SSRMEET_START_DATE", toMySQLDate($row->SSRMEET_START_DATE));
		$insert->bindValue(":SSRMEET_END_DATE", toMySQLDate($row->SSRMEET_END_DATE));
		$insert->bindValue(":SSRMEET_CATAGORY", $row->SSRMEET_CATAGORY);
		$insert->bindValue(":SSRMEET_SUN_DAY", $row->SSRMEET_SUN_DAY);
		$insert->bindValue(":SSRMEET_MON_DAY", $row->SSRMEET_MON_DAY);
		$insert->bindValue(":SSRMEET_TUE_DAY", $row->SSRMEET_TUE_DAY);
		$insert->bindValue(":SSRMEET_WED_DAY", $row->SSRMEET_WED_DAY);
		$insert->bindValue(":SSRMEET_THU_DAY", $row->SSRMEET_THU_DAY);
		$insert->bindValue(":SSRMEET_FRI_DAY", $row->SSRMEET_FRI_DAY);
		$insert->bindValue(":SSRMEET_SAT_DAY", $row->SSRMEET_SAT_DAY);
		$insert->bindValue(":SSRMEET_SCHD_CODE", $row->SSRMEET_SCHD_CODE);
		$insert->bindValue(":SSRMEET_OVER_RIDE", $row->SSRMEET_OVER_RIDE);
		$insert->bindValue(":SSRMEET_CREDIT_HR_SESS", $row->SSRMEET_CREDIT_HR_SESS);
		$insert->bindValue(":SSRMEET_MEET_NO", $row->SSRMEET_MEET_NO);
		$insert->bindValue(":SSRMEET_HRS_WEEK", $row->SSRMEET_HRS_WEEK);
		$insert->bindValue(":SSRMEET_FUNC_CODE", $row->SSRMEET_FUNC_CODE);
		$insert->bindValue(":SSRMEET_COMT_CODE", $row->SSRMEET_COMT_CODE);
		$insert->bindValue(":SSRMEET_SCHS_CODE", $row->SSRMEET_SCHS_CODE);
		$insert->bindValue(":SSRMEET_MTYP_CODE", $row->SSRMEET_MTYP_CODE);
		$insert->bindValue(":SSRMEET_DATA_ORIGIN", $row->SSRMEET_DATA_ORIGIN);
		$insert->bindValue(":SSRMEET_USER_ID", $row->SSRMEET_USER_ID);
		$insert->execute();
	}
	
	$mysql->commit();
	$ssrmeet->closeCursor();
	print "...\tUpdated SSRMEET\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}


// SATURN.STVACYR
 
try {
	print "Updating STVACYR\t";
	$mysql->beginTransaction();
	$tstvacyr = $mysql->prepare("TRUNCATE TABLE STVACYR");
	$tstvacyr->execute();
	
	$stvacyr = $banner->prepare("SELECT * FROM SATURN.STVACYR");
	$stvacyr->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVACYR (STVACYR_CODE, STVACYR_DESC, STVACYR_ACTIVITY_DATE, STVACYR_SYSREQ_IND) VALUES (:STVACYR_CODE, :STVACYR_DESC, :STVACYR_ACTIVITY_DATE, :STVACYR_SYSREQ_IND)");
	while($row = $stvacyr->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVACYR_CODE", $row->STVACYR_CODE);
		$insert->bindValue(":STVACYR_DESC", $row->STVACYR_DESC);
		$insert->bindValue(":STVACYR_ACTIVITY_DATE", toMySQLDate($row->STVACYR_ACTIVITY_DATE));
		$insert->bindValue(":STVACYR_SYSREQ_IND", $row->STVACYR_SYSREQ_IND);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvacyr->closeCursor();
	print "...\tUpdated STVACYR\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVAPRV
 
try {
	print "Updating STVAPRV\t";
	$mysql->beginTransaction();
	$tstvaprv = $mysql->prepare("TRUNCATE TABLE STVAPRV");
	$tstvaprv->execute();
	
	$stvaprv = $banner->prepare("SELECT * FROM SATURN.STVAPRV");
	$stvaprv->execute();

	$insert = $mysql->prepare("INSERT INTO STVAPRV (STVAPRV_CODE, STVAPRV_DESC, STVAPRV_ACTIVITY_DATE) VALUES (:STVAPRV_CODE, :STVAPRV_DESC, :STVAPRV_ACTIVITY_DATE)");
	while($row = $stvaprv->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVAPRV_CODE", $row->STVAPRV_CODE);
		$insert->bindValue(":STVAPRV_DESC", $row->STVAPRV_DESC);
		$insert->bindValue(":STVAPRV_ACTIVITY_DATE", toMySQLDate($row->STVAPRV_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$stvaprv->closeCursor();
	print "...\tUpdated STVAPRV\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVASTY
 
try {
	print "Updating STVASTY\t";
	$mysql->beginTransaction();
	$tstvasty = $mysql->prepare("TRUNCATE TABLE STVASTY");
	$tstvasty->execute();
	
	$stvasty = $banner->prepare("SELECT * FROM SATURN.STVASTY");
	$stvasty->execute();

	$insert = $mysql->prepare("INSERT INTO STVASTY (STVASTY_CODE, STVASTY_DESC, STVASTY_ACTIVITY_DATE) VALUES (:STVASTY_CODE, :STVASTY_DESC, :STVASTY_ACTIVITY_DATE)");
	while($row = $stvasty->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVASTY_CODE", $row->STVASTY_CODE);
		$insert->bindValue(":STVASTY_DESC", $row->STVASTY_DESC);
		$insert->bindValue(":STVASTY_ACTIVITY_DATE", toMySQLDate($row->STVASTY_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$stvasty->closeCursor();
	print "...\tUpdated STVASTY\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVATTR
 
try {
	print "Updating STVATTR\t";
	$mysql->beginTransaction();
	$tstvattr = $mysql->prepare("TRUNCATE TABLE STVATTR");
	$tstvattr->execute();
	
	$stvattr = $banner->prepare("SELECT * FROM SATURN.STVATTR");
	$stvattr->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVATTR (STVATTR_CODE, STVATTR_DESC, STVATTR_ACTIVITY_DATE) VALUES (:STVATTR_CODE, :STVATTR_DESC, :STVATTR_ACTIVITY_DATE)");
	while($row = $stvattr->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVATTR_CODE", $row->STVATTR_CODE);
		$insert->bindValue(":STVATTR_DESC", $row->STVATTR_DESC);
		$insert->bindValue(":STVATTR_ACTIVITY_DATE", $row->STVATTR_ACTIVITY_DATE);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvattr->closeCursor();
	print "...\tUpdated STVATTR\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVBLDG
 
try {
	print "Updating STVBLDG\t";
	$mysql->beginTransaction();
	$tstvbldg = $mysql->prepare("TRUNCATE TABLE STVBLDG");
	$tstvbldg->execute();
	
	$stvbldg = $banner->prepare("SELECT * FROM SATURN.STVBLDG");
	$stvbldg->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVBLDG (STVBLDG_CODE, STVBLDG_DESC, STVBLDG_ACTIVITY_DATE, STVBLDG_VR_MSG_NO) VALUES (:STVBLDG_CODE, :STVBLDG_DESC, :STVBLDG_ACTIVITY_DATE, :STVBLDG_VR_MSG_NO)");
	while($row = $stvbldg->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVBLDG_CODE", $row->STVBLDG_CODE);
		$insert->bindValue(":STVBLDG_DESC", $row->STVBLDG_DESC);
		$insert->bindValue(":STVBLDG_ACTIVITY_DATE", toMySQLDate($row->STVBLDG_ACTIVITY_DATE));
		$insert->bindValue(":STVBLDG_VR_MSG_NO", $row->STVBLDG_VR_MSG_NO);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvbldg->closeCursor();
	print "...\tUpdated STVBLDG\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVCAMP
 
try {
	print "Updating STVCAMP\t";
	$mysql->beginTransaction();
	$tstvcamp = $mysql->prepare("TRUNCATE TABLE STVCAMP");
	$tstvcamp->execute();
	
	$stvcamp = $banner->prepare("SELECT * FROM SATURN.STVCAMP");
	$stvcamp->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVCAMP (STVCAMP_CODE, STVCAMP_DESC, STVCAMP_ACTIVITY_DATE, STVCAMP_DICD_CODE) VALUES (:STVCAMP_CODE, :STVCAMP_DESC, :STVCAMP_ACTIVITY_DATE, :STVCAMP_DICD_CODE)");
	while($row = $stvcamp->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVCAMP_CODE", $row->STVCAMP_CODE);
		$insert->bindValue(":STVCAMP_DESC", $row->STVCAMP_DESC);
		$insert->bindValue(":STVCAMP_ACTIVITY_DATE", toMySQLDate($row->STVCAMP_ACTIVITY_DATE));
		$insert->bindValue(":STVCAMP_DICD_CODE", $row->STVCAMP_DICD_CODE);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvcamp->closeCursor();
	print "...\tUpdated STVCAMP\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVCIPC
 
try {
	print "Updating STVCIPC\t";
	$mysql->beginTransaction();
	$tstvcipc = $mysql->prepare("TRUNCATE TABLE STVCIPC");
	$tstvcipc->execute();
	
	$stvcipc = $banner->prepare("SELECT * FROM SATURN.STVCIPC");
	$stvcipc->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVCIPC (STVCIPC_CODE, STVCIPC_DESC, STVCIPC_ACTIVITY_DATE, STVCIPC_CIPC_A_IND, STVCIPC_CIPC_B_IND, STVCIPC_CIPC_C_IND, STVCIPC_SP04_PROGRAM_CDE) VALUES (:STVCIPC_CODE, :STVCIPC_DESC, :STVCIPC_ACTIVITY_DATE, :STVCIPC_CIPC_A_IND, :STVCIPC_CIPC_B_IND, :STVCIPC_CIPC_C_IND, :STVCIPC_SP04_PROGRAM_CDE)");
	while($row = $stvcipc->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVCIPC_CODE", $row->STVCIPC_CODE);
		$insert->bindValue(":STVCIPC_DESC", $row->STVCIPC_DESC);
		$insert->bindValue(":STVCIPC_ACTIVITY_DATE", toMySQLDate($row->STVCIPC_ACTIVITY_DATE));
		$insert->bindValue(":STVCIPC_CIPC_A_IND", $row->STVCIPC_CIPC_A_IND);
		$insert->bindValue(":STVCIPC_CIPC_B_IND", $row->STVCIPC_CIPC_B_IND);
		$insert->bindValue(":STVCIPC_CIPC_C_IND", $row->STVCIPC_CIPC_C_IND);
		$insert->bindValue(":STVCIPC_SP04_PROGRAM_CDE", $row->STVCIPC_SP04_PROGRAM_CDE);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvcipc->closeCursor();
	print "...\tUpdated STVCIPC\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVCOLL
 
try {
	print "Updating STVCOLL\t";
	$mysql->beginTransaction();
	$tstvcoll = $mysql->prepare("TRUNCATE TABLE STVCOLL");
	$tstvcoll->execute();
	
	$stvcoll = $banner->prepare("SELECT * FROM SATURN.STVCOLL");
	$stvcoll->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVCOLL (STVCOLL_CODE, STVCOLL_DESC, STVCOLL_ADDR_STREET_LINE1, STVCOLL_ADDR_STREET_LINE2, STVCOLL_ADDR_STREET_LINE3, STVCOLL_ADDR_CITY, STVCOLL_ADDR_STATE, STVCOLL_ADDR_COUNTRY, STVCOLL_ADDR_ZIP_CODE, STVCOLL_ACTIVITY_DATE, STVCOLL_SYSTEM_REQ_IND, STVCOLL_VR_MSG_NO, STVCOLL_STATSCAN_CDE3, STVCOLL_DICD_CODE) VALUES (:STVCOLL_CODE, :STVCOLL_DESC, :STVCOLL_ADDR_STREET_LINE1, :STVCOLL_ADDR_STREET_LINE2, :STVCOLL_ADDR_STREET_LINE3, :STVCOLL_ADDR_CITY, :STVCOLL_ADDR_STATE, :STVCOLL_ADDR_COUNTRY, :STVCOLL_ADDR_ZIP_CODE, :STVCOLL_ACTIVITY_DATE, :STVCOLL_SYSTEM_REQ_IND, :STVCOLL_VR_MSG_NO, :STVCOLL_STATSCAN_CDE3, :STVCOLL_DICD_CODE)");
	while($row = $stvcoll->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVCOLL_CODE", $row->STVCOLL_CODE);
		$insert->bindValue(":STVCOLL_DESC", $row->STVCOLL_DESC);
		$insert->bindValue(":STVCOLL_ADDR_STREET_LINE1", $row->STVCOLL_ADDR_STREET_LINE1);
		$insert->bindValue(":STVCOLL_ADDR_STREET_LINE2", $row->STVCOLL_ADDR_STREET_LINE2);
		$insert->bindValue(":STVCOLL_ADDR_STREET_LINE3", $row->STVCOLL_ADDR_STREET_LINE3);
		$insert->bindValue(":STVCOLL_ADDR_CITY", $row->STVCOLL_ADDR_CITY);
		$insert->bindValue(":STVCOLL_ADDR_STATE", $row->STVCOLL_ADDR_STATE);
		$insert->bindValue(":STVCOLL_ADDR_COUNTRY", $row->STVCOLL_ADDR_COUNTRY);
		$insert->bindValue(":STVCOLL_ADDR_ZIP_CODE", $row->STVCOLL_ADDR_ZIP_CODE);
		$insert->bindValue(":STVCOLL_ACTIVITY_DATE", toMySQLDate($row->STVCOLL_ACTIVITY_DATE));
		$insert->bindValue(":STVCOLL_SYSTEM_REQ_IND", $row->STVCOLL_SYSTEM_REQ_IND);
		$insert->bindValue(":STVCOLL_VR_MSG_NO", $row->STVCOLL_VR_MSG_NO);
		$insert->bindValue(":STVCOLL_STATSCAN_CDE3", $row->STVCOLL_STATSCAN_CDE3);
		$insert->bindValue(":STVCOLL_DICD_CODE", $row->STVCOLL_DICD_CODE);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvcoll->closeCursor();
	print "...\tUpdated STVCOLL\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}


// SATURN.STVCOMT
 
try {
	print "Updating STVCOMT\t";
	$mysql->beginTransaction();
	$tstvcomt = $mysql->prepare("TRUNCATE TABLE STVCOMT");
	$tstvcomt->execute();
	
	$stvcomt = $banner->prepare("SELECT * FROM SATURN.STVCOMT");
	$stvcomt->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVCOMT (STVCOMT_CODE, STVCOMT_DESC, STVCOMT_TRANS_PRINT, STVCOMT_ACTIVITY_DATE) VALUES (:STVCOMT_CODE, :STVCOMT_DESC, :STVCOMT_TRANS_PRINT, :STVCOMT_ACTIVITY_DATE)");
	while($row = $stvcomt->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVCOMT_CODE", $row->STVCOMT_CODE);
		$insert->bindValue(":STVCOMT_DESC", $row->STVCOMT_DESC);
		$insert->bindValue(":STVCOMT_TRANS_PRINT", $row->STVCOMT_TRANS_PRINT);
		$insert->bindValue(":STVCOMT_ACTIVITY_DATE", toMySQLDate($row->STVCOMT_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$stvcomt->closeCursor();
	print "...\tUpdated STVCOMT\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVCSTA
 
try {
	print "Updating STVCSTA\t";
	$mysql->beginTransaction();
	$tstvcsta = $mysql->prepare("TRUNCATE TABLE STVCSTA");
	$tstvcsta->execute();
	
	$stvcsta = $banner->prepare("SELECT * FROM SATURN.STVCSTA");
	$stvcsta->execute();

	$insert = $mysql->prepare("INSERT INTO STVCSTA (STVCSTA_CODE, STVCSTA_DESC, STVCSTA_ACTIVITY_DATE, STVCSTA_ACTIVE_IND) VALUES (:STVCSTA_CODE, :STVCSTA_DESC, :STVCSTA_ACTIVITY_DATE, :STVCSTA_ACTIVE_IND)");	
	while($row = $stvcsta->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVCSTA_CODE", $row->STVCSTA_CODE);
		$insert->bindValue(":STVCSTA_DESC", $row->STVCSTA_DESC);
		$insert->bindValue(":STVCSTA_ACTIVITY_DATE", toMySQLDate($row->STVCSTA_ACTIVITY_DATE));
		$insert->bindValue(":STVCSTA_ACTIVE_IND", $row->STVCSTA_ACTIVE_IND);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvcsta->closeCursor();
	print "...\tUpdated STVCSTA\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVDEPT
 
try {
	print "Updating STVDEPT\t";
	$mysql->beginTransaction();
	$tstvdept = $mysql->prepare("TRUNCATE TABLE STVDEPT");
	$tstvdept->execute();
	
	$stvdept = $banner->prepare("SELECT * FROM SATURN.STVDEPT");
	$stvdept->execute();

	$insert = $mysql->prepare("INSERT INTO STVDEPT (STVDEPT_CODE, STVDEPT_DESC, STVDEPT_ACTIVITY_DATE, STVDEPT_SYSTEM_REQ_IND, STVDEPT_VR_MSG_NO) VALUES (:STVDEPT_CODE, :STVDEPT_DESC, :STVDEPT_ACTIVITY_DATE, :STVDEPT_SYSTEM_REQ_IND, :STVDEPT_VR_MSG_NO)");
	while($row = $stvdept->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVDEPT_CODE", $row->STVDEPT_CODE);
		$insert->bindValue(":STVDEPT_DESC", $row->STVDEPT_DESC);
		$insert->bindValue(":STVDEPT_ACTIVITY_DATE", $row->STVDEPT_ACTIVITY_DATE);
		$insert->bindValue(":STVDEPT_SYSTEM_REQ_IND", toMySQLDate($row->STVDEPT_SYSTEM_REQ_IND));
		$insert->bindValue(":STVDEPT_VR_MSG_NO", $row->STVDEPT_VR_MSG_NO);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvdept->closeCursor();
	print "...\tUpdated STVDEPT\n";
} catch(Exception $e) {
	$exceptions[] = $e->toString();
	$mysql->rollBack();
}
	

// SATURN.STVDIVS

try {
	print "Updating STVDIVS\t";
	$mysql->beginTransaction();
	$tstvdivs = $mysql->prepare("TRUNCATE TABLE STVDIVS");
	$tstvdivs->execute();
	
	$stvdivs = $banner->prepare("SELECT * FROM SATURN.STVDIVS");
	$stvdivs->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVDIVS (STVDIVS_CODE, STVDIVS_DESC, STVDIVS_ACTIVITY_DATE) VALUES (:STVDIVS_CODE, :STVDIVS_DESC, :STVDIVS_ACTIVITY_DATE)");
	while($row = $stvdivs->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVDIVS_CODE", $row->STVDIVS_CODE);
		$insert->bindValue(":STVDIVS_DESC", $row->STVDIVS_DESC);
		$insert->bindValue(":STVDIVS_ACTIVITY_DATE", toMySQLDate($row->STVDIVS_ACTIVITY_DATE));
		$insert->execute();
	}

	$mysql->commit();
	$stvdivs->closeCursor();
	print "...\tUpdated STVDIVS\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	
	

// SATURN.STVFCNT
 
try {
	print "Updating STVFCNT\t";
	$mysql->beginTransaction();
	$tstvfcnt = $mysql->prepare("TRUNCATE TABLE STVFCNT");
	$tstvfcnt->execute();
	
	$stvfcnt = $banner->prepare("SELECT * FROM SATURN.STVFCNT");
	$stvfcnt->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVFCNT (STVFCNT_CODE, STVFCNT_DESC, STVFCNT_ACTIVITY_DATE) VALUES (:STVFCNT_CODE, :STVFCNT_DESC, :STVFCNT_ACTIVITY_DATE)");
	while($row = $stvfcnt->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVFCNT_CODE", $row->STVFCNT_CODE);
		$insert->bindValue(":STVFCNT_DESC", $row->STVFCNT_DESC);
		$insert->bindValue(":STVFCNT_ACTIVITY_DATE", toMySQLDate($row->STVFCNT_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$stvfcnt->closeCursor();
	print "...\tUpdated STVFCNT\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVPWAV
 
try {
	print "Updating STVPWAV\t";
	$mysql->beginTransaction();
	$tstvpwav = $mysql->prepare("TRUNCATE TABLE STVPWAV");
	$tstvpwav->execute();
	
	$stvpwav = $banner->prepare("SELECT * FROM SATURN.STVPWAV");
	$stvpwav->execute();

	$insert = $mysql->prepare("INSERT INTO STVPWAV (STVPWAV_CODE, STVPWAV_DESC, STVPWAV_ACTIVITY_DATE) VALUES (:STVPWAV_CODE, :STVPWAV_DESC, :STVPWAV_ACTIVITY_DATE)");
	while($row = $stvpwav->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVPWAV_CODE", $row->STVPWAV_CODE);
		$insert->bindValue(":STVPWAV_DESC", $row->STVPWAV_DESC);
		$insert->bindValue(":STVPWAV_ACTIVITY_DATE", toMySQLDate($row->STVPWAV_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$stvpwav->closeCursor();
	print "...\tUpdated STVPWAV\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVREPS
 
try {
	print "Updating STVREPS\t";
	$mysql->beginTransaction();
	$tstvreps = $mysql->prepare("TRUNCATE TABLE STVREPS");
	$tstvreps->execute();
	
	$stvreps = $banner->prepare("SELECT * FROM SATURN.STVREPS");
	$stvreps->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVREPS (STVREPS_CODE, STVREPS_DESC, STVREPS_ACTIVITY_DATE) VALUES (:STVREPS_CODE, :STVREPS_DESC, :STVREPS_ACTIVITY_DATE)");
	while($row = $stvreps->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVREPS_CODE", $row->STVREPS_CODE);
		$insert->bindValue(":STVREPS_DESC", $row->STVREPS_DESC);
		$insert->bindValue(":STVREPS_ACTIVITY_DATE", toMySQLDate($row->STVREPS_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$stvreps->closeCursor();
	print "...\tUpdated STVREPS\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVSCHD
 
try {
	print "Updating STVSCHD\t";
	$mysql->beginTransaction();
	$tstvschd = $mysql->prepare("TRUNCATE TABLE STVSCHD");
	$tstvschd->execute();
	
	$stvschd = $banner->prepare("SELECT * FROM STVSCHD");
	$stvschd->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVSCHD (STVSCHD_CODE, STVSCHD_DESC, STVSCHD_ACTIVITY_DATE, STVSCHD_INSTRUCT_METHOD, STVSCHD_COOP_IND, STVSCHD_AUTO_SCHEDULER_IND, STVSCHD_INSM_CODE, STVSCHD_VR_MSG_NO) VALUES (:STVSCHD_CODE, :STVSCHD_DESC, :STVSCHD_ACTIVITY_DATE, :STVSCHD_INSTRUCT_METHOD, :STVSCHD_COOP_IND, :STVSCHD_AUTO_SCHEDULER_IND, :STVSCHD_INSM_CODE, :STVSCHD_VR_MSG_NO)");
	while($row = $stvschd->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVSCHD_CODE", $row->STVSCHD_CODE);
		$insert->bindValue(":STVSCHD_DESC", $row->STVSCHD_DESC);
		$insert->bindValue(":STVSCHD_ACTIVITY_DATE", toMySQLDate($row->STVSCHD_ACTIVITY_DATE));
		$insert->bindValue(":STVSCHD_INSTRUCT_METHOD", $row->STVSCHD_INSTRUCT_METHOD);
		$insert->bindValue(":STVSCHD_COOP_IND", $row->STVSCHD_COOP_IND);
		$insert->bindValue(":STVSCHD_AUTO_SCHEDULER_IND", $row->STVSCHD_AUTO_SCHEDULER_IND);
		$insert->bindValue(":STVSCHD_INSM_CODE", $row->STVSCHD_INSM_CODE);
		$insert->bindValue(":STVSCHD_VR_MSG_NO", $row->STVSCHD_VR_MSG_NO);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvschd->closeCursor();
	print "...\tUpdated STVSCHD\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVSUBJ
 
try {
	print "Updating STVSUBJ\t";
	$mysql->beginTransaction();
	$tstvsubj = $mysql->prepare("TRUNCATE TABLE STVSUBJ");
	$tstvsubj->execute();
	
	$stvsubj = $banner->prepare("SELECT * FROM STVSUBJ");
	$stvsubj->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVSUBJ (STVSUBJ_CODE, STVSUBJ_DESC, STVSUBJ_ACTIVITY_DATE, STVSUBJ_VR_MSG_NO, STVSUBJ_DISP_WEB_IND) VALUES (:STVSUBJ_CODE, :STVSUBJ_DESC, :STVSUBJ_ACTIVITY_DATE, :STVSUBJ_VR_MSG_NO, :STVSUBJ_DISP_WEB_IND)");
	while($row = $stvsubj->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVSUBJ_CODE", $row->STVSUBJ_CODE);
		$insert->bindValue(":STVSUBJ_DESC", $row->STVSUBJ_DESC);
		$insert->bindValue(":STVSUBJ_ACTIVITY_DATE", toMySQLDate($row->STVSUBJ_ACTIVITY_DATE));
		$insert->bindValue(":STVSUBJ_VR_MSG_NO", $row->STVSUBJ_VR_MSG_NO);
		$insert->bindValue(":STVSUBJ_DISP_WEB_IND", $row->STVSUBJ_DISP_WEB_IND);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvsubj->closeCursor();
	print "...\tUpdated STVSUBJ\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVTERM
 
try {
	print "Updating STVTERM\t";
	$mysql->beginTransaction();
	$tstvterm = $mysql->prepare("TRUNCATE TABLE STVTERM");
	$tstvterm->execute();
	
	$stvterm = $banner->prepare("SELECT * FROM STVTERM");
	$stvterm->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVTERM (STVTERM_CODE, STVTERM_DESC, STVTERM_START_DATE, STVTERM_END_DATE, STVTERM_FA_PROC_YR, STVTERM_ACTIVITY_DATE, STVTERM_FA_TERM, STVTERM_FA_PERIOD, STVTERM_FA_END_PERIOD, STVTERM_ACYR_CODE, STVTERM_HOUSING_START_DATE, STVTERM_HOUSING_END_DATE, STVTERM_SYSTEM_REQ_IND, STVTERM_TRMT_CODE) VALUES (:STVTERM_CODE, :STVTERM_DESC, :STVTERM_START_DATE, :STVTERM_END_DATE, :STVTERM_FA_PROC_YR, :STVTERM_ACTIVITY_DATE, :STVTERM_FA_TERM, :STVTERM_FA_PERIOD, :STVTERM_FA_END_PERIOD, :STVTERM_ACYR_CODE, :STVTERM_HOUSING_START_DATE, :STVTERM_HOUSING_END_DATE, :STVTERM_SYSTEM_REQ_IND, :STVTERM_TRMT_CODE)");
	while($row = $stvterm->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVTERM_CODE", $row->STVTERM_CODE);
		$insert->bindValue(":STVTERM_DESC", $row->STVTERM_DESC);
		$insert->bindValue(":STVTERM_START_DATE", toMySQLDate($row->STVTERM_START_DATE));
		$insert->bindValue(":STVTERM_END_DATE", toMySQLDate($row->STVTERM_END_DATE));
		$insert->bindValue(":STVTERM_FA_PROC_YR", $row->STVTERM_FA_PROC_YR);
		$insert->bindValue(":STVTERM_ACTIVITY_DATE", toMySQLDate($row->STVTERM_ACTIVITY_DATE));
		$insert->bindValue(":STVTERM_FA_TERM", $row->STVTERM_FA_TERM);
		$insert->bindValue(":STVTERM_FA_PERIOD", $row->STVTERM_FA_PERIOD);
		$insert->bindValue(":STVTERM_FA_END_PERIOD", $row->STVTERM_FA_END_PERIOD);
		$insert->bindValue(":STVTERM_ACYR_CODE", $row->STVTERM_ACYR_CODE);
		$insert->bindValue(":STVTERM_HOUSING_START_DATE", $row->STVTERM_HOUSING_START_DATE);
		$insert->bindValue(":STVTERM_HOUSING_END_DATE", $row->STVTERM_HOUSING_END_DATE);
		$insert->bindValue(":STVTERM_SYSTEM_REQ_IND", $row->STVTERM_SYSTEM_REQ_IND);
		$insert->bindValue(":STVTERM_TRMT_CODE", $row->STVTERM_TRMT_CODE);
		$insert->execute();
	}
	
	$mysql->commit();
	$stvterm->closeCursor();
	print "...\tUpdated STVTERM\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN.STVTRMT
 
try {
	print "Updating STVTRMT\t";
	$mysql->beginTransaction();
	$tstvtrmt = $mysql->prepare("TRUNCATE TABLE STVTRMT");
	$tstvtrmt->execute();
	
	$stvtrmt = $banner->prepare("SELECT * FROM STVTRMT");
	$stvtrmt->execute();
	
	$insert = $mysql->prepare("INSERT INTO STVTRMT (STVTRMT_CODE, STVTRMT_DESC, STVTRMT_ACTIVITY_DATE) VALUES (:STVTRMT_CODE, :STVTRMT_DESC, :STVTRMT_ACTIVITY_DATE)");
	while($row = $stvtrmt->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":STVTRMT_CODE", $row->STVTRMT_CODE);
		$insert->bindValue(":STVTRMT_DESC", $row->STVTRMT_DESC);
		$insert->bindValue(":STVTRMT_ACTIVITY_DATE", toMySQLDate($row->STVTRMT_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$mysql->commit();
	$stvtrmt->closeCursor();
	print "...\tUpdated STVTRMT\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}
	

// SATURN_MIDD.SYVINST
 
try {
	print "Updating SYVINST\t";
	$mysql->beginTransaction();
	$tsyvinst = $mysql->prepare("TRUNCATE TABLE SYVINST");
	$tsyvinst->execute();
	
	$syvinst = $banner->prepare("SELECT * FROM SATURN_MIDD.SYVINST");
	$syvinst->execute();
	
	$insert = $mysql->prepare("INSERT INTO SYVINST (SYVINST_TERM_CODE, SYVINST_CRN, SYVINST_PIDM, SYVINST_LAST_NAME, SYVINST_FIRST_NAME, WEB_ID) VALUES (:SYVINST_TERM_CODE, :SYVINST_CRN, :SYVINST_PIDM, :SYVINST_LAST_NAME, :SYVINST_FIRST_NAME, :WEB_ID)");
	while($row = $syvinst->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT)) {
		$insert->bindValue(":SYVINST_TERM_CODE", $row->SYVINST_TERM_CODE);
		$insert->bindValue(":SYVINST_CRN", $row->SYVINST_CRN);
		$insert->bindValue(":SYVINST_PIDM", $row->SYVINST_PIDM);
		$insert->bindValue(":SYVINST_LAST_NAME", $row->SYVINST_LAST_NAME);
		$insert->bindValue(":SYVINST_FIRST_NAME", $row->SYVINST_FIRST_NAME);
		$insert->bindValue(":WEB_ID", $row->WEB_ID);
		$insert->execute();
	}
	
	$mysql->commit();
	$syvinst->closeCursor();
	print "...\tUpdated SYVINST\n";
} catch(Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}

// Build derived table for easier term-catalog lookups
try {
	print "Updating derived tables\t";
	$mysql->beginTransaction();
	$ttermcat = $mysql->prepare("TRUNCATE TABLE catalog_term");
	$ttermcat->execute();
	
	$searches = $mysql->query("SELECT * FROM catalog_term_match")->fetchAll();
	
	$itermcat = $mysql->prepare("
INSERT INTO
	catalog_term
	(catalog_id, term_code, term_display_label)
SELECT
	:catalog_id,
	STVTERM_CODE,
	:term_display_label
FROM
	STVTERM
WHERE
	STVTERM_CODE LIKE (:term_code_match)
");

	foreach ($searches as $search) {
		$itermcat->execute(array(
			':catalog_id' => $search['catalog_id'], 
			':term_code_match' => $search['term_code_match'],
			':term_display_label' => $search['term_display_label']
		));
	}

	$mysql->commit();
	
	print "...\tUpdated derived table: catalog_term\n";
	
	// Rebuild our "materialized views"
	require_once(dirname(__FILE__).'/../application/library/harmoni/SQLUtils.php');
	$mysql->beginTransaction();
	harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/../application/library/banner/sql/create_views.sql', $mysql);
	$mysql->commit();

} catch (Exception $e) {
	$exceptions[] = $e->__toString();
	$mysql->rollBack();
}

sendExceptions($exceptions);

?>