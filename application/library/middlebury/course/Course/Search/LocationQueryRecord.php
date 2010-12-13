<?php

/**
 * Copyright (c) 2009 Middlebury College.
 * 
 *     Permission is hereby granted, free of charge, to any person
 *     obtaining a copy of this software and associated documentation
 *     files (the "Software"), to deal in the Software without
 *     restriction, including without limitation the rights to use,
 *     copy, modify, merge, publish, distribute, sublicesne, and/or
 *     sell copies of the Software, and to permit the persons to whom the
 *     Software is furnished to do so, subject the following conditions:
 *     
 *     The above copyright notice and this permission notice shall be
 *     included in all copies or substantial portions of the Software.
 *     
 *     The Software is provided "AS IS", WITHOUT WARRANTY OF ANY KIND,
 *     EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 *     OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 *     NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 *     HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 *     WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *     OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 *     DEALINGS IN THE SOFTWARE.
 * 
 * @package middlebury.course
 */

/**
 * <p>A record for matching the location of a <code> Course. </code>
 * While courses may not be strongly identified with locations, they may have
 * offerings that only are taught on certain campuses. This record provides a
 * way to search for courses that have offerings at particular places.
 * The methods specified by the record type are available through the 
 * underlying object. </p>
 * 
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:location
 *
 * @package middlebury.course
 */
interface middlebury_course_Course_Search_LocationQueryRecord
    extends osid_course_CourseQueryRecord
{

	/**
     *  Sets the location resource <code> Id </code> for this query to match courses 
     *  that have a related location resource. 
     *
     *  @param object osid_id_Id $resourceId A location resource <code> Id </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> resourceId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchLocationId(osid_id_Id $instructorId, $match);


    /**
     *  Tests if a <code> LocationQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a location query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLocationQuery();


    /**
     *  Gets the query interface for a location. Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_resource_ResourceQuery the location query 
     *  @throws osid_UnimplementedException <code> supportsLocationQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLocationQuery() </code> is <code> true. </code> 
     */
    public function getLocationQuery();

}
