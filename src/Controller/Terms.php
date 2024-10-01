<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Controller;

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
     * Construct a new Catalogs controller.
     *
     * @param \App\Service\Osid\Runtime $osidRuntime
     *   The osid.runtime service.
     * @param \App\Service\Osid\IdMap $osidIdMap
     *   The osid.id_map service.
     */
    public function __construct(Runtime $osidRuntime, IdMap $osidIdMap) {
        $this->osidRuntime = $osidRuntime;
        $this->osidIdMap = $osidIdMap;
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

    #[Route('/terms/view/{id}', name: 'view_term')]
    public function viewAction($id)
    {
        $id = $this->osidIdMap->fromString($this->_getParam('term'));
        $lookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        $this->view->term = $lookupSession->getTerm($id);

        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        $this->view->offerings = $lookupSession->getCourseOfferingsByTerm($id);

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
