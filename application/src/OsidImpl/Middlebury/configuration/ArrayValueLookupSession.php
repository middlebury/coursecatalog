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

namespace Catalog\OsidImpl\Middlebury\configuration;

/**
 *  <p>This session is used to retrieve configuration values. Two views of the
 *  configuration data are defined; </p>.
 *
 *  <p>
 *  <ul>
 *      <li> federated: parameters defined in configurations that are a parent
 *      of this configuration in the configuration hierarchy are included
 *      </li>
 *      <li> isolated: parameters are contained to within this configuration
 *      </li>
 *  </ul>
 *  Values are not OSID objects and are obtained using a reference to a
 *  Parameter. </p>
 */
class ArrayValueLookupSession implements \osid_configuration_ValueLookupSession
{
    /**
     * Constructor.
     *
     * @param optional string $configPath If not specified, will default to the directory of the original script
     *
     * @return void
     *
     * @since 10/30/08
     */
    public function __construct(
        private \osid_id_Id $id,
        private array $configuration,
    ) {
    }

    /**
     *  Gets the <code> Configuration </code> <code> Id </code> associated
     *  with this session.
     *
     * @return object \osid_configuration_Configuration the <code>
     *                Configuration </code> <code> Id </code> associated with this
     *                session
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getConfigurationId()
    {
        return $this->id;
    }

    /**
     *  Gets the <code> Configuration </code> associated with this session.
     *
     * @return object \osid_configuration_Configuration the <code>
     *                Configuration </code> associated with this session
     *
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException authorization failure
     * @throws \osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getConfiguration()
    {
        throw new \osid_OperationFailedException('Unimplemented');
    }

    /**
     *  Tests if this user can perform <code> Value </code> lookups. A return
     *  of true does not guarantee successful authorization. A return of false
     *  indicates that it is known all methods in this session will result in
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an
     *  application that may opt not to offer lookup operations to
     *  unauthorized users.
     *
     * @return bool <code> false </code> if lookup methods are not
     *                     authorized, <code> true </code> otherwise
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canLookupValues()
    {
        return true;
    }

    /**
     *  Federates the view for methods in this session. A federated view will
     *  include values from parent configurations in the configuration
     *  hierarchy.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useFederatedConfigurationView()
    {
        // Not doing anything for now.
    }

    /**
     *  Isolates the view for methods in this session. An isolated view
     *  restricts lookups to this configuration only.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useIsolatedConifgurationView()
    {
        // Not doing anything for now.
    }

    /**
     *  Gets the <code> Values </code> with the given <code> Id. </code>.
     *
     *  @param object \osid_id_Id $parameterId the <code> Id </code> of the
     *          <code> Parameter </code> to retrieve
     *
     * @return object \osid_configuration_ValueList the value list
     *
     * @throws \osid_NotFoundException         the <code> parameterId </code> not
     *                                         found
     * @throws \osid_NullArgumentException     the <code> parameterId </code> is
     *                                         <code> null </code>
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException authorization failure
     * @throws \osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getParameterValues(\osid_id_Id $parameterId)
    {
        return new \phpkit_configuration_ArrayValueList($this->getValues($parameterId));
    }

    /**
     *  Gets the <code> Parameter </code> values with the given <code> Id.
     *  </code> The returned array is sorted by the value index.
     *
     *  @param object \osid_id_Id $parameterId the <code> Id </code> of the
     *          <code> Parameter </code> to retrieve
     *
     * @return array of objects the value list
     *
     * @throws \osid_NotFoundException         the <code> parameterId </code> not
     *                                         found
     * @throws \osid_NullArgumentException     the <code> parameterId </code> is
     *                                         <code> null </code>
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException authorization failure
     * @throws \osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getValues(\osid_id_Id $parameterId)
    {
        if (!isset($this->configuration)) {
            throw new \osid_IllegalStateException('this session has been closed');
        }

        $idString = \phpkit_id_URNInetId::getInetURNString($parameterId);
        $values = [];
        $index = 1;
        $found = false;
        foreach ($this->configuration as $item) {
            if ($item['id'] == $idString) {
                $found = true;
                if (isset($item['values'])) {
                    foreach ($item['values'] as $key => $value) {
                        $values[$key] = new Value($parameterId, $index, $value);
                        ++$index;
                    }
                } elseif (isset($item['value'])) {
                    $values[] = new Value($parameterId, $index, $item['value']);
                    ++$index;
                } else {
                    throw new \osid_OperationFailedException("Configuration key $idString must have either a 'value' or 'values' property.");
                }
            }
        }
        if ($found) {
            return $values;
        } else {
            throw new \osid_NotFoundException("Configuration key $idString is not set.");
        }
    }

    /*********************************************************
     * Methods from \osid_OsidSession
     *********************************************************/
    /**
     *  <p>The <code> OsidSession </code> is the top level interface for all OSID
     *  sessions. An <code> OsidSession </code> is created through its
     *  corresponding <code> OsidManager. </code> A new <code> OsidSession </code>
     *  should be created for each user of a service and for each processing
     *  thread. A session maintains a single authenticated user and is not
     *  required to ensure thread-protection. A typical OSID session defines a set
     *  of service methods corresponding to some compliance level as defined by
     *  the service and is generally responsible for the management and retrieval
     *  of <code> OsidObjects. </code> </p>.
     *
     *  <p> <code> OsidSession </code> defines a set of common methods used
     *  throughout all OSID sessions. An OSID session may optionally support
     *  transactions through the transaction interface. </p>
     */

