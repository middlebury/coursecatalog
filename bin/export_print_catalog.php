#!/usr/bin/env php
<?php

$myDir = dirname(__FILE__);
$destRoot = 'docroot/archive';

$jobs = array(
	'MCUG-2010-2011' => array(
		'dest_dir'	=> 'MCUG/2010-2011',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201090&term[]=term%2F201110&term[]=term%2F201120',
	),
	'MCUG-2011-2012' => array(
		'dest_dir'	=> 'MCUG/2011-2012',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201190&term[]=term%2F201220',
	),
	'MCUG-Winter-2012' => array(
		'dest_dir'	=> 'MCUG/Winter-2012',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201210',
	),
	'MCUG-2012-2013' => array(
		'dest_dir'	=> 'MCUG/2012-2013',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201290&term[]=term%2F201320',
	),
	'MCUG-Winter-2013' => array(
		'dest_dir'	=> 'MCUG/Winter-2013',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201310',
	),
	'MCUG-2013-2014' => array(
		'dest_dir'	=> 'MCUG/2013-2014',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201390&term[]=term%2F201420',
	),
	'MCUG-Winter-2014' => array(
		'dest_dir'	=> 'MCUG/Winter-2014',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201410',
	),
	'MCUG-2014-2015' => array(
		'dest_dir'	=> 'MCUG/2014-2015',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201490&term[]=term%2F201520',
	),
	'MCUG-Winter-2015' => array(
		'dest_dir'	=> 'MCUG/Winter-2015',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201510',
	),
	'MCUG-2015-2016' => array(
		'dest_dir'	=> 'MCUG/2015-2016',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201590&term[]=term%2F201620',
	),
	'MCUG-2016-2017' => array(
		'dest_dir'	=> 'MCUG/2016-2017',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201690&term[]=term%2F201720',
	),
	'MCUG-2017-2018' => array(
		'dest_dir'	=> 'MCUG/2017-2018',
		'params'	=> 'catalog=catalog%2FMCUG&password=LetMePrintPlease&term[]=term%2F201790&term[]=term%2F201820',
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
	unlink($htmlPath);
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
		file_put_contents('php://stderr', "New version is the same as the last. Not generating the pdf.\n");
		return 0;
	}
}

$linkName = str_replace('/', '-', $job['dest_dir']).'_latest.html';
$linkPath = $jobRoot.'/'.$linkName;
if (file_exists($linkPath)) {
	if (!unlink($linkPath)) {
		file_put_contents('php://stderr', "Error deleting latest link: $linkPath\n");
		return 4;
	}
}
if (!symlink($htmlPath, $linkPath)) {
	file_put_contents('php://stderr', "Error creating latest link: $linkPath\n");
	return 5;
}

// Success
return 0;
