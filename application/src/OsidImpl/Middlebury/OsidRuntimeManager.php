<?php

/**
 * Copyright (c) 2025 Middlebury College.
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

namespace Catalog\OsidImpl\Middlebury;

/**
 *  <p>The <code> OsidRuntimeManager </code> represents and OSID platform and
 *  contains the information required for running OSID implementations such as
 *  search paths and configurations. </p>.
 *
 *  <p> The <code> OsidRuntimeManager </code> is defined as an interface to
 *  provide flexibility for managing an OSID environment. The instantiation of
 *  a <code> OsidRuntimeManager </code> implementation is defined by the OSID
 *  platform. </p>
 *
 *  <p> The <code> OsidRuntimeManager </code> should be instantiated with a
 *  string that identifies the application or environment current at the time
 *  of instantiation. This key is used soley for the purpose of seeding the
 *  configuration service as a means to enable lower level OSIDs to tune their
 *  configuration in response to this key, or, it can be used by the
 *  application to retrieve configuration data for itself. </p>
 */
class OsidRuntimeManager extends \phpkit_AbstractOsidManager implements \osid_OsidRuntimeManager
{
    protected string $configPath;

    /*********************************************************
     * Custom bootstrapping methods
     *********************************************************/
    /**
     * Set the configuration and class paths.
     *
     * @return void
     *
     * @since 4/9/09
     */
    public function __construct(
        private \osid_configuration_ValueLookupSession $configuration,
    ) {
        parent::__construct();
        $this->setId(new \phpkit_id_URNInetId('urn:inet:middlebury.edu:id:implementations/\catalog_runtime'));
        $this->setDisplayName('Catalog Runtime Manager');
        $this->setDescription('An OSID runtime manager in the catalog application.');
    }

    /*********************************************************
     * From OsidManager
     *********************************************************/

    /**
     *  Initializes this manager. A manager is initialized once at the time of
     *  creation.
     *
     *  @param object \osid_OsidRuntimeManager $runtime the runtime environment
     *
     * @throws \osid_ConfigurationErrorException  an error with implementation
     *                                            configuration
     * @throws \osid_IllegalStateException        this manager has already been
     *                                            initialized by the <code> OsidLoader </code> or this manager
     *                                            has been shut down
     * @throws \osid_NullArgumentException <code> runtime </code> is <code>
     *                                            null </code>
     * @throws \osid_OperationFailedException     unable to complete request
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
    public function initialize(\osid_OsidRuntimeManager $runtime)
    {
        throw new \osid_IllegalStateException('Cannot intialize this runtime manager again. This runtime manager is initialized at creation only.');
    }

    /**
     *	Shuts down this <code>osid.OsidManager</code>.
     */
    public function shutdown()
    {
    }

    /*********************************************************
     * From OsidRuntimeManager
     *********************************************************/

    /**
     *  Finds, loads and instantiates providers of OSID managers. Providers
     *  must conform to an OsidManager interface. The interfaces are defined
     *  in the OSID enumeration. For all OSID requests, an instance of <code>
     *  OsidManager </code> that implements the <code> OsidManager </code>
     *  interface is returned. In bindings where permitted, this can be safely
     *  cast into the requested manager.
     *
     *  @param object \osid_OSID $osid represents the OSID
     * @param string $implClassName the name of the implementation
     * @param string $version       the minimum required interface version
     *
     * @return object \osid_OsidManager the manager of the service
     *
     * @throws \osid_NotFoundException            the implementation class name was not
     *                                            found
     * @throws \osid_NullArgumentException <code> implClassName </code> is
     *                                            <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_UnsupportedException <code>  implClassName </code> does
     *                                            not support the requested OSID
     * @throws \osid_IllegalStateException        this manager has been shut down
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  After finding and instantiating the requested <code>
     *          OsidManager, </code> providers must invoke <code>
     *          OsidManager.initialize(OsidRuntimeManager) </code> where the
     *          environment is an instance of the current environment that
     *          includes the configuration for the service being initialized.
     *          The <code> OsidRuntimeManager </code> passed may include
     *          information useful for the configuration such as the identity
     *          of the service being instantiated.
     */
    public function getManager(\osid_OSID $osid, $implClassName, $version)
    {
        $parts = explode('_', $implClassName);

        $className = implode('_', $parts);

        $manager = new $className();

        // Validate manager is an instance of the requested OSID.
        $interface = $osid->getManager();
        if (!($manager instanceof $interface)) {
            throw new \osid_UnsupportedException(get_class($manager).' is not an instance of '.$osid->manager());
        }

        $manager->initialize($this);

        return $manager;
    }

