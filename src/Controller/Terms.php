<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Controller;

use App\Service\Osid\DataLoader;
use App\Service\Osid\IdMap;
use App\Service\Osid\Runtime;
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
    public function __construct(
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private DataLoader $osidDataLoader,
    ) {
    }

    /**
     * Print out a list of all terms.
     */
    #[Route('/terms/list/{catalogId}', name: 'list_terms')]
    public function listAction(?\osid_id_Id $catalogId = null)
    {
        $data = [];
        if ($catalogId) {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $data['title'] = 'Terms in '.$lookupSession->getCourseCatalog()->getDisplayName();
            $data['catalogId'] = $catalogId;
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $data['title'] = 'Terms in All Catalogs';
            $data['catalogId'] = null;
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
    #[Route('/terms/listxml/{catalogId}', name: 'list_terms_xml')]
    public function listxmlAction(?\osid_id_Id $catalogId = null)
    {
        $data = [];
        if ($catalogId) {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $data['title'] = 'Terms in '.$lookupSession->getCourseCatalog()->getDisplayName();
            $data['catalogId'] = $catalogId;
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $data['title'] = 'Terms in All Catalogs';
            $data['catalogId'] = null;
        }
        $lookupSession->useFederatedCourseCatalogView();

        $terms = $lookupSession->getTerms();
        $data['terms'] = [];
        while ($terms->hasNext()) {
            $data['terms'][] = $terms->getNextTerm();
        }
        $data['feedLink'] = $this->generateUrl('list_terms_xml', ['catalogId' => $catalogId], UrlGeneratorInterface::ABSOLUTE_URL);
        $response = new Response($this->renderView('terms/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }

    #[Route('/terms/view/{termId}/{catalogId}', name: 'view_term')]
    public function viewAction(\osid_id_Id $termId, ?\osid_id_Id $catalogId = null)
    {
        $data = [];
        if ($catalogId) {
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

        $data['term'] = $termLookupSession->getTerm($termId);
        $offerings = $offeringLookupSession->getCourseOfferingsByTerm($termId);
        $data['offerings'] = [];
        while ($offerings->hasNext()) {
            $data['offerings'][] = $this->osidDataLoader->getOfferingData($offerings->getNextCourseOffering());
        }

        return $this->render('terms/view.html.twig', $data);
    }

    /**
     * View term details.
     */
    #[Route('/terms/details/{termId}/{catalogId}', name: 'view_term_details')]
    public function detailsAction(\osid_id_Id $termId, ?\osid_id_Id $catalogId = null)
    {
        $data = [];
        if ($catalogId) {
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $termLookupSession->useIsolatedCourseCatalogView();
            $data['catalogId'] = $catalogId;
        } else {
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $data['catalogId'] = null;
        }
        $data['term'] = $termLookupSession->getTerm($termId);

        return $this->render('terms/details.html.twig', $data);
    }

    /**
     * View a catalog details.
     */
    #[Route('/terms/detailsxml/{termId}/{catalogId}', name: 'view_term_details_xml')]
    public function detailsxmlAction(\osid_id_Id $termId, ?\osid_id_Id $catalogId = null)
    {
        $data = [];
        if ($catalogId) {
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $termLookupSession->useIsolatedCourseCatalogView();
            $data['catalogId'] = $catalogId;
        } else {
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $data['catalogId'] = null;
        }
        $data['term'] = $termLookupSession->getTerm($termId);

        $data['feedLink'] = $this->generateUrl(
            'view_term_details_xml',
            [
                'termId' => $termId,
                'catalogId' => $catalogId,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $response = new Response($this->renderView('terms/details.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }
}
