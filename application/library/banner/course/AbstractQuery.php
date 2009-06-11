<?php
/**
 * @since 6/11/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * This is an abstract query class that provides common functions for search Querys.
 * 
 * @since 6/11/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_AbstractQuery {
	
	private $clauseSets;
	private $parameters;
	private $additionalTableJoins;
	private $stringMatchTypes;
	protected $session;
	
	
	/**
	 * Constructor
	 * 
	 * @param banner_course_CourseOffering_AbstractSession $session
	 * @return void
	 * @access public
	 * @since 5/20/09
	 */
	public function __construct (banner_course_AbstractSession $session) {
		$this->session = $session;
		
		$this->clauseSets = array();
		$this->parameters = array();
		$this->additionalTableJoins = array();
		
	}
	
	/**
	 * Enable a string-match type as supported.
	 * 
	 * @param osid_type_Type $type
	 * @return void
	 * @access protected
	 * @since 6/11/09
	 */
	protected function addStringMatchType (osid_type_Type $type) {
		$this->stringMatchTypes[] = $type;
	}
	
	/**
	 * Add a clause. All clauses in the same set will be OR'ed, sets will be AND'ed.
	 * 
	 * @param string $set 
	 * @param string $where A where clause with parameters in '?' form.
	 * @param array $parameters An indexed array of parameters
     * @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
	 * @return void
	 * @access protected
	 * @since 5/20/09
	 */
	protected function addClause ($set, $where, array $parameters, $match) {
		$numParams = preg_match_all('/\?/', $where, $matches);
		if ($numParams === false)
			throw new osid_OperationFailedException('An error occured in matching.');
		if ($numParams != count($parameters))
			throw new osid_InvalidArgumentException('The number of \'?\'s must match the number of parameters.');
		if (!is_bool($match))
    		throw new osid_InvalidArgumentException("\$match '$match' must be a boolean.");
		
		if (!isset($this->clauseSets[$set]))
			$this->clauseSets[$set] = array();
		if (!isset($this->parameters[$set]))
			$this->parameters[$set] = array();
		
		if ($match)
			$this->clauseSets[$set][] = $where;
		else
			$this->clauseSets[$set][] = 'NOT '.$where;
		$this->parameters[$set][] = $parameters;
	}
	
	/**
	 * Add a table join
	 * 
	 * @param string $joinClause
	 * @return void
	 * @access protected
	 * @since 5/27/09
	 */
	protected function addTableJoin ($joinClause) {
		if (!in_array($joinClause, $this->additionalTableJoins))
			$this->additionalTableJoins[] = $joinClause;
	}
	
	/**
	 * Answer the clause sets
	 * 
	 * @return array
	 * @access protected
	 * @since 6/11/09
	 */
	protected function getClauseSets () {
		return $this->clauseSets;
	}
	
	/**
	 * Answer the SQL WHERE clause that reflects our current state
	 * 
	 * @return string
	 * @access public
	 * @since 5/20/09
	 */
	public function getWhereClause () {
		$sets = array();
		foreach ($this->getClauseSets() as $set) {
			$sets[] = '('.implode("\n\t\tOR ", $set).')';
		}
		
		return implode("\n\tAND ", $sets);
	}
	
	/**
	 * Answer the array of parameters that matches our current state
	 * 
	 * @return array
	 * @access public
	 * @since 5/20/09
	 */
	public function getParameters () {
		$params = array();
		foreach ($this->parameters as $set) {
			foreach ($set as $clauseParams) {
				$params = array_merge($params, $clauseParams);
			}
		}
		
		return $params;
	}
	
	/**
	 * Answer an array of additional columns to return.
	 * 
	 * @return array
	 * @access public
	 * @since 6/10/09
	 */
	public function getAdditionalColumns () {
		return array();
	}
	
	/**
	 * Answer an array column/direction terms for a SQL ORDER BY clause
	 * 
	 * @return array
	 * @access public
	 * @since 5/28/09
	 */
	public function getOrderByTerms () {
		return array();
	}
	
	/**
	 * Answer any additional table join clauses to use
	 * 
	 * @return string
	 * @access public
	 * @since 4/29/09
	 */
	public function getAdditionalTableJoins () {
		return $this->additionalTableJoins;
	}
	
/*********************************************************
 * Methods from osid_OsidQuery
 *********************************************************/

	/**
     *  Gets the string matching types supported. A string match type 
     *  specifies the syntax of the string query, such as matching a word or 
     *  including a wildcard or regular expression. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          string match types 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getStringMatchTypes() {
    	return new phpkit_type_ArrayTypeList($this->stringMatchTypes);
    }


    /**
     *  Tests if the given string matching type is supported. 
     *
     *  @param object osid_type_Type $searchType a <code> Type </code> 
     *          indicating a string match type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsStringMatchType(osid_type_Type $searchType) {
    	foreach ($this->stringMatchTypes as $type) {
    		if ($searchType->isEqual($type))
    			return true;
    	}
    	return false;
    }
	
}

?>