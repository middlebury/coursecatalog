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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * A controller for working with resources.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Resources extends AbstractController
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
     *   The osid.topic_helper service.
     */
    public function __construct(Runtime $osidRuntime, IdMap $osidIdMap, DataLoader $osidDataLoader) {
        $this->osidRuntime = $osidRuntime;
        $this->osidIdMap = $osidIdMap;
        $this->osidDataLoader = $osidDataLoader;
        $this->instructorType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
    }

    // 	/**
    // 	 * Print out a list of all topics
    // 	 *
    // 	 * @return void
    // 	 * @access public
    // 	 * @since 4/21/09
    // 	 */
    // 	public function listAction () {
    // 		if ($this->_getParam('catalog')) {
    // 			$catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));
    // 			$lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
    // 			$this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
    // 		} else {
    // 			$lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
    // 			$this->view->title = 'Topics in All Catalogs';
    // 		}
    // 		$lookupSession->useFederatedCourseCatalogView();
    //
    // 		$this->loadTopics($lookupSession->getTopics());
    //
    // 		$this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
    // 		$this->view->headTitle($this->view->title);
    // 	}

    #[Route('/resources/view/{resource}/{term}', name: 'view_resource')]
    public function viewAction(Request $request, $resource, $term = NULL)
    {
        $id = $this->osidIdMap->fromString($resource);
        $lookupSession = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSession();
        $lookupSession->useFederatedBinView();
        $data['resource'] = $lookupSession->getResource($id);

        $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSession();
        $offeringSearchSession->useFederatedCourseCatalogView();
        $query = $offeringSearchSession->getCourseOfferingQuery();

        if ($term) {
            $termId = $this->osidIdMap->fromString($term);

            $query->matchTermId($termId, true);

            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $data['term'] = $termLookupSession->getTerm($termId);
        }
        else {
            $data['term'] = NULL;
        }

        $data['offeringsTitle'] = 'Sections';
        $data['offerings'] = [];
        $data['offering_count'] = 0;
        $data['offering_display_limit'] = 100;

        // Match the instructor Id
        if (preg_match('/^resource\.person\./', $resource)) {
            if ($query->hasRecordType($this->instructorType)) {
                $queryRecord = $query->getCourseOfferingQueryRecord($this->instructorType);
                $queryRecord->matchInstructorId($id, true);
                $offerings = $offeringSearchSession->getCourseOfferingsByQuery($query);
            }
        }
        // Match a location id
        elseif (preg_match('/^resource\.place\./', $resource)) {
            $query->matchLocationId($id, TRUE);
            $offerings = $offeringSearchSession->getCourseOfferingsByQuery($query);
        }
        if (isset($offerings)) {
            $data['offering_count'] = $offerings->available();
            $i = 0;
            while ($offerings->hasNext() && $i < $data['offering_display_limit']) {
                $data['offerings'][] = $this->osidDataLoader->getOfferingData($offerings->getNextCourseOffering());
                $i++;
            }
        }

        return $this->render('resources/view.html.twig', $data);
    }

    /**
     * List all department topics as a text file with each line being Id|DisplayName.
     *
     * @return void
     *
     * @since 10/20/09
     */
    public function listcampusestxtAction()
    {
        $this->renderTextList(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:resource.place.campus'));
    }

    /**
     * Render a text feed for a given topic type.
     *
     * @return void
     *
     * @since 11/17/09
     */
    private function renderTextList(\osid_type_Type $genusType)
    {
        header('Content-Type: text/plain');

        if ($this->_getParam('catalog')) {
            $catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));
            $lookupSession = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSessionForBin($catalogId);
            $this->view->title = 'Resources in '.$lookupSession->getBin()->getDisplayName();
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSession();
            $this->view->title = 'Resources in All Bins';
        }
        $lookupSession->useFederatedBinView();

        $resources = $lookupSession->getResourcesByGenusType($genusType);

        while ($resources->hasNext()) {
            $resource = $resources->getNextResource();
            echo $this->osidIdMap->toString($resource->getId()).'|'.$this->osidIdMap->toString($resource->getId()).' - '.$resource->getDisplayName()."\n";
        }
        // 		var_dump($lookupSession);
        // 		var_dump($resources);
        exit;
    }
}
