<?php

/**
 * Copyright (c) 2020 Middlebury College.
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
 * A record for accessing the instruction method of a <code> CourseOffering. </code>
 *
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:instruction_method
 *
 * @package middlebury.course
 */
interface middlebury_course_CourseOffering_InstructionMethodRecord
	extends osid_course_CourseOfferingRecord
{

	/**
	 *  Answer if this offering has an instruction method defined.
	 *
	 *  @return bool True if an instruction method is defined.
	 *  @compliance mandatory This method must be implemented.
	 */
	public function hasInstructionMethod();

	/**
	 *  Answer the Type of instruction method this offering uses.
	 *
	 *  @return object osid_type_Type The instruction method type.
	 *  @compliance mandatory This method must be implemented.
	 *  @throws osid_OperationFailedException unable to complete request
	 */
	public function getInstructionMethod();

}
