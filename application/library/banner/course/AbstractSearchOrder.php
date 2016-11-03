<?php
/**
 * @since 10/14/09
 * @package banner.course
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * Abstract search order
 *
 * @since 10/14/09
 * @package banner.course
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_AbstractSearchOrder {

	protected $terms;
	private $additionalTableJoins;
	private $recordTypes;

	/**
	 * Constructor
	 *
	 * @return void
	 * @access public
	 * @since 5/28/09
	 */
	public function __construct () {
		$this->terms = array();
		$this->additionalTableJoins = array();
		$this->recordTypes = array();
	}

	/**
	 * Answer th SQL ORDER BY clause
	 *
	 * @return string
	 * @access public
	 * @since 5/28/09
	 */
	public function getOrderByClause () {
		$orderTerms = $this->getOrderByTerms();
		if (count($orderTerms))
			return 'ORDER BY '.(implode(', ', $orderTerms));
		else
			return '';
	}

	/**
	 * Answer an array column/direction terms for a SQL ORDER BY clause
	 *
	 * @return array
	 * @access public
	 * @since 5/28/09
	 */
	public function getOrderByTerms () {
		$parts = array();
		foreach ($this->terms as $term) {
			foreach ($term['columns'] as $column) {
				$parts[] = $column.' '.$term['direction'];
			}
		}
		return $parts;
	}

	/**
	 * Answer any additional table join clauses to use
	 *
	 * @return array
	 * @access public
	 * @since 4/29/09
	 */
	public function getAdditionalTableJoins () {
		return $this->additionalTableJoins;
	}

	/**
	 * Add a set of columns to order on.
	 *
	 * @param array $columns An array of column strings
	 * @return void
	 * @access protected
	 * @since 5/28/09
	 */
	protected function addOrderColumns (array $columns) {
		// Check that this set hasn't been added yet.
		$key = implode(',', $columns);
		foreach ($this->terms as $term) {
			if ($term['key'] == $key)
				return;
		}

		$this->terms[] = array(
				'key'		=> $key,
				'columns'	=> $columns,
				'direction'	=> 'ASC'
			);
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
	 * Add a record type to our supported list
	 *
	 * @param osid_type_Type $recordType
	 * @return void
	 * @access protected
	 * @since 10/14/09
	 */
	protected function addSupportedRecordType (osid_type_Type $recordType) {
		$this->recordTypes[] = $recordType;
	}

	/**
	 *  Tests if this search order supports the given record <code> Type.
	 *  </code> The given record type may be supported by the object through
	 *  interface/type inheritence. This method should be checked before
	 *  retrieving the record interface.
	 *
	 *  @param object osid_type_Type $recordType a type
	 *  @return boolean <code> true </code> if an order record of the given
	 *          record <code> Type </code> is available, <code> false </code>
	 *          otherwise
	 *  @throws osid_NullArgumentException <code> recordType </code> is <code>
	 *          null </code>
	 *  @compliance mandatory This method must be implemented.
	 */
	public function hasRecordType(osid_type_Type $recordType) {
		foreach ($this->recordTypes as $type) {
			if ($type->isEqual($recordType))
				return true;
		}
		return false;
	}



/*********************************************************
 * Methods from osid_OsidSearchRecord
 *********************************************************/

	/**
	 *  Tests if the given type is implemented by this record. Other types
	 *  than that directly indicated by <code> getType() </code> may be
	 *  supported through an inheritance scheme where the given type specifies
	 *  a record that is a parent interface of the interface specified by
	 *  <code> getType(). </code>
	 *
	 *  @param object osid_type_Type $recordType a type
	 *  @return boolean <code> true </code> if the given record <code> Type
	 *          </code> is implemented by this record, <code> false </code>
	 *          otherwise
	 *  @throws osid_NullArgumentException <code> recordType </code> is <code>
	 *          null </code>
	 *  @compliance mandatory This method must be implemented.
	 */
	public function implementsRecordType(osid_type_Type $recordType) {
		return $this->hasRecordType($recordType);
	}
}