    /**
     *  Finds, loads and instantiates providers of OSID managers. Providers
     *  must conform to an <code> OsidManager </code> interface. The
     *  interfaces are defined in the OSID enumeration. For all OSID requests,
     *  an instance of <code> OsidManager </code> that implements the <code>
     *  OsidManager </code> interface is returned. In bindings where
     *  permitted, this can be safely cast into the requested manager (the
     *  only place in the specification where this is permitted). Other
     *  bindings may require a different expression of this interface to cope
     *  with constraints in the programming language.
     *
     *  @param object \osid_OSID $osid represents the OSID
     * @param string $implementation the name of the implementation
     *
     * @return object OsidProxyManager the manager of the service
     *
     * @throws \osid_NotFoundException            the implementation package was not
     *                                            found
     * @throws \osid_NullArgumentException <code> implementation </code> is
     *                                            <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_UnsupportedException <code>  implementation </code> does
     *                                            not support the requested OSID
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes After finding and instantiating the requested <code>
     *      OsidManager, </code> providers must invoke <code>
     *      OsidManager.initialize(OsidRuntimeManager) </code> where the
     *      environment is an instance of the current environment that
     *      includes the configuration for the service being initialized. The
     *      <code> OsidRuntimeManager </code> passed may include information
     *      useful for the configuration such as the identity of the service
     *      being instantiated.
     */
    public function getProxyManager(\osid_OSID $osid, $implementation, $version)
    {
        throw new \osid_UnimplementedException();
    }

    /**
     *  Gets the current configuration in the runtime environment.
     *
     * @return object \osid_configuration_ValueLookupSession a configuration
     *
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException an authorization failure
     *                                         occured
     * @throws \osid_UnimplementedException    a configuration service is not
     *                                         supported
     * @throws \osid_IllegalStateException     this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsConfiguration() </code> is <code> true. </code>
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     *  Gets the current configuration for updating in the runtime
     *  environment.
     *
     * @return object \osid_configuration_ConfigurationManager a configuration
     *                manager
     *
     * @throws \osid_OperationFailedException unable to complete request
     * @throws \osid_UnimplementedException   a configuration service is not
     *                                        supported
     * @throws \osid_IllegalStateException    this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsConfiguration() </code> is <code> true. </code>
     *
     *  @notes  A configuration service may provide user-specific
     *          configurations by making use of an authentication service.
     */
    public function getConfigurationManager()
    {
        throw new \osid_UnimplementedException();
    }

    /**
     *  Gets the installation manager used in the runtime environment.
     *
     * @return object \osid_installation_InstallationManager a configuration
     *                manager
     *
     * @throws \osid_OperationFailedException unable to complete request
     * @throws \osid_UnimplementedException   a configuration service is not
     *                                        supported
     * @throws \osid_IllegalStateException    this manager has been shut down
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsInstallation() </code> is <code> true. </code>
     */
    public function getInstallationManager()
    {
        throw new \osid_UnimplementedException();
    }

    /*********************************************************
     * From OsidRuntimeProfile
     *********************************************************/

    /**
     *  Tests if a configuration service is provided within this runtime
     *  environment.
     *
     * @return bool <code> true </code> if a configuration service is available,
     *                     <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsConfiguration()
    {
        return false;
    }

    /**
     *  Tests if an installation service is provided within this runtime
     *  environment.
     *
     * @return bool <code> true </code> if a installation service is available,
     *                     <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsInstallation()
    {
        return false;
    }
}
