#!/usr/bin/env php
<?php

$myDir = dirname(__FILE__);
$destRoot = '/tmp/archive';

$jobs = array(
	'MCUG-2010-2011' => array(
		'dest_dir'	=> 'MCUG/2010-2011',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201090&term[]=term%2F201110&term[]=term%2F201120',
	),
);

$cmd = array_shift($argv);
$jobName = array_shift($argv);
if (count($argv) || !isset($jobs[$jobName])) {
	print "Usage:
	$cmd <job>
	
Where job is one of:
	".implode("\n\t", array_keys($jobs))."\n\n";
	return 1;
}

$job = $jobs[$jobName];
$jobRoot = $destRoot.'/'.$job['dest_dir'];
$htmlRoot = $jobRoot.'/html';
$pdfRoot = $jobRoot.'/pdf';

if (!file_exists($htmlRoot)) {
	if (!mkdir($htmlRoot, 0775, true))
		file_put_contents('php://stderr', "Unable to create destination directory '$htmlRoot'.\n");
}
if (!file_exists($pdfRoot)) {
	if (!mkdir($pdfRoot, 0775, true))
		file_put_contents('php://stderr', "Unable to create destination directory '$pdfRoot'.\n");
}

$fileBase = str_replace('/', '-', $job['dest_dir']).'_snapshot-'.date('Y-m-d');
$htmlName = $fileBase.'.html';
$htmlPath = $htmlRoot.'/'.$htmlName;

// Generate the export.
$command = $myDir.'/zfcli.php -a courses.allrecentcourses -p '.escapeshellarg($job['params']).' > '.$htmlPath;
exec($command, $output, $return);
if ($return) {
	file_put_contents('php://stderr', "Error running command:\n\n\t$command\n");
	return 2;
}

// Check to see if the export is different from the previous one.
$exports = explode("\n", trim(shell_exec('ls -1t '.escapeshellarg($htmlRoot))));
array_shift($exports);
if (count($exports)) {
	$diff = trim(shell_exec('diff '.escapeshellarg($htmlPath).' '.escapeshellarg($htmlRoot.'/'.$exports[0])));
	// Delete our current export if it is the same as the last one.
	// This way we only keep versions that contain changes.
	if (!strlen($diff)) {
		unlink($htmlPath);
		return 0;
	}
}

// If we have a new export, convert it to a PDF.
$pdfName = $fileBase.'.pdf';
$pdfPath = $pdfRoot.'/'.$pdfName;
$command = "htmldoc --book -f ".escapeshellarg($pdfPath)." ".escapeshellarg($htmlPath).' 2>&1';
exec($command, $output, $return);
if ($return) {
	file_put_contents('php://stderr', "Error running command:\n\n\t$command\n$output\n");
	return 3;
}

// Update the symbolic link to the latest snapshot.
$linkName = str_replace('/', '-', $job['dest_dir']).'_latest.pdf';
$linkPath = $jobRoot.'/'.$linkName;
if (file_exists($linkPath)) {
	if (!unlink($linkPath)) {
		file_put_contents('php://stderr', "Error deleting latest link: $linkPath\n");
		return 4;
	}
}
if (!symlink($pdfPath, $linkPath)) {
	file_put_contents('php://stderr', "Error creating latest link: $linkPath\n");
	return 5;
}

// Success
return 0;
