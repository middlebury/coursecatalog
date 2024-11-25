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
    #[Route('/topics/list/{catalogId}/{type}', name: 'list_topics')]
    public function listAction(?\osid_id_Id $catalogId = null, ?\osid_type_Type $type = null)
    {
        $data = $this->getTopicData($catalogId, $type);

        return $this->render('topics/list.html.twig', $data);
    }

    /**
     * Print out an XML list of all catalogs.
     */
    #[Route('/topics/listxml/{catalogId}/{type}', name: 'list_topics_xml')]
    public function listxmlAction(?\osid_id_Id $catalogId = null, ?\osid_type_Type $type = null)
    {
        $data = $this->getTopicData($catalogId, $type);
        $data['feedLink'] = $this->generateUrl(
            'list_topics_xml',
            [
                'catalogId' => $catalogId,
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
    #[Route('/topics/recent/{catalogId}/{type}', name: 'list_recent_topics')]
    public function recentAction(?\osid_id_Id $catalogId = null, ?\osid_type_Type $type = null)
    {
        $data = $this->getRecentTopicData($catalogId, $type);

        return $this->render('topics/list.html.twig', $data);
    }

    /**
     * Print out an XML list of all catalogs.
     */
    #[Route('/topics/recentxml/{catalogId}/{type}', name: 'list_recent_topics_xml')]
    public function recentxmlAction(?\osid_id_Id $catalogId = null, ?\osid_type_Type $type = null)
    {
        $data = $this->getRecentTopicData($catalogId, $type);
        $data['feedLink'] = $this->generateUrl(
            'list_recent_topics_xml',
            [
                'catalogId' => $catalogId,
                'type' => $type,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
        $response = new Response($this->renderView('topics/list.xml.twig', $data));
        $response->headers->set('Content-Type', 'text/xml; charset=utf-8');

        return $response;
    }

    #[Route('/topics/view/{topicId}/{catalogId}/{termId}', name: 'view_topic')]
    public function view(\osid_id_Id $topicId, ?\osid_id_Id $catalogId = null, ?\osid_id_Id $termId = null)
    {
        $data = [];

        if ($catalogId) {
            $topicLookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $topicLookupSession->useIsolatedCourseCatalogView();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $termLookupSession->useIsolatedCourseCatalogView();
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
            $offeringLookupSession->useIsolatedCourseCatalogView();
            $data['catalogId'] = $catalogId;
        } else {
            $topicLookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
            $topicLookupSession->useFederatedCourseCatalogView();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
            $offeringLookupSession->useFederatedCourseCatalogView();
            $data['catalogId'] = null;
        }

        $data['topic'] = $topicLookupSession->getTopic($topicId);

        if ($termId) {
            $offerings = $offeringLookupSession->getCourseOfferingsByTermByTopic($termId, $topicId);
            $data['term'] = $termLookupSession->getTerm($termId);
            $data['offeringsForAllTermsUrl'] = $this->generateUrl(
                'view_topic',
                [
                    'topicId' => $topicId,
                    'catalogId' => $catalogId,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        } else {
            $offerings = $offeringLookupSession->getCourseOfferingsByTopic($topicId);
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
    #[Route('/topics/viewxml/{topicId}/{catalogId}', name: 'view_topic_xml')]
    public function viewxmlAction(\osid_id_Id $topicId, ?\osid_id_Id $catalogId = null)
    {
        $data = [];

        if ($catalogId) {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $data['title'] = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
            $data['catalogId'] = $catalogId;
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
            $data['title'] = 'Topics in All Catalogs';
            $data['catalogId'] = null;
        }
        $lookupSession->useFederatedCourseCatalogView();

        $topics = new \phpkit_course_ArrayTopicList([$lookupSession->getTopic($topicId)]);
        $data = array_merge($data, $this->osidDataLoader->getTopics($topics));

        $data['feedLink'] = $this->generateUrl(
            'view_topic_xml',
            [
                'catalogId' => $catalogId,
                'topicId' => $topicId,
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
    #[Route('/topics/listsubjectstxt/{catalogId}', name: 'list_subjects_txt')]
    public function listsubjectstxtAction(?\osid_id_Id $catalogId = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject'),
            $catalogId,
        );
    }

    /**
     * List all requirement topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listrequirementstxt/{catalogId}', name: 'list_requirements_txt')]
    public function listrequirementstxtAction(?\osid_id_Id $catalogId = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement'),
            $catalogId,
        );
    }

    /**
     * List all level topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listlevelstxt/{catalogId}', name: 'list_levels_txt')]
    public function listlevelstxtAction(?\osid_id_Id $catalogId = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.level'),
            $catalogId,
        );
    }

    /**
     * List all block topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listblockstxt/{catalogId}', name: 'list_blocks_txt')]
    public function listblockstxtAction(?\osid_id_Id $catalogId = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.block'),
            $catalogId,
        );
    }

    /**
     * List all instruction-methods topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listinstructionmethodstxt/{catalogId}', name: 'list_instructionmethods_txt')]
    public function listinstructionmethodstxtAction(?\osid_id_Id $catalogId = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.instruction_method'),
            $catalogId,
        );
    }

    /**
     * List all department topics as a text file with each line being Id|DisplayName.
     */
    #[Route('/topics/listdepartmentstxt/{catalogId}', name: 'list_departments_txt')]
    public function listdepartmentstxtAction(?\osid_id_Id $catalogId = null)
    {
        return $this->renderTextList(
            new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department'),
            $catalogId,
        );
    }

    /**
     * Answer an array of topic data for an optional catalog and type.
     *
     * @param osid_id_Id     $catalogId
     *                                  The catalog id to search within
     * @param osid_type_Type $type
     *                                  The type id to search within
     *
     * @return array
     *               The topic data ready for templating
     */
    protected function getTopicData(?\osid_id_Id $catalogId = null, ?\osid_type_Type $type = null)
    {
        $data = [];
        if ($catalogId) {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
            $data['title'] = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
            $data['catalogId'] = $catalogId;
        } else {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSession();
            $data['title'] = 'Topics in All Catalogs';
            $data['catalogId'] = null;
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
     * @param osid_id_Id     $catalogId
     *                                  The catalog id to search within
     * @param osid_type_Type $type
     *                                  The type id to search within
     *
     * @return array
     *               The topic data ready for templating
     */
    protected function getRecentTopicData(?\osid_id_Id $catalogId = null, ?\osid_type_Type $type = null)
    {
        if ($catalogId) {
            $searchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSessionForCatalog($catalogId);
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
            $data['title'] = 'Topics in '.$searchSession->getCourseCatalog()->getDisplayName();
            $data['catalogId'] = $catalogId;
        } else {
            $searchSession = $this->osidRuntime->getCourseManager()->getTopicSearchSession();
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $data['title'] = 'Topics in All Catalogs';
            $data['catalogId'] = null;
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
     * @param \osid_id_Id     $catalogId
     *                                   An optional catalog to limit results to
     *
     * @return Response
     *                  The rendered response
     */
    private function renderTextList(\osid_type_Type $genusType, ?\osid_id_Id $catalogId = null)
    {
        $data = [
            'topics' => [],
        ];

        if ($catalogId) {
            $lookupSession = $this->osidRuntime->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
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
