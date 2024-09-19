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
 * <p>A record for ordering by the weekly schedule of a <code> CourseOffering. </code>
 * The methods specified by the record type are available through the
 * underlying object. </p>.
 *
 *  The type for this record is:
 *		id namespace:	urn
 *		authority:		middlebury.edu
 *		identifier:		record:weekly_schedule
 */
interface WeeklyScheduleSearchOrderRecord extends osid_course_CourseOfferingSearchOrderRecord
{
    /**
     * Order by meeting on Sunday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsSunday();

    /**
     * Order by meeting on Monday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsMonday();

    /**
     * Order by meeting on Tuesday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsTuesday();

    /**
     * Order by meeting on Wednesday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsWednesday();

    /**
     * Order by meeting on Thursday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsThursday();

    /**
     * Order by meeting on Friday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsFriday();

    /**
     * Order by meeting on Saturday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsSaturday();

    /**
     * Order by meeting times.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetingTime();
}
