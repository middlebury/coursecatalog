<?php
/**
 * @since 6/11/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>An interface for specifying the ordering of search results. </p>
 * 
 * @since 6/11/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Topic_Search_Order
	implements osid_course_TopicSearchOrder
{

/*********************************************************
 * Methods from osid_OsidSearchOrder
 *********************************************************/

	/**
     *  Specifies a preference for ordering the result set in an ascending 
     *  manner. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function ascend() {
    	throw new osid_UnimplementedException;	
    }


    /**
     *  Specifies a preference for ordering the result set in a descending 
     *  manner. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function descend() {
    	throw new osid_UnimplementedException;	
    }


    /**
     *  Specifies a preference for ordering the result set by the display 
     *  name. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByDisplayName() {
    	throw new osid_UnimplementedException;	
    }


    /**
     *  Specifies a preference for ordering the result set by the genus type. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByGenusType() {
    	throw new osid_UnimplementedException;	
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
    	throw new osid_UnimplementedException;	
    }

/*********************************************************
 * Methods from osid_course_TopicSearchOrder
 *********************************************************/
	
	/**
     *  Gets the topic order record corresponding to the given course record 
     *  <code> Type. </code> Multiple retrievals return the same underlying 
     *  object. 
     *
     *  @param object osid_type_Type $topicRecordType a topic record type 
     *  @return object osid_course_TopicSearchOrderRecord the topic search 
     *          order record interface 
     *  @throws osid_NullArgumentException <code> topicRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(topicRecordType) </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicSearchOrderRecord(osid_type_Type $topicRecordType) {
    	throw new osid_UnimplementedException;	
    }
	
}

?>