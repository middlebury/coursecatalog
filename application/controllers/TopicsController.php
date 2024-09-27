<?php
/**
 * @since 4/21/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A controller for working with terms.
 *
 * @since 4/21/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class TopicsController extends AbstractCatalogController
{
    /**
     * Print out a list of all topics.
     *
     * @return void
     *
     * @since 4/21/09
     */
    public function listAction()
    {
        if ($this->_getParam('catalog')) {
            $catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
            $lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
        } else {
            $lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
            $this->view->title = 'Topics in All Catalogs';
        }
        $lookupSession->useFederatedCourseCatalogView();

        if ($this->_getParam('type')) {
            $genusType = $this->_helper->osidType->fromString($this->_getParam('type'));
            $topics = $lookupSession->getTopicsByGenusType($genusType);
            $this->view->title .= ' of type '.$this->_getParam('type');
        } else {
            $topics = $lookupSession->getTopics();
        }

        $this->loadTopics($topics);

        $this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
        $this->view->headTitle($this->view->title);
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
     * Print out a list of all topics.
     *
     * @return void
     *
     * @since 4/21/09
     */
    public function recentAction()
    {
        if ($this->_getParam('catalog')) {
            $catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
            $searchSession = $this->_helper->osid->getCourseManager()->getTopicSearchSessionForCatalog($catalogId);
            $termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $this->view->title = 'Topics in '.$searchSession->getCourseCatalog()->getDisplayName();
        } else {
            $searchSession = $this->_helper->osid->getCourseManager()->getTopicSearchSession();
            $termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();

            $this->view->title = 'Topics in All Catalogs';
        }
        $searchSession->useFederatedCourseCatalogView();
        $query = $searchSession->getTopicQuery();

        // Match recent terms
        $terms = $termLookupSession->getTerms();
        // Define a cutoff date after which courses will be included in the feed.
        // Currently set to 4 years. Would be good to have as a configurable time.
        $now = new DateTime();
        $cutOff = $this->DateTime_getTimestamp($now) - (60 * 60 * 24 * 365 * 4);
        while ($terms->hasNext()) {
            $term = $terms->getNextTerm();
            if ($this->DateTime_getTimestamp($term->getEndTime()) > $cutOff) {
                $query->matchTermId($term->getId(), true);
            }
        }

        if ($this->_getParam('type')) {
            $genusType = $this->_helper->osidType->fromString($this->_getParam('type'));
            $query->matchGenusType($genusType, true);
            $this->view->title .= ' of type '.$this->_getParam('type');
        }

        $topics = $searchSession->getTopicsByQuery($query);

        $this->loadTopics($topics);

        $this->setSelectedCatalogId($searchSession->getCourseCatalogId());
        $this->view->headTitle($this->view->title);

        $this->_helper->viewRenderer->setRender('topics/list', null, true);
    }

    public function DateTime_getTimestamp($dt)
    {
        $dtz_original = $dt->getTimezone();
        $dtz_utc = new DateTimeZone('UTC');
        $dt->setTimezone($dtz_utc);
        $year = (int) $dt->format('Y');
        $month = (int) $dt->format('n');
        $day = (int) $dt->format('j');
        $hour = (int) $dt->format('G');
        $minute = (int) $dt->format('i');
        $second = (int) $dt->format('s');
        $dt->setTimezone($dtz_original);

        return gmmktime($hour, $minute, $second, $month, $day, $year);
    }

    /**
     * Print out an XML list of all catalogs.
     *
     * @return void
     */
    public function recentxmlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-Type', 'text/xml');

        $this->recentAction();
        $this->_helper->viewRenderer->setRender('topics/listxml', null, true);
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
        $id = $this->_helper->osidId->fromString($this->_getParam('topic'));
        $lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        $this->view->topic = $lookupSession->getTopic($id);

        $lookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        if ($this->_getParam('term')) {
            $termId = $this->_helper->osidId->fromString($this->_getParam('term'));
            $this->view->offerings = $lookupSession->getCourseOfferingsByTermByTopic($termId, $id);

            $termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $this->view->term = $termLookupSession->getTerm($termId);
        } else {
            $this->view->offerings = $lookupSession->getCourseOfferingsByTopic($id);
        }

        // Don't do the work to display instructors if we have a very large number of
        // offerings.
        if ($this->view->offerings->available() > 200) {
            $this->view->hideOfferingInstructors = true;
        }

        // Set the selected Catalog Id.
        if ($this->_getParam('catalog')) {
            $this->setSelectedCatalogId($this->_helper->osidId->fromString($this->_getParam('catalog')));
        }

        // Set the title
        $this->view->title = $this->view->topic->getDisplayName();
        $this->view->headTitle($this->view->title);

        $this->view->offeringsTitle = 'Sections';

        $allParams = [];
        $allParams['topic'] = $this->_getParam('topic');
        if ($this->getSelectedCatalogId()) {
            $allParams['catalog'] = $this->_helper->osidId->toString($this->getSelectedCatalogId());
        }
        $this->view->offeringsForAllTermsUrl = $this->_helper->url('view', 'topics', null, $allParams);

        $this->render('offerings', null, true);
    }

    /**
     * Print out an XML listing of a topic.
     *
     * @return void
     */
    public function viewxmlAction()
    {
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-Type', 'text/xml');

        if ($this->_getParam('catalog')) {
            $catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
            $lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
        } else {
            $lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
            $this->view->title = 'Topics in All Catalogs';
        }
        $lookupSession->useFederatedCourseCatalogView();

        $topicId = $this->_helper->osidId->fromString($this->_getParam('topic'));

        $topic = $lookupSession->getTopic($topicId);
        $topics = new phpkit_course_ArrayTopicList([$topic]);

        $this->loadTopics($topics);

        $this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
        $this->view->title = 'Catalog Details';
        $this->view->headTitle($this->view->title);

        $this->render('topics/listxml', null, true);
    }

    /**
     * List all department topics as a text file with each line being Id|DisplayName.
     *
     * @return void
     *
     * @since 10/20/09
     */
    public function listsubjectstxtAction()
    {
        $this->renderTextList(new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject'));
    }

    /**
     * List all requirement topics as a text file with each line being Id|DisplayName.
     *
     * @return void
     *
     * @since 10/20/09
     */
    public function listrequirementstxtAction()
    {
        $this->renderTextList(new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement'));
    }

    /**
     * List all level topics as a text file with each line being Id|DisplayName.
     *
     * @return void
     *
     * @since 1/15/10
     */
    public function listlevelstxtAction()
    {
        $this->renderTextList(new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.level'));
    }

    /**
     * List all block topics as a text file with each line being Id|DisplayName.
     *
     * @return void
     *
     * @since 10/20/09
     */
    public function listblockstxtAction()
    {
        $this->renderTextList(new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.block'));
    }

    /**
     * List all instruction-methods topics as a text file with each line being Id|DisplayName.
     *
     * @return void
     *
     * @since 10/20/09
     */
    public function listinstructionmethodstxtAction()
    {
        $this->renderTextList(new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.instruction_method'));
    }

    /**
     * List all department topics as a text file with each line being Id|DisplayName.
     *
     * @return void
     *
     * @since 10/20/09
     */
    public function listdepartmentstxtAction()
    {
        $this->renderTextList(new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department'));
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
            $lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
        } else {
            $lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
            $this->view->title = 'Topics in All Catalogs';
        }
        $lookupSession->useFederatedCourseCatalogView();

        $topics = $lookupSession->getTopicsByGenusType($genusType);

        while ($topics->hasNext()) {
            $topic = $topics->getNextTopic();
            echo $this->_helper->osidId->toString($topic->getId()).'|'.$this->_helper->osidId->toString($topic->getId()).' - '.$topic->getDisplayName()."\n";
        }

        exit;
    }
}
