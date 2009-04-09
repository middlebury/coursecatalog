<?php

/**
 * osid_course_CourseCatalogForm
 * 
 *     Specifies the OSID definition for osid_course_CourseCatalogForm.
 * 
 * Copyright (C) 2009 Massachusetts Institute of Technology. All Rights 
 * Reserved. 
 * 
 *     This Work is being provided by the copyright holder(s) subject to the 
 *     following license. By obtaining, using and/or copying this Work, you 
 *     agree that you have read, understand, and will comply with the 
 *     following terms and conditions. 
 *     
 *     This Work and the information contained herein is provided on an ""AS 
 *     IS"" basis. The Massachusetts Institute of Technology, the Open 
 *     Knowledge Initiative, and THE AUTHORS DISCLAIM ALL WARRANTIES, EXPRESS 
 *     OR IMPLIED, INCLUDING BUT NOT LIMITED TO WARRANTIES OF MERCHANTABILITY, 
 *     FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
 *     THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR 
 *     OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
 *     ARISING FROM, OUT OF OR IN CONNECTION WITH THE WORK OR THE USE OR OTHER 
 *     DEALINGS IN THE WORK. 
 *     
 *     Permission to use, copy and distribute unmodified versions of this 
 *     Work, for any purpose, without fee or royalty is hereby granted, 
 *     provided that you include the above copyright notice and the terms of 
 *     this license on ALL copies of the Work or portions thereof. 
 *     
 *     You may nodify or create Derivatives of this Work only for your 
 *     internal purposes. You shall not distribute or transfer any such 
 *     Derivative of this Work to any location or to any third party. For the 
 *     purposes of this license, Derivative shall mean any derivative of the 
 *     Work as defined in the United States Copyright Act of 1976, such as a 
 *     translation or modification. 
 *     
 *     The export of software employing encryption technology may require a 
 *     specific license from the United States Government. It is the 
 *     responsibility of any person or organization comtemplating export to 
 *     obtain such a license before exporting this Work. 
 * 
 * @package org.osid.course
 */

require_once(dirname(__FILE__)."/../OsidCatalogForm.php");

/**
 *  <p>This is the form for creating and updating course catalogs. <code> 
 *  </code> Like all <code> OsidForm </code> objects, various data elements 
 *  may be set here for use in the create and update methods in the <code> 
 *  CourseCatalogAdminSession. </code> For each data element that may be set, 
 *  metadata may be examined to provide display hints or data constraints. 
 *  </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseCatalogForm
    extends osid_OsidCatalogForm
{


    /**
     *  Gets the <code> CourseCatalogFormRecord </code> interface 
     *  corresponding to the given course catalog record interface <code> 
     *  Type. </code> 
     *
     *  @param object osid_type_Type $courseCatalogRecordType a course catalog 
     *          record type 
     *  @return object osid_course_CourseCatalogFormRecord the record 
     *  @throws osid_NullArgumentException 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogFormRecord(osid_type_Type $courseCatalogRecordType);

}
