<?php
/**
 * @since 4/20/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Controller;

use App\Service\Osid\Runtime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * @var \App\Service\Osid\Runtime
     */
    private $osidRuntime;

    /**
     * Construct a new Catalogs controller.
     *
     * @param \App\Service\Osid\Runtime $osidRuntime
     *   The osid.runtime service.
     */
    public function __construct(Runtime $osidRuntime) {
        $this->osidRuntime = $osidRuntime;
    }

    #[Route('/catalogs/', name: 'List all catalogs')]
    public function listAction()
    {
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession();

        return $this->render('catalogs.html.twig', [
            'title' => 'Available Catalogs',
            'catalogs' => $lookupSession->getCourseCatalogs(),
        ]);
    }

    /**
     * Print out an XML list of all catalogs.
     *
     * @return void
     */
    public function listxmlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-Type', 'text/xml');

        $this->listAction();
    }

    /**
     * View a catalog details.
     *
     * @return void
     *
     * @since 4/21/09
     */
    public function viewAction()
    {
        $catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
        if ($this->_getParam('term')) {
            try {
                // Verify that the term is valid in this catalog
                $termLookup = $this->_helper->osid->getCourseManager()->getTermLookupSession();
                $termLookup->useFederatedCourseCatalogView();
                $termId = $termLookup->getTerm($this->_helper->osidId->fromString($this->_getParam('term')))->getId();
            } catch (osid_NotFoundException $e) {
            }
        }
        if (!isset($termId)) {
            $termId = $this->_helper->osidTerms->getNextOrLatestTermId($catalogId);
        }

        $this->_forward('search', 'Offerings', null, [
            'catalog' => $this->_helper->osidId->toString($catalogId),
            'term' => $this->_helper->osidId->toString($termId),
        ]);
    }

    /**
     * Print out an XML listing of a catalog.
     *
     * @return void
     */
    public function viewxmlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-Type', 'text/xml');

        $catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));

        $lookupSession = $this->_helper->osid->getCourseManager()->getCourseCatalogLookupSession();
        $catalog = $lookupSession->getCourseCatalog($catalogId);
        $this->view->catalogs = new phpkit_course_ArrayCourseCatalogList([$catalog]);

        $this->view->title = 'Catalog Details';
        $this->view->headTitle($this->view->title);

        $this->render('catalogs/listxml', null, true);
    }
}
