<?php

/**
 * osid_OsidSession
 * 
 *     Specifies the OSID definition for osid_OsidSession.
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
 * @package org.osid
 */


/**
 *  <p>The <code> OsidSession </code> is the top level interface for all OSID 
 *  sessions. An <code> OsidSession </code> is created through its 
 *  corresponding <code> OsidManager. </code> A new <code> OsidSession </code> 
 *  should be created for each user of a service and for each processing 
 *  thread. A session maintains a single authenticated user and is not 
 *  required to ensure thread-protection. A typical OSID session defines a set 
 *  of service methods corresponding to some compliance level as defined by 
 *  the service and is generally responsible for the management and retrieval 
 *  of <code> OsidObjects. </code> </p> 
 *  
 *  <p> <code> OsidSession </code> defines a set of common methods used 
 *  throughout all OSID sessions. An OSID session may optionally support 
 *  transactions through the transaction interface. </p>
 * 
 * @package org.osid
 */
interface osid_OsidSession
{


    /**
     *  Tests if there are valid authentication credentials used by this 
     *  service. 
     *
     *  @return boolean <code> true </code> if valid authentication 
     *          credentials exist, <code> false </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Providers must also query <code> OsidSessions </code> 
     *          instantiated by this session. 
     */
    public function isAuthenticated();


    /**
     *  Gets the authenticated identities used by this service to give the 
     *  user feedback as to which of the Agent identitites are actively being 
     *  used on the user's behalf. 
     *
     *  @return object osid_authentication_AgentList the list of authenticated 
     *          Agents 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Providers must also include any authenticated <code> Agents 
     *          </code> from all <code> OsidSessions </code> instantiated by 
     *          this service. 
     */
    public function getAuthenticatedAgents();


    /**
     *  Tests for the availability of transactions. 
     *
     *  @return boolean <code> true </code> if transaction methods are 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTransactions();


    /**
     *  Starts a new transaction for this sesson. Transactions are a means for 
     *  an OSID to provide an all-or-nothing set of operations within a 
     *  session and may be used to coordinate this service from an external 
     *  transaction manager. A session supports one transaction at a time. 
     *  Starting a second transaction before the previous has been committed 
     *  or aborted results in an <code> ILLEGAL_STATE </code> error. 
     *
     *  @return object osid_transaction_Transaction a new transaction 
     *  @throws osid_IllegalStateException a transaction is already open or 
     *          this session has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnsupportedException transactions not supported 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTransactions() </code> is true. 
     *  @notes  Ideally, a provider that supports transactions should 
     *          guarantee atomicity, consistency, isolation and durability in 
     *          a 2 phase commit process. This is not always possible in 
     *          distributed systems and a transaction provider may simply 
     *          allow for a means of processing bulk updates. 
     *          <br/><br/>
     *          To maximize interoperability, providers should honor the 
     *          one-transaction-at-a-time rule. 
     */
    public function startTransaction();


    /**
     *  Closes this <code>osid.OsidSession</code>
     */

    public function close();

}
