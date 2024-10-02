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
     * @var \App\Service\Osid\IdMap
     */
    private $osidIdMap;

    /**
     * @var \App\Service\Osid\TermHelper
     */
    private $osidTermHelper;

    /**
     * Construct a new Catalogs controller.
     *
     * @param \App\Service\Osid\Runtime $osidRuntime
     *   The osid.runtime service.
     * @param \App\Service\Osid\IdMap $osidIdMap
     *   The osid.id_map service.
     * @param \App\Service\Osid\TermHelper $osidTermHelper
     *   The osid.term_helper service.
     */
    public function __construct(Runtime $osidRuntime, IdMap $osidIdMap, TermHelper $osidTermHelper) {
        $this->osidRuntime = $osidRuntime;
        $this->osidIdMap = $osidIdMap;
        $this->osidTermHelper = $osidTermHelper;
    }

    #[Route('/catalogs/', name: 'list_catalogs')]
    public function listAction()
    {
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseCatalogLookupSession();

        $catalogs = $lookupSession->getCourseCatalogs();
        $catalogData = [];
        while ($catalogs->hasNext()) {
            $catalog = $catalogs->getNextCourseCatalog();
            $catalogData[] = [
                "id" => $this->osidIdMap->toString($catalog->getId()),
                "display_name" => $catalog->getDisplayName(),
                "description" => $catalog->getDescription(),
            ];
        }
        return $this->render('catalogs/list.html.twig', [
            'title' => 'Available Catalogs',
            'catalogs' => $catalogData,
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

    #[Route('/catalogs/view/{catalog}/{term}', name: 'view_catalog')]
    public function viewAction(string $catalog, $term = NULL)
    {
        $catalogId = $this->osidIdMap->fromString($catalog);
        if ($term) {
            try {
                // Verify that the term is valid in this catalog
                $termLookup = $this->osidRuntime->getCourseManager()->getTermLookupSession();
                $termLookup->useFederatedCourseCatalogView();
                $termId = $termLookup->getTerm($this->osidIdMap->fromString($term))->getId();
            } catch (\osid_NotFoundException $e) {
            }
        }
        if (!isset($termId)) {
            $termId = $this->osidTermHelper->getNextOrLatestTermId($catalogId);
        }

        return $this->redirectToRoute('search_offerings', [
            'catalog' => $this->osidIdMap->toString($catalogId),
            'term' => $this->osidIdMap->toString($termId),
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

        $catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));

        $lookupSession = $this->runtime->getCourseManager()->getCourseCatalogLookupSession();
        $catalog = $lookupSession->getCourseCatalog($catalogId);
        $this->view->catalogs = new phpkit_course_ArrayCourseCatalogList([$catalog]);

        $this->view->title = 'Catalog Details';
        $this->view->headTitle($this->view->title);

        $this->render('catalogs/listxml', null, true);
    }
}
