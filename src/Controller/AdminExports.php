<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminExports extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Manage archive export configurations.
     */
    #[Route('/admin/export', name: 'admin_archive_config')]
    public function exportAction()
    {
        $db = Zend_Registry::get('db');

        $this->view->configs = $db->query('SELECT * FROM archive_configurations')->fetchAllAssociative();

        $this->view->config = null;
        if ($this->_getParam('config') && -1 != $this->_getParam('config')) {
            foreach ($this->view->configs as $config) {
                if ($config['id'] == $this->_getParam('config')) {
                    $this->view->config = $config;
                }
            }
        }

        // If user has selected a configuration to modify, get the latest revision.
        if (isset($this->view->config)) {
            $catalogId = $this->_helper->osidId->fromString($this->view->config['catalog_id']);
            $this->view->catalogId = $this->view->config['catalog_id'];
        }
    }

    /**
     * Manage catalog archive scheduling.
     */
    #[Route('/admin/export', name: 'admin_archive_schedule')]
    public function scheduleAction()
    {
    }
}
