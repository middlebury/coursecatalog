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
 * A record for accessing the link identifiers for a <code> course. </code>
 *
 * The offerings of a course in a term will be grouped into one or more link sets
 * (set 1, set 2, set 3, etc).
 * Each offering also has a link type (such as lecture, discussion, lab, etc).
 *
 * When registering for a Course that has multiple Offerings (such as lecture + lab or
 * lectures at different times), students must choose a link set and then one offering
 * of each type within that set.
 *
 *
 * The methods specified by the record type are available through the
 * underlying object.
 *
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:instructors
 *
 * @package middlebury.course
 */
interface middlebury_course_Course_LinkRecord
	extends osid_course_CourseRecord
{
	/**
	 * Answer the link-set ids for the offerings of this course in the term specified.
	 *
	 * The offerings of a course in a term will be grouped into one or more link sets
	 * (set 1, set 2, set 3, etc).
	 * Each offering also has a link type (such as lecture, discussion, lab, etc).
	 *
	 * When registering for a Course that has multiple Offerings (such as lecture + lab or
	 * lectures at different times), students must choose a link set and then one offering
	 * of each type within that set.
	 *
	 *
	 * @param osid_id_Id $termId
	 * @return osid_id_IdList
	 * @access public
	 * @since 8/3/10
	 */
	public function getLinkSetIdsForTerm (osid_id_Id $termId);

	/**
	 * Answer the link-type ids for the offerings of this course in the term specified.
	 *
	 * The offerings of a course in a term will be grouped into one or more link sets
	 * (set 1, set 2, set 3, etc).
	 * Each offering also has a link type (such as lecture, discussion, lab, etc).
	 *
	 * When registering for a Course that has multiple Offerings (such as lecture + lab or
	 * lectures at different times), students must choose a link set and then one offering
	 * of each type within that set.
	 *
	 *
	 * @param osid_id_Id $termId
	 * @param osid_id_Id $linkSetId
	 * @return osid_id_IdList
	 * @access public
	 * @since 8/3/10
	 */
	public function getLinkTypeIdsForTermAndSet (osid_id_Id $termId, osid_id_Id $linkSetId);
}
