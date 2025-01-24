<?php

namespace App\Controller;

use App\Service\Osid\Runtime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminExportJobs extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Runtime $osidRuntime,
    ) {
    }

    /**
     * Manage catalog archive scheduling.
     */
    #[Route('/admin/exports/jobs', name: 'export_list_jobs')]
    public function jobsAction()
    {
        $data['page_title'] = 'Manage Catalog Export Job Scheduling';

        return $this->render('admin/export/jobs.html.twig', $data);
    }

    /**
     * Echo JSON data of all archive export jobs.
     *
     * @return void
     *
     * @since 1/23/18
     */
    #[Route('/admin/exports/jobs.json', name: 'export_list_jobs_json')]
    public function listjobsAction()
    {
        $db = $this->entityManager->getConnection();
        $configs = $db->executeQuery('SELECT * FROM archive_configurations')->fetchAllAssociative();
        $revisions = $db->executeQuery('SELECT `id`, `note`, `last_saved`, `arch_conf_id` FROM archive_configuration_revisions ORDER BY last_saved DESC')->fetchAllAssociative();
        $jobs = $db->executeQuery('SELECT * FROM archive_jobs ORDER BY terms DESC')->fetchAllAssociative();

        $data = [
            'configs' => $configs,
            'revisions' => $revisions,
            'jobs' => $jobs,
        ];

        return new JsonResponse($data);
    }

    /**
     * Provide an interface for creating a new archive export job.
     *
     * @return void
     *
     * @since 1/23/18
     */
    #[Route('/admin/exports/jobs/new', name: 'export_new_job_form')]
    public function newjobAction(Request $request)
    {
        $data['page_title'] = 'Create new Catalog Export job';

        $db = $this->entityManager->getConnection();
        $data['configs'] = $db->executeQuery('SELECT * FROM archive_configurations')->fetchAllAssociative();

        $data['config'] = null;
        $configLabel = $request->get('config');
        if ($configLabel) {
            foreach ($data['configs'] as $config) {
                if ($config['label'] === $configLabel) {
                    $data['config'] = $config;
                }
            }
        }

        return $this->render('admin/export/new_job.html.twig', $data);
    }

    /**
     * Delete an archive export job.
     *
     * @return void
     *
     * @since 1/23/18
     */
    #[Route('/admin/exports/jobs/{job}/delete', name: 'export_delete_job', methods: ['POST'])]
    public function deletejobAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        // Delete revisions that depend on this config.
        $db = Zend_Registry::get('db');
        $query = 'DELETE FROM archive_jobs WHERE id = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([filter_var($this->getRequest()->getPost('jobId'), \FILTER_SANITIZE_NUMBER_INT)]);
    }

    /**
     * Insert a new archive export job into the DB.
     *
     * @return void
     *
     * @since 1/23/18
     */
    #[Route('/admin/exports/jobs/insert', name: 'export_insert_job', methods: ['POST'])]
    public function insertjobAction(Request $request)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-export-insert-job', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $safeConfigId = filter_var($request->get('configId'), \FILTER_SANITIZE_SPECIAL_CHARS);
        $safeExportPath = filter_var($request->get('export_path'), \FILTER_SANITIZE_SPECIAL_CHARS);
        $safeTerms = filter_var($request->get('terms'), \FILTER_SANITIZE_SPECIAL_CHARS);

        $db = $this->entityManager->getConnection();
        $query =
        'INSERT INTO archive_jobs (id, active, export_path, config_id, revision_id, terms)
  VALUES (NULL, 1, :export_path, :config_id, NULL, :terms)';
        $stmt = $db->prepare($query);
        $stmt->bindValue('export_path', $safeExportPath);
        $stmt->bindValue('config_id', $safeConfigId);
        $stmt->bindValue('terms', $safeTerms);
        $stmt->executeQuery();

        return $this->redirectToRoute('export_list_jobs');
    }

    /**
     * Update an existing archive export job.
     *
     * @return void
     *
     * @since 1/23/18
     */
    #[Route('/admin/exports/jobs/{job}/update', name: 'export_update_job', methods: ['POST'])]
    public function updatejobAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->getRequest()->isPost()) {
            $safeId = filter_input(\INPUT_POST, 'jobId', \FILTER_SANITIZE_SPECIAL_CHARS);
            $safeActive = filter_input(\INPUT_POST, 'active', \FILTER_SANITIZE_SPECIAL_CHARS);
            $safeExportPath = filter_input(\INPUT_POST, 'export_path', \FILTER_SANITIZE_SPECIAL_CHARS);
            $safeConfigId = filter_input(\INPUT_POST, 'config_id', \FILTER_SANITIZE_SPECIAL_CHARS);
            $safeRevisionId = filter_input(\INPUT_POST, 'revision_id', \FILTER_SANITIZE_SPECIAL_CHARS);
            if ('latest' === $safeRevisionId) {
                $safeRevisionId = null;
            }
            $safeTerms = filter_input(\INPUT_POST, 'terms', \FILTER_SANITIZE_SPECIAL_CHARS);

            $db = Zend_Registry::get('db');
            $query =
            'UPDATE archive_jobs
      SET active = :active, export_path = :export_path, config_id = :config_id, revision_id = :revision_id, terms = :terms
      WHERE id = :id';
            $stmt = $db->prepare($query);
            $stmt->execute([':id' => $safeId, ':active' => $safeActive, ':export_path' => $safeExportPath, ':config_id' => $safeConfigId, ':revision_id' => $safeRevisionId, ':terms' => $safeTerms]);
        }
    }

    /**
     * Echo JSON data of all archive configuration revisions.
     *
     * @return void
     *
     * @since 1/23/18
     */
    #[Route('/admin/exports/config-revisions.json', name: 'export_config_revisions_json')]
    public function listrevisionsAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $db = Zend_Registry::get('db');
        $revisions = $db->query('SELECT * FROM archive_configuration_revisions ORDER BY last_saved DESC')->fetchAll();

        echo json_encode($revisions);
    }

    /**
     * Echo whether a user-entered term ID is valid.
     *
     * @return void
     *
     * @since 1/23/18
     */
    // TODO - return instead of echo?
    #[Route('/admin/exports/term-valid/{catalogId}/{termId}', name: 'export_term_valid')]
    public function validtermAction()
    {
        $request = $this->getRequest();

        if (!$request->getParam('catalogId')) {
            echo 'No catalog specified!';
            exit;
        }
        if (!$request->getParam('term')) {
            echo 'No term specified!';
            exit;
        }

        $catalogId = $this->_helper->osidId->fromString($request->getParam('catalogId'));
        $this->termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);

        try {
            $termString = 'term.'.$request->getParam('term');
            $termId = $this->_helper->osidId->fromString($termString);
        } catch (osid_InvalidArgumentException $e) {
            header('HTTP/1.1 400 Bad Request');
            echo 'The term id specified was not of the correct format.';
            exit;
        } catch (osid_NotFoundException $e) {
            echo 'not valid';
        }

        if ($this->termLookupSession->getTerm($termId)) {
            echo 'valid';
        } else {
            echo 'not valid';
        }

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }
}
