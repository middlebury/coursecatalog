<?php

namespace App\Controller;

use App\Archive\ExportConfiguration\ExportConfigurationStorage;
use App\Archive\ExportJob\ExportJobStorage;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminExportJobs extends AbstractController
{
    public function __construct(
        private ExportConfigurationStorage $exportConfigurationStorage,
        private ExportJobStorage $exportJobStorage,
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
        $configs = [];
        foreach ($this->exportConfigurationStorage->getAllConfigurations() as $config) {
            $configData = [
                'id' => $config->getId(),
                'label' => $config->getLabel(),
                'catalog_id' => $this->osidIdMap->toString($config->getCatalogId()),
                'revisions' => [],
            ];
            foreach ($config->getAllRevisions() as $revision) {
                $configData['revisions'][] = [
                    'id' => $revision->getId(),
                    'note' => $revision->getNote(),
                    'last_saved' => $revision->getTimestamp()->format('Y-m-j H:i:s'),
                ];
            }
            $configs[] = $configData;
        }

        $jobs = [];
        foreach ($this->exportJobStorage->getAllJobs() as $job) {
            $jobs[] = [
                'id' => $job->getId(),
                'active' => $job->getActive(),
                'export_path' => $job->getExportPath(),
                'config_id' => $job->getConfigurationId(),
                'revision_id' => $job->getRevisionId(),
                'terms' => $job->getTerms(),
            ];
        }
        $data = [
            'configs' => $configs,
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

        $data['configs'] = $this->exportConfigurationStorage->getAllConfigurations();

        $data['config'] = null;
        $configLabel = $request->get('config');
        if ($configLabel) {
            foreach ($data['configs'] as $config) {
                if ($config->getLabel() === $configLabel) {
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
    public function deletejobAction(Request $request, int $job)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-export-delete-job', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $this->exportJobStorage->getJob($job)->delete();

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

        $configId = (int) $request->get('configId');
        $exportPath = filter_var($request->get('export_path'), \FILTER_SANITIZE_SPECIAL_CHARS);
        $terms = filter_var($request->get('terms'), \FILTER_SANITIZE_SPECIAL_CHARS);

        $this->exportJobStorage->createJob($exportPath, $configId, null, $terms);

        return $this->redirectToRoute('export_list_jobs');
    }

    /**
     * Update an existing archive export job.
     */
    #[Route('/admin/exports/jobs/{job}/update', name: 'export_update_job', methods: ['POST'])]
    public function updatejobAction(Request $request, int $job)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-export-update-job', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $job = $this->exportJobStorage->getJob($job);

        $job->setActive((bool) $request->get('active'));
        $job->setExportPath(filter_var($request->get('export_path'), \FILTER_SANITIZE_SPECIAL_CHARS));
        $job->setConfigurationId($request->get('config_id'));
        if ('latest' == $request->get('revision_id')) {
            $job->setRevisionId(null);
        } else {
            $job->setRevisionId($request->get('revision_id'));
        }
        $job->setTerms(filter_var($request->get('terms'), \FILTER_SANITIZE_SPECIAL_CHARS));

        $job->save();

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
            $termId = $this->osidIdMap->fromString('term-'.$termString);
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
