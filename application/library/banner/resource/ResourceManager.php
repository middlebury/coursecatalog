<?php
/**
 * @since 5/04/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>The resource manager provides access to resource lookup and creation
 *  sessions and provides interoperability tests for various aspects of this
 *  service. The sessions included in this manager are:
 *  <ul>
 *      <li> <code> ResourceLookupSession: </code> a session to retrieve
 *      resources </li>
 *      <li> <code> ResourceSearchSession: </code> a session to search for
 *      resources </li>
 *      <li> <code> ResourceAdminSession: </code> a session to create and
 *      delete resources </li>
 *      <li> <code> ResourceNotificationSession: </code> a session to receive
 *      notifications pertaining to resource changes </li>
 *      <li> <code> ResourceBinSession: </code> a session to look up resource
 *      to bin mappings </li>
 *      <li> <code> ResourceBinAssignmentSession: </code> a session to manage
 *      resource to bin mappings </li>
 *  </ul>.
 *
 *  <ul>
 *      <li> <code> BinLookupSession: a </code> session to retrieve bins </li>
 *      <li> <code> BinSearchSession: </code> a session to search for bins
 *      </li>
 *      <li> <code> BinAdminSession: </code> a session to create, update and
 *      delete bins </li>
 *      <li> <code> BinNotificationSession: </code> a session to receive
 *      notifications pertaining to changes in bins </li>
 *      <li> <code> BinHierarchySession: </code> a session to traverse bin
 *      hierarchies </li>
 *      <li> <code> BinHierarchyDesignSession: </code> a session to manage bin
 *      hierarchies </li>
 *  </ul>
 *  </p>
 */
class banner_resource_ResourceManager extends phpkit_AbstractOsidManager implements osid_resource_ResourceManager, banner_resource_ResourceManagerInterface
{
    /*********************************************************
     * From banner_ManagerInterface
     *********************************************************/
    /**
     * Answer the database connection.
     *
     * @return PDO
     *
     * @since 4/13/09
     */
    public function getDB()
    {
        return $this->db;
    }

    /**
     * Answer the Identifier authority to use.
     *
     * @return string
     *
     * @since 4/13/09
     */
    public function getIdAuthority()
    {
        return $this->idAuthority;
    }

    /**
     * Answer the Id of the 'All'/'Combined' resource bin.
     *
     * @return osid_id_Id
     *
     * @since 4/20/09
     */
    public function getCombinedBinId()
    {
        return new phpkit_id_Id($this->getIdAuthority(), 'urn', 'resource.all');
    }

    /*********************************************************
     * From OsidManager
     *********************************************************/

    /**
     *  Initializes this manager. A manager is initialized once at the time of
     *  creation.
     *
     *  @param object osid_OsidRuntimeManager $runtime the runtime environment
     *
     * @throws osid_ConfigurationErrorException  an error with implementation
     *                                           configuration
     * @throws osid_IllegalStateException        this manager has already been
     *                                           initialized by the <code> OsidLoader </code> or this manager
     *                                           has been shut down
     * @throws osid_NullArgumentException <code> runtime </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  In addition to loading its runtime configuration an
     *          implementation may create shared resources such as connection
     *          pools to be shared among all sessions of this service and
     *          released when this manager is closed. Providers must
     *          thread-protect any data stored in the manager.
     *          <br/><br/>
     *          To maximize interoperability, providers should not honor a
     *          second call to <code> initialize() </code> and must set an
     *          <code> ILLEGAL_STATE </code> error.
     */
    public function initialize(osid_OsidRuntimeManager $runtime)
    {
        parent::initialize($runtime);
        $runtime = $this->impl_getRuntimeManager();

        try {
            $dsn = phpkit_configuration_ConfigUtil::getSingleValuedValue(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.pdo_dsn'),
                new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));

            $username = phpkit_configuration_ConfigUtil::getSingleValuedValue(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.pdo_username'),
                new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));

