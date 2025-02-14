<?php

namespace App\Controller;

use App\Archive\ExportConfiguration\ExportConfigurationStorage;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminExportConfig extends AbstractController
{
    private \osid_type_Type $subjectType;
    private \osid_type_Type $departmentType;

    public function __construct(
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private ExportConfigurationStorage $configStorage,
    ) {
    }

    /**
     * List archive export configurations.
     */
    #[Route('/admin/exports/config/{exportId}', name: 'export_config_form')]
    public function listExportConfigs(?int $exportId = null)
    {
        $data['configs'] = $this->configStorage->getAllConfigurations();

        $data['selected_config'] = null;
        if ($exportId && -1 != $exportId) {
            foreach ($data['configs'] as $config) {
                if ($config->getId() == $exportId) {
                    $data['selected_config'] = $config;
                }
            }
        }

        $data['page_title'] = 'Manage Catalog Export Configurations';

        return $this->render('admin/export/config_form.html.twig', $data);
    }

    /**
     * Provide interface for creating a new archive configuration.
     */
    #[Route('/admin/exports/create', name: 'export_config_create_form', methods: ['GET'])]
    public function createFormAction()
    {
        $data['catalogs'] = [];
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession();
        $catalogs = $lookupSession->getCourseCatalogs();
        while ($catalogs->hasNext()) {
            $data['catalogs'][] = $catalogs->getNextCourseCatalog();
        }

        $data['page_title'] = 'Create a new Catalog Export Configuration';

        return $this->render('admin/export/create_config.html.twig', $data);
    }

    /**
     * Insert a new archive configuration into the database.
     */
    #[Route('/admin/exports/create', name: 'export_config_create', methods: ['POST'])]
    public function createAction(Request $request)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-export-config-create', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $label = filter_var($request->get('label'), \FILTER_SANITIZE_SPECIAL_CHARS);
        $catalogId = $this->osidIdMap->fromString(filter_var($request->get('catalog_id'), \FILTER_SANITIZE_SPECIAL_CHARS));

        $config = $this->configStorage->createConfiguration($label, $catalogId);

        return $this->redirectToRoute('export_config_form', [
            'exportId' => $config->getId(),
        ]);
    }

    /**
     * Delete an archive configuration.
     */
    #[Route('/admin/exports/{exportId}/delete', name: 'export_config_delete', methods: ['POST'])]
    public function deleteconfigAction(Request $request, $exportId)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-export-config-modify', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $config = $this->configStorage->getConfiguration($exportId);
        $config->delete();

        $response = new Response('Success');
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }

    /**
     * Echo JSON data of latest revision for a particular archive configuration.
     */
    #[Route('/admin/exports/{exportId}/latest.json', name: 'export_config_latest_revision')]
    public function latestrevisionAction(int $exportId)
    {
        $config = $this->configStorage->getConfiguration($exportId);
        try {
            $latestRevision = $config->getLatestRevision();

            return new JsonResponse($latestRevision->getContent());
        } catch (\Exception $e) {
            return new JsonResponse([
                'group1' => [
                    'title' => 'Please fill out an h1 section to give this group a name',
                    'section1' => [
                        'type' => 'h1',
                        'value' => '',
                    ],
                ],
            ]);
        }
    }

    /**
     * Display revision history for a given archive configuration.
     */
    #[Route('/admin/exports/{exportId}/revisions', name: 'export_config_revisions')]
    public function revisionhistoryAction(int $exportId)
    {
        $config = $this->configStorage->getConfiguration($exportId);
        $data['config'] = $config;
        $data['revisions'] = $config->getAllRevisions();
        $data['page_title'] = 'Revision history for '.$config->getLabel();

        return $this->render('admin/export/revisionhistory.html.twig', $data);
    }

    /**
     * Insert new archive configuration revision to the DB.
     */
    #[Route('/admin/exports/{exportId}/insertrevision', name: 'export_config_insert_revision', methods: ['POST'])]
    public function insertrevisionAction(Request $request, int $exportId)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-export-config-modify', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $note = filter_var($request->get('note'), \FILTER_SANITIZE_SPECIAL_CHARS);
        $jsonArray = json_decode($request->get('jsonData'), true);
        foreach ($jsonArray as $key => $value) {
            $value = filter_var($value, \FILTER_SANITIZE_SPECIAL_CHARS);
        }

        $config = $this->configStorage->getConfiguration($exportId);
        $config->createRevision($jsonArray, $note);

        $response = new Response('Success');
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }

    /**
     * Revert to an older revision by re-inserting it with new timestamp.
     *
     * @return void
     *
     * @since 1/25/18
     */
    #[Route('/admin/exports/{exportId}/reverttorevision', name: 'export_config_revert_to_revision', methods: ['POST'])]
    public function reverttorevisionAction(Request $request, int $exportId)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-export-config-revert', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $revId = (int) $request->get('revId');
        $config = $this->configStorage->getConfiguration($exportId);
        $oldRevision = $config->getRevision($revId);
        $note = 'Revert to revision #'.$revId.' from '.$oldRevision->getTimestamp()->format('Y-m-j H:i:s').' by '.$oldRevision->getUserDisplayName();

        $config->createRevision($oldRevision->getContent(), $note);

        $response = new Response('Success');
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }

    /**
     * Display diff between two revisions.
     */
    #[Route('/admin/exports/{exportId}/revisiondiff/{rev1}/{rev2}', name: 'export_config_revision_diff')]
    public function revisiondiffAction(int $exportId, int $rev1, int $rev2)
    {
        $config = $this->configStorage->getConfiguration($exportId);
        $data['rev1'] = $config->getRevision($rev1);
        $data['rev2'] = $config->getRevision($rev2);
        $data['page_title'] = 'Revision differences for '.$config->getLabel();

        return $this->render('admin/export/revisiondiff.html.twig', $data);
    }

    /**
     * Display revision JSON data.
     */
    #[Route('/admin/exports/{exportId}/revision/{revisionId}/json', name: 'export_config_revision_json')]
    public function viewjsonAction(int $exportId, int $revisionId)
    {
        $config = $this->configStorage->getConfiguration($exportId);
        $revision = $config->getRevision($revisionId);
        $data['rev'] = $revision;
        $data['page_title'] = 'Viewing revision #'.$revision->getId().' ('.$revision->getTimestamp()->format('Y-m-j H:i:s').')';

        return $this->render('admin/export/revisionjson.html.twig', $data);
    }

    /**
     * Echo HTML for a course list dropdown menu based on an archive configuration ID.
     *
     * @return void
     *
     * @since 1/23/18
     */
    #[Route('/admin/exports/generatecourselist/{catalogId}', name: 'export_config_generate_course_list')]
    public function generatecourselistAction(\osid_id_Id $catalogId)
    {
        $this->departmentType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic-department');
        $this->subjectType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic-subject');

        $topicSearchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSessionForCatalog($catalogId);
        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->departmentType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $search = $topicSearchSession->getTopicSearch();
        $order = $topicSearchSession->getTopicSearchOrder();
        $order->orderByDisplayName();
        $search->orderTopicResults($order);
        $searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
        $departments = $searchResults->getTopics();

        $topicQuery = $topicSearchSession->getTopicQuery();
        $topicQuery->matchGenusType($this->subjectType, true);
        if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
            $record = $topicQuery->getTopicQueryRecord($this->termType);
            $record->matchTermId($termId, true);
        }
        $search = $topicSearchSession->getTopicSearch();
        $order = $topicSearchSession->getTopicSearchOrder();
        $order->orderByDisplayName();
        $search->orderTopicResults($order);
        $searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
        $subjects = $searchResults->getTopics();

        $data = [
            'subjects' => [],
            'departments' => [],
        ];
        while ($subjects->hasNext()) {
            $data['subjects'][] = $subjects->getNextTopic();
        }
        while ($departments->hasNext()) {
            $data['departments'][] = $departments->getNextTopic();
        }

        return $this->render('admin/subject_department_select.html.twig', $data);
    }
}