    /**
     *  Tests if there are valid authentication credentials used by this
     *  service.
     *
     *  @return <code> true </code> if valid authentication credentials exist,
     *          <code> false </code> otherwise
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  Providers must also query <code> OsidSessions </code>
     *          instantiated by this session.
     */
    public function isAuthenticated()
    {
        if (!isset($this->doc)) {
            throw new \osid_IllegalStateException('This session has been closed');
        }

        return false;
    }

    /**
     *  Gets the authenticated identities used by this service to give the
     *  user feedback as to which of the Agent identitites are actively being
     *  used on the user's behalf.
     *
     * @return the list of authenticated Agents
     *
     * @throws \osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  Providers must also include any authenticated <code> Agents
     *          </code> from all <code> OsidSessions </code> instantiated by
     *          this service.
     */
    public function getAuthenticatedAgents()
    {
        if (!isset($this->doc)) {
            throw new \osid_IllegalStateException('This session has been closed');
        }

        return new \phpkit_authentication_ArrayAgentList([]);
    }

    /**
     *  Tests for the availability of transactions.
     *
     *  @return <code> true </code> if transaction methods are available,
     *          <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTransactions()
    {
        return false;
    }

    /**
     *  Starts a new transaction for this sesson. Transactions are a means for
     *  an OSID to provide an all-or-nothing set of operations within a
     *  session and may be used to coordinate this service from an external
     *  transaction manager. A session supports one transaction at a time.
     *  Starting a second transaction before the previous has been committed
     *  or aborted results in an <code> ILLEGAL_STATE </code> error.
     *
     * @return a new transaction
     *
     * @throws \osid_IllegalStateException    a transaction is already open or
     *                                        this session has been closed
     * @throws \osid_OperationFailedException unable to complete request
     * @throws \osid_UnsupportedException     transactions not supported
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTransactions() </code> is true.
     *
     *  @notes  Ideally, a provider that supports transactions should
     *          guarantee atomicity, consistency, isolation and durability in
     *          a 2 phase commit process. This is not always possible in
     *          distributed systems and a transaction provider may simply
     *          allow for a means of processing bulk updates.
     *          <br/><br/>
     *          To maximize interoperability, providers should honor the
     *          one-transaction-at-a-time rule.
     */
    public function startTransaction()
    {
        throw new \osid_UnsupportedException();
    }

    /**
     *  Closes this <code>osid.OsidSession</code>.
     */
    public function close()
    {
        unset($this->configuration);
    }
}
