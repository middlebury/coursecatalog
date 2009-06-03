<?php
/**
 * @package harmoni.dbc
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SQLUtils.static.php,v 1.12 2007/10/10 22:58:31 adamfranco Exp $
 */

/**
 * This is a static class that provides functions for the running of arbitrary
 * SQL strings and files.
 * 
 *
 * @package harmoni.dbc
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: SQLUtils.static.php,v 1.12 2007/10/10 22:58:31 adamfranco Exp $
 * @static
 */

class harmoni_SQLUtils {

	/**
	 * Parse SQL textfile to remove comments and line returns.
	 * 
	 * @param string $file The file to be parsed
	 * @return string The parsed SQL string.
	 * @access public
	 * @since 7/2/04
	 * @static
	 */
	public static function parseSQLFile ( $file ) {
		$queryString = file_get_contents($file);
		if ($queryString)
			return self::parseSQLString($queryString);
		else
			throw new harmoni_DatabaseException("The file, '".$file."' was empty or doesn't exist.");
	}
	
	/**
	 * Parse SQL string to remove comments and line returns.
	 * 
	 * @param string $queryString The string to be parsed
	 * @return string The parsed SQL string.
	 * @access public
	 * @since 7/2/04
	 * @static
	 */
	public static function parseSQLString ( $queryString ) {
		// Remove the comments
		$queryString = ereg_replace("(#|--)[^\n\r]*(\n|\r|\n\r)", "", $queryString);
		
		// Remove the line returns
		$queryString = ereg_replace("\n|\r", " ", $queryString);
		
		// Remove multiple spaces
		$queryString = ereg_replace("\ +", " ", $queryString);
		
		// Remove backticks included by MySQL since they aren't needed anyway.
		$queryString = ereg_replace("`", "", $queryString);
		return $queryString;
	}
	
	/**
	 * Break up a SQL string with multiple queries (separated by ';') and run each
	 * query
	 * 
	 * @param string $queryString The string of queries.
	 * @param PDO $db The database to run the queries on.
	 * @return void
	 * @access public
	 * @since 7/2/04
	 * @static
	 */
	public static function multiQuery ( $queryString, PDO $db ) {
		// break up the query string.
		$queryStrings = explode(";", $queryString);
		
		// Run each query
		foreach ($queryStrings as $string) {
			$string = trim($string);
			if ($string) {
				$db->exec($string);
			}
		}
	}
	
	/**
	 * Run all of the queries in a text file. Comments must start with '#' and
	 * queries must be separated by ';'.
	 * 
	 * @param string $file The input file containing the queries.
	 * @param PDO $db The database to run the queries on.
	 * @return void
	 * @access public
	 * @since 7/2/04
	 * @static
	 */
	public static function runSQLfile ($file, PDO $db) {
		$string = self::parseSQLFile($file);
		self::multiQuery($string, $db);
	}
	
	/**
	 * Run all of the files with a given extention in a directory as SQL files.
	 * 
	 * @param string $dir
	 * @param PDO $db The database to run the queries on.
	 * @param optional string $extn The file extention to execute, default: 'sql'.
	 * @return void
	 * @access public
	 * @since 9/11/07
	 * @static
	 */
	public static function runSQLdir ($dir, PDO $db, $extn = 'sql') {
		$sqlFiles = array();
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$path = $dir."/".$file;
					// Recurse into sub directories
					if (is_dir($path))
						self::runSQLdir($path, $db, $extn);
					// Run any SQL files
					else if (preg_match('/.+\.'.$extn.'$/i', $file))
						$sqlFiles[] = $path;
					// Ignore any other files.
				}
			}
			closedir($handle);
		} else {
			throw new Exception ("Could not open SQL directory, '$dir', for reading.");
		}
		
		sort ($sqlFiles);
		foreach ($sqlFiles as $path)
			self::runSQLfile($path, $db);
	}
	
	/**
	 * Run all of the files with a given extention in a directory as SQL files.
	 * 
	 * @param string $dir
	 * @param array $exceptions An array of filenames to exclude.
	 * @param PDO $db The database to run the queries on.
	 * @param optional string $extn The file extention to execute, default: 'sql'.
	 * @return void
	 * @access public
	 * @since 04/28/08
	 * @static
	 */
	public static function runSQLdirWithExceptions ($dir, array $exceptions = array(), PDO $db, $extn = 'sql') {
		$sqlFiles = array();
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$path = $dir."/".$file;
					// Recurse into sub directories
					if (is_dir($path))
						self::runSQLdir($path, $db, $extn);
					// Run any SQL files
					else if (preg_match('/.+\.'.$extn.'$/i', $file)
							&& !in_array($file, $exceptions))
						$sqlFiles[] = $path;
					// Ignore any other files.
				}
			}
			closedir($handle);
		} else {
			throw new Exception ("Could not open SQL directory, '$dir', for reading.");
		}
		
		sort ($sqlFiles);
		foreach ($sqlFiles as $path)
			self::runSQLfile($path, $db);
	}
}
?>