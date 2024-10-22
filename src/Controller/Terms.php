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
     *
     * @return void
     *
     * @since 4/21/09
     */
    public function listAction()
    {
        if ($this->_getParam('catalog')) {
            $catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));
            $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $this->view->title = 'Terms in '.$lookupSession->getCourseCatalog()->getDisplayName();
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $this->view->title = 'Terms in All Catalogs';
        }
        $lookupSession->useFederatedCourseCatalogView();

        $this->view->terms = $lookupSession->getTerms();

        $this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
        $this->view->headTitle($this->view->title);

        $this->view->menuIsTerms = true;
    }

    /**
     * Print out an XML list of all terms.
     *
     * @return void
     */
    public function listxmlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-Type', 'text/xml');

        $this->listAction();
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
     * View a catalog details.
     *
     * @return void
     */
    public function detailsAction()
    {
        $id = $this->osidIdMap->fromString($this->_getParam('term'));
        $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        $this->view->term = $lookupSession->getTerm($id);

        // Set the selected Catalog Id.
        $catalogSession = $this->osidRuntime->getCourseManager()->getTermCatalogSession();
        $catalogIds = $catalogSession->getCatalogIdsByTerm($id);
        if ($catalogIds->hasNext()) {
            $this->setSelectedCatalogId($catalogIds->getNextId());
        }

        // Set the title
        $this->view->title = $this->view->term->getDisplayName();
        $this->view->headTitle($this->view->title);

        $this->view->menuIsTerms = true;
    }

    /**
     * View a catalog details.
     *
     * @return void
     */
    public function detailsxmlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->detailsAction();
    }
}
