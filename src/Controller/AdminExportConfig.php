<?php

namespace App\Controller;

use App\Service\Osid\Runtime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminExportConfig extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Runtime $osidRuntime,
    ) {
    }

    /**
     * List archive export configurations.
     */
    #[Route('/admin/exports/config/{exportId}', name: 'export_config_form')]
    public function listExportConfigs(?int $exportId = null)
    {
        $db = $this->entityManager->getConnection();

        $data['configs'] = $db->executeQuery('SELECT * FROM archive_configurations')->fetchAllAssociative();

        $data['selected_config'] = null;
        if ($exportId && -1 != $exportId) {
            foreach ($data['configs'] as $config) {
                if ($config['id'] == $exportId) {
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
        $catalogId = filter_var($request->get('catalog_id'), \FILTER_SANITIZE_SPECIAL_CHARS);

        $db = $this->entityManager->getConnection();
        $query = 'INSERT INTO archive_configurations (id, label, catalog_id) VALUES (NULL,:label,:catalogId)';
        $stmt = $db->prepare($query);
        $stmt->bindValue('label', $label);
        $stmt->bindValue('catalogId', $catalogId);
        $stmt->executeQuery();

        return $this->redirectToRoute('export_config_form', [
            'exportId' => $db->lastInsertId(),
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

        $db = $this->entityManager->getConnection();

        // Delete revisions that depend on this config.
        $query = 'DELETE FROM archive_configuration_revisions WHERE arch_conf_id = ?';
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $exportId);
        $stmt->executeQuery();

        // Delete this config.
        $query = 'DELETE FROM archive_configurations WHERE id = ?';
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $exportId);
        $stmt->executeQuery();

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
        $db = $this->entityManager->getConnection();
        $query =
        'SELECT
      *
     FROM archive_configuration_revisions a
     INNER JOIN (
      SELECT
        arch_conf_id,
        MAX(last_saved) as latest
      FROM archive_configuration_revisions
      GROUP BY arch_conf_id
    ) b ON a.arch_conf_id = b.arch_conf_id and a.last_saved = b.latest
     WHERE a.arch_conf_id = ?';
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $exportId);
        $result = $stmt->executeQuery();
        $latestRevision = $result->fetchAssociative();

        if ($latestRevision) {
            return JsonResponse::fromJsonString($latestRevision['json_data']);
        } else {
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
        $data['configId'] = $exportId;
        $db = $this->entityManager->getConnection();

        $query = 'SELECT label FROM archive_configurations WHERE id = ?';
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $exportId);
        $result = $stmt->executeQuery();
        $data['configLabel'] = $result->fetchAssociative()['label'];

        $query = 'SELECT * FROM archive_configuration_revisions WHERE arch_conf_id = ? ORDER BY last_saved DESC';
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $exportId);
        $result = $stmt->executeQuery();
        $data['revisions'] = $result->fetchAllAssociative();

        $data['page_title'] = 'Revision history for '.$data['configLabel'];

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

        $safeNote = filter_var($request->get('note'), \FILTER_SANITIZE_SPECIAL_CHARS);
        $jsonArray = json_decode($request->get('jsonData'));
        foreach ($jsonArray as $key => $value) {
            $value = filter_var($value, \FILTER_SANITIZE_SPECIAL_CHARS);
        }
        $safeJsonData = json_encode($jsonArray, \JSON_PRETTY_PRINT);

        $db = $this->entityManager->getConnection();
        $query =
        'INSERT INTO archive_configuration_revisions (`arch_conf_id`, `note`, `last_saved`, `user_id`, `user_disp_name`, `json_data`)
    VALUES (
      :configId,
      :note,
      CURRENT_TIMESTAMP,
      :userId,
      :userDN,
      :jsonData)';
        $stmt = $db->prepare($query);
        $stmt->bindValue('configId', $exportId);
        $stmt->bindValue('note', $safeNote);
        $stmt->bindValue('userId', $this->getUser()->getUserIdentifier());
        $stmt->bindValue('userDN', $this->getUser()->getName());
        $stmt->bindValue('jsonData', $safeJsonData);
        $stmt->executeQuery();

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
    #[Route('/admin/exports/reverttorevision', name: 'export_config_revert_to_revision', methods: ['POST'])]
    public function reverttorevisionAction(Request $request)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-export-config-revert', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $revId = (int) $request->get('revId');
        $db = $this->entityManager->getConnection();
        $query = 'SELECT * FROM archive_configuration_revisions WHERE id=:id';
        $stmt = $db->prepare($query);
        $stmt->bindValue('id', $revId);
        $result = $stmt->executeQuery();
        $oldRevision = $result->fetchAssociative();
        $note = 'Revert to revision #'.$revId.' from '.$oldRevision['last_saved'].' by '.$oldRevision['user_disp_name'];

        $query =
        'INSERT INTO archive_configuration_revisions (`arch_conf_id`, `note`, `last_saved`, `user_id`, `user_disp_name`, `json_data`)
    VALUES (
      :configId,
      :note,
      CURRENT_TIMESTAMP,
      :userId,
      :userDN,
      :jsonData)';

        $stmt = $db->prepare($query);
        $stmt->bindValue('configId', $oldRevision['arch_conf_id']);
        $stmt->bindValue('note', $note);
        $stmt->bindValue('userId', $this->getUser()->getUserIdentifier());
        $stmt->bindValue('userDN', $this->getUser()->getName());
        $stmt->bindValue('jsonData', $oldRevision['json_data']);
        $stmt->executeQuery();

        $response = new Response('Success');
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }

    /**
     * Display diff between two revisions.
     */
    #[Route('/admin/exports/revisiondiff/{rev1}/{rev2}', name: 'export_config_revision_diff')]
    public function revisiondiffAction(int $rev1, int $rev2)
    {
        $db = $this->entityManager->getConnection();
        $query = 'SELECT r.*, c.label FROM archive_configuration_revisions r INNER JOIN archive_configurations c ON r.arch_conf_id = c.id WHERE r.id = ?';
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $rev1);
        $result = $stmt->executeQuery();
        $data['rev1'] = $result->fetchAssociative();

        $stmt->bindValue(1, $rev2);
        $result = $stmt->executeQuery();
        $data['rev2'] = $result->fetchAssociative();

        $data['page_title'] = 'Revision differences for '.$data['rev1']['label'];

        return $this->render('admin/export/revisiondiff.html.twig', $data);
    }

    /**
     * Display revision JSON data.
     */
    #[Route('/admin/exports/revision/{revisionId}/json', name: 'export_config_revision_json')]
    public function viewjsonAction(int $revisionId)
    {
        $db = $this->entityManager->getConnection();
        $query = 'SELECT * FROM archive_configuration_revisions WHERE id = ?';
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $revisionId);
        $result = $stmt->executeQuery();
        $data['rev'] = $result->fetchAssociative();

        $data['page_title'] = 'Viewing revision #'.$data['rev']['id'].' ('.$data['rev']['last_saved'].')';

        return $this->render('admin/export/revisionjson.html.twig', $data);
    }

    /**
     * Echo JSON data of all archive configuration revisions.
     *
     * @return void
     *
     * @since 1/23/18
     */
    public function listrevisionsAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $db = Zend_Registry::get('db');
        $revisions = $db->executeQuery('SELECT * FROM archive_configuration_revisions ORDER BY last_saved DESC')->fetchAll();

        echo json_encode($revisions);
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
        $this->departmentType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department');
        $this->subjectType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject');

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
