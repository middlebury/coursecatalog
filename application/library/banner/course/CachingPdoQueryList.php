<?php
/**
 * @since 4/28/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A query list that caches prepared statements in class vars.
 * 
 * @since 4/28/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_CachingPdoQueryList
	extends phpkit_List_Pdo_Query_FetchAll
{
		
	private static $statements = array();
	private $statementCacheKey;
	
	/**
	 * Answer a cache-key for the query string passed
	 * 
	 * @param string $query
	 * @return string
	 * @access protected
	 * @since 4/28/09
	 */
	protected function getStatementCacheKey ($query = null) {
		if (!isset($this->statementCacheKey)) {
			if (is_null($query))
				throw new osid_OperationFailedException('A query must be present when setting the cache key');
			$this->statementCacheKey = md5($query);
		}
		return $this->statementCacheKey;
	}
	
	/**
	 * Prepare a statement and store it for later execution.
	 *
	 * This method and getStatement() may be overridden to enable a variety of
	 * statement caching schemes.
	 * 
	 * @param PDO $db
	 * @param string $query
	 * @return void
	 * @access protected
	 * @since 4/28/09
	 */
	protected function prepareStatement (PDO $db, $query) {
		if (!isset(self::$statements[$this->getStatementCacheKey($query)]))
			self::$statements[$this->getStatementCacheKey($query)] = $db->prepare($query);
	}
	
	/**
	 * Answer the statement already prepared.
	 * 
	 * @return PDOStatement
	 * @access protected
	 * @since 4/28/09
	 */
	protected function getStatement () {
		return self::$statements[$this->getStatementCacheKey()];
	}
	
	/**
	 * Prepare a count() statement and store it for later execution.
	 *
	 * This method and getStatement() may be overridden to enable a variety of
	 * statement caching schemes.
	 * 
	 * @param PDO $db
	 * @param string $query
	 * @return void
	 * @access protected
	 * @since 4/28/09
	 */
	protected function prepareCountStatement (PDO $db, $query) {
		if (!isset(self::$statements[$this->getStatementCacheKey($query).'-count']))
			self::$statements[$this->getStatementCacheKey($query).'-count'] = $db->prepare($query);
	}
	
	/**
	 * Answer the count() statement already prepared.
	 * 
	 * @return PDOStatement
	 * @access protected
	 * @since 4/28/09
	 */
	protected function getCountStatement () {
		return self::$statements[$this->getStatementCacheKey().'-count'];
	}
	
}

?>