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
$bannerUser 	= "RO_USER_NAME";
$bannerPassword = "password";

$tempMysqlHost		= "localhost";
$tempMysqlDatabase  = "catalog_temp";
$tempMysqlUser		= "testuser";
$tempMysqlPassword	= "testpassword";

$primaryMysqlHost		= "localhost";
$primaryMysqlDatabase   = "catalog";
$primaryMysqlUser		= "testuser";
$primaryMysqlPassword	= "testpassword";

$mysql = '/usr/bin/mysql';
$mysqldump = '/usr/bin/mysqldump';

$sendMailOnError = false;
$errorMailFrom	= "admin@example.edu";
$errorMailTo	= array("admin@example.edu", "admin2@example.edu");

$allowedBlckCodes = array('CC');

/*********************************************************
 * End - Config
 *********************************************************/

$tempMysqlDSN		= "mysql:dbname=".$tempMysqlDatabase.";host=".$tempMysqlHost;
$primaryMysqlDSN		= "mysql:dbname=".$primaryMysqlDatabase.";host=".$primaryMysqlHost;

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
		
	}
}

/**
 * Custom Error handler function to throw exceptions on any PHP
 * Warnings or Errors. This should catch any OCI problems that
 * are not picked up by calls to oci_error().
 */
function exception_error_handler ($errno, $errstr, $errfile, $errLine, $errcontext) {
	throw new Exception($errstr, $errno);
}
set_error_handler('exception_error_handler', E_ERROR | E_WARNING);

function oci_execute_query($connection, $query) { 
	$statement = oci_parse($connection, $query);
	if ($error = oci_error($connection))
		throw new Exception($error['message'], $error['code']);
	
	oci_execute($statement);
	if ($error = oci_error($connection))
		throw new Exception($error['message'], $error['code']);
	return $statement;
}

/*********************************************************
 * Create the cache tables
 *********************************************************/

$bannerTables = array(
	'GORINTG',
	'GTVDUNT',
	'GTVINSM',
	'GTVINTP',
	'GTVMTYP',
	'GTVSCHS',
	'SCBCRSE',
	'SCBDESC',
	'SCRATTR',
	'SCREQIV',
	'SCRLEVL',
	'SIRASGN',
	'SSBDESC',
	'SSBSECT',
	'SSBXLST',
	'SSRATTR',
	'SSRBLCK',
	'SSRMEET',
	'SSRXLST',
	'STVACYR',
	'STVAPRV',
	'STVASTY',
	'STVATTR',
	'STVBLCK',
	'STVBLDG',
	'STVCAMP',
	'STVCIPC',
	'STVCOLL',
	'STVCOMT',
	'STVCSTA',
	'STVDEPT',
	'STVDIVS',
	'STVFCNT',
	'STVLEVL',
	'STVMEET',
	'STVPWAV',
	'STVREPS',
	'STVSCHD',
	'STVSUBJ',
	'STVTERM',
	'STVTRMT',
	'SYVINST',
);
$command = $mysqldump.' --add-drop-table --single-transaction --no-data '
	.' -h '.escapeshellarg($primaryMysqlHost)
	.' -u '.escapeshellarg($primaryMysqlUser)
	.' -p'.escapeshellarg($primaryMysqlPassword)
	.' '.escapeshellarg($primaryMysqlDatabase)
	.' '.implode(' ', $bannerTables)
	.' | mysql'
	.' -h '.escapeshellarg($tempMysqlHost)
	.' -u '.escapeshellarg($tempMysqlUser)
	.' -p'.escapeshellarg($tempMysqlPassword)
	.' -D '.escapeshellarg($tempMysqlDatabase);
print "Creating cache tables	...";
exec($command, $output, $return_var);
print "	done\n";
if ($return_var) {
	fwrite(STDERR, 'Creating tables in temp database failed: '.implode("\n", $output));
	exit(1);
}


/*********************************************************
 * Update the Cache tables from Banner
 *********************************************************/


/**
 * Note: I use "TRUNCATE TABLE" instead of "TRUNCATE TABLE" here because versions of
 * MySQL 4.x treat TRUNCATE as a DDL statement and refuse to do it properly during
 * a transaction due to the lock on the table. TRUNCATE is faster than DELETE and
 * you should replace the statements appropriately if you're using a version of
 * MySQL where this works.
 */

$banner; $tempMysql; $primaryMysql;

