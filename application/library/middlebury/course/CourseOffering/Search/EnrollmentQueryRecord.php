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
 * <p>A record for matching the enrollment data of a <code> CourseOffering. </code>
 * The methods specified by the record type are available through the 
 * underlying object. </p>
 * 
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:enrollment
 *
 * @package middlebury.course
 */
interface middlebury_course_CourseOffering_Search_EnrollmentQueryRecord
    extends osid_course_CourseOfferingQueryRecord
{

	/**
     * Match CourseOfferings that may be or have been open for enrollment.
     * These may have a non-zero maximum enrollment or other flag that indicates
     * that they are not just placeholders (such as for cross-lists).
     * 
     * @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     */
    public function matchEnrollable ($match);
    
    /**
     * Match CourseOfferings based on their enrollment.
     * 
     * @param integer $rangeStart The lower bound of enrollment range to match. 0 or greater.
     * @param integer $rangeEnd The upper bound of the enrollment range to match. 0 or greater, or NULL to indicate no upper bound.
     * @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     */
    public function matchEnrollment ($rangeStart, $rangeEnd, $match);
    
    /**
     * Match CourseOfferings based on their seats available.
     * 
     * @param integer $rangeStart The lower bound of the seats range to match. 0 or greater.
     * @param integer $rangeEnd The upper bound of the seats range to match. 0 or greater, or NULL to indicate no upper bound.
     * @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     */
    public function matchSeatsAvailable ($rangeStart, $rangeEnd, $match);
}
