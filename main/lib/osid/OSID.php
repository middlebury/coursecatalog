<?php

/**
 * osid_OSID
 * 
 *     Specifies the OSID definition for osid_OSID.
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
 *  This enumeration contains the list of OSIDs. 
 */

class osid_OSID {

    /** The Assessment Open Service Interface Definition. */
    public static function ASSESSMENT() {
        return new osid_OSID ("assessment", null, "osid_assessment_AssessmentManager", "osid_assessment_AssessmentProxyManager", "The Assessment Open Service Interface Definition.");
    }

    /** The Authentication Open Service Interface Definition. */
    public static function AUTHENTICATION() {
        return new osid_OSID ("authentication", null, "osid_authentication_AuthenticationManager", "osid_authentication_AuthenticationProxyManager", "The Authentication Open Service Interface Definition.");
    }

    /** The Authorization Open Service Interface Definition. */
    public static function AUTHORIZATION() {
        return new osid_OSID ("authorization", null, "osid_authorization_AuthorizationManager", "osid_authorization_AuthorizationProxyManager", "The Authorization Open Service Interface Definition.");
    }

    /** The Catalogging Open Service Interface Definition. */
    public static function CATALOGGING() {
        return new osid_OSID ("catalogging", null, "osid_catalogging_CataloggingManager", "osid_catalogging_CataloggingProxyManager", "The Catalogging Open Service Interface Definition.");
    }

    /** The Configuration Open Service Interface Definition. */
    public static function CONFIGURATION() {
        return new osid_OSID ("configuration", null, "osid_configuration_ConfigurationManager", "osid_configuration_ConfigurationProxyManager", "The Configuration Open Service Interface Definition.");
    }

    /** The Course Open Service Interface Definition. */
    public static function COURSE() {
        return new osid_OSID ("course", null, "osid_course_CourseManager", "osid_course_CourseProxyManager", "The Course Open Service Interface Definition.");
    }

    /** The Dictionary Open Service Interface Definition. */
    public static function DICTIONARY() {
        return new osid_OSID ("dictionary", null, "osid_dictionary_DictionaryManager", "osid_dictionary_DictionaryProxyManager", "The Dictionary Open Service Interface Definition.");
    }

    /** The Filing Open Service Interface Definition. */
    public static function FILING() {
        return new osid_OSID ("filing", null, "osid_filing_FilingManager", "osid_filing_FilingProxyManager", "The Filing Open Service Interface Definition.");
    }

    /** The Grading Open Service Interface Definition. */
    public static function GRADING() {
        return new osid_OSID ("grading", null, "osid_grading_GradingManager", "osid_grading_GradingProxyManager", "The Grading Open Service Interface Definition.");
    }

    /** The Hierarchy Open Service Interface Definition. */
    public static function HIERARCHY() {
        return new osid_OSID ("hierarchy", null, "osid_hierarchy_HierarchyManager", "osid_hierarchy_HierarchyProxyManager", "The Hierarchy Open Service Interface Definition.");
    }

    /** The Id Open Service Interface Definition. */
    public static function ID() {
        return new osid_OSID ("id", null, "osid_id_IdManager", "osid_id_IdProxyManager", "The Id Open Service Interface Definition.");
    }

    /** The Installation Open Service Interface Definition. */
    public static function INSTALLATION() {
        return new osid_OSID ("installation", null, "osid_installation_InstallationManager", "osid_installation_InstallationProxyManager", "The Installation Open Service Interface Definition.");
    }

    /** The Locale Open Service Interface Definition. */
    public static function LOCALE() {
        return new osid_OSID ("locale", null, "osid_locale_LocaleManager", "osid_locale_LocaleProxyManager", "The Locale Open Service Interface Definition.");
    }

    /** The Logging Open Service Interface Definition. */
    public static function LOGGING() {
        return new osid_OSID ("logging", null, "osid_logging_LoggingManager", "osid_logging_LoggingProxyManager", "The Logging Open Service Interface Definition.");
    }

    /** The Messaging Open Service Interface Definition. */
    public static function MESSAGING() {
        return new osid_OSID ("messaging", null, "osid_messaging_MessagingManager", "osid_messaging_MessagingProxyManager", "The Messaging Open Service Interface Definition.");
    }

    /** The Provisioning Open Service Interface Definition. */
    public static function PROVISIONING() {
        return new osid_OSID ("provisioning", null, "osid_provisioning_ProvisioningManager", "osid_provisioning_ProvisioningProxyManager", "The Provisioning Open Service Interface Definition.");
    }

    /** The Repository Open Service Interface Definition. */
    public static function REPOSITORY() {
        return new osid_OSID ("repository", null, "osid_repository_RepositoryManager", "osid_repository_RepositoryProxyManager", "The Repository Open Service Interface Definition.");
    }

    /** The Resource Open Service Interface Definition. */
    public static function RESOURCE() {
        return new osid_OSID ("resource", null, "osid_resource_ResourceManager", "osid_resource_ResourceProxyManager", "The Resource Open Service Interface Definition.");
    }

    /** The Scheduling Open Service Interface Definition. */
    public static function SCHEDULING() {
        return new osid_OSID ("scheduling", null, "osid_scheduling_SchedulingManager", "osid_scheduling_SchedulingProxyManager", "The Scheduling Open Service Interface Definition.");
    }

    /** The Topology Open Service Interface Definition. */
    public static function TOPOLOGY() {
        return new osid_OSID ("topology", null, "osid_topology_TopologyManager", "osid_topology_TopologyProxyManager", "The Topology Open Service Interface Definition.");
    }

    /** The Transaction Open Service Interface Definition. */
    public static function TRANSACTION() {
        return new osid_OSID ("transaction", null, "osid_transaction_TransactionManager", "osid_transaction_TransactionProxyManager", "The Transaction Open Service Interface Definition.");
    }

    /** The Transport Open Service Interface Definition. */
    public static function TRANSPORT() {
        return new osid_OSID ("transport", null, "osid_transport_TransportManager", "osid_transport_TransportProxyManager", "The Transport Open Service Interface Definition.");
    }

    /** The Type Open Service Interface Definition. */
    public static function TYPE() {
        return new osid_OSID ("type", null, "osid_type_TypeManager", "osid_type_TypeProxyManager", "The Type Open Service Interface Definition.");
    }

    /** The Workflow Open Service Interface Definition. */
    public static function WORKFLOW() {
        return new osid_OSID ("workflow", null, "osid_workflow_WorkflowManager", "osid_workflow_WorkflowProxyManager", "The Workflow Open Service Interface Definition.");
    }


    public static function values() {
        $ret = array();
        $ref = new ReflectionClass(__CLASS__);
        $properties = $ref->getProperties();
        foreach ($properties as $property)
            $ret[$property->getName()] = $property->getValue();
        return $ret;
    }


    private $service;
    private $osid;
    private $manager;
    private $proxyManager;
    private $description;

    public function __construct($service, $osid, $manager, $proxyManager, $description) {
        $this->service = $service;
        $this->osid = $osid;
        $this->manager = $manager;
        $this->proxyManager = $proxyManager;
        $this->description = $description;
    }

    public function getOSIDServiceName() {
        return $this->service;
    }

    public function getOSIDPackageName() {
        return $this->osid;
    }

    public function getManager() {
        return $this->manager;
    }

    public function getProxyManager() {
        return $this->proxyManager;
    }

    public function getDescription() {
        return $this->description;
    }
}

