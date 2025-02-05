<?php

namespace App\Controller;

/*
 * @since 8/23/17
 *
 * @copyright Copyright &copy; 2017, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * A controller for working with courses.
 *
 * @since 8/23/17
 *
 * @copyright Copyright &copy; 2017, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class AdminExporter extends AbstractController
{
    /**
     * Initialize object.
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
    // public function init()
    // {
    //     $this->alternateType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');
    //     $this->alternateInTermsType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates-in-terms');
    //     $this->identifiersType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:banner_identifiers');
    //
    //     parent::init();
    //     $this->_helper->layout()->setLayout('midd_archive');
    //
    //     // Store an HTTP client configuration for later use.
    //     $this->httpClientConfig = [
    //         'maxredirects' => 10,
    //         'timeout' => 30,
    //     ];
    // }

    /**
     * Export a single archive export job.
     *
     * @return void
     *
     * @since 1/23/18
     */
    public function exportjobAction()
    {
        $request = $this->getRequest();
        if (!$request->getParam('dest_dir')) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A dest_dir must be specified.';
            exit;
        }
        if (!$request->getParam('config_id')) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A config_id must be specified.';
            exit;
        }
        if (!$request->getParam('term')) {
            header('HTTP/1.1 400 Bad Request');
            echo 'Terms must be specified.';
            exit;
        }
        if (!$request->getParam('revision_id')) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A revision_id must be specified.';
            exit;
        }

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        // Send a first byte to any clients/proxies to avoid timeouts.
        echo "Beginning export...\n";
        while (ob_get_level()) {
            ob_end_flush();
        }
        flush();
        $this->_helper->exportJob($request->getParam('dest_dir'), $request->getParam('config_id'), $request->getParam('term'), $request->getParam('revision_id'));
        echo "Export complete.\n";
    }

    /**
     * Report progress of export job.
     *
     * @return void
     *
     * @since 2/5/18
     */
    public function jobprogressAction()
    {
        $db = Zend_Registry::get('db');
        $query =
        'SELECT
			progress
		 FROM archive_export_progress';
        $stmt = $db->prepare($query);
        $stmt->execute();
        $progress = $stmt->fetch();
        echo $progress['progress'];

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    /**
     * Export a single archive export job from the command line.
     *
     * @return void
     *
     * @since 1/26/18
     */
    public function exportsinglejobAction()
    {
        $request = $this->getRequest();
        if (!$request->getParam('id')) {
            header('HTTP/1.1 400 Bad Request');
            echo 'A job id must be specified.';
            exit;
        }

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $db = Zend_Registry::get('db');
        $query =
        'SELECT
			*
		 FROM archive_jobs
		 WHERE id = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$request->getParam('id')]);
        $job = $stmt->fetch();

        // Revision is set to 'latest'
        if (!$job['revision_id']) {
            $query =
            'SELECT
				id
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
            $stmt->execute([$job['config_id']]);
            $latestRevision = $stmt->fetch();
            $job['revision_id'] = $latestRevision['id'];
        }

        $job['terms'] = explode(',', $job['terms']);
        array_walk($job['terms'], function (&$value, $key) { $value = 'term/'.$value; });

        $this->_helper->exportJob($job['export_path'], $job['config_id'], $job['terms'], $job['revision_id']);
    }

    /**
     * Export all 'active' archive export jobs.
     *
     * @return void
     *
     * @since 1/23/18
     */
    public function exportactivejobsAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $request = $this->getRequest();

        if ($request->getParam('verbose')) {
            $verbose = '1';
        } else {
            $verbose = '0';
        }

        $db = Zend_Registry::get('db');
        $jobs = $db->query('SELECT * FROM archive_jobs WHERE active=1')->fetchAll();

        foreach ($jobs as $job) {
            $terms = explode(',', $job['terms']);
            foreach ($terms as &$term) {
                $term = 'term/'.$term;
            }
            unset($term);

            if (null === $job['revision_id']) {
                $revision = 'latest';
            } else {
                $revision = $job['revision_id'];
            }

            if ($verbose) {
                file_put_contents('php://stderr', 'Beginning export: '.$job['config_id'].' '.$job['export_path'].' '.$job['terms'].' '.$revision."\n");
            }

            $this->_helper->exportJob($job['export_path'], $job['config_id'], $terms, $revision, $verbose);
        }
    }
}
