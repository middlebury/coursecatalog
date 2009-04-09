<?php

/**
 * osid_transaction_TransactionSession
 * 
 *     Specifies the OSID definition for osid_transaction_TransactionSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>The transaction session is coordinate transactions. A transaction 
 *  session allows for <code> Transactions </code> to be added to the list of 
 *  transactions it is managing. Upon a <code> commit(), </code> all 
 *  registered transactions receive a <code> prepare() </code> and a <code> 
 *  commit(). </code> Upon an <code> abort() </code> all registered 
 *  transactions receive an <code> abort(). </code> A <code> 
 *  TransactionSession </code> itself may implement transactions (as it is an 
 *  <code> OsidSession </code> ) as a means of enabling a form of federated 
 *  transaction management. </p>
 * 
 * @package org.osid.transaction
 */
interface osid_transaction_TransactionSession
    extends osid_OsidSession
{


    /**
     *  Adds a Transaction to be managed by this transaction service. 
     *
     *  @param object osid_transaction_Transaction $transaction the 
     *          transaction to add 
     *  @throws osid_AlreadyExistsException transaction already added 
     *  @throws osid_IllegalStateException this transaction has ended or this 
     *          session has been closed 
     *  @throws osid_InvalidArgumentException the session doesn't support 
     *          transactions 
     *  @throws osid_NullArgumentException a <code> null </code> argument 
     *          provided 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function add(osid_transaction_Transaction $transaction);


    /**
     *  Commits the transaction and makes the state change(s) visible. This 
     *  transaction is effectively closed and the only valid method that may 
     *  be invoked is <code> getState(). </code> 
     *
     *  @throws osid_IllegalStateException this transaction has been committed 
     *          or aborted or this session has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  <code> prepare() </code> should be invoked on all regstered 
     *          transactions and iff all are successful should <code> commit() 
     *          </code> be invoked on all registered transactions. In case of 
     *          error on any <code> prepare(), </code> all transactions should 
     *          be aborted. If an error occurs on a <code> commit() </code> 
     *          after a transaction reported success on a <code> prepare() 
     *          </code> after one or more transactions were already committed, 
     *          then it is not ACID compliant and success should be assumed by 
     *          committing the rest of the transactions. If a <code> commit() 
     *          </code> error occurs when no transactions have been committed, 
     *          then this operation should not proceed. 
     */
    public function commit();


    /**
     *  Cancels this transaction, rolling back the queue of operations since 
     *  the start of this transaction. This transaction is effectively closed 
     *  and the only valid method that may be invoked is <code> getState(). 
     *  </code> 
     *
     *  @throws osid_IllegalStateException this transaction has been committed 
     *          or aborted or this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Invokes <code> abort() </code> on all registered 
     *          transactions(). 
     */
    public function abort();


    /**
     *  Gets the current state of this transaction. 
     *
     *  @return object osid_transaction_TransactionState the current state of 
     *          this transaction 
     *  @throws osid_IllegalStateException this transaction has been committed 
     *          or aborted or this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getState();

}