            $password = phpkit_configuration_ConfigUtil::getSingleValuedValue(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.pdo_password'),
                new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));
        } catch (osid_NotFoundException $e) {
            throw new osid_ConfigurationErrorException($e->getMessage(), $e->getCode());
        }

        try {
            $debug = phpkit_configuration_ConfigUtil::getSingleValuedValue(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.pdo_count_queries'),
                new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/Boolean'));
        } catch (osid_ConfigurationErrorException $e) {
            $debug = false;
        }

        try {
            if ($debug) {
                $this->db = new PDODebug_PDO($dsn, $username, $password);
            } else {
                $this->db = new PDO($dsn, $username, $password);
            }
        } catch (PDOException $e) {
            throw new osid_ConfigurationErrorException($e->getMessage(), $e->getCode());
        }

        try {
            $this->idAuthority = phpkit_configuration_ConfigUtil::getSingleValuedValue(
                $runtime->getConfiguration(),
                new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:banner_course.id_authority'),
                new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));
            if (!strlen($this->idAuthority)) {
                throw new osid_ConfigurationErrorException('urn:inet:middlebury.edu:config:banner_course.id_authority must be specified.');
            }
        } catch (osid_NotFoundException $e) {
            throw new osid_ConfigurationErrorException($e->getMessage(), $e->getCode());
        }
    }

    /*********************************************************
     * From osid_resource_ResourceManager
     *********************************************************/

    /**
     *  Gets the <code> OsidSession </code> associated with the resource
     *  lookup service.
     *
     * @return object osid_resource_ResourceLookupSession <code> a
     *                ResourceLookupSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsResourceLookup()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsResourceLookup() </code> is <code> true. </code>
     */
    public function getResourceLookupSession()
    {
        return new banner_resource_Resource_Lookup_CombinedSession($this);
    }

    /**
     *  Gets the <code> OsidSession </code> associated with the resource
     *  lookup service for the given bin.
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin
     *
     * @return object osid_resource_ResourceLookupSession <code> a
     *                ResourceLookupSession </code>
     *
     * @throws osid_NotFoundException <code>        binId </code> not found
     * @throws osid_NullArgumentException <code>    binId </code> is <code> null
     *                                              </code>
     * @throws osid_OperationFailedException <code> unable to complete
     *                                              request </code>
     * @throws osid_UnimplementedException <code>   supportsResourceLookup()
     *                                              </code> or <code> supportsVisibleFederation() </code> is
     *                                              <code> false </code>
     * @throws osid_IllegalStateException           this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsResourceLookup() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true.
     *              </code>
     */
    public function getResourceLookupSessionForBin(osid_id_Id $binId)
    {
        if ($binId->isEqual($this->getCombinedBinId())) {
            return $this->getResourceLookupSession();
        } else {
            return new banner_resource_Resource_Lookup_PerCatalogSession($this, $binId);
        }
    }

    /**
     *  Gets a resource search session.
     *
     * @return object osid_resource_ResourceSearchSession <code> a
     *                ResourceSearchSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsResourceSearch()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsResourceSearch() </code> is <code> true. </code>
     */
    public function getResourceSearchSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets a resource search session for the given bin.
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin
     *
     * @return object osid_resource_ResourceSearchSession <code> a
     *                ResourceSearchSession </code>
     *
     * @throws osid_NotFoundException <code>        binId </code> not found
     * @throws osid_NullArgumentException <code>    binId </code> is <code> null
     *                                              </code>
     * @throws osid_OperationFailedException <code> unable to complete
     *                                              request </code>
     * @throws osid_UnimplementedException <code>   supportsResourceSearch()
     *                                              </code> or <code> supportsVisibleFederation() </code> is
     *                                              <code> false </code>
     * @throws osid_IllegalStateException           this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsResourceSearch() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true.
     *              </code>
     */
    public function getResourceSearchSessionForBin(osid_id_Id $binId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets a resource administration session for creating, updating and
     *  deleting resources.
     *
     * @return object osid_resource_ResourceAdminSession <code> a
     *                ResourceAdminSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsResourceAdmin()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsResourceAdmin() </code> is <code> true. </code>
     */
    public function getResourceAdminSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets a resource administration session for the given bin.
     *
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin
     *
     * @return object osid_resource_ResourceAdminSession <code> a
     *                ResourceAdminSession </code>
     *
     * @throws osid_NotFoundException <code>      binId </code> not found
     * @throws osid_NullArgumentException <code>  binId </code> is <code> null
     *                                            </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsResourceAdmin()
     *                                            </code> or <code> supportsVisibleFederation() </code> is
     *                                            <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsResourceAdmin() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true.
     *              </code>
     */
    public function getResourceAdminSessionForBin(osid_id_Id $binId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the notification session for notifications pertaining to resource
     *  changes.
     *
     *  @param object osid_resource_ResourceReceiver $receiver the
     *          notification callback
     *
     * @return object osid_resource_ResourceNotificationSession <code> a
     *                ResourceNotificationSession </code>
     *
     * @throws osid_NullArgumentException <code>  receiver </code> is <code>
     *                                            null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsResourceNotification() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsResourceNotification() </code> is <code> true.
     *              </code>
     */
    public function getResourceNotificationSession(osid_resource_ResourceReceiver $receiver)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the resource notification session for the given bin.
     *
     *  @param object osid_resource_ResourceReceiver $receiver the
     *          notification callback
     *  @param object osid_id_Id $binId the <code> Id </code> of the bin
     *
     * @return object osid_resource_ResourceNotificationSession <code> a
     *                ResourceNotificationSession </code>
     *
     * @throws osid_NotFoundException <code>        binId </code> not found
     * @throws osid_NullArgumentException <code>    receiver </code> or <code>
     *                                              binId </code> is <code> null </code>
     * @throws osid_OperationFailedException <code> unable to complete
     *                                              request </code>
     * @throws osid_UnimplementedException <code>
     *                                              supportsResourceNotification() </code> or <code>
     *                                              supportsVisibleFederation() </code> is <code> false </code>
     * @throws osid_IllegalStateException           this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsResourceNotfication() </code> and <code>
     *              supportsVisibleFederation() </code> are <code> true.
     *              </code>
     */
    public function getResourceNotificationSessionForBin(osid_resource_ResourceReceiver $receiver,
        osid_id_Id $binId)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the session for retrieving resource to bin mappings.
     *
     * @return object osid_resource_ResourceBinSession a <code>
     *                ResourceBinSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsResourceBin()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsResourceBin() </code> is <code> true. </code>
     */
    public function getResourceBinSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the session for assigning resource to bin mappings.
     *
     * @return object osid_resource_ResourceBinAssignmentSession a <code>
     *                ResourceBinAssignmentSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsResourceBinAssignment() </code> is <code> false
     *                                            </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsResourceBinAssignment() </code> is <code> true.
     *              </code>
     */
    public function getResourceBinAssignmentSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the bin lookup session.
     *
     * @return object osid_resource_BinLookupSession a <code>
     *                BinLookupSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsBinLookup() </code>
     *                                            is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsBinLookup() </code> is <code> true. </code>
     */
    public function getBinLookupSession()
    {
        return new banner_resource_Bin_Lookup_Session($this);
    }

    /**
     *  Gets the bin search session.
     *
     * @return object osid_resource_BinSearchSession a <code>
     *                BinSearchSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsBinSearch() </code>
     *                                            is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsBinSearch() </code> is <code> true. </code>
     */
    public function getBinSearchSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the bin administrative session for creating, updating and
     *  deleteing bins.
     *
     * @return object osid_resource_BinAdminSession a <code> BinAdminSession
     *                </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsBinAdmin() </code>
     *                                            is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsBinAdmin() </code> is <code> true. </code>
     */
    public function getBinAdminSession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the notification session for subscribing to changes to a bin.
     *
     *  @param object osid_resource_BinReceiver $receiver the notification
     *          callback
     *
     * @return object osid_resource_BinNotificationSession a <code>
     *                BinNotificationSession </code>
     *
     * @throws osid_NullArgumentException <code>  receiver </code> is <code>
     *                                            null </code>
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsBinNotification()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsBinNotification() </code> is <code> true. </code>
     */
    public function getBinNotificationSession(osid_resource_BinReceiver $receiver)
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the bin hierarchy traversal session.
     *
     * @return object osid_resource_BinHierarchySession <code> a
     *                BinHierarchySession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code> supportsBinHierarchy()
     *                                            </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsBinHierarchy() </code> is <code> true. </code>
     */
    public function getBinHierarchySession()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the bin hierarchy design session.
     *
     * @return object osid_resource_BinHierarchyDesignSession a <code>
     *                BinHierarchyDesignSession </code>
     *
     * @throws osid_OperationFailedException      unable to complete request
     * @throws osid_UnimplementedException <code>
     *                                            supportsBinHierarchyDesign() </code> is <code> false </code>
     * @throws osid_IllegalStateException         this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsBinHierarchyDesign() </code> is <code> true.
     *              </code>
     */
    public function getBinHierarchyDesignSession()
    {
        throw new osid_UnimplementedException();
    }

    /*********************************************************
     * From osid_resource_ResourceProfile
     *********************************************************/

    /**
     *  Tests if federation is visible.
     *
     * @return boolean <code> true </code> if visible federation is supported
     *                        <code> , </code> <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsVisibleFederation()
    {
        return false;
    }

    /**
     *  Tests if resource lookup is supported.
     *
     * @return boolean <code> true </code> if resource lookup is supported
     *                        <code> , </code> <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsResourceLookup()
    {
        return true;
    }

    /**
     *  Tests if resource search is supported.
     *
     * @return boolean <code> true </code> if resource search is supported
     *                        <code> , </code> <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsResourceSearch()
    {
        return false;
    }

    /**
     *  Tests if resource administration is supported.
     *
     * @return boolean <code> true </code> if resource administration is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsResourceAdmin()
    {
        return false;
    }

    /**
     *  Tests if resource notification is supported. Messages may be sent when
     *  resources are created, modified, or deleted.
     *
     * @return boolean <code> true </code> if resource notification is
     *                        supported <code> , </code> <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsResourceNotification()
    {
        return false;
    }

    /**
     *  Tests if rerieving mappings of resource and bins is supported.
     *
     * @return boolean <code> true </code> if resource bin mapping retrieval
     *                        is supported <code> , </code> <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsResourceBin()
    {
        return false;
    }

    /**
     *  Tests if managing mappings of resources and bins is supported.
     *
     * @return boolean <code> true </code> if resource bin assignment is
     *                        supported <code> , </code> <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsResourceBinAssignment()
    {
        return false;
    }

    /**
     *  Tests if bin lookup is supported.
     *
     * @return boolean <code> true </code> if bin lookup is supported <code>
     *                        , </code> <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsBinLookup()
    {
        return true;
    }

    /**
     *  Tests if bin search is supported.
     *
     * @return boolean <code> true </code> if bin search is supported <code>
     *                        , </code> <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsBinSearch()
    {
        return false;
    }

    /**
     *  Tests if bin administration is supported.
     *
     * @return boolean <code> true </code> if bin administration is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsBinAdmin()
    {
        return false;
    }

    /**
     *  Tests if bin notification is supported. Messages may be sent when
     *  <code> Bin </code> objects are created, deleted or updated.
     *  Notifications for resources within bins are sent via the resource
     *  notification session.
     *
     * @return boolean <code> true </code> if bin notification is supported
     *                        <code> , </code> <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsBinNotification()
    {
        return false;
    }

    /**
     *  Tests if a bin hierarchy traversal is supported.
     *
     * @return boolean <code> true </code> if a bin hierarchy traversal is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsBinHierarchy()
    {
        return false;
    }

    /**
     *  Tests if a bin hierarchy design is supported.
     *
     * @return boolean <code> true </code> if a bin hierarchy design is
     *                        supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsBinHierarchyDesign()
    {
        return false;
    }

    /**
     *  Tests if the bin hierarchy supports node sequencing.
     *
     * @return boolean <code> true </code> if bin hierarchy node sequencing
     *                        is supported, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsBinHierarchySequencing()
    {
        return false;
    }

    /**
     *  Gets all the resource record types supported.
     *
     * @return object osid_type_TypeList the list of supported resource
     *                record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResourceRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if a given resource record type is supported.
     *
     *  @param object osid_type_Type $resourceRecordType the resource type
     *
     * @return boolean <code> true </code> if the resource record type is
     *                        supported <code> , </code> <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsResourceRecordType(osid_type_Type $resourceRecordType)
    {
        return false;
    }

    /**
     *  Gets all the resource search record types supported.
     *
     * @return object osid_type_TypeList the list of supported resource
     *                search record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResourceSearchRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if a given resource search type is supported.
     *
     *  @param object osid_type_Type $resourceSearchRecordType the resource
     *          search type
     *
     * @return boolean <code> true </code> if the resource search record type
     *                        is supported <code> , </code> <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsResourceSearchRecordType(osid_type_Type $resourceSearchRecordType)
    {
        return false;
    }

    /**
     *  Gets all the bin record types supported.
     *
     * @return object osid_type_TypeList the list of supported bin record
     *                types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getBinRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if a given bin record type is supported.
     *
     *  @param object osid_type_Type $binRecordType the bin record type
     *
     * @return boolean <code> true </code> if the bin record type is
     *                        supported <code> , </code> <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsBinRecordType(osid_type_Type $binRecordType)
    {
        return false;
    }

    /**
     *  Gets all the bin search record types supported.
     *
     * @return object osid_type_TypeList the list of supported bin search
     *                record types
     *
     * @throws osid_IllegalStateException this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getBinSearchRecordTypes()
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Tests if a given bin search record type is supported.
     *
     *  @param object osid_type_Type $binSearchRecordType the bin search
     *          record type
     *
     * @return boolean <code> true </code> if the bin search record type is
     *                        supported <code> , </code> <code> false </code> otherwise
     *
     * @throws osid_NullArgumentException null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsBinSearchRecordType(osid_type_Type $binSearchRecordType)
    {
        return false;
    }
}
