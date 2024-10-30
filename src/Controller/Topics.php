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
 * A controller for working with topics.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Topics extends AbstractController
{
    public function __construct(
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private DataLoader $osidDataLoader,
    ) {
    }

    /**
     * Print out a list of all topics.
     */
    #[Route('/topics/list/{catalog}/{type}', name: 'list_topics')]
    public function listAction(?\osid_id_Id $catalog = null, ?\osid_type_Type $type = null)
    {
        $data = $this->getTopicData($catalog, $type);

        return $this->render('topics/list.html.twig', $data);
    }

    /**
     * Print out an XML list of all catalogs.
     */
    #[Route('/topics/listxml/{catalog}/{type}', name: 'list_topics_xml')]
    public function listxmlAction(?\osid_id_Id $catalog = null, ?\osid_type_Type $type = null)
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
    public function recentAction(?\osid_id_Id $catalog = null, ?\osid_type_Type $type = null)
    {
        $data = $this->getRecentTopicData($catalog, $type);

        return $this->render('topics/list.html.twig', $data);
    }

    /**
     * Print out an XML list of all catalogs.
     */
    #[Route('/topics/recentxml/{catalog}/{type}', name: 'list_recent_topics_xml')]
    public function recentxmlAction(?\osid_id_Id $catalog = null, ?\osid_type_Type $type = null)
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
    public function view(\osid_id_Id $topic, ?\osid_id_Id $catalog = null, ?\osid_id_Id $term = null)
    {
        $data = [];

        if ($catalog) {
            $topicLookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalog);
            $topicLookupSession->useIsolatedCourseCatalogView();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalog);
            $termLookupSession->useIsolatedCourseCatalogView();
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalog);
            $offeringLookupSession->useIsolatedCourseCatalogView();
            $data['catalog_id'] = $catalog;
        } else {
            $topicLookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
            $topicLookupSession->useFederatedCourseCatalogView();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
            $offeringLookupSession->useFederatedCourseCatalogView();
            $data['catalog_id'] = null;
        }

        $data['topic'] = $topicLookupSession->getTopic($topic);

        if ($term) {
            $offerings = $offeringLookupSession->getCourseOfferingsByTermByTopic($term, $topic);
            $data['term'] = $termLookupSession->getTerm($term);
            $data['offeringsForAllTermsUrl'] = $this->generateUrl(
                'view_topic',
                [
                    'topic' => $topic,
                    'catalog' => $catalog,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        } else {
            $offerings = $offeringLookupSession->getCourseOfferingsByTopic($topic);
            $data['term'] = null;
            $data['offeringsForAllTermsUrl'] = null;
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
                ++$i;
            }
        }

        return $this->render('topics/view.html.twig', $data);
    }

    /**
     * Print out an XML listing of a topic.
     */
    #[Route('/topics/viewxml/{topic}/{catalog}', name: 'view_topic_xml')]
    public function viewxmlAction(\osid_id_Id $topic, ?\osid_id_Id $catalog = null)
    {
        $data = [];

        if ($catalog) {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalog);
            $data['title'] = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
            $data['catalog_id'] = $catalog;
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
            $data['title'] = 'Topics in All Catalogs';
            $data['catalog_id'] = null;
        }
        $lookupSession->useFederatedCourseCatalogView();

        $topics = new \phpkit_course_ArrayTopicList([$lookupSession->getTopic($topic)]);
        $data = array_merge($data, $this->osidDataLoader->getTopics($topics));

        $data['feedLink'] = $this->generateUrl(
            'view_topic_xml',
            [
                'catalog' => $catalog,
                'topic' => $topic,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
        $response = new Response($this->renderView('topics/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }

    /**
     * List all subject topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listsubjectstxt/{catalog}', name: 'list_subjects_txt')]
    public function listsubjectstxtAction(?\osid_id_Id $catalog = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject'),
            $catalog,
        );
    }

    /**
     * List all requirement topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listrequirementstxt/{catalog}', name: 'list_requirements_txt')]
    public function listrequirementstxtAction(?\osid_id_Id $catalog = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement'),
            $catalog,
        );
    }

    /**
     * List all level topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listlevelstxt/{catalog}', name: 'list_levels_txt')]
    public function listlevelstxtAction(?\osid_id_Id $catalog = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.level'),
            $catalog,
        );
    }

    /**
     * List all block topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listblockstxt/{catalog}', name: 'list_blocks_txt')]
    public function listblockstxtAction(?\osid_id_Id $catalog = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.block'),
            $catalog,
        );
    }

    /**
     * List all instruction-methods topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listinstructionmethodstxt/{catalog}', name: 'list_instructionmethods_txt')]
    public function listinstructionmethodstxtAction(?\osid_id_Id $catalog = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.instruction_method'),
            $catalog,
        );
    }

    /**
     * List all department topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listdepartmentstxt/{catalog}', name: 'list_departments_txt')]
    public function listdepartmentstxtAction(?\osid_id_Id $catalog = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department'),
            $catalog,
        );
    }

    /**
     * Answer an array of topic data for an optional catalog and type.
     *
     * @param string $catalog
     *                        The catalog id to search within
     * @param string $type
     *                        The type id to search within
     *
     * @return array
     *               The topic data ready for templating
     */
    protected function getTopicData(?\osid_id_Id $catalog = null, ?\osid_type_Type $type = null)
    {
        $data = [];
        if ($catalog) {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalog);
            $data['title'] = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
            $data['catalog_id'] = $catalog;
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
            $data['title'] = 'Topics in All Catalogs';
            $data['catalog_id'] = null;
        }
        $lookupSession->useFederatedCourseCatalogView();

        if ($type) {
            $topics = $lookupSession->getTopicsByGenusType($type);
            $data['title'] .= ' of type '.$this->osidIdMap->typeToString($type);
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
     *                        The catalog id to search within
     * @param string $type
     *                        The type id to search within
     *
     * @return array
     *               The topic data ready for templating
     */
    protected function getRecentTopicData(?\osid_id_Id $catalog = null, ?\osid_type_Type $type = null)
    {
        if ($catalog) {
            $searchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSessionForCatalog($catalog);
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalog);
            $data['title'] = 'Topics in '.$searchSession->getCourseCatalog()->getDisplayName();
            $data['catalog_id'] = $catalog;
        } else {
            $searchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSession();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $data['title'] = 'Topics in All Catalogs';
            $data['catalog_id'] = null;
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
            $query->matchGenusType($type, true);
            $data['title'] .= ' of type '.$this->osidIdMap->typeToString($type);
        }

        $topics = $searchSession->getTopicsByQuery($query);
        $data = array_merge($data, $this->osidDataLoader->getTopics($topics));

        return $data;
    }

    protected function DateTime_getTimestamp(\DateTime $dt)
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
     * @param \osid_type_Type $genusType
     *                                   The type of topics to list
     * @param string          $catalog
     *                                   An optional catalog to limit results to
     *
     * @return Response
     *                  The rendered response
     */
    private function renderTextList(\osid_type_Type $genusType, ?\osid_id_Id $catalog = null)
    {
        $data = [];

        if ($catalog) {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalog);
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
        }
        $lookupSession->useFederatedCourseCatalogView();

        $topics = $lookupSession->getTopicsByGenusType($genusType);
        while ($topics->hasNext()) {
            $data['topics'][] = $topics->getNextTopic();
        }

        $response = new Response($this->renderView('topics/list.txt.twig', $data));
        $response->headers->set('Content-Type', 'text/plain; charset=utf-8');

        return $response;
    }
}
