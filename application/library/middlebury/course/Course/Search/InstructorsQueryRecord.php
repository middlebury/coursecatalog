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
 */

/**
 * <p>A record for matching the instructors of a <code> Course. </code>
 * The methods specified by the record type are available through the
 * underlying object. </p>.
 *
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:instructors
 */
interface middlebury_course_Course_Search_InstructorsQueryRecord extends osid_course_CourseQueryRecord
{
    /**
     *  Sets the instructor <code> Id </code> for this query to match courses
     *  that have a related instructor.
     *
     *  @param object osid_id_Id $instructorId an instructor <code> Id </code>
     * @param bool $match <code> true </code> if a positive match, <code>
     *                    false </code> for negative match
     *
     * @throws osid_NullArgumentException <code> instructorId </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function matchInstructorId(osid_id_Id $instructorId, $match);

    /**
     *  Tests if an <code> InstructorQuery </code> is available.
     *
     * @return bool <code> true </code> if a instructor query interface is
     *                     available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsInstructorQuery();

    /**
     *  Gets the query interface for an instructor. Multiple retrievals produce a
     *  nested <code> OR </code> term.
     *
     * @return object osid_resource_ResourceQuery the instructor query
     *
     * @throws osid_UnimplementedException <code> supportsInstructorQuery()
     *                                            </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsInstructorQuery() </code> is <code> true. </code>
     */
    public function getInstructorQuery();
}
