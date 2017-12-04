<?php

/**
 * Copyright (c) 2017 Middlebury College.
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
 * <p>A record for accessing the alternate instances of a <code> Course </code>
 * such as in the case of cross-listed courses or alternate numbering schemes
 * The methods specified by the record type are available through the
 * underlying object. </p>
 *
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:alternates-in-terms
 *
 * @package middlebury.course
 */
interface middlebury_course_Course_AlternatesInTermsRecord
	extends osid_course_CourseRecord
{

	/**
	 * Tests if this course has any alternate courses, effective between the terms specified (inclusive).
	 *
	 * @param osid_id_Id $startTerm
	 * @param osid_id_Id $endTerm
	 * @return boolean <code> true </code> if this course has any
	 *          alternates, <code> false </code> otherwise
	 * @access public
	 * @compliance mandatory This method must be implemented.
	 */
	public function hasAlternatesinTerms (osid_id_Id $startTerm, osid_id_Id $endTerm);

	/**
	 *  Gets the Ids of any alternate courses, effective between the terms specified (inclusive).
	 *
	 * @param osid_id_Id $startTerm
	 * @param osid_id_Id $endTerm
	 * @return object osid_id_IdList the list of alternate ids.
	 * @compliance mandatory This method must be implemented.
	 * @throws osid_OperationFailedException unable to complete request
	 * @throws osid_PermissionDeniedException authorization failure
	 */
	public function getAlternateIdsInTerms(osid_id_Id $startTerm, osid_id_Id $endTerm);

	/**
	 *  Gets the alternate <code> Courses </code>, effective between the terms specified (inclusive).
	 *
	 * @param osid_id_Id $startTerm
	 * @param osid_id_Id $endTerm
	 * @return object osid_course_CourseList The list of alternates.
	 * @compliance mandatory This method must be implemented.
	 * @throws osid_OperationFailedException unable to complete request
	 * @throws osid_PermissionDeniedException authorization failure
	 */
	public function getAlternatesInTerms(osid_id_Id $startTerm, osid_id_Id $endTerm);

	/**
	 * Answer <code> true </code> if this course is the primary version in a group of
	 * alternates, effective between the terms specified (inclusive).
	 *
	 * @return boolean
	 * @compliance mandatory This method must be implemented.
	 * @throws osid_OperationFailedException unable to complete request
	 * @throws osid_PermissionDeniedException authorization failure
	 */
	public function isPrimaryInTerms (osid_id_Id $startTerm, osid_id_Id $endTerm);
}
