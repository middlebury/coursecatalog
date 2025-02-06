<?php

namespace App\Controller;

use App\Archive\Export\EventListener\ExportProcessMonitor;
use App\Archive\ExportJob\ExportJobStorage;
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
    public function __construct(
        private ExportProcessMonitor $exportProcessMonitor,
        private ExportJobStorage $exportJobStorage,
    ) {
    }

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
        return new JsonResponse($this->exportProcessMonitor->getAllProcesses());
    }

    /**
     * Export a single archive export job from the command line.
     *
     * @return void
     *
     * @since 1/26/18
     */
    public function exportsinglejobAction(int $jobId)
    {
        $job = $this->exportJobStorage->getJob($jobId);

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
        foreach ($this->exportJobStorage->getActiveJobs() as $job) {
            $this->_helper->exportJob($job['export_path'], $job['config_id'], $terms, $revision, $verbose);
        }
    }
}
