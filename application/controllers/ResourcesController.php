<?php
/**
 * @since 4/21/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A controller for working with resources.
 *
 * @since 4/21/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class ResourcesController extends AbstractCatalogController
{
    /**
     * Initialize object.
     *
     * Called from {@link __construct()} as final step of object instantiation.
     *
     * @return void
     */
    public function init()
    {
        $this->instructorType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
        parent::init();
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
    // 			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
    // 			$lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
    // 			$this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
    // 		} else {
    // 			$lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
    // 			$this->view->title = 'Topics in All Catalogs';
    // 		}
    // 		$lookupSession->useFederatedCourseCatalogView();
    //
    // 		$this->loadTopics($lookupSession->getTopics());
    //
    // 		$this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
    // 		$this->view->headTitle($this->view->title);
    // 	}

    /**
     * View a catalog details.
     *
     * @return void
     *
     * @since 4/21/09
     */
    public function viewAction()
    {
        $id = $this->_helper->osidId->fromString($this->_getParam('resource'));
        $lookupSession = $this->_helper->osid->getCourseManager()->getResourceManager()->getResourceLookupSession();
        $lookupSession->useFederatedBinView();
        $this->view->resource = $lookupSession->getResource($id);

        $offeringSearchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSession();
        $offeringSearchSession->useFederatedCourseCatalogView();
        $query = $offeringSearchSession->getCourseOfferingQuery();

        if ($this->_getParam('term')) {
            $termId = $this->_helper->osidId->fromString($this->_getParam('term'));

            $query->matchTermId($termId, true);

            $termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $this->view->term = $termLookupSession->getTerm($termId);
        }

        // Match the instructor Id
        if ($query->hasRecordType($this->instructorType)) {
            $queryRecord = $query->getCourseOfferingQueryRecord($this->instructorType);
            $queryRecord->matchInstructorId($id, true);

            $this->view->offerings = $offeringSearchSession->getCourseOfferingsByQuery($query);

            // Don't do the work to display instructors if we have a very large number of
            // offerings.
            if ($this->view->offerings->available() > 200) {
                $this->view->hideOfferingInstructors = true;
            }

            $this->view->offeringsTitle = 'Sections';

            $allParams = [];
            $allParams['resource'] = $this->_getParam('resource');
            if ($this->getSelectedCatalogId()) {
                $allParams['catalog'] = $this->_helper->osidId->toString($this->getSelectedCatalogId());
            }
            $this->view->offeringsForAllTermsUrl = $this->_helper->url('view', 'resources', null, $allParams);

            $this->render('offerings', null, true);
        } else {
            $this->view->hideOfferingInstructors = true;
        }

        // Set the selected Catalog Id.
        if ($this->_getParam('catalog')) {
            $this->setSelectedCatalogId($this->_helper->osidId->fromString($this->_getParam('catalog')));
        }

        // Set the title
        $this->view->title = $this->view->resource->getDisplayName();
        $this->view->headTitle($this->view->title);
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
        $this->renderTextList(new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:resource/place/campus'));
    }

    /**
     * Render a text feed for a given topic type.
     *
     * @return void
     *
     * @since 11/17/09
     */
    private function renderTextList(osid_type_Type $genusType)
    {
        header('Content-Type: text/plain');

        if ($this->_getParam('catalog')) {
            $catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
            $lookupSession = $this->_helper->osid->getCourseManager()->getResourceManager()->getResourceLookupSessionForBin($catalogId);
            $this->view->title = 'Resources in '.$lookupSession->getBin()->getDisplayName();
        } else {
            $lookupSession = $this->_helper->osid->getCourseManager()->getResourceManager()->getResourceLookupSession();
            $this->view->title = 'Resources in All Bins';
        }
        $lookupSession->useFederatedBinView();

        $resources = $lookupSession->getResourcesByGenusType($genusType);

        while ($resources->hasNext()) {
            $resource = $resources->getNextResource();
            echo $this->_helper->osidId->toString($resource->getId()).'|'.$this->_helper->osidId->toString($resource->getId()).' - '.$resource->getDisplayName()."\n";
        }
        // 		var_dump($lookupSession);
        // 		var_dump($resources);
        exit;
    }
}
