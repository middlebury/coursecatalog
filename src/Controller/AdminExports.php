<?php

namespace App\Controller;

use App\Service\Osid\Runtime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminExports extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Runtime $osidRuntime,
    ) {
    }

    /**
     * List archive export configurations.
     */
    #[Route('/admin/exports/config/{exportId}', name: 'admin_exports_config')]
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

        return $this->render('admin/export_config.html.twig', $data);
    }

    /**
     * Manage catalog archive scheduling.
     */
    #[Route('/admin/exports/schedule', name: 'admin_exports_list_schedules')]
    public function scheduleAction()
    {
    }

    /**
     * Echo JSON data of latest revision for a particular archive configuration.
     */
    #[Route('/admin/exports/{exportId}/latest.json', name: 'admin_exports_config_latest_revision')]
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
    #[Route('/admin/exports/{exportId}/revisions', name: 'admin_exports_config_revisions')]
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

        return $this->render('admin/export/revisionhistory.html.twig', $data);
    }

    /**
     * Display diff between two revisions.
     *
     * @return void
     *
     * @since 1/25/18
     */
    #[Route('/admin/exports/revisiondiff/{rev1}/{rev2}', name: 'admin_exports_config_revision_diff')]
    public function revisiondiffAction(int $rev1, int $rev2)
    {
        $db = $this->entityManager->getConnection();
        $query = 'SELECT * FROM archive_configuration_revisions WHERE id = ?';
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $rev1);
        $result = $stmt->executeQuery();
        $data['rev1'] = $result->fetchAssociative();

        $stmt->bindValue(1, $rev2);
        $result = $stmt->executeQuery();
        $data['rev2'] = $result->fetchAssociative();

        return $this->render('admin/export/revisiondiff.html.twig', $data);
    }

    /**
     * Display revision JSON data.
     *
     * @return void
     *
     * @since 1/25/18
     */
    #[Route('/admin/exports/revision/{revisionId}/json', name: 'admin_exports_config_revision_json')]
    public function viewjsonAction(int $revisionId)
    {
        $db = $this->entityManager->getConnection();
        $query = 'SELECT * FROM archive_configuration_revisions WHERE id = ?';
        $stmt = $db->prepare($query);
        $stmt->bindValue(1, $revisionId);
        $result = $stmt->executeQuery();
        $data['rev'] = $result->fetchAssociative();

        return $this->render('admin/export/revisionjson.html.twig', $data);
    }

    /**
     * Provide interface for creating a new archive configuration.
     *
     * @return void
     *
     * @since 1/23/18
     */
    #[Route('/admin/exports/create', name: 'admin_exports_create')]
    public function newconfigAction()
    {
        $data['catalogs'] = [];
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession();
        $catalogs = $lookupSession->getCourseCatalogs();
        while ($catalogs->hasNext()) {
            $data['catalogs'][] = $catalogs->getNextCourseCatalog();
        }

        return $this->render('admin/export/create_config.html.twig', $data);
    }

    /**
     * Delete an archive configuration.
     *
     * @return void
     *
     * @since 1/23/18
     */
    #[Route('/admin/exports/{exportId}/delete', name: 'admin_exports_delete_config', methods: ['POST'])]
    public function deleteconfigAction($exportId)
    {
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
     * Insert a new archive configuration into the database.
     */
    #[Route('/admin/exports/insert', name: 'admin_exports_insert_config', methods: ['POST'])]
    public function insertconfigAction(Request $request)
    {
        $label = filter_var($request->get('label'), \FILTER_SANITIZE_SPECIAL_CHARS);
        $catalogId = filter_var($request->get('catalog_id'), \FILTER_SANITIZE_SPECIAL_CHARS);

        $db = $this->entityManager->getConnection();
        $query = 'INSERT INTO archive_configurations (id, label, catalog_id) VALUES (NULL,:label,:catalogId)';
        $stmt = $db->prepare($query);
        $stmt->bindValue('label', $label);
        $stmt->bindValue('catalogId', $catalogId);
        $stmt->execute();

        return $this->redirectToRoute('admin_exports_config', [
            'exportId' => $db->lastInsertId(),
        ]);
    }

    /**
     * Echo JSON data of all archive export jobs.
     *
     * @return void
     *
     * @since 1/23/18
     */
    public function listjobsAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $db = Zend_Registry::get('db');
        $configs = $db->query('SELECT * FROM archive_configurations')->fetchAll();
        $revisions = $db->query('SELECT `id`, `note`, `last_saved`, `arch_conf_id` FROM archive_configuration_revisions ORDER BY last_saved DESC')->fetchAll();
        $jobs = $db->query('SELECT * FROM archive_jobs ORDER BY terms DESC')->fetchAll();

        $data = [];
        $data[] = ['configs' => $configs];
        $data[] = ['revisions' => $revisions];
        $data[] = ['jobs' => $jobs];

        echo json_encode($data);
    }

    /**
     * Provide an interface for creating a new archive export job.
     *
     * @return void
     *
     * @since 1/23/18
     */
    public function newjobAction()
    {
        $request = $this->getRequest();

        $db = Zend_Registry::get('db');
        $data['configs'] = $db->query('SELECT * FROM archive_configurations')->fetchAll();

        $data['config'] = null;
        if ($request->getParam('config')) {
            foreach ($data['configs'] as $config) {
                if ($config['label'] === $request->getParam('config')) {
                    $data['config'] = $config;
                }
            }
        }
    }

    /**
     * Delete an archive export job.
     *
     * @return void
     *
     * @since 1/23/18
     */
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
    public function insertjobAction()
    {
        if ($this->getRequest()->isPost()) {
            $safeConfigId = filter_input(\INPUT_POST, 'configId', \FILTER_SANITIZE_SPECIAL_CHARS);
            $safeExportPath = filter_input(\INPUT_POST, 'export_path', \FILTER_SANITIZE_SPECIAL_CHARS);
            $safeTerms = filter_input(\INPUT_POST, 'terms', \FILTER_SANITIZE_SPECIAL_CHARS);

            $db = Zend_Registry::get('db');
            $query =
            'INSERT INTO archive_jobs (id, active, export_path, config_id, revision_id, terms)
      VALUES (NULL, 1, :export_path, :config_id, NULL, :terms)';
            $stmt = $db->prepare($query);
            $stmt->execute([':export_path' => $safeExportPath, ':config_id' => $safeConfigId, ':terms' => $safeTerms]);
        }

        $this->_helper->redirector('schedule', 'admin');
    }

    /**
     * Update an existing archive export job.
     *
     * @return void
     *
     * @since 1/23/18
     */
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
    public function listrevisionsAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $db = Zend_Registry::get('db');
        $revisions = $db->query('SELECT * FROM archive_configuration_revisions ORDER BY last_saved DESC')->fetchAll();

        echo json_encode($revisions);
    }

    /**
     * Revert to an older revision by re-inserting it with new timestamp.
     *
     * @return void
     *
     * @since 1/25/18
     */
    #[Route('/admin/exports/reverttorevision', name: 'admin_exports_config_revert_to_revision', methods: ['POST'])]
    public function reverttorevisionAction(Request $request)
    {
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
     * Insert new archive configuration revision to the DB.
     */
    #[Route('/admin/exports/{exportId}/insertrevision', name: 'admin_exports_config_insert_revision', methods: ['POST'])]
    public function insertrevisionAction(Request $request, int $exportId)
    {
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
     * Echo HTML for a course list dropdown menu based on an archive configuration ID.
     *
     * @return void
     *
     * @since 1/23/18
     */
    #[Route('/admin/exports/generatecourselist/{catalogId}', name: 'admin_exports_generate_course_list')]
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

    /**
     * Echo whether a user-entered term ID is valid.
     *
     * @return void
     *
     * @since 1/23/18
     */
    // TODO - return instead of echo?
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
