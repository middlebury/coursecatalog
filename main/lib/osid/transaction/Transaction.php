<?php

/**
 * osid_transaction_Transaction
 * 
 *     Specifies the OSID definition for osid_transaction_Transaction.
 * 
 * Copyright (C) 2002-2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.transaction
 */


/**
 *  <p><code> OsidTransaction </code> is used by an <code> OsidSession </code> 
 *  to provide transactions across its methods. Transactions are performed 
 *  within a session. Coordination of transactions across OSIDS of there 
 *  sessions requires the availability of a transaction manager. </p> 
 *  
 *  <p> A trsnaction is started upon creation of an <code> OsidTransaction. 
 *  </code> Actions within a session are queued until the transaction is 
 *  committed or aborted. </p>
 * 
 * @package org.osid.transaction
 */
interface osid_transaction_Transaction
{


    /**
     *  Prepares for a <code> commit </code> . No further operations are 
     *  permitted in the associated manager until this transaction is 
     *  committed or aborted. 
     *
     *  @throws osid_IllegalStateException this transaction has been committed 
     *          or aborted 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_TransactionFailureException this transaction cannot 
     *          proceed due to a bad transaction element 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  The provider must verify this transaction such that a <code> 
     *          commit </code> will succeed and reliably record the state 
     *          changes resulting from this transaction before returning. 
     */
    public function prepare();


    /**
     *  Commits the transaction and makes the state change(s) visible. This 
     *  transaction is effectively closed and the only valid method that may 
     *  be invoked is <code> getState(). </code> 
     *
     *  @throws osid_IllegalStateException this transaction has been committed 
     *          or aborted 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Any resources allocated for this transaction can be released. 
     */
    public function commit();


    /**
     *  Cancels this transaction, rolling back the queue of operations since 
     *  the start of this transaction. This transaction is effectively closed 
     *  and the only valid method that may be invoked is <code> getState(). 
     *  </code> 
     *
     *  @throws osid_IllegalStateException this transaction has been committed 
     *          or aborted 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Any resources allocated for this transaction can be released. 
     */
    public function abort();


    /**
     *  Gets the current state of this transaction. 
     *
     *  @return object osid_transaction_TransactionState the current state of 
     *          this transaction 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getState();

}
