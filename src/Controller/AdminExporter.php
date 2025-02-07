<?php

namespace App\Controller;

use App\Archive\Export\EventListener\ExportProcessMonitor;
use App\Archive\Export\Message\RunArchiveExportJob;
use App\Archive\ExportJob\ExportJobStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        private MessageBusInterface $messageBus,
    ) {
    }

    /**
     * Report progress of export jobs.
     */
    #[Route('/admin/exports/run/progress.json', name: 'export_progress_json')]
    public function jobProgressAction()
    {
        $this->exportProcessMonitor->clearStaleProcesses();

        return new JsonResponse($this->exportProcessMonitor->getAllProcesses());
    }

    /**
     * Export a single archive export job from the command line.
     */
    #[Route('/admin/exports/run/single/{jobId}', name: 'export_enqueue_single_job', methods: ['POST'])]
    public function exportSingleJobAction(Request $request, int $jobId)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-run-export', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        // Verify that our job exists.
        $job = $this->exportJobStorage->getJob($jobId);
        // Add the job to our queue for processing.
        $this->messageBus->dispatch(new RunArchiveExportJob($job->getId()));

        $response = new Response('Job queued for export');
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }

    /**
     * Export all 'active' archive export jobs.
     */
    #[Route('/admin/exports/run/active', name: 'export_enqueue_active_jobs', methods: ['POST'])]
    public function exportActiveJobsAction(Request $request)
    {
        // Verify our CSRF key
        if (!$this->isCsrfTokenValid('admin-run-export', $request->get('csrf_key'))) {
            throw new AccessDeniedException('Invalid CSRF key.');
        }

        $i = 0;
        foreach ($this->exportJobStorage->getAllJobs() as $job) {
            if ($job->getActive()) {
                // Add the job to our queue for processing.
                $this->messageBus->dispatch(new RunArchiveExportJob($job->getId()));
                ++$i;
            }
        }

        $response = new Response($i.' active jobs queued for export');
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }
}
