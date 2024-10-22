<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Controller;

use App\Service\Osid\DataLoader;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
use App\Service\Osid\TermHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * A controller for working with terms.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Terms extends AbstractController
{

    /**
     * @var \App\Service\Osid\Runtime
     */
    private $osidRuntime;

    /**
     * @var \App\Service\Osid\IdMap
     */
    private $osidIdMap;

    /**
     * @var \App\Service\Osid\DataLoader
     */
    private $osidDataLoader;

    /**
     * Construct a new Catalogs controller.
     *
     * @param \App\Service\Osid\Runtime $osidRuntime
     *   The osid.runtime service.
     * @param \App\Service\Osid\IdMap $osidIdMap
     *   The osid.id_map service.
     * @param \App\Service\Osid\DataLoader $osidDataLoader
     *   The data loader service.
     */
    public function __construct(Runtime $osidRuntime, IdMap $osidIdMap, DataLoader $osidDataLoader) {
        $this->osidRuntime = $osidRuntime;
        $this->osidIdMap = $osidIdMap;
        $this->osidDataLoader = $osidDataLoader;
    }

    /**
     * Print out a list of all terms.
     */
    #[Route('/terms/list/{catalog}', name: 'list_terms')]
    public function listAction($catalog = NULL)
    {
        $data = [];
        if ($catalog) {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $data['title'] = 'Terms in '.$lookupSession->getCourseCatalog()->getDisplayName();
            $data['catalog_id'] = $catalogId;
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $data['title'] = 'Terms in All Catalogs';
            $data['catalog_id'] = NULL;
        }
        $lookupSession->useFederatedCourseCatalogView();

        $terms = $lookupSession->getTerms();
        $data['terms'] = [];
        while ($terms->hasNext()) {
            $data['terms'][] = $terms->getNextTerm();
        }
        return $this->render('terms/list.html.twig', $data);
    }

    /**
     * Print out an XML list of all terms.
     */
    #[Route('/terms/listxml/{catalog}', name: 'list_terms_xml')]
    public function listxmlAction($catalog = NULL)
    {
        $data = [];
        if ($catalog) {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $data['title'] = 'Terms in '.$lookupSession->getCourseCatalog()->getDisplayName();
            $data['catalog_id'] = $catalogId;
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $data['title'] = 'Terms in All Catalogs';
            $data['catalog_id'] = NULL;
        }
        $lookupSession->useFederatedCourseCatalogView();

        $terms = $lookupSession->getTerms();
        $data['terms'] = [];
        while ($terms->hasNext()) {
            $data['terms'][] = $terms->getNextTerm();
        }
        $data['feedLink'] = $this->generateUrl('list_terms_xml', ['catalog' => $catalog], UrlGeneratorInterface::ABSOLUTE_URL);
        $response = new Response($this->renderView('terms/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

    #[Route('/terms/view/{term}/{catalog}', name: 'view_term')]
    public function viewAction($term, $catalog = NULL)
    {
        $data = [];
        $id = $this->osidIdMap->fromString($term);

        if ($catalog) {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $termLookupSession->useIsolatedCourseCatalogView();
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
            $offeringLookupSession->useIsolatedCourseCatalogView();
        } else {
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
            $offeringLookupSession->useFederatedCourseCatalogView();
        }
        $data['term'] = $termLookupSession->getTerm($id);
        $offerings = $offeringLookupSession->getCourseOfferingsByTerm($id);
        $data['offerings'] = [];
        while ($offerings->hasNext()) {
            $data['offerings'][] = $this->osidDataLoader->getOfferingData($offerings->getNextCourseOffering());
        }

        return $this->render('terms/view.html.twig', $data);
    }

    /**
     * View term details.
     */
    #[Route('/terms/details/{term}/{catalog}', name: 'view_term_details')]
    public function detailsAction($term, $catalog = NULL)
    {
        $id = $this->osidIdMap->fromString($term);
        $data = [];
        if ($catalog) {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $termLookupSession->useIsolatedCourseCatalogView();
            $data['catalog_id'] = $catalogId;
        } else {
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $data['catalog_id'] = NULL;
        }
        $data['term'] = $termLookupSession->getTerm($id);

        return $this->render('terms/details.html.twig', $data);
    }

    /**
     * View a catalog details.
     */
    #[Route('/terms/detailsxml/{term}/{catalog}', name: 'view_term_details_xml')]
    public function detailsxmlAction($term, $catalog = NULL)
    {
        $id = $this->osidIdMap->fromString($term);
        $data = [];
        if ($catalog) {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $termLookupSession->useIsolatedCourseCatalogView();
            $data['catalog_id'] = $catalogId;
        } else {
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $data['catalog_id'] = NULL;
        }
        $data['term'] = $termLookupSession->getTerm($id);

        $data['feedLink'] = $this->generateUrl('view_term_details_xml', ['term' => $term, 'catalog' => $catalog], UrlGeneratorInterface::ABSOLUTE_URL);

        $response = new Response($this->renderView('terms/details.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }
}
