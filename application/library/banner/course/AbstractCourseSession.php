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
	 * @param banner_course_CourseManagerInterface $manager
	 * @return void
	 * @access public
	 * @since 4/10/09
	 */
	public function __construct (banner_course_CourseManagerInterface $manager, $prefix) {
		$this->manager = $manager;
		$this->idPrefix = strval($prefix);
	}
	
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
    public function useComparativeView() {
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
    public function usePlenaryView() {
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
	 * @var boolean $isolatedView; 
	 * @access private
	 * @since 4/10/09
	 */
	private $isolatedView = true;
    
    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include courses in catalogs which are children of this catalog in the 
     *  course catalog hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedView() {
    	$this->isolatedView = false;
    }


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts retrievals to this course catalog only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedView() {
    	$this->isolatedView = true;
    }
    
    /**
     * Answer the value of the view state
     * 
     * @return boolean
     * @access protected
     * @since 4/10/09
     */
    protected function usesIsolatedView () {
    	return $this->isolatedView;
    }
    
    /**
	 * @var banner_course_CourseManagerInterface $manager; 
	 * @access protected
	 * @since 4/10/09
	 */
	protected $manager;
	
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
		
		if ($id->getAuthority() != $this->manager->getIdAuthority())
			throw new osid_NotFoundException('I only know about Ids under the '.$this->manager->getIdAuthority().' authority.');
		
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
		return new phpkit_id_Id($this->manager->getIdAuthority(), 'urn', $prefix.$databaseId);
	}
	
	/**
	 * Answer the combined catalog id
	 * 
	 * @return osid_id_Id
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getCombinedCatalogId () {
		return $this->manager->getCombinedCatalogId();
	}
}

?>