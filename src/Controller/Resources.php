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
    public function __construct(
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private DataLoader $osidDataLoader,
    ) {
        $this->instructorType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
    }

    #[Route('/resources/view/{resource}/{term}', name: 'view_resource')]
    public function viewAction(Request $request, \osid_id_Id $resource, ?\osid_id_Id $term = null)
    {
        $lookupSession = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSession();
        $lookupSession->useFederatedBinView();
        $data['resource'] = $lookupSession->getResource($resource);

        $offeringSearchSession = $this->osidRuntime->getCourseManager()->getCourseOfferingSearchSession();
        $offeringSearchSession->useFederatedCourseCatalogView();
        $query = $offeringSearchSession->getCourseOfferingQuery();

        if ($term) {
            $query->matchTermId($term, true);

            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $data['term'] = $termLookupSession->getTerm($term);
        } else {
            $data['term'] = null;
        }

        $data['offeringsTitle'] = 'Sections';
        $data['offerings'] = [];
        $data['offering_count'] = 0;
        $data['offering_display_limit'] = 100;

        // Match the instructor Id
        if (preg_match('/^resource\.person\./', $this->osidIdMap->toString($resource))) {
            if ($query->hasRecordType($this->instructorType)) {
                $queryRecord = $query->getCourseOfferingQueryRecord($this->instructorType);
                $queryRecord->matchInstructorId($resource, true);
                $offerings = $offeringSearchSession->getCourseOfferingsByQuery($query);
            }
        }
        // Match a location id
        elseif (preg_match('/^resource\.place\./', $this->osidIdMap->toString($resource))) {
            $query->matchLocationId($resource, true);
            $offerings = $offeringSearchSession->getCourseOfferingsByQuery($query);
        }
        if (isset($offerings)) {
            $data['offering_count'] = $offerings->available();
            $i = 0;
            while ($offerings->hasNext() && $i < $data['offering_display_limit']) {
                $data['offerings'][] = $this->osidDataLoader->getOfferingData($offerings->getNextCourseOffering());
                ++$i;
            }
        }

        return $this->render('resources/view.html.twig', $data);
    }

    /**
     * List all department topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/resources/listcampusestxt/{catalog}', name: 'list_campuses_txt')]
    public function listcampusestxt(Request $request, ?\osid_id_Id $catalog = null)
    {
        $data = [];
        if ($catalog) {
            $lookupSession = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSessionForBin($catalog);
            $data['title'] = 'Resources in '.$lookupSession->getBin()->getDisplayName();
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getResourceManager()->getResourceLookupSession();
            $data['title'] = 'Resources in All Bins';
        }
        $lookupSession->useFederatedBinView();

        $data['resources'] = [];
        $resources = $lookupSession->getResourcesByGenusType(new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:resource.place.campus'));
        while ($resources->hasNext()) {
            $data['resources'][] = $resources->getNextResource();
        }

        $response = new Response($this->renderView('resources/list.txt.twig', $data));
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }
}
