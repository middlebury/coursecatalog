<?php
/**
 * @since 4/20/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Controller;

use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Service\Osid\TermHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * A controller for accessing catalogs.
 *
 * @since 4/20/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalogs extends AbstractController
{
    public function __construct(
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private TermHelper $osidTermHelper,
    ) {
    }

    #[Route('/', name: 'home')]
    #[Route('/catalogs/list', name: 'list_catalogs')]
    public function listAction()
    {
        $data = [];
        $data['title'] = 'Available Catalogs';

        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession();
        $catalogs = $lookupSession->getCourseCatalogs();
        $data['catalogs'] = [];
        while ($catalogs->hasNext()) {
            $data['catalogs'][] = $catalogs->getNextCourseCatalog();
        }

        return $this->render('catalogs/list.html.twig', $data);
    }

    /**
     * Print out an XML list of all catalogs.
     */
    #[Route('/catalogs/listxml', name: 'list_catalogs_xml')]
    public function listxmlAction()
    {
        $data = [];
        $data['title'] = 'All Catalogs';
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession();
        $catalogs = $lookupSession->getCourseCatalogs();
        $data['catalogs'] = [];
        while ($catalogs->hasNext()) {
            $data['catalogs'][] = $catalogs->getNextCourseCatalog();
        }
        $data['feedLink'] = $this->generateUrl(
            'list_catalogs_xml',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
        $response = new Response($this->renderView('catalogs/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }

    #[Route('/catalogs/view/{catalogId}/{termId}', name: 'view_catalog')]
    public function viewAction(\osid_id_Id $catalogId, ?\osid_id_Id $termId = null)
    {
        if ($termId) {
            try {
                // Verify that the term is valid in this catalog
                $termLookup = $this->osidRuntime->getCourseManager()->getTermLookupSession();
                $termLookup->useFederatedCourseCatalogView();
                $termLookup->getTerm($termId);
            } catch (\osid_NotFoundException $e) {
            }
        }
        if (!isset($termId)) {
            $termId = $this->osidTermHelper->getNextOrLatestTermId($catalogId);
        }

        return $this->redirectToRoute('search_offerings', [
            'catalogId' => $catalogId,
            'termId' => $termId,
        ]);
    }

    /**
     * Print out an XML listing of a catalog.
     */
    #[Route('/catalogs/viewxml/{catalogId}', name: 'view_catalog_xml')]
    public function viewxmlAction(\osid_id_Id $catalogId)
    {
        $data = [];
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession();
        $data['title'] = 'View Catalog';
        $data['catalogs'] = [$lookupSession->getCourseCatalog($catalogId)];
        $data['feedLink'] = $this->generateUrl(
            'list_catalogs_xml',
            [
                'catalogId' => $catalogId,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
        $response = new Response($this->renderView('catalogs/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }
}
