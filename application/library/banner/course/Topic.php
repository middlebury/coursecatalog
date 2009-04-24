<?php
/**
 * @since 4/9/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>A <code> Topic </code> represents a subject area of a course. </p>
 * 
 * @package org.osid.course
 */
class banner_course_Topic
    extends phpkit_AbstractOsidObject
    implements osid_course_Topic
{
	
	/**
	 * Constructor
	 * 
	 * @param osid_id_Id $id
	 * @param string $displayName
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (osid_id_Id $id, $displayName, $description, osid_type_Type $genusType) {
		parent::__construct();
		$this->setId($id);
		$this->setDisplayName($displayName);
		$this->setDescription($description);
		$this->setGenusType($genusType);
	}
	
    /**
     *  Gets the record corresponding to the given <code> Topic </code> record 
     *  <code> Type. </code> This method must be used to retrieve an object 
     *  implementing the requested record interface along with all of its 
     *  ancestor interfaces. The <code> topicRecordType </code> may be the 
     *  <code> Type </code> returned in <code> getRecordTypes() </code> or any 
     *  of its parents in a <code> Type </code> hierarchy where <code> 
     *  hasRecordType(topicRecordType) </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $topicRecordType the type of topic record 
     *          to retrieve 
     *  @return object osid_course_TopicRecord the topic record 
     *  @throws osid_NullArgumentException <code> topicRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(topicRecordType) </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicRecord(osid_type_Type $topicRecordType) {
    	throw new osid_UnsupportedException('Topic record type is not supported.');
    }

}
