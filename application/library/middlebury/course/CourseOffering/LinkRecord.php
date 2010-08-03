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
 * <p>A record for accessing the link identifier of a <code> CourseOffering. </code>
 *
 * When registering for a Course that has multiple Offerings (such as lecture + lab or 
 * lectures at different times), they must register for one Offering for 
 * each link identifier present.
 *
 * The methods specified by the record type are available through the 
 * underlying object. </p>
 * 
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:instructors
 *
 * @package middlebury.course
 */
interface middlebury_course_CourseOffering_LinkRecord
    extends osid_course_CourseOfferingRecord
{

    /**
	 * Answer the link identifier for an Offering. When registering
	 * for a Course that has multiple Offerings (such as lecture + lab or 
	 * lectures at different times), they must register for one Offering for 
	 * each link identifier present.
	 * 
	 * 
	 * @return osid_id_Id
	 * @access public
	 * @since 8/3/10
	 */
	public function getLinkId ();

}
