<?php
/**
 * @since 4/10/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * This is an abstract course session that includes much of the common methods needed
 * by all course sessions in this package
 * 
 * @since 4/10/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_AbstractCourseSession
	extends phpkit_AbstractOsidSession
{
		
	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @return void
	 * @access public
	 * @since 4/10/09
	 */
	public function __construct (PDO $db, $idAuthority, $prefix) {
		$this->db = $db;
		
		if (!strlen($idAuthority))
			throw new osid_OperationFailedException('No id authority specified.');
		
		$this->idAuthority = strval($idAuthority);
		$this->idPrefix = strval($prefix);
	}
	
	/**
	 * @var PDO $db;  
	 * @access protected
	 * @since 4/10/09
	 */
	protected $db;
	
	/**
	 * @var boolean $plenaryView; 
	 * @access private
	 * @since 4/10/09
	 */
	private $plenaryView = true;
	
	/**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeCourseCatalogView() {
    	$this->plenaryView = false;
    }

    /**
     *  A complete view of the <code> CourseCatalog </code> returns is 
     *  desired. Methods will return what is requested or result in an error. 
     *  This view is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryCourseCatalogView() {
    	$this->plenaryView = true;
    }
    
    /**
     * Answer the value of the view state
     * 
     * @return boolean
     * @access protected
     * @since 4/10/09
     */
    protected function usesPlenaryView () {
    	return $this->plenaryView;
    }
    
    /**
	 * @var string $idAuthority; 
	 * @access private
	 * @since 4/10/09
	 */
	private $idAuthority;
	
	/**
	 * @var string $idPrefix; 
	 * @access private
	 * @since 4/10/09
	 */
	private $idPrefix;
	
	/**
	 * Answer a database-id for an Id object passed or throw an osid_NotFoundException
	 * if the Id is not one that this implementation might know about.
	 * 
	 * @param object osid_id_Id $id
	 * @param string optional $prefix
	 * @return string
	 * @access protected
	 * @since 4/10/09
	 */
	protected function getDatabaseIdString (osid_id_Id $id, $prefix = null) {
		if ($id->getIdentifierNamespace() != 'urn')
			throw new osid_NotFoundException('I only know about Ids in the urn namespace.');
		
		if ($id->getAuthority() != $this->idAuthority)
			throw new osid_NotFoundException('I only know about Ids under the '.$this->idAuthority.' authority.');
		
		if (is_null($prefix))
			$prefix = $this->idPrefix;
		else
			$prefix = strval($prefix);
		
    	if (strpos($id->getIdentifier(), $prefix) !== 0)
    		throw new osid_NotFoundException('I only know about Ids with the '.$prefix.' prefix.');
    	
    	return substr($id->getIdentifier(), strlen($prefix));
	}
	
	/**
	 * Answer an Id object from a string database Id
	 * 
	 * @param string $databaseId
	 * @param string optional $prefix
	 * @return osid_id_Id
	 * @access protected
	 * @since 4/10/09
	 */
	protected function getOsidIdFromString ($databaseId, $prefix = null) {
		if (is_null($prefix))
			$prefix = $this->idPrefix;
		return new phpkit_id_Id($this->idAuthority, 'urn', $prefix.$databaseId);
	}
}

?>