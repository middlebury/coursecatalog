<?php

/**
 * osid_hierarchy_Position
 * 
 *     Specifies the OSID definition for osid_hierarchy_Position.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
 * Reserved. 
 * 
 *     This Work is being provided by the copyright holder(s) subject to the 
 *     following license. By obtaining, using and/or copying this Work, you 
 *     agree that you have read, understand, and will comply with the 
 *     following terms and conditions. 
 *     
 *     This Work and the information contained herein is provided on an "AS 
 *     IS" basis. The Massachusetts Institute of Technology, the Open 
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
 * @package org.osid.hierarchy
 */


/**
 *  <p>This interface is a container for the node Id and level for use with 
 *  the hierarchy traversal methods. </p>
 * 
 * @package org.osid.hierarchy
 */
interface osid_hierarchy_Position
{


    /**
     *  Gets the node Id. 
     *
     *  @return object osid_id_Id the <code> Id </code> of this node 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNodeId();


    /**
     *  Gets the level of this node in relation to the starting node as 
     *  specified in the traversal method. Descendants are assigned 
     *  increasingly positive levels. Ancestors are assigned increasingly 
     *  negative levels. 
     *
     *  @return integer the node level 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLevel();


    /**
     *  Tests if this node has any parents. 
     *
     *  @return boolean <code> true </code> if this <code> Id </code> has 
     *          parents, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasParents();


    /**
     *  Gets the parents of this node. 
     *
     *  @return object osid_id_IdList the parents of the <code> id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParents();


    /**
     *  Tests if this node has any children. 
     *
     *  @return boolean <code> true </code> if this <code> Id </code> has 
     *          children, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasChildren();


    /**
     *  Gets the children of the given <code> Id. </code> 
     *
     *  @return object osid_id_IdList the children of the <code> id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getChildren();

}
