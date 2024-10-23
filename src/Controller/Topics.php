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
 * A controller for working with topics.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Topics extends AbstractController
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
    }

    /**
     * Print out a list of all topics.
     */
    #[Route('/topics/list/{catalog}/{type}', name: 'list_topics')]
    public function listAction(string $catalog = NULL, string $type = NULL)
    {
        $data = $this->getTopicData($catalog, $type);
        return $this->render('topics/list.html.twig', $data);
    }

    /**
     * Print out an XML list of all catalogs.
     */
    #[Route('/topics/listxml/{catalog}/{type}', name: 'list_topics_xml')]
    public function listxmlAction(string $catalog = NULL, string $type = NULL)
    {
        $data = $this->getTopicData($catalog, $type);
        $data['feedLink'] = $this->generateUrl(
            'list_topics_xml',
            [
                'catalog' => $catalog,
                'type' => $type,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
        $response = new Response($this->renderView('topics/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

    /**
     * Print out a list of all topics.
     */
    #[Route('/topics/recent/{catalog}/{type}', name: 'list_recent_topics')]
    public function recentAction(string $catalog = NULL, string $type = NULL)
    {
        $data = $this->getRecentTopicData($catalog, $type);
        return $this->render('topics/list.html.twig', $data);
    }

    /**
     * Print out an XML list of all catalogs.
     */
    #[Route('/topics/recentxml/{catalog}/{type}', name: 'list_recent_topics_xml')]
    public function recentxmlAction(string $catalog = NULL, string $type = NULL)
    {
        $data = $this->getRecentTopicData($catalog, $type);
        $data['feedLink'] = $this->generateUrl(
            'list_recent_topics_xml',
            [
                'catalog' => $catalog,
                'type' => $type,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
        $response = new Response($this->renderView('topics/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');
        return $response;
    }

    #[Route('/topics/view/{topic}/{catalog}/{term}', name: 'view_topic')]
    public function view($topic, $catalog = NULL, $term = NULL)
    {
        $data = [];
        $id = $this->osidIdMap->fromString($topic);

        if ($catalog) {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $topicLookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $topicLookupSession->useIsolatedCourseCatalogView();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $termLookupSession->useIsolatedCourseCatalogView();
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
            $offeringLookupSession->useIsolatedCourseCatalogView();
            $data['catalog_id'] = $catalogId;
        }
        else {
            $topicLookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
            $topicLookupSession->useFederatedCourseCatalogView();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
            $offeringLookupSession->useFederatedCourseCatalogView();
            $data['catalog_id'] = NULL;
        }

        $data['topic'] = $topicLookupSession->getTopic($id);

        if ($term) {
            $termId = $this->osidIdMap->fromString($term);
            $offerings = $offeringLookupSession->getCourseOfferingsByTermByTopic($termId, $id);
            $data['term'] = $termLookupSession->getTerm($termId);
            $data['offeringsForAllTermsUrl'] = $this->generateUrl(
                'view_topic',
                [
                    'topic' => $topic,
                    'catalog' => $catalog,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        } else {
            $offerings = $offeringLookupSession->getCourseOfferingsByTopic($id);
            $data['term'] = NULL;
            $data['offeringsForAllTermsUrl'] = NULL;
        }

        // Don't do the work to display all offerings if we have a very large
        // number of offerings.
        $data['offerings'] = [];
        $data['offering_count'] = 0;
        $data['offering_display_limit'] = 200;
        $data['offeringsTitle'] = 'Sections';
        if (isset($offerings)) {
            $data['offering_count'] = $offerings->available();
            $i = 0;
            while ($offerings->hasNext() && $i < $data['offering_display_limit']) {
                $data['offerings'][] = $this->osidDataLoader->getOfferingData($offerings->getNextCourseOffering());
                $i++;
            }
        }

        return $this->render('topics/view.html.twig', $data);
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
            $catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
            $this->view->title = 'Topics in All Catalogs';
        }
        $lookupSession->useFederatedCourseCatalogView();

        $topicId = $this->osidIdMap->fromString($this->_getParam('topic'));

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
     * Answer an array of topic data for an optional catalog and type.
     *
     * @param string $catalog
     *   The catalog id to search within.
     * @param string  $type
     *   The type id to search within.
     * @return array
     *   The topic data ready for templating.
     */
    protected function getTopicData(string $catalog = NULL, string $type = NULL) {
        $data = [];
        if ($catalog) {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $data['title'] = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
            $data['catalog_id'] = $catalogId;
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
            $data['title'] = 'Topics in All Catalogs';
            $data['catalog_id'] = NULL;
        }
        $lookupSession->useFederatedCourseCatalogView();

        if ($type) {
            $genusType = $this->osidIdMap->typeFromString($type);
            $topics = $lookupSession->getTopicsByGenusType($genusType);
            $data['title'] .= ' of type ' . $type;
        } else {
            $topics = $lookupSession->getTopics();
        }

        $data = array_merge($data, $this->osidDataLoader->getTopics($topics));
        return $data;
    }

    /**
     * Answer an array of recent topic data for an optional catalog and type.
     *
     * @param string $catalog
     *   The catalog id to search within.
     * @param string  $type
     *   The type id to search within.
     * @return array
     *   The topic data ready for templating.
     */
    protected function getRecentTopicData(string $catalog = NULL, string $type = NULL) {
        if ($catalog) {
            $catalogId = $this->osidIdMap->fromString($catalog);
            $searchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSessionForCatalog($catalogId);
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $data['title'] = 'Topics in '.$searchSession->getCourseCatalog()->getDisplayName();
            $data['catalog_id'] = $catalogId;
        } else {
            $searchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSession();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $data['title'] = 'Topics in All Catalogs';
            $data['catalog_id'] = $catalogId;
        }
        $searchSession->useFederatedCourseCatalogView();
        $query = $searchSession->getTopicQuery();

        // Match recent terms
        $terms = $termLookupSession->getTerms();
        // Define a cutoff date after which courses will be included in the feed.
        // Currently set to 4 years. Would be good to have as a configurable time.
        $now = new \DateTime();
        $cutOff = $this->DateTime_getTimestamp($now) - (60 * 60 * 24 * 365 * 4);
        while ($terms->hasNext()) {
            $term = $terms->getNextTerm();
            if ($this->DateTime_getTimestamp($term->getEndTime()) > $cutOff) {
                $query->matchTermId($term->getId(), true);
            }
        }

        if ($type) {
            $genusType = $this->osidIdMap->typeFromString($type);
            $query->matchGenusType($genusType, true);
            $data['title'] .= ' of type ' . $type;
        }

        $topics = $searchSession->getTopicsByQuery($query);
        $data = array_merge($data, $this->osidDataLoader->getTopics($topics));

        return $data;
    }

    protected function DateTime_getTimestamp($dt)
    {
        $dtz_original = $dt->getTimezone();
        $dtz_utc = new \DateTimeZone('UTC');
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
            $catalogId = $this->osidIdMap->fromString($this->_getParam('catalog'));
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
            $this->view->title = 'Topics in All Catalogs';
        }
        $lookupSession->useFederatedCourseCatalogView();

        $topics = $lookupSession->getTopicsByGenusType($genusType);

        while ($topics->hasNext()) {
            $topic = $topics->getNextTopic();
            echo $this->osidIdMap->toString($topic->getId()).'|'.$this->osidIdMap->toString($topic->getId()).' - '.$topic->getDisplayName()."\n";
        }

        exit;
    }
}
