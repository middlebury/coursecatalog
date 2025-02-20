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
 * <p>This interface defines custom extensions that have not yet made it back into the OSID core.</p>.
 */
interface middlebury_course_CourseOffering_Lookup_SessionInterface extends osid_course_CourseOfferingLookupSession
{
    /**
     *  Gets a list of the genus types for course offerings.
     *
     * @return object osid_id_TypeList the list of course offering genus types
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getCourseOfferingGenusTypes();

    /**
     *  Gets a list of the genus types for course offerings in a given term.
     *
     * @param osid_id_Id $termId the term id to scope to
     *
     * @return object osid_id_TypeList the list of course offering genus types
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getCourseOfferingGenusTypesByTermId(osid_id_Id $termId);
}