try {
	$banner = oci_connect($bannerUser, $bannerPassword, $bannerTNS, "UTF8");
	if (!$banner) {
		$error = oci_error();
		throw new Exception('Oracle connect failed with message: '.$error['message'], $error['code']);
	}
	
	// The libmysql driver needs to allocate a buffer bigger than the expected data
	if (defined('PDO::MYSQL_ATTR_MAX_BUFFER_SIZE')) {
		$mysqlOptions = array(PDO::MYSQL_ATTR_MAX_BUFFER_SIZE => 1024*1024*100);
	}
	// The mysqlnd driver on the other hand allocates buffers as big as needed.
	else {
		$mysqlOptions = array();
	}
	
	$tempMysql = new PDO($tempMysqlDSN, $tempMysqlUser, $tempMysqlPassword, $mysqlOptions);
	$tempMysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$primaryMysql = new PDO($primaryMysqlDSN, $primaryMysqlUser, $primaryMysqlPassword, $mysqlOptions);
	$primaryMysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	


	// GENERAL.GORINTG
	print "Updating GORINTG\t";
	$tempMysql->beginTransaction();
	$tgorintg = $tempMysql->prepare("TRUNCATE TABLE GORINTG");
	$tgorintg->execute();
	
	$gorintg = oci_execute_query($banner, "SELECT * FROM GENERAL.GORINTG");
	
	$insert = $tempMysql->prepare("INSERT INTO GORINTG (GORINTG_CODE, GORINTG_DESC, GORINTG_INTP_CODE, GORINTG_USER_ID, GORINTG_ACTIVITY_DATE, GORINTG_DATA_ORIGIN) VALUES (:GORINTG_CODE, :GORINTG_DESC, :GORINTG_INTP_CODE, :GORINTG_USER_ID, :GORINTG_ACTIVITY_DATE, :GORINTG_DATA_ORIGIN)");
	while($row = oci_fetch_object($gorintg)) {
		$insert->bindValue(":GORINTG_CODE", $row->GORINTG_CODE);
		$insert->bindValue(":GORINTG_DESC", $row->GORINTG_DESC);
		$insert->bindValue(":GORINTG_INTP_CODE", $row->GORINTG_INTP_CODE);
		$insert->bindValue(":GORINTG_USER_ID", $row->GORINTG_USER_ID);
		$insert->bindValue(":GORINTG_ACTIVITY_DATE", toMySQLDate($row->GORINTG_ACTIVITY_DATE));
		$insert->bindValue(":GORINTG_DATA_ORIGIN", $row->GORINTG_DATA_ORIGIN);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($gorintg);
	print "...\tUpdated GORINTG\n";

		
	
	// GENERAL.GTVDUNT	 
	print "Updating GTVDUNT\t";
	$tempMysql->beginTransaction();
	$tgtvdunt = $tempMysql->prepare("TRUNCATE TABLE GTVDUNT");
	$tgtvdunt->execute();
	
	$gtvdunt = oci_execute_query($banner, "SELECT * FROM GENERAL.GTVDUNT");
	
	$insert = $tempMysql->prepare("INSERT INTO GTVDUNT (GTVDUNT_CODE, GTVDUNT_DESC, GTVDUNT_NUMBER_OF_DAYS, GTVDUNT_ACTIVITY_DATE, GTVDUNT_USER_ID, GTVDUNT_VR_MSG_NO) VALUES (:GTVDUNT_CODE, :GTVDUNT_DESC, :GTVDUNT_NUMBER_OF_DAYS, :GTVDUNT_ACTIVITY_DATE, :GTVDUNT_USER_ID, :GTVDUNT_VR_MSG_NO)");
	while($row = oci_fetch_object($gtvdunt)) {
		$insert->bindValue(":GTVDUNT_CODE", $row->GTVDUNT_CODE);
		$insert->bindValue(":GTVDUNT_DESC", $row->GTVDUNT_DESC);
		$insert->bindValue(":GTVDUNT_NUMBER_OF_DAYS", $row->GTVDUNT_NUMBER_OF_DAYS);
		$insert->bindValue(":GTVDUNT_ACTIVITY_DATE", toMySQLDate($row->GTVDUNT_ACTIVITY_DATE));
		$insert->bindValue(":GTVDUNT_USER_ID", $row->GTVDUNT_USER_ID);
		$insert->bindValue(":GTVDUNT_VR_MSG_NO", $row->GTVDUNT_VR_MSG_NO);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($gtvdunt);
	print "...\tUpdated GTVDUNT\n";

	
	
	// GENERAL.GTVINSM
	print "Updating GTVINSM\t";
	$tempMysql->beginTransaction();
	$tgtvinsm = $tempMysql->prepare("TRUNCATE TABLE GTVINSM");
	$tgtvinsm->execute();
	
	$gtvinsm = oci_execute_query($banner, "SELECT * FROM GENERAL.GTVINSM");
	
	$insert = $tempMysql->prepare("INSERT INTO GTVINSM (GTVINSM_CODE, GTVINSM_DESC, GTVINSM_ACTIVITY_DATE, GTVINSM_USER_ID, GTVINSM_VR_MSG_NO) VALUES (:GTVINSM_CODE, :GTVINSM_DESC, :GTVINSM_ACTIVITY_DATE, :GTVINSM_USER_ID, :GTVINSM_VR_MSG_NO)");
	while($row = oci_fetch_object($gtvinsm)) {
		$insert->bindValue(":GTVINSM_CODE", $row->GTVINSM_CODE);
		$insert->bindValue(":GTVINSM_DESC", $row->GTVINSM_DESC);
		$insert->bindValue(":GTVINSM_ACTIVITY_DATE", toMySQLDate($row->GTVINSM_ACTIVITY_DATE));
		$insert->bindValue(":GTVINSM_USER_ID", $row->GTVINSM_USER_ID);
		$insert->bindValue(":GTVINSM_VR_MSG_NO", $row->GTVINSM_VR_MSG_NO);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($gtvinsm);
	print "...\tUpdated GTVINSM\n";

		
	
	// GENERAL.GTVINTP
	print "Updating GTVINTP\t";
	$tempMysql->beginTransaction();
	$tgtvintp = $tempMysql->prepare("TRUNCATE TABLE GTVINTP");
	$tgtvintp->execute();
	
	$gtvintp = oci_execute_query($banner, "SELECT * FROM GENERAL.GTVINTP");
	
	$insert = $tempMysql->prepare("INSERT INTO GTVINTP (GTVINTP_CODE, GTVINTP_DESC, GTVINTP_USER_ID, GTVINTP_ACTIVITY_DATE, GTVINTP_DATA_ORIGIN) VALUES (GTVINTP_CODE, GTVINTP_DESC, GTVINTP_USER_ID, GTVINTP_ACTIVITY_DATE, GTVINTP_DATA_ORIGIN)");
	while($row = oci_fetch_object($gtvintp)) {
		$insert->bindValue(":GTVINTP_CODE", $row->GTVINTP_CODE);
		$insert->bindValue(":GTVINTP_DESC", $row->GTVINTP_DESC);
		$insert->bindValue(":GTVINTP_USER_ID", $row->GTVINTP_USER_ID);
		$insert->bindValue(":GTVINTP_ACTIVITY_DATE", toMySQLDate($row->GTVINTP_ACTIVITY_DATE));
		$insert->bindValue(":GTVINTP_DATA_ORIGIN", $row->GTVINTP_DATA_ORIGIN);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($gtvintp);
	print "...\tUpdated GTVINTP\n";

		
	
	// GENERAL.GTVMTYP
	print "Updating GTVMTYP\t";
	$tempMysql->beginTransaction();
	$tgtvmtyp = $tempMysql->prepare("TRUNCATE TABLE GTVMTYP");
	$tgtvmtyp->execute();
	
	$gtvmtyp = oci_execute_query($banner, "SELECT * FROM GENERAL.GTVMTYP");

	$insert = $tempMysql->prepare("INSERT INTO GTVMTYP (GTVMTYP_CODE, GTVMTYP_DESC, GTVMTYP_SYS_REQ_IND, GTVMTYP_ACTIVITY_DATE, GTVMTYP_USER_ID, GTVMTYP_VR_MSG_NO) VALUES (:GTVMTYP_CODE, :GTVMTYP_DESC, :GTVMTYP_SYS_REQ_IND, :GTVMTYP_ACTIVITY_DATE, :GTVMTYP_USER_ID, :GTVMTYP_VR_MSG_NO)");
	while($row = oci_fetch_object($gtvmtyp)) {
		$insert->bindValue(":GTVMTYP_CODE", $row->GTVMTYP_CODE);
		$insert->bindValue(":GTVMTYP_DESC", $row->GTVMTYP_DESC);
		$insert->bindValue(":GTVMTYP_SYS_REQ_IND", $row->GTVMTYP_SYS_REQ_IND);
		$insert->bindValue(":GTVMTYP_ACTIVITY_DATE", toMySQLDate($row->GTVMTYP_ACTIVITY_DATE));
		$insert->bindValue(":GTVMTYP_USER_ID", $row->GTVMTYP_USER_ID);
		$insert->bindValue(":GTVMTYP_VR_MSG_NO", $row->GTVMTYP_VR_MSG_NO);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($gtvmtyp);
	print "...\tUpdated GTVMTYP\n";

	
	
	// GENERAL.GTVSCHS
	print "Updating GTVSCHS\t";
	$tempMysql->beginTransaction();
	$tgtvschs = $tempMysql->prepare("TRUNCATE TABLE GTVSCHS");
	$tgtvschs->execute();
	
	$gtvschs = oci_execute_query($banner, "SELECT * FROM GENERAL.GTVSCHS");
	
	$insert = $tempMysql->prepare("INSERT INTO GTVSCHS (GTVSCHS_CODE, GTVSCHS_DESC, GTVSCHS_SYSTEM_REQ_IND, GTVSCHS_ACTIVITY_DATE) VALUES (:GTVSCHS_CODE, :GTVSCHS_DESC, :GTVSCHS_SYSTEM_REQ_IND, :GTVSCHS_ACTIVITY_DATE)");
	while($row = oci_fetch_object($gtvschs)) {
		$insert->bindValue(":GTVSCHS_CODE", $row->GTVSCHS_CODE);
		$insert->bindValue(":GTVSCHS_DESC", $row->GTVSCHS_DESC);
		$insert->bindValue(":GTVSCHS_SYSTEM_REQ_IND", $row->GTVSCHS_SYSTEM_REQ_IND);
		$insert->bindValue(":GTVSCHS_ACTIVITY_DATE", toMySQLDate($row->GTVSCHS_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($gtvschs);
	print "...\tUpdated GTVSCHS\n";

		
	
	// SATURN.SCBCRSE
	print "Updating SCBCRSE\t";
	$tempMysql->beginTransaction();
	$tscbcrse = $tempMysql->prepare("TRUNCATE TABLE SCBCRSE");
	$tscbcrse->execute();
	
	$scbcrse = oci_execute_query($banner, "SELECT * FROM SATURN.SCBCRSE");
	
	$insert = $tempMysql->prepare("INSERT INTO SCBCRSE (SCBCRSE_SUBJ_CODE, SCBCRSE_CRSE_NUMB, SCBCRSE_EFF_TERM, SCBCRSE_COLL_CODE, SCBCRSE_DIVS_CODE, SCBCRSE_DEPT_CODE, SCBCRSE_CSTA_CODE, SCBCRSE_TITLE, SCBCRSE_CIPC_CODE, SCBCRSE_CREDIT_HR_IND, SCBCRSE_CREDIT_HR_LOW, SCBCRSE_CREDIT_HR_HIGH, SCBCRSE_LEC_HR_IND, SCBCRSE_LEC_HR_LOW, SCBCRSE_LEC_HR_HIGH, SCBCRSE_LAB_HR_IND, SCBCRSE_LAB_HR_LOW, SCBCRSE_LAB_HR_HIGH, SCBCRSE_OTH_HR_IND, SCBCRSE_OTH_HR_LOW, SCBCRSE_OTH_HR_HIGH, SCBCRSE_BILL_HR_IND, SCBCRSE_BILL_HR_LOW, SCBCRSE_BILL_HR_HIGH, SCBCRSE_APRV_CODE, SCBCRSE_REPEAT_LIMIT, SCBCRSE_PWAV_CODE, SCBCRSE_TUIW_IND, SCBCRSE_ADD_FEES_IND, SCBCRSE_ACTIVITY_DATE, SCBCRSE_CONT_HR_LOW, SCBCRSE_CONT_HR_IND, SCBCRSE_CONT_HR_HIGH, SCBCRSE_CEU_IND, SCBCRSE_REPS_CODE, SCBCRSE_MAX_RPT_UNITS, SCBCRSE_CAPP_PREREQ_TEST_IND, SCBCRSE_DUNT_CODE, SCBCRSE_NUMBER_OF_UNITS, SCBCRSE_DATA_ORIGIN, SCBCRSE_USER_ID) VALUES (:SCBCRSE_SUBJ_CODE, :SCBCRSE_CRSE_NUMB, :SCBCRSE_EFF_TERM,	:SCBCRSE_COLL_CODE,	:SCBCRSE_DIVS_CODE,	:SCBCRSE_DEPT_CODE,	:SCBCRSE_CSTA_CODE,	:SCBCRSE_TITLE,	:SCBCRSE_CIPC_CODE,	:SCBCRSE_CREDIT_HR_IND,	:SCBCRSE_CREDIT_HR_LOW,	:SCBCRSE_CREDIT_HR_HIGH, :SCBCRSE_LEC_HR_IND,:SCBCRSE_LEC_HR_LOW, :SCBCRSE_LEC_HR_HIGH,	:SCBCRSE_LAB_HR_IND, :SCBCRSE_LAB_HR_LOW, :SCBCRSE_LAB_HR_HIGH, :SCBCRSE_OTH_HR_IND, :SCBCRSE_OTH_HR_LOW, :SCBCRSE_OTH_HR_HIGH, :SCBCRSE_BILL_HR_IND, :SCBCRSE_BILL_HR_LOW, :SCBCRSE_BILL_HR_HIGH, :SCBCRSE_APRV_CODE, :SCBCRSE_REPEAT_LIMIT, :SCBCRSE_PWAV_CODE, :SCBCRSE_TUIW_IND, :SCBCRSE_ADD_FEES_IND, :SCBCRSE_ACTIVITY_DATE, :SCBCRSE_CONT_HR_LOW, :SCBCRSE_CONT_HR_IND, :SCBCRSE_CONT_HR_HIGH, :SCBCRSE_CEU_IND, :SCBCRSE_REPS_CODE, :SCBCRSE_MAX_RPT_UNITS, :SCBCRSE_CAPP_PREREQ_TEST_IND, :SCBCRSE_DUNT_CODE, :SCBCRSE_NUMBER_OF_UNITS, :SCBCRSE_DATA_ORIGIN, :SCBCRSE_USER_ID)");
	while($row = oci_fetch_object($scbcrse)) {
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
	
	$tempMysql->commit();
	oci_free_statement($scbcrse);
	print "...\tUpdated SCBCRSE\n";

	
	// SATURN.SCBDESC
	print "Updating SCBDESC\t";
	$tempMysql->beginTransaction();
	$tSCBDESC = $tempMysql->prepare("TRUNCATE TABLE SCBDESC");
	$tSCBDESC->execute();
	
	$SCBDESC = oci_execute_query($banner, "SELECT SCBDESC_SUBJ_CODE, SCBDESC_CRSE_NUMB, SCBDESC_TERM_CODE_EFF, SCBDESC_ACTIVITY_DATE, SCBDESC_USER_ID, SCBDESC_TEXT_NARRATIVE, SCBDESC_TERM_CODE_END FROM SATURN.SCBDESC");
	
	$insert = $tempMysql->prepare("INSERT INTO SCBDESC (SCBDESC_SUBJ_CODE, SCBDESC_CRSE_NUMB, SCBDESC_TERM_CODE_EFF, SCBDESC_ACTIVITY_DATE, SCBDESC_USER_ID, SCBDESC_TEXT_NARRATIVE, SCBDESC_TERM_CODE_END) VALUES (:SCBDESC_SUBJ_CODE, :SCBDESC_CRSE_NUMB, :SCBDESC_TERM_CODE_EFF, :SCBDESC_ACTIVITY_DATE, :SCBDESC_USER_ID, :SCBDESC_TEXT_NARRATIVE, :SCBDESC_TERM_CODE_END)");
	while($row = oci_fetch_object($SCBDESC)) {
		$insert->bindValue(":SCBDESC_SUBJ_CODE", $row->SCBDESC_SUBJ_CODE);
		$insert->bindValue(":SCBDESC_CRSE_NUMB", $row->SCBDESC_CRSE_NUMB);
		$insert->bindValue(":SCBDESC_TERM_CODE_EFF", $row->SCBDESC_TERM_CODE_EFF);
		$insert->bindValue(":SCBDESC_ACTIVITY_DATE", toMySQLDate($row->SCBDESC_ACTIVITY_DATE));
		$insert->bindValue(":SCBDESC_USER_ID", $row->SCBDESC_USER_ID);
		if (is_null($row->SCBDESC_TEXT_NARRATIVE)) {
			$insert->bindValue(":SCBDESC_TEXT_NARRATIVE", null);
		} else {
			$desc = $row->SCBDESC_TEXT_NARRATIVE->load();
			$insert->bindValue(":SCBDESC_TEXT_NARRATIVE", $desc);
		}
		$insert->bindValue(":SCBDESC_TERM_CODE_END", $row->SCBDESC_TERM_CODE_END);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($SCBDESC);
	print "...\tUpdated SCBDESC\n";

	
	
	// SATURN.SCRATTR
	print "Updating SCRATTR\t";
	$tempMysql->beginTransaction();
	$tSCRATTR = $tempMysql->prepare("TRUNCATE TABLE SCRATTR");
	$tSCRATTR->execute();
	
	$SCRATTR = oci_execute_query($banner, "SELECT SCRATTR_SUBJ_CODE, SCRATTR_CRSE_NUMB, SCRATTR_EFF_TERM, SCRATTR_ATTR_CODE, SCRATTR_ACTIVITY_DATE FROM SATURN.SCRATTR");
	
	$insert = $tempMysql->prepare("INSERT INTO SCRATTR (SCRATTR_SUBJ_CODE, SCRATTR_CRSE_NUMB, SCRATTR_EFF_TERM, SCRATTR_ATTR_CODE, SCRATTR_ACTIVITY_DATE) VALUES (:SCRATTR_SUBJ_CODE, :SCRATTR_CRSE_NUMB, :SCRATTR_EFF_TERM, :SCRATTR_ATTR_CODE, :SCRATTR_ACTIVITY_DATE)");
	while($row = oci_fetch_object($SCRATTR)) {
		$insert->bindValue(":SCRATTR_SUBJ_CODE", $row->SCRATTR_SUBJ_CODE);
		$insert->bindValue(":SCRATTR_CRSE_NUMB", $row->SCRATTR_CRSE_NUMB);
		$insert->bindValue(":SCRATTR_EFF_TERM", $row->SCRATTR_EFF_TERM);
		$insert->bindValue(":SCRATTR_ATTR_CODE", $row->SCRATTR_ATTR_CODE);
		$insert->bindValue(":SCRATTR_ACTIVITY_DATE", toMySQLDate($row->SCRATTR_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($SCRATTR);
	print "...\tUpdated SCRATTR\n";

	
	
	// SATURN.SCREQIV
	print "Updating SCREQIV\t";
	$tempMysql->beginTransaction();
	$tSCREQIV = $tempMysql->prepare("TRUNCATE TABLE SCREQIV");
	$tSCREQIV->execute();
	
	$SCREQIV = oci_execute_query($banner, "SELECT SCREQIV_SUBJ_CODE, SCREQIV_CRSE_NUMB, SCREQIV_EFF_TERM, SCREQIV_SUBJ_CODE_EQIV, SCREQIV_CRSE_NUMB_EQIV, SCREQIV_START_TERM, SCREQIV_END_TERM, SCREQIV_ACTIVITY_DATE FROM SATURN.SCREQIV");
	
	$insert = $tempMysql->prepare("INSERT INTO SCREQIV (SCREQIV_SUBJ_CODE, SCREQIV_CRSE_NUMB, SCREQIV_EFF_TERM, SCREQIV_SUBJ_CODE_EQIV, SCREQIV_CRSE_NUMB_EQIV, SCREQIV_START_TERM, SCREQIV_END_TERM, SCREQIV_ACTIVITY_DATE) VALUES (:SCREQIV_SUBJ_CODE, :SCREQIV_CRSE_NUMB, :SCREQIV_EFF_TERM, :SCREQIV_SUBJ_CODE_EQIV, :SCREQIV_CRSE_NUMB_EQIV, :SCREQIV_START_TERM, :SCREQIV_END_TERM, :SCREQIV_ACTIVITY_DATE)");
	while($row = oci_fetch_object($SCREQIV)) {
		$insert->bindValue(":SCREQIV_SUBJ_CODE", $row->SCREQIV_SUBJ_CODE);
		$insert->bindValue(":SCREQIV_CRSE_NUMB", $row->SCREQIV_CRSE_NUMB);
		$insert->bindValue(":SCREQIV_EFF_TERM", $row->SCREQIV_EFF_TERM);
		$insert->bindValue(":SCREQIV_SUBJ_CODE_EQIV", $row->SCREQIV_SUBJ_CODE_EQIV);
		$insert->bindValue(":SCREQIV_CRSE_NUMB_EQIV", $row->SCREQIV_CRSE_NUMB_EQIV);
		$insert->bindValue(":SCREQIV_START_TERM", $row->SCREQIV_START_TERM);
		$insert->bindValue(":SCREQIV_END_TERM", $row->SCREQIV_END_TERM);
		$insert->bindValue(":SCREQIV_ACTIVITY_DATE", toMySQLDate($row->SCREQIV_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($SCREQIV);
	print "...\tUpdated SCREQIV\n";

	
	// SATURN.SCRLEVL
	print "Updating SCRLEVL\t";
	$tempMysql->beginTransaction();
	$tSCRLEVL = $tempMysql->prepare("TRUNCATE TABLE SCRLEVL");
	$tSCRLEVL->execute();
	
	$SCRLEVL = oci_execute_query($banner, "SELECT SCRLEVL_SUBJ_CODE, SCRLEVL_CRSE_NUMB, SCRLEVL_EFF_TERM, SCRLEVL_LEVL_CODE, SCRLEVL_ACTIVITY_DATE FROM SATURN.SCRLEVL");
	
	$insert = $tempMysql->prepare("INSERT INTO SCRLEVL (SCRLEVL_SUBJ_CODE, SCRLEVL_CRSE_NUMB, SCRLEVL_EFF_TERM, SCRLEVL_LEVL_CODE, SCRLEVL_ACTIVITY_DATE) VALUES (:SCRLEVL_SUBJ_CODE, :SCRLEVL_CRSE_NUMB, :SCRLEVL_EFF_TERM, :SCRLEVL_LEVL_CODE, :SCRLEVL_ACTIVITY_DATE)");
	while($row = oci_fetch_object($SCRLEVL)) {
		$insert->bindValue(":SCRLEVL_SUBJ_CODE", $row->SCRLEVL_SUBJ_CODE);
		$insert->bindValue(":SCRLEVL_CRSE_NUMB", $row->SCRLEVL_CRSE_NUMB);
		$insert->bindValue(":SCRLEVL_EFF_TERM", $row->SCRLEVL_EFF_TERM);
		$insert->bindValue(":SCRLEVL_LEVL_CODE", $row->SCRLEVL_LEVL_CODE);
		$insert->bindValue(":SCRLEVL_ACTIVITY_DATE", toMySQLDate($row->SCRLEVL_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($SCRLEVL);
	print "...\tUpdated SCRLEVL\n";

	
	// SATURN.SSBXLST
	print "Updating SSBXLST\t";
	$tempMysql->beginTransaction();
	$tSSBXLST = $tempMysql->prepare("TRUNCATE TABLE SSBXLST");
	$tSSBXLST->execute();
	
	$SSBXLST = oci_execute_query($banner, "SELECT * FROM SATURN.SSBXLST");
	
	$insert = $tempMysql->prepare("INSERT INTO SSBXLST (SSBXLST_TERM_CODE, SSBXLST_XLST_GROUP, SSBXLST_DESC, SSBXLST_MAX_ENRL, SSBXLST_ENRL, SSBXLST_SEATS_AVAIL, SSBXLST_ACTIVITY_DATE) VALUES (:SSBXLST_TERM_CODE, :SSBXLST_XLST_GROUP, :SSBXLST_DESC, :SSBXLST_MAX_ENRL, :SSBXLST_ENRL, :SSBXLST_SEATS_AVAIL, :SSBXLST_ACTIVITY_DATE)");
	while($row = oci_fetch_object($SSBXLST)) {
		$insert->bindValue(":SSBXLST_TERM_CODE", $row->SSBXLST_TERM_CODE);
		$insert->bindValue(":SSBXLST_XLST_GROUP", $row->SSBXLST_XLST_GROUP);
		$insert->bindValue(":SSBXLST_DESC", $row->SSBXLST_DESC);
		$insert->bindValue(":SSBXLST_MAX_ENRL", $row->SSBXLST_MAX_ENRL);
		$insert->bindValue(":SSBXLST_ENRL", $row->SSBXLST_ENRL);
		$insert->bindValue(":SSBXLST_SEATS_AVAIL", $row->SSBXLST_SEATS_AVAIL);
		$insert->bindValue(":SSBXLST_ACTIVITY_DATE", toMySQLDate($row->SSBXLST_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($SSBXLST);
	print "...\tUpdated SSBXLST\n";

	
	// SATURN.SSRXLST
	print "Updating SSRXLST\t";
	$tempMysql->beginTransaction();
	$tSSRXLST = $tempMysql->prepare("TRUNCATE TABLE SSRXLST");
	$tSSRXLST->execute();
	
	$SSRXLST = oci_execute_query($banner, "SELECT * FROM SATURN.SSRXLST");
	
	$insert = $tempMysql->prepare("INSERT INTO SSRXLST (SSRXLST_TERM_CODE, SSRXLST_CRN, SSRXLST_XLST_GROUP, SSRXLST_ACTIVITY_DATE) VALUES (:SSRXLST_TERM_CODE, :SSRXLST_CRN, :SSRXLST_XLST_GROUP, :SSRXLST_ACTIVITY_DATE)");
	while($row = oci_fetch_object($SSRXLST)) {
		$insert->bindValue(":SSRXLST_TERM_CODE", $row->SSRXLST_TERM_CODE);
		$insert->bindValue(":SSRXLST_CRN", $row->SSRXLST_CRN);
		$insert->bindValue(":SSRXLST_XLST_GROUP", $row->SSRXLST_XLST_GROUP);
		$insert->bindValue(":SSRXLST_ACTIVITY_DATE", toMySQLDate($row->SSRXLST_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($SSRXLST);
	print "...\tUpdated SSRXLST\n";

		
	// SATURN.SIRASGN
	print "Updating SIRASGN\t";
	$tempMysql->beginTransaction();
	$tsirasgn = $tempMysql->prepare("TRUNCATE TABLE SIRASGN");
	$tsirasgn->execute();
	
	$sirasgn = oci_execute_query($banner, "SELECT * FROM SATURN.SIRASGN");

	$insert = $tempMysql->prepare("INSERT INTO SIRASGN (SIRASGN_TERM_CODE, SIRASGN_CRN, SIRASGN_PIDM, SIRASGN_CATEGORY, SIRASGN_PERCENT_RESPONSE, SIRASGN_WORKLOAD_ADJUST, SIRASGN_PERCENT_SESS, SIRASGN_PRIMARY_IND, SIRASGN_OVER_RIDE, SIRASGN_POSITION, SIRASGN_ACTIVITY_DATE, SIRASGN_FCNT_CODE, SIRASGN_POSN, SIRASGN_SUFF, SIRASGN_ASTY_CODE, SIRASGN_DATA_ORIGIN, SIRASGN_USER_ID) VALUES (:SIRASGN_TERM_CODE, :SIRASGN_CRN, :SIRASGN_PIDM, :SIRASGN_CATEGORY, :SIRASGN_PERCENT_RESPONSE, :SIRASGN_WORKLOAD_ADJUST, :SIRASGN_PERCENT_SESS, :SIRASGN_PRIMARY_IND, :SIRASGN_OVER_RIDE, :SIRASGN_POSITION, :SIRASGN_ACTIVITY_DATE, :SIRASGN_FCNT_CODE, :SIRASGN_POSN, :SIRASGN_SUFF, :SIRASGN_ASTY_CODE, :SIRASGN_DATA_ORIGIN, :SIRASGN_USER_ID)");
	while($row = oci_fetch_object($sirasgn)) {
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
	
	$tempMysql->commit();
	oci_free_statement($sirasgn);
	print "...\tUpdated SIRASGN\n";

	
	// SATURN.SSBDESC
	print "Updating SSBDESC\t";
	$tempMysql->beginTransaction();
	$tSSBDESC = $tempMysql->prepare("TRUNCATE TABLE SSBDESC");
	$tSSBDESC->execute();
	
	$SSBDESC = oci_execute_query($banner, "SELECT SSBDESC_TERM_CODE, SSBDESC_CRN, SSBDESC_TEXT_NARRATIVE, SSBDESC_ACTIVITY_DATE, SSBDESC_USER_ID FROM SATURN.SSBDESC");
	
	$insert = $tempMysql->prepare("INSERT INTO SSBDESC (SSBDESC_TERM_CODE, SSBDESC_CRN, SSBDESC_TEXT_NARRATIVE, SSBDESC_ACTIVITY_DATE, SSBDESC_USER_ID) VALUES (:SSBDESC_TERM_CODE, :SSBDESC_CRN, :SSBDESC_TEXT_NARRATIVE, :SSBDESC_ACTIVITY_DATE, :SSBDESC_USER_ID)");
	while($row = oci_fetch_object($SSBDESC)) {
		$insert->bindValue(":SSBDESC_TERM_CODE", $row->SSBDESC_TERM_CODE);
		$insert->bindValue(":SSBDESC_CRN", $row->SSBDESC_CRN);
		if (is_null($row->SSBDESC_TEXT_NARRATIVE)) {
			$insert->bindValue(":SSBDESC_TEXT_NARRATIVE", null);
		} else {
			$desc = $row->SSBDESC_TEXT_NARRATIVE->load();
			$insert->bindValue(":SSBDESC_TEXT_NARRATIVE", $desc);
		}
		$insert->bindValue(":SSBDESC_ACTIVITY_DATE", toMySQLDate($row->SSBDESC_ACTIVITY_DATE));
		$insert->bindValue(":SSBDESC_USER_ID", $row->SSBDESC_USER_ID);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($SSBDESC);
	print "...\tUpdated SSBDESC\n";

	
	
	// SATURN.SSBSECT
	print "Updating SSBSECT\t";
	$tempMysql->beginTransaction();
	$tssbsect = $tempMysql->prepare("TRUNCATE TABLE SSBSECT");
	$tssbsect->execute();
	
	$ssbsect = oci_execute_query($banner, "SELECT * FROM SATURN.SSBSECT");
	
	$insert = $tempMysql->prepare("INSERT INTO SSBSECT (SSBSECT_TERM_CODE, SSBSECT_CRN, SSBSECT_PTRM_CODE, SSBSECT_SUBJ_CODE, SSBSECT_CRSE_NUMB, SSBSECT_SEQ_NUMB, SSBSECT_SSTS_CODE, SSBSECT_SCHD_CODE, SSBSECT_CAMP_CODE, SSBSECT_CRSE_TITLE, SSBSECT_CREDIT_HRS, SSBSECT_BILL_HRS, SSBSECT_GMOD_CODE, SSBSECT_SAPR_CODE, SSBSECT_SESS_CODE, SSBSECT_LINK_IDENT, SSBSECT_PRNT_IND, SSBSECT_GRADABLE_IND, SSBSECT_TUIW_IND, SSBSECT_REG_ONEUP, SSBSECT_PRIOR_ENRL, SSBSECT_PROJ_ENRL, SSBSECT_MAX_ENRL, SSBSECT_ENRL, SSBSECT_SEATS_AVAIL, SSBSECT_TOT_CREDIT_HRS, SSBSECT_CENSUS_ENRL, SSBSECT_CENSUS_ENRL_DATE, SSBSECT_ACTIVITY_DATE, SSBSECT_PTRM_START_DATE, SSBSECT_PTRM_END_DATE, SSBSECT_PTRM_WEEKS, SSBSECT_RESERVED_IND, SSBSECT_WAIT_CAPACITY, SSBSECT_WAIT_COUNT, SSBSECT_WAIT_AVAIL, SSBSECT_LEC_HR, SSBSECT_LAB_HR, SSBSECT_OTH_HR, SSBSECT_CONT_HR, SSBSECT_ACCT_CODE, SSBSECT_ACCL_CODE, SSBSECT_CENSUS_2_DATE, SSBSECT_ENRL_CUT_OFF_DATE, SSBSECT_ACAD_CUT_OFF_DATE, SSBSECT_DROP_CUT_OFF_DATE, SSBSECT_CENSUS_2_ENRL, SSBSECT_VOICE_AVAIL, SSBSECT_CAPP_PREREQ_TEST_IND, SSBSECT_GSCH_NAME, SSBSECT_BEST_OF_COMP, SSBSECT_SUBSET_OF_COMP, SSBSECT_INSM_CODE, SSBSECT_REG_FROM_DATE, SSBSECT_REG_TO_DATE, SSBSECT_LEARNER_REGSTART_FDATE, SSBSECT_LEARNER_REGSTART_TDATE, SSBSECT_DUNT_CODE, SSBSECT_NUMBER_OF_UNITS, SSBSECT_NUMBER_OF_EXTENSIONS, SSBSECT_DATA_ORIGIN, SSBSECT_USER_ID, SSBSECT_INTG_CDE) VALUES (:SSBSECT_TERM_CODE, :SSBSECT_CRN, :SSBSECT_PTRM_CODE, :SSBSECT_SUBJ_CODE, :SSBSECT_CRSE_NUMB, :SSBSECT_SEQ_NUMB, :SSBSECT_SSTS_CODE, :SSBSECT_SCHD_CODE, :SSBSECT_CAMP_CODE, :SSBSECT_CRSE_TITLE, :SSBSECT_CREDIT_HRS, :SSBSECT_BILL_HRS, :SSBSECT_GMOD_CODE, :SSBSECT_SAPR_CODE, :SSBSECT_SESS_CODE, :SSBSECT_LINK_IDENT, :SSBSECT_PRNT_IND, :SSBSECT_GRADABLE_IND, :SSBSECT_TUIW_IND, :SSBSECT_REG_ONEUP, :SSBSECT_PRIOR_ENRL, :SSBSECT_PROJ_ENRL, :SSBSECT_MAX_ENRL, :SSBSECT_ENRL, :SSBSECT_SEATS_AVAIL, :SSBSECT_TOT_CREDIT_HRS, :SSBSECT_CENSUS_ENRL, :SSBSECT_CENSUS_ENRL_DATE, :SSBSECT_ACTIVITY_DATE, :SSBSECT_PTRM_START_DATE, :SSBSECT_PTRM_END_DATE, :SSBSECT_PTRM_WEEKS, :SSBSECT_RESERVED_IND, :SSBSECT_WAIT_CAPACITY, :SSBSECT_WAIT_COUNT, :SSBSECT_WAIT_AVAIL, :SSBSECT_LEC_HR, :SSBSECT_LAB_HR, :SSBSECT_OTH_HR, :SSBSECT_CONT_HR, :SSBSECT_ACCT_CODE, :SSBSECT_ACCL_CODE, :SSBSECT_CENSUS_2_DATE, :SSBSECT_ENRL_CUT_OFF_DATE, :SSBSECT_ACAD_CUT_OFF_DATE, :SSBSECT_DROP_CUT_OFF_DATE, :SSBSECT_CENSUS_2_ENRL, :SSBSECT_VOICE_AVAIL, :SSBSECT_CAPP_PREREQ_TEST_IND, :SSBSECT_GSCH_NAME, :SSBSECT_BEST_OF_COMP, :SSBSECT_SUBSET_OF_COMP, :SSBSECT_INSM_CODE, :SSBSECT_REG_FROM_DATE, :SSBSECT_REG_TO_DATE, :SSBSECT_LEARNER_REGSTART_FDATE, :SSBSECT_LEARNER_REGSTART_TDATE, :SSBSECT_DUNT_CODE, :SSBSECT_NUMBER_OF_UNITS, :SSBSECT_NUMBER_OF_EXTENSIONS, :SSBSECT_DATA_ORIGIN, :SSBSECT_USER_ID, :SSBSECT_INTG_CDE)");
	while($row = oci_fetch_object($ssbsect)) {
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
	
	$tempMysql->commit();
	oci_free_statement($ssbsect);
	print "...\tUpdated SSBSECT\n";

	
	
	// SATURN.SSRATTR
	print "Updating SSRATTR\t";
	$tempMysql->beginTransaction();
	$tssrattr = $tempMysql->prepare("TRUNCATE TABLE SSRATTR");
	$tssrattr->execute();
	
	$ssrattr = oci_execute_query($banner, "SELECT * FROM SATURN.SSRATTR");
	
	$insert = $tempMysql->prepare("INSERT INTO SSRATTR (SSRATTR_TERM_CODE, SSRATTR_CRN, SSRATTR_ATTR_CODE, SSRATTR_ACTIVITY_DATE) VALUES (:SSRATTR_TERM_CODE, :SSRATTR_CRN, :SSRATTR_ATTR_CODE, :SSRATTR_ACTIVITY_DATE)");
	while($row = oci_fetch_object($ssrattr)) {
		$insert->bindValue(":SSRATTR_TERM_CODE", $row->SSRATTR_TERM_CODE);
		$insert->bindValue(":SSRATTR_CRN", $row->SSRATTR_CRN);
		$insert->bindValue(":SSRATTR_ATTR_CODE", $row->SSRATTR_ATTR_CODE);
		$insert->bindValue(":SSRATTR_ACTIVITY_DATE", toMySQLDate($row->SSRATTR_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($ssrattr);
	print "...\tUpdated SSRATTR\n";

	
	
	// SATURN.SSRBLCK
	print "Updating SSRBLCK\t";
	$tempMysql->beginTransaction();
	$tssrblck = $tempMysql->prepare("TRUNCATE TABLE SSRBLCK");
	$tssrblck->execute();

	$query = "SELECT * FROM SATURN.SSRBLCK";
	if (count($allowedBlckCodes)) {
		$codes = array();
		foreach ($allowedBlckCodes as $code) {
			$codes[] = "'".$code."'";
		}
		$query .= " WHERE SSRBLCK_BLCK_CODE IN (".implode(', ', $codes).")";
	}
	$ssrblck = oci_execute_query($banner, $query);

	$insert = $tempMysql->prepare("INSERT INTO SSRBLCK (SSRBLCK_TERM_CODE, SSRBLCK_BLCK_CODE, SSRBLCK_CRN, SSRBLCK_CREDIT_HRS, SSRBLCK_BILL_HRS, SSRBLCK_GMOD_CODE, SSRBLCK_APPR_IND, SSRBLCK_ACTIVITY_DATE) VALUES (:SSRBLCK_TERM_CODE, :SSRBLCK_BLCK_CODE, :SSRBLCK_CRN, :SSRBLCK_CREDIT_HRS, :SSRBLCK_BILL_HRS, :SSRBLCK_GMOD_CODE, :SSRBLCK_APPR_IND, :SSRBLCK_ACTIVITY_DATE)");
	while($row = oci_fetch_object($ssrblck)) {
		$insert->bindValue(":SSRBLCK_TERM_CODE", $row->SSRBLCK_TERM_CODE);
		$insert->bindValue(":SSRBLCK_BLCK_CODE", $row->SSRBLCK_BLCK_CODE);
		$insert->bindValue(":SSRBLCK_CRN", $row->SSRBLCK_CRN);
		$insert->bindValue(":SSRBLCK_CREDIT_HRS", $row->SSRBLCK_CREDIT_HRS);
		$insert->bindValue(":SSRBLCK_BILL_HRS", $row->SSRBLCK_BILL_HRS);
		$insert->bindValue(":SSRBLCK_GMOD_CODE", $row->SSRBLCK_GMOD_CODE);
		$insert->bindValue(":SSRBLCK_APPR_IND", $row->SSRBLCK_APPR_IND);
		$insert->bindValue(":SSRBLCK_ACTIVITY_DATE", toMySQLDate($row->SSRBLCK_ACTIVITY_DATE));
		$insert->execute();
	}

	$tempMysql->commit();
	oci_free_statement($ssrblck);
	print "...\tUpdated SSRBLCK\n";



	// SATURN.SSRMEET
	print "Updating SSRMEET\t";
	$tempMysql->beginTransaction();
	$tssrmeet = $tempMysql->prepare("TRUNCATE TABLE SSRMEET");
	$tssrmeet->execute();
	
	$ssrmeet = oci_execute_query($banner, "SELECT * FROM SATURN.SSRMEET");

	$insert = $tempMysql->prepare("INSERT INTO SSRMEET (SSRMEET_TERM_CODE, SSRMEET_CRN, SSRMEET_DAYS_CODE, SSRMEET_DAY_NUMBER, SSRMEET_BEGIN_TIME, SSRMEET_END_TIME, SSRMEET_BLDG_CODE, SSRMEET_ROOM_CODE, SSRMEET_ACTIVITY_DATE, SSRMEET_START_DATE, SSRMEET_END_DATE, SSRMEET_CATAGORY, SSRMEET_SUN_DAY, SSRMEET_MON_DAY, SSRMEET_TUE_DAY, SSRMEET_WED_DAY, SSRMEET_THU_DAY, SSRMEET_FRI_DAY, SSRMEET_SAT_DAY, SSRMEET_SCHD_CODE, SSRMEET_OVER_RIDE, SSRMEET_CREDIT_HR_SESS, SSRMEET_MEET_NO, SSRMEET_HRS_WEEK, SSRMEET_FUNC_CODE, SSRMEET_COMT_CODE, SSRMEET_SCHS_CODE, SSRMEET_MTYP_CODE, SSRMEET_DATA_ORIGIN, SSRMEET_USER_ID) VALUES (:SSRMEET_TERM_CODE, :SSRMEET_CRN, :SSRMEET_DAYS_CODE, :SSRMEET_DAY_NUMBER, :SSRMEET_BEGIN_TIME, :SSRMEET_END_TIME, :SSRMEET_BLDG_CODE, :SSRMEET_ROOM_CODE, :SSRMEET_ACTIVITY_DATE, :SSRMEET_START_DATE, :SSRMEET_END_DATE, :SSRMEET_CATAGORY, :SSRMEET_SUN_DAY, :SSRMEET_MON_DAY, :SSRMEET_TUE_DAY, :SSRMEET_WED_DAY, :SSRMEET_THU_DAY, :SSRMEET_FRI_DAY, :SSRMEET_SAT_DAY, :SSRMEET_SCHD_CODE, :SSRMEET_OVER_RIDE, :SSRMEET_CREDIT_HR_SESS, :SSRMEET_MEET_NO, :SSRMEET_HRS_WEEK, :SSRMEET_FUNC_CODE, :SSRMEET_COMT_CODE, :SSRMEET_SCHS_CODE, :SSRMEET_MTYP_CODE, :SSRMEET_DATA_ORIGIN, :SSRMEET_USER_ID)");
	while($row = oci_fetch_object($ssrmeet)) {
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
	
	$tempMysql->commit();
	oci_free_statement($ssrmeet);
	print "...\tUpdated SSRMEET\n";

	
	
	// SATURN.STVACYR
	print "Updating STVACYR\t";
	$tempMysql->beginTransaction();
	$tstvacyr = $tempMysql->prepare("TRUNCATE TABLE STVACYR");
	$tstvacyr->execute();
	
	$stvacyr = oci_execute_query($banner, "SELECT * FROM SATURN.STVACYR");
	
	$insert = $tempMysql->prepare("INSERT INTO STVACYR (STVACYR_CODE, STVACYR_DESC, STVACYR_ACTIVITY_DATE, STVACYR_SYSREQ_IND) VALUES (:STVACYR_CODE, :STVACYR_DESC, :STVACYR_ACTIVITY_DATE, :STVACYR_SYSREQ_IND)");
	while($row = oci_fetch_object($stvacyr)) {
		$insert->bindValue(":STVACYR_CODE", $row->STVACYR_CODE);
		$insert->bindValue(":STVACYR_DESC", $row->STVACYR_DESC);
		$insert->bindValue(":STVACYR_ACTIVITY_DATE", toMySQLDate($row->STVACYR_ACTIVITY_DATE));
		$insert->bindValue(":STVACYR_SYSREQ_IND", $row->STVACYR_SYSREQ_IND);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvacyr);
	print "...\tUpdated STVACYR\n";

		
	
	// SATURN.STVAPRV
	print "Updating STVAPRV\t";
	$tempMysql->beginTransaction();
	$tstvaprv = $tempMysql->prepare("TRUNCATE TABLE STVAPRV");
	$tstvaprv->execute();
	
	$stvaprv = oci_execute_query($banner, "SELECT * FROM SATURN.STVAPRV");

	$insert = $tempMysql->prepare("INSERT INTO STVAPRV (STVAPRV_CODE, STVAPRV_DESC, STVAPRV_ACTIVITY_DATE) VALUES (:STVAPRV_CODE, :STVAPRV_DESC, :STVAPRV_ACTIVITY_DATE)");
	while($row = oci_fetch_object($stvaprv)) {
		$insert->bindValue(":STVAPRV_CODE", $row->STVAPRV_CODE);
		$insert->bindValue(":STVAPRV_DESC", $row->STVAPRV_DESC);
		$insert->bindValue(":STVAPRV_ACTIVITY_DATE", toMySQLDate($row->STVAPRV_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvaprv);
	print "...\tUpdated STVAPRV\n";

		
	
	// SATURN.STVASTY
	print "Updating STVASTY\t";
	$tempMysql->beginTransaction();
	$tstvasty = $tempMysql->prepare("TRUNCATE TABLE STVASTY");
	$tstvasty->execute();
	
	$stvasty = oci_execute_query($banner, "SELECT * FROM SATURN.STVASTY");

	$insert = $tempMysql->prepare("INSERT INTO STVASTY (STVASTY_CODE, STVASTY_DESC, STVASTY_ACTIVITY_DATE) VALUES (:STVASTY_CODE, :STVASTY_DESC, :STVASTY_ACTIVITY_DATE)");
	while($row = oci_fetch_object($stvasty)) {
		$insert->bindValue(":STVASTY_CODE", $row->STVASTY_CODE);
		$insert->bindValue(":STVASTY_DESC", $row->STVASTY_DESC);
		$insert->bindValue(":STVASTY_ACTIVITY_DATE", toMySQLDate($row->STVASTY_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvasty);
	print "...\tUpdated STVASTY\n";

		
	
	// SATURN.STVATTR
	print "Updating STVATTR\t";
	$tempMysql->beginTransaction();
	$tstvattr = $tempMysql->prepare("TRUNCATE TABLE STVATTR");
	$tstvattr->execute();
	
	$stvattr = oci_execute_query($banner, "SELECT * FROM SATURN.STVATTR");
	
	$insert = $tempMysql->prepare("INSERT INTO STVATTR (STVATTR_CODE, STVATTR_DESC, STVATTR_ACTIVITY_DATE) VALUES (:STVATTR_CODE, :STVATTR_DESC, :STVATTR_ACTIVITY_DATE)");
	while($row = oci_fetch_object($stvattr)) {
		$insert->bindValue(":STVATTR_CODE", $row->STVATTR_CODE);
		$insert->bindValue(":STVATTR_DESC", $row->STVATTR_DESC);
		$insert->bindValue(":STVATTR_ACTIVITY_DATE", $row->STVATTR_ACTIVITY_DATE);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvattr);
	print "...\tUpdated STVATTR\n";

		
	
	// SATURN.STVBLCK
	print "Updating STVBLCK\t";
	$tempMysql->beginTransaction();
	$tstvblck = $tempMysql->prepare("TRUNCATE TABLE STVBLCK");
	$tstvblck->execute();

	$query = "SELECT * FROM SATURN.STVBLCK";
	if (count($allowedBlckCodes)) {
		$codes = array();
		foreach ($allowedBlckCodes as $code) {
			$codes[] = "'".$code."'";
		}
		$query .= " WHERE STVBLCK_CODE IN (".implode(', ', $codes).")";
	}
	$stvblck = oci_execute_query($banner, $query);

	$insert = $tempMysql->prepare("INSERT INTO STVBLCK (STVBLCK_CODE, STVBLCK_DESC, STVBLCK_ACTIVITY_DATE) VALUES (:STVBLCK_CODE, :STVBLCK_DESC, :STVBLCK_ACTIVITY_DATE)");
	while($row = oci_fetch_object($stvblck)) {
		$insert->bindValue(":STVBLCK_CODE", $row->STVBLCK_CODE);
		$insert->bindValue(":STVBLCK_DESC", $row->STVBLCK_DESC);
		$insert->bindValue(":STVBLCK_ACTIVITY_DATE", $row->STVBLCK_ACTIVITY_DATE);
		$insert->execute();
	}

	$tempMysql->commit();
	oci_free_statement($stvblck);
	print "...\tUpdated STVBLCK\n";



	// SATURN.STVBLDG
	print "Updating STVBLDG\t";
	$tempMysql->beginTransaction();
	$tstvbldg = $tempMysql->prepare("TRUNCATE TABLE STVBLDG");
	$tstvbldg->execute();
	
	$stvbldg = oci_execute_query($banner, "SELECT * FROM SATURN.STVBLDG");
	
	$insert = $tempMysql->prepare("INSERT INTO STVBLDG (STVBLDG_CODE, STVBLDG_DESC, STVBLDG_ACTIVITY_DATE, STVBLDG_VR_MSG_NO) VALUES (:STVBLDG_CODE, :STVBLDG_DESC, :STVBLDG_ACTIVITY_DATE, :STVBLDG_VR_MSG_NO)");
	while($row = oci_fetch_object($stvbldg)) {
		$insert->bindValue(":STVBLDG_CODE", $row->STVBLDG_CODE);
		$insert->bindValue(":STVBLDG_DESC", $row->STVBLDG_DESC);
		$insert->bindValue(":STVBLDG_ACTIVITY_DATE", toMySQLDate($row->STVBLDG_ACTIVITY_DATE));
		$insert->bindValue(":STVBLDG_VR_MSG_NO", $row->STVBLDG_VR_MSG_NO);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvbldg);
	print "...\tUpdated STVBLDG\n";


	
	// SATURN.STVCAMP
	print "Updating STVCAMP\t";
	$tempMysql->beginTransaction();
	$tstvcamp = $tempMysql->prepare("TRUNCATE TABLE STVCAMP");
	$tstvcamp->execute();
	
	$stvcamp = oci_execute_query($banner, "SELECT * FROM SATURN.STVCAMP");
	
	$insert = $tempMysql->prepare("INSERT INTO STVCAMP (STVCAMP_CODE, STVCAMP_DESC, STVCAMP_ACTIVITY_DATE, STVCAMP_DICD_CODE) VALUES (:STVCAMP_CODE, :STVCAMP_DESC, :STVCAMP_ACTIVITY_DATE, :STVCAMP_DICD_CODE)");
	while($row = oci_fetch_object($stvcamp)) {
		$insert->bindValue(":STVCAMP_CODE", $row->STVCAMP_CODE);
		$insert->bindValue(":STVCAMP_DESC", $row->STVCAMP_DESC);
		$insert->bindValue(":STVCAMP_ACTIVITY_DATE", toMySQLDate($row->STVCAMP_ACTIVITY_DATE));
		$insert->bindValue(":STVCAMP_DICD_CODE", $row->STVCAMP_DICD_CODE);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvcamp);
	print "...\tUpdated STVCAMP\n";

		
	
	// SATURN.STVCIPC
	print "Updating STVCIPC\t";
	$tempMysql->beginTransaction();
	$tstvcipc = $tempMysql->prepare("TRUNCATE TABLE STVCIPC");
	$tstvcipc->execute();
	
	$stvcipc = oci_execute_query($banner, "SELECT * FROM SATURN.STVCIPC");
	
	$insert = $tempMysql->prepare("INSERT INTO STVCIPC (STVCIPC_CODE, STVCIPC_DESC, STVCIPC_ACTIVITY_DATE, STVCIPC_CIPC_A_IND, STVCIPC_CIPC_B_IND, STVCIPC_CIPC_C_IND, STVCIPC_SP04_PROGRAM_CDE) VALUES (:STVCIPC_CODE, :STVCIPC_DESC, :STVCIPC_ACTIVITY_DATE, :STVCIPC_CIPC_A_IND, :STVCIPC_CIPC_B_IND, :STVCIPC_CIPC_C_IND, :STVCIPC_SP04_PROGRAM_CDE)");
	while($row = oci_fetch_object($stvcipc)) {
		$insert->bindValue(":STVCIPC_CODE", $row->STVCIPC_CODE);
		$insert->bindValue(":STVCIPC_DESC", $row->STVCIPC_DESC);
		$insert->bindValue(":STVCIPC_ACTIVITY_DATE", toMySQLDate($row->STVCIPC_ACTIVITY_DATE));
		$insert->bindValue(":STVCIPC_CIPC_A_IND", $row->STVCIPC_CIPC_A_IND);
		$insert->bindValue(":STVCIPC_CIPC_B_IND", $row->STVCIPC_CIPC_B_IND);
		$insert->bindValue(":STVCIPC_CIPC_C_IND", $row->STVCIPC_CIPC_C_IND);
		$insert->bindValue(":STVCIPC_SP04_PROGRAM_CDE", $row->STVCIPC_SP04_PROGRAM_CDE);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvcipc);
	print "...\tUpdated STVCIPC\n";

		
	
	// SATURN.STVCOLL
	print "Updating STVCOLL\t";
	$tempMysql->beginTransaction();
	$tstvcoll = $tempMysql->prepare("TRUNCATE TABLE STVCOLL");
	$tstvcoll->execute();
	
	$stvcoll = oci_execute_query($banner, "SELECT * FROM SATURN.STVCOLL");
	
	$insert = $tempMysql->prepare("INSERT INTO STVCOLL (STVCOLL_CODE, STVCOLL_DESC, STVCOLL_ADDR_STREET_LINE1, STVCOLL_ADDR_STREET_LINE2, STVCOLL_ADDR_STREET_LINE3, STVCOLL_ADDR_CITY, STVCOLL_ADDR_STATE, STVCOLL_ADDR_COUNTRY, STVCOLL_ADDR_ZIP_CODE, STVCOLL_ACTIVITY_DATE, STVCOLL_SYSTEM_REQ_IND, STVCOLL_VR_MSG_NO, STVCOLL_STATSCAN_CDE3, STVCOLL_DICD_CODE) VALUES (:STVCOLL_CODE, :STVCOLL_DESC, :STVCOLL_ADDR_STREET_LINE1, :STVCOLL_ADDR_STREET_LINE2, :STVCOLL_ADDR_STREET_LINE3, :STVCOLL_ADDR_CITY, :STVCOLL_ADDR_STATE, :STVCOLL_ADDR_COUNTRY, :STVCOLL_ADDR_ZIP_CODE, :STVCOLL_ACTIVITY_DATE, :STVCOLL_SYSTEM_REQ_IND, :STVCOLL_VR_MSG_NO, :STVCOLL_STATSCAN_CDE3, :STVCOLL_DICD_CODE)");
	while($row = oci_fetch_object($stvcoll)) {
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
	
	$tempMysql->commit();
	oci_free_statement($stvcoll);
	print "...\tUpdated STVCOLL\n";

	
	
	// SATURN.STVCOMT
	print "Updating STVCOMT\t";
	$tempMysql->beginTransaction();
	$tstvcomt = $tempMysql->prepare("TRUNCATE TABLE STVCOMT");
	$tstvcomt->execute();
	
	$stvcomt = oci_execute_query($banner, "SELECT * FROM SATURN.STVCOMT");
	
	$insert = $tempMysql->prepare("INSERT INTO STVCOMT (STVCOMT_CODE, STVCOMT_DESC, STVCOMT_TRANS_PRINT, STVCOMT_ACTIVITY_DATE) VALUES (:STVCOMT_CODE, :STVCOMT_DESC, :STVCOMT_TRANS_PRINT, :STVCOMT_ACTIVITY_DATE)");
	while($row = oci_fetch_object($stvcomt)) {
		$insert->bindValue(":STVCOMT_CODE", $row->STVCOMT_CODE);
		$insert->bindValue(":STVCOMT_DESC", $row->STVCOMT_DESC);
		$insert->bindValue(":STVCOMT_TRANS_PRINT", $row->STVCOMT_TRANS_PRINT);
		$insert->bindValue(":STVCOMT_ACTIVITY_DATE", toMySQLDate($row->STVCOMT_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvcomt);
	print "...\tUpdated STVCOMT\n";

		
	
	// SATURN.STVCSTA
	print "Updating STVCSTA\t";
	$tempMysql->beginTransaction();
	$tstvcsta = $tempMysql->prepare("TRUNCATE TABLE STVCSTA");
	$tstvcsta->execute();
	
	$stvcsta = oci_execute_query($banner, "SELECT * FROM SATURN.STVCSTA");

	$insert = $tempMysql->prepare("INSERT INTO STVCSTA (STVCSTA_CODE, STVCSTA_DESC, STVCSTA_ACTIVITY_DATE, STVCSTA_ACTIVE_IND) VALUES (:STVCSTA_CODE, :STVCSTA_DESC, :STVCSTA_ACTIVITY_DATE, :STVCSTA_ACTIVE_IND)");	
	while($row = oci_fetch_object($stvcsta)) {
		$insert->bindValue(":STVCSTA_CODE", $row->STVCSTA_CODE);
		$insert->bindValue(":STVCSTA_DESC", $row->STVCSTA_DESC);
		$insert->bindValue(":STVCSTA_ACTIVITY_DATE", toMySQLDate($row->STVCSTA_ACTIVITY_DATE));
		$insert->bindValue(":STVCSTA_ACTIVE_IND", $row->STVCSTA_ACTIVE_IND);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvcsta);
	print "...\tUpdated STVCSTA\n";

		
	
	// SATURN.STVDEPT
	print "Updating STVDEPT\t";
	$tempMysql->beginTransaction();
	$tstvdept = $tempMysql->prepare("TRUNCATE TABLE STVDEPT");
	$tstvdept->execute();
	
	$stvdept = oci_execute_query($banner, "SELECT * FROM SATURN.STVDEPT");

	$insert = $tempMysql->prepare("INSERT INTO STVDEPT (STVDEPT_CODE, STVDEPT_DESC, STVDEPT_ACTIVITY_DATE, STVDEPT_SYSTEM_REQ_IND, STVDEPT_VR_MSG_NO) VALUES (:STVDEPT_CODE, :STVDEPT_DESC, :STVDEPT_ACTIVITY_DATE, :STVDEPT_SYSTEM_REQ_IND, :STVDEPT_VR_MSG_NO)");
	while($row = oci_fetch_object($stvdept)) {
		$insert->bindValue(":STVDEPT_CODE", $row->STVDEPT_CODE);
		$insert->bindValue(":STVDEPT_DESC", $row->STVDEPT_DESC);
		$insert->bindValue(":STVDEPT_ACTIVITY_DATE", $row->STVDEPT_ACTIVITY_DATE);
		$insert->bindValue(":STVDEPT_SYSTEM_REQ_IND", toMySQLDate($row->STVDEPT_SYSTEM_REQ_IND));
		$insert->bindValue(":STVDEPT_VR_MSG_NO", $row->STVDEPT_VR_MSG_NO);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvdept);
	print "...\tUpdated STVDEPT\n";

		
	
	// SATURN.STVDIVS
	print "Updating STVDIVS\t";
	$tempMysql->beginTransaction();
	$tstvdivs = $tempMysql->prepare("TRUNCATE TABLE STVDIVS");
	$tstvdivs->execute();
	
	$stvdivs = oci_execute_query($banner, "SELECT * FROM SATURN.STVDIVS");
	
	$insert = $tempMysql->prepare("INSERT INTO STVDIVS (STVDIVS_CODE, STVDIVS_DESC, STVDIVS_ACTIVITY_DATE) VALUES (:STVDIVS_CODE, :STVDIVS_DESC, :STVDIVS_ACTIVITY_DATE)");
	while($row = oci_fetch_object($stvdivs)) {
		$insert->bindValue(":STVDIVS_CODE", $row->STVDIVS_CODE);
		$insert->bindValue(":STVDIVS_DESC", $row->STVDIVS_DESC);
		$insert->bindValue(":STVDIVS_ACTIVITY_DATE", toMySQLDate($row->STVDIVS_ACTIVITY_DATE));
		$insert->execute();
	}

	$tempMysql->commit();
	oci_free_statement($stvdivs);
	print "...\tUpdated STVDIVS\n";

		
		
	
	// SATURN.STVFCNT
	print "Updating STVFCNT\t";
	$tempMysql->beginTransaction();
	$tstvfcnt = $tempMysql->prepare("TRUNCATE TABLE STVFCNT");
	$tstvfcnt->execute();
	
	$stvfcnt = oci_execute_query($banner, "SELECT * FROM SATURN.STVFCNT");
	
	$insert = $tempMysql->prepare("INSERT INTO STVFCNT (STVFCNT_CODE, STVFCNT_DESC, STVFCNT_ACTIVITY_DATE) VALUES (:STVFCNT_CODE, :STVFCNT_DESC, :STVFCNT_ACTIVITY_DATE)");
	while($row = oci_fetch_object($stvfcnt)) {
		$insert->bindValue(":STVFCNT_CODE", $row->STVFCNT_CODE);
		$insert->bindValue(":STVFCNT_DESC", $row->STVFCNT_DESC);
		$insert->bindValue(":STVFCNT_ACTIVITY_DATE", toMySQLDate($row->STVFCNT_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvfcnt);
	print "...\tUpdated STVFCNT\n";

	
	// SATURN.STVLEVL
	print "Updating STVLEVL\t";
	$tempMysql->beginTransaction();
	$tstvlevl = $tempMysql->prepare("TRUNCATE TABLE STVLEVL");
	$tstvlevl->execute();
	
	$stvlevl = oci_execute_query($banner, "SELECT * FROM SATURN.STVLEVL");
	
	$insert = $tempMysql->prepare("INSERT INTO STVLEVL (STVLEVL_CODE, STVLEVL_DESC, STVLEVL_ACTIVITY_DATE, STVLEVL_ACAD_IND, STVLEVL_CEU_IND, STVLEVL_SYSTEM_REQ_IND, STVLEVL_VR_MSG_NO, STVLEVL_EDI_EQUIV) VALUES (:STVLEVL_CODE, :STVLEVL_DESC, :STVLEVL_ACTIVITY_DATE, :STVLEVL_ACAD_IND, :STVLEVL_CEU_IND, :STVLEVL_SYSTEM_REQ_IND, :STVLEVL_VR_MSG_NO, :STVLEVL_EDI_EQUIV)");
	while($row = oci_fetch_object($stvlevl)) {
		$insert->bindValue(":STVLEVL_CODE", $row->STVLEVL_CODE);
		$insert->bindValue(":STVLEVL_DESC", $row->STVLEVL_DESC);
		$insert->bindValue(":STVLEVL_ACTIVITY_DATE", toMySQLDate($row->STVLEVL_ACTIVITY_DATE));
		$insert->bindValue(":STVLEVL_ACAD_IND", $row->STVLEVL_ACAD_IND);
		$insert->bindValue(":STVLEVL_CEU_IND", $row->STVLEVL_CEU_IND);
		$insert->bindValue(":STVLEVL_SYSTEM_REQ_IND", $row->STVLEVL_SYSTEM_REQ_IND);
		$insert->bindValue(":STVLEVL_VR_MSG_NO", $row->STVLEVL_VR_MSG_NO);
		$insert->bindValue(":STVLEVL_EDI_EQUIV", $row->STVLEVL_EDI_EQUIV);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvlevl);
	print "...\tUpdated STVLEVL\n";

	
	// SATURN.STVMEET
	print "Updating STVMEET\t";
	$tempMysql->beginTransaction();
	$tSTVMEET = $tempMysql->prepare("TRUNCATE TABLE STVMEET");
	$tSTVMEET->execute();
	
	$STVMEET = oci_execute_query($banner, "SELECT * FROM SATURN.STVMEET");
	
	$insert = $tempMysql->prepare("INSERT INTO STVMEET (STVMEET_CODE, STVMEET_MON_DAY, STVMEET_TUE_DAY, STVMEET_WED_DAY, STVMEET_THU_DAY, STVMEET_FRI_DAY, STVMEET_SAT_DAY, STVMEET_SUN_DAY, STVMEET_BEGIN_TIME, STVMEET_END_TIME, STVMEET_ACTIVITY_DATE) VALUES (:STVMEET_CODE, :STVMEET_MON_DAY, :STVMEET_TUE_DAY, :STVMEET_WED_DAY, :STVMEET_THU_DAY, :STVMEET_FRI_DAY, :STVMEET_SAT_DAY, :STVMEET_SUN_DAY, :STVMEET_BEGIN_TIME, :STVMEET_END_TIME, :STVMEET_ACTIVITY_DATE)");
	while($row = oci_fetch_object($STVMEET)) {
		$insert->bindValue(":STVMEET_CODE", $row->STVMEET_CODE);
		$insert->bindValue(":STVMEET_MON_DAY", $row->STVMEET_MON_DAY);
		$insert->bindValue(":STVMEET_TUE_DAY", $row->STVMEET_TUE_DAY);
		$insert->bindValue(":STVMEET_WED_DAY", $row->STVMEET_WED_DAY);
		$insert->bindValue(":STVMEET_THU_DAY", $row->STVMEET_THU_DAY);
		$insert->bindValue(":STVMEET_FRI_DAY", $row->STVMEET_FRI_DAY);
		$insert->bindValue(":STVMEET_SAT_DAY", $row->STVMEET_SAT_DAY);
		$insert->bindValue(":STVMEET_SUN_DAY", $row->STVMEET_SUN_DAY);
		$insert->bindValue(":STVMEET_BEGIN_TIME", $row->STVMEET_BEGIN_TIME);
		$insert->bindValue(":STVMEET_END_TIME", $row->STVMEET_END_TIME);
		$insert->bindValue(":STVMEET_ACTIVITY_DATE", toMySQLDate($row->STVMEET_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($STVMEET);
	print "...\tUpdated STVMEET\n";

		
	
	// SATURN.STVPWAV
	print "Updating STVPWAV\t";
	$tempMysql->beginTransaction();
	$tstvpwav = $tempMysql->prepare("TRUNCATE TABLE STVPWAV");
	$tstvpwav->execute();
	
	$stvpwav = oci_execute_query($banner, "SELECT * FROM SATURN.STVPWAV");

	$insert = $tempMysql->prepare("INSERT INTO STVPWAV (STVPWAV_CODE, STVPWAV_DESC, STVPWAV_ACTIVITY_DATE) VALUES (:STVPWAV_CODE, :STVPWAV_DESC, :STVPWAV_ACTIVITY_DATE)");
	while($row = oci_fetch_object($stvpwav)) {
		$insert->bindValue(":STVPWAV_CODE", $row->STVPWAV_CODE);
		$insert->bindValue(":STVPWAV_DESC", $row->STVPWAV_DESC);
		$insert->bindValue(":STVPWAV_ACTIVITY_DATE", toMySQLDate($row->STVPWAV_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvpwav);
	print "...\tUpdated STVPWAV\n";

		
	
	// SATURN.STVREPS
	print "Updating STVREPS\t";
	$tempMysql->beginTransaction();
	$tstvreps = $tempMysql->prepare("TRUNCATE TABLE STVREPS");
	$tstvreps->execute();
	
	$stvreps = oci_execute_query($banner, "SELECT * FROM SATURN.STVREPS");
	
	$insert = $tempMysql->prepare("INSERT INTO STVREPS (STVREPS_CODE, STVREPS_DESC, STVREPS_ACTIVITY_DATE) VALUES (:STVREPS_CODE, :STVREPS_DESC, :STVREPS_ACTIVITY_DATE)");
	while($row = oci_fetch_object($stvreps)) {
		$insert->bindValue(":STVREPS_CODE", $row->STVREPS_CODE);
		$insert->bindValue(":STVREPS_DESC", $row->STVREPS_DESC);
		$insert->bindValue(":STVREPS_ACTIVITY_DATE", toMySQLDate($row->STVREPS_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvreps);
	print "...\tUpdated STVREPS\n";

		
	
	// SATURN.STVSCHD
	print "Updating STVSCHD\t";
	$tempMysql->beginTransaction();
	$tstvschd = $tempMysql->prepare("TRUNCATE TABLE STVSCHD");
	$tstvschd->execute();
	
	$stvschd = oci_execute_query($banner, "SELECT * FROM STVSCHD");
	
	$insert = $tempMysql->prepare("INSERT INTO STVSCHD (STVSCHD_CODE, STVSCHD_DESC, STVSCHD_ACTIVITY_DATE, STVSCHD_INSTRUCT_METHOD, STVSCHD_COOP_IND, STVSCHD_AUTO_SCHEDULER_IND, STVSCHD_INSM_CODE, STVSCHD_VR_MSG_NO) VALUES (:STVSCHD_CODE, :STVSCHD_DESC, :STVSCHD_ACTIVITY_DATE, :STVSCHD_INSTRUCT_METHOD, :STVSCHD_COOP_IND, :STVSCHD_AUTO_SCHEDULER_IND, :STVSCHD_INSM_CODE, :STVSCHD_VR_MSG_NO)");
	while($row = oci_fetch_object($stvschd)) {
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
	
	$tempMysql->commit();
	oci_free_statement($stvschd);
	print "...\tUpdated STVSCHD\n";

		
	
	// SATURN.STVSUBJ
	print "Updating STVSUBJ\t";
	$tempMysql->beginTransaction();
	$tstvsubj = $tempMysql->prepare("TRUNCATE TABLE STVSUBJ");
	$tstvsubj->execute();
	
	$stvsubj = oci_execute_query($banner, "SELECT * FROM STVSUBJ");
	
	$insert = $tempMysql->prepare("INSERT INTO STVSUBJ (STVSUBJ_CODE, STVSUBJ_DESC, STVSUBJ_ACTIVITY_DATE, STVSUBJ_VR_MSG_NO, STVSUBJ_DISP_WEB_IND) VALUES (:STVSUBJ_CODE, :STVSUBJ_DESC, :STVSUBJ_ACTIVITY_DATE, :STVSUBJ_VR_MSG_NO, :STVSUBJ_DISP_WEB_IND)");
	while($row = oci_fetch_object($stvsubj)) {
		$insert->bindValue(":STVSUBJ_CODE", $row->STVSUBJ_CODE);
		$insert->bindValue(":STVSUBJ_DESC", $row->STVSUBJ_DESC);
		$insert->bindValue(":STVSUBJ_ACTIVITY_DATE", toMySQLDate($row->STVSUBJ_ACTIVITY_DATE));
		$insert->bindValue(":STVSUBJ_VR_MSG_NO", $row->STVSUBJ_VR_MSG_NO);
		$insert->bindValue(":STVSUBJ_DISP_WEB_IND", $row->STVSUBJ_DISP_WEB_IND);
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvsubj);
	print "...\tUpdated STVSUBJ\n";

		
	
	// SATURN.STVTERM
	print "Updating STVTERM\t";
	$tempMysql->beginTransaction();
	$tstvterm = $tempMysql->prepare("TRUNCATE TABLE STVTERM");
	$tstvterm->execute();
	
	$stvterm = oci_execute_query($banner, "SELECT * FROM STVTERM");
	
	$insert = $tempMysql->prepare("INSERT INTO STVTERM (STVTERM_CODE, STVTERM_DESC, STVTERM_START_DATE, STVTERM_END_DATE, STVTERM_FA_PROC_YR, STVTERM_ACTIVITY_DATE, STVTERM_FA_TERM, STVTERM_FA_PERIOD, STVTERM_FA_END_PERIOD, STVTERM_ACYR_CODE, STVTERM_HOUSING_START_DATE, STVTERM_HOUSING_END_DATE, STVTERM_SYSTEM_REQ_IND, STVTERM_TRMT_CODE) VALUES (:STVTERM_CODE, :STVTERM_DESC, :STVTERM_START_DATE, :STVTERM_END_DATE, :STVTERM_FA_PROC_YR, :STVTERM_ACTIVITY_DATE, :STVTERM_FA_TERM, :STVTERM_FA_PERIOD, :STVTERM_FA_END_PERIOD, :STVTERM_ACYR_CODE, :STVTERM_HOUSING_START_DATE, :STVTERM_HOUSING_END_DATE, :STVTERM_SYSTEM_REQ_IND, :STVTERM_TRMT_CODE)");
	while($row = oci_fetch_object($stvterm)) {
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
	
	$tempMysql->commit();
	oci_free_statement($stvterm);
	print "...\tUpdated STVTERM\n";

		
	
	// SATURN.STVTRMT
	print "Updating STVTRMT\t";
	$tempMysql->beginTransaction();
	$tstvtrmt = $tempMysql->prepare("TRUNCATE TABLE STVTRMT");
	$tstvtrmt->execute();
	
	$stvtrmt = oci_execute_query($banner, "SELECT * FROM STVTRMT");
	
	$insert = $tempMysql->prepare("INSERT INTO STVTRMT (STVTRMT_CODE, STVTRMT_DESC, STVTRMT_ACTIVITY_DATE) VALUES (:STVTRMT_CODE, :STVTRMT_DESC, :STVTRMT_ACTIVITY_DATE)");
	while($row = oci_fetch_object($stvtrmt)) {
		$insert->bindValue(":STVTRMT_CODE", $row->STVTRMT_CODE);
		$insert->bindValue(":STVTRMT_DESC", $row->STVTRMT_DESC);
		$insert->bindValue(":STVTRMT_ACTIVITY_DATE", toMySQLDate($row->STVTRMT_ACTIVITY_DATE));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($stvtrmt);
	print "...\tUpdated STVTRMT\n";

		
	
	// SATURN_MIDD.SYVINST
	print "Updating SYVINST\t";
	$tempMysql->beginTransaction();
	$tsyvinst = $tempMysql->prepare("TRUNCATE TABLE SYVINST");
	$tsyvinst->execute();
	
	$syvinst = oci_execute_query($banner, "SELECT * FROM SATURN_MIDD.SYVINST");
	
	$insert = $tempMysql->prepare("INSERT INTO SYVINST (SYVINST_TERM_CODE, SYVINST_CRN, SYVINST_PIDM, SYVINST_LAST_NAME, SYVINST_FIRST_NAME, WEB_ID) VALUES (:SYVINST_TERM_CODE, :SYVINST_CRN, :SYVINST_PIDM, :SYVINST_LAST_NAME, :SYVINST_FIRST_NAME, :WEB_ID)");
	while($row = oci_fetch_object($syvinst)) {
		$insert->bindValue(":SYVINST_TERM_CODE", $row->SYVINST_TERM_CODE);
		$insert->bindValue(":SYVINST_CRN", $row->SYVINST_CRN);
		$insert->bindValue(":SYVINST_PIDM", $row->SYVINST_PIDM);
		$insert->bindValue(":SYVINST_LAST_NAME", $row->SYVINST_LAST_NAME);
		$insert->bindValue(":SYVINST_FIRST_NAME", $row->SYVINST_FIRST_NAME);
		$insert->bindValue(":WEB_ID", bin2hex($row->WEB_ID));
		$insert->execute();
	}
	
	$tempMysql->commit();
	oci_free_statement($syvinst);
	print "...\tUpdated SYVINST\n";

	
	
	/*********************************************************
	 * Copy the temporary tables into our primary database
	 *********************************************************/
	// If we haven't had any problems updating from banner, import into our primary database
	$command = $mysqldump.' --add-drop-table --single-transaction '
		.' -h '.escapeshellarg($tempMysqlHost)
		.' -u '.escapeshellarg($tempMysqlUser)
		.' -p'.escapeshellarg($tempMysqlPassword)
		.' '.escapeshellarg($tempMysqlDatabase)
		.' '.implode(' ', $bannerTables)
		.' | mysql'
		.' -h '.escapeshellarg($primaryMysqlHost)
		.' -u '.escapeshellarg($primaryMysqlUser)
		.' -p'.escapeshellarg($primaryMysqlPassword)
		.' -D '.escapeshellarg($primaryMysqlDatabase);
	print "Moving from cache database to primary database 	...";
	exec($command, $output, $return_var);
	print "	done\n";
	if ($return_var) {
		throw new Exception('Moving from temp database to primary database failed: '.implode("\n", $output));
	}
	
	
	/*********************************************************
	 * Build derived tables
	 *********************************************************/
	
	// Build derived table for easier term-catalog lookups
	print "Updating derived tables\t";
	$primaryMysql->beginTransaction();
	$ttermcat = $primaryMysql->prepare("TRUNCATE TABLE catalog_term");
	$ttermcat->execute();
	
	$searches = $primaryMysql->query("SELECT * FROM catalog_term_match")->fetchAll();
	
	$itermcat = $primaryMysql->prepare("
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

	$primaryMysql->commit();
	
	print "...\tUpdated derived table: catalog_term\n";

	// Delete terms that have no sections in them.
	print "Removing empty terms\t";
	$primaryMysql->beginTransaction();
	
	$primaryMysql->query(
"CREATE TEMPORARY TABLE empty_terms
SELECT 
	term_code
FROM 
	`catalog_term`
	LEFT JOIN SSBSECT ON term_code = SSBSECT_TERM_CODE
WHERE 
	SSBSECT_CRN IS NULL
GROUP BY 
	term_code
");
	$primaryMysql->query(
"DELETE FROM catalog_term
WHERE
	term_code IN (SELECT term_code FROM empty_terms)
");

	$primaryMysql->query("DROP TEMPORARY TABLE empty_terms");
	$primaryMysql->commit();
	
	print "...\tRemoved empty terms from derived table: catalog_term\n";
	
	// Delete terms that are manually inactivated.
	print "Removing deactivated terms\t";
	$primaryMysql->beginTransaction();
	
	$primaryMysql->query(
"DELETE FROM 
	catalog_term
WHERE
	term_code IN (SELECT term_code FROM catalog_term_inactive)
");

	$primaryMysql->commit();
	
	print "...\tRemoved deactivated terms from derived table: catalog_term\n";
	
	// Rebuild our "materialized views"
	require_once(dirname(__FILE__).'/../application/library/harmoni/SQLUtils.php');
	$primaryMysql->beginTransaction();
	
	print "Updating materialized views\t";
	harmoni_SQLUtils::runSQLfile(dirname(__FILE__).'/../application/library/banner/sql/create_views.sql', $primaryMysql);
	$primaryMysql->commit();
	print "...\tUpdated materialized views\n";

} catch (Exception $e) {
	fwrite(STDERR, $e->getMessage()."\n");
	sendExceptions(array($e->getMessage()));
	exit(1);
}
