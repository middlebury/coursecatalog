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
abstract class banner_course_AbstractSession
	extends banner_AbstractSession
{
	
	/**
	 * Answer a catalog database id string.
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access public
	 * @since 4/20/09
	 */
	public function getCatalogDatabaseId (osid_id_Id $id) {
		return $this->getDatabaseIdString($id, 'catalog/');
	}
	
	/**
	 * Answer the Id of the 'All'/'Combined' catalog.
	 * 
	 * @return osid_id_Id
	 * @access public
	 * @since 4/20/09
	 */
	public function getCombinedCatalogId () {
		return $this->manager->getCombinedCatalogId();
	}
	
	/**
	 * Answer a topic lookup session
	 * 
	 * @return osid_course_TopicLookupSession
	 * @access public
	 * @since 4/16/09
	 */
	public function getTopicLookupSession () {
		if (!isset($this->topicLookupSession)) {
			$this->topicLookupSession = $this->manager->getTopicLookupSessionForCatalog($this->getCourseCatalogId());
// 			$this->topicLookupSession = $this->manager->getTopicLookupSession();
			$this->topicLookupSession->useFederatedCourseCatalogView();
		}
		
		return $this->topicLookupSession;
	}
}

?>