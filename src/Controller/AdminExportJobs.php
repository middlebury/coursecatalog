<?php

namespace App\Controller;

use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminExportJobs extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
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
     */
    #[Route('/admin/exports/jobs/{job}/delete', name: 'export_delete_job', methods: ['POST'])]
    public function deletejobAction(Request $request, string $job)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-export-delete-job', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        // Delete revisions that depend on this config.
        $db = $this->entityManager->getConnection();
        $query = 'DELETE FROM archive_jobs WHERE id = ?';
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $job);
        $stmt->executeQuery();

        $response = new Response('Success');
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }

    /**
     * Insert a new archive export job into the DB.
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
     */
    #[Route('/admin/exports/jobs/{job}/update', name: 'export_update_job', methods: ['POST'])]
    public function updatejobAction(Request $request, string $job)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-export-update-job', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $safeId = filter_var($job, \FILTER_SANITIZE_SPECIAL_CHARS);
        $safeActive = filter_var($request->get('active'), \FILTER_SANITIZE_SPECIAL_CHARS);
        $safeExportPath = filter_var($request->get('export_path'), \FILTER_SANITIZE_SPECIAL_CHARS);
        $safeConfigId = filter_var($request->get('config_id'), \FILTER_SANITIZE_SPECIAL_CHARS);
        $safeRevisionId = filter_var($request->get('revision_id'), \FILTER_SANITIZE_SPECIAL_CHARS);
        if ('latest' === $safeRevisionId) {
            $safeRevisionId = null;
        }
        $safeTerms = filter_var($request->get('terms'), \FILTER_SANITIZE_SPECIAL_CHARS);

        $db = $this->entityManager->getConnection();
        $query =
        'UPDATE archive_jobs
  SET active = :active, export_path = :export_path, config_id = :config_id, revision_id = :revision_id, terms = :terms
  WHERE id = :id';
        $stmt = $db->prepare($query);
        $stmt->bindValue('id', $safeId);
        $stmt->bindValue('active', $safeActive);
        $stmt->bindValue('export_path', $safeExportPath);
        $stmt->bindValue('config_id', $safeConfigId);
        $stmt->bindValue('terms', $safeTerms);
        $stmt->bindValue('revision_id', $safeRevisionId);
        $stmt->executeQuery();

        $response = new Response('Success');
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }

    /**
     * Echo whether a user-entered term ID is valid.
     */
    #[Route('/admin/exports/term-valid/{catalogId}/{termString}', name: 'export_term_valid')]
    public function validtermAction(\osid_id_Id $catalogId, string $termString)
    {
        try {
            $termId = $this->osidIdMap->fromString('term.'.$termString);
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            if ($termLookupSession->getTerm($termId)) {
                $response = new Response('valid');
            } else {
                $response = new Response('not valid');
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
            }
        } catch (\osid_InvalidArgumentException $e) {
            $response = new Response('not valid');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        } catch (\osid_NotFoundException $e) {
            $response = new Response('not valid');
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }
}
