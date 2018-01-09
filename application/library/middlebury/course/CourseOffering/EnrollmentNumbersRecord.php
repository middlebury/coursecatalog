<?php

/**
 * Copyright (c) 2018 Middlebury College.
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
 * A record for accessing numbers of seats and availability of a <code> CourseOffering. </code>
 *
 * Course Offerings may have a number of seats total, filled, and available
 *
 * The methods specified by the record type are available through the
 * underlying object.
 *
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:enrollment_numbers
 *
 * @package middlebury.course
 */
interface middlebury_course_CourseOffering_EnrollmentNumbersRecord
	extends osid_course_CourseOfferingRecord
{

	/**
	 * Answer the maximum enrollment for the offering.
	 *
	 * This is the total number of seats available.
	 *
	 * @return int
	 * @access public
	 * @since 1/9/18
	 */
	public function getMaxEnrollment ();

	/**
	 * Answer the current enrollment for the offering.
	 *
	 * The number of seats currently filled.
	 *
	 * @return int
	 * @access public
	 * @since 1/9/18
	 */
	public function getEnrollment ();

	/**
	 * Answer the number of seats available to be filled.
	 *
	 * This should generally be the maximum enrollment minus the current
	 * enrollment unless other constraints are in place.
	 *
	 * @return int
	 * @access public
	 * @since 1/9/18
	 */
	public function getSeatsAvailable ();

}
