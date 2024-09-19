<?php
/**
 * @since 10/26/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * The front controller plugin handles redirecting to external URIs for departments
 * and other items that might be configured to live externally.
 *
 * @since 10/26/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CatalogExternalRedirector extends Zend_Controller_Plugin_Abstract
{
    /**
     * Check if we have a URL configured for the route and id.
     *
     * @return void
     *
     * @since 10/26/09
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        if ('topics' == $request->getControllerName() && 'view' == $request->getActionName()) {
            // Per-catalog topic mapping.
            if ($request->getParam('catalog')) {
                $topics = $this->getTopicMapForCatalog($request->getParam('catalog'));
                if (isset($topics[$request->getParam('topic')])) {
                    $response = $this->getResponse();
                    $response->setRedirect($topics[$request->getParam('topic')]);
                    $response->sendResponse();
                    exit;
                }
            }
            // Global topic mapping.
            $topics = $this->getTopicMap();
            if (isset($topics[$request->getParam('topic')])) {
                $response = $this->getResponse();
                $response->setRedirect($topics[$request->getParam('topic')]);
                $response->sendResponse();
                exit;
            }
        }
    }

    /**
     * Answer the topic mapping.
     *
     * @return array
     *
     * @since 10/26/09
     */
    private function getTopicMap()
    {
        $config = Zend_Registry::getInstance()->config;

        return $this->extractTopicMap($config->catalog->topic_map);
    }

    /**
     * Answer the topic mapping for a particular catalog.
     *
     * @param string $catalogId
     *
     * @return array
     */
    private function getTopicMapForCatalog($catalogId)
    {
        $config = Zend_Registry::getInstance()->config;
        if (isset($config->catalog->catalog_topic_map->$catalogId)) {
            return $this->extractTopicMap($config->catalog->catalog_topic_map->$catalogId);
        } else {
            return [];
        }
    }

    /**
     * Answer the topic map for a set of config entries.
     *
     * @param iterator $entries
     *                          The config entries to map
     *
     * @return array
     */
    private function extractTopicMap($entries)
    {
        $topicMap = [];
        if ($entries && count($entries)) {
            foreach ($entries as $key => $entry) {
                if (isset($entry->id) && $entry->id) {
                    $id = $entry->id;
                } else {
                    $id = $key;
                }
                if (!isset($entry->url) || !$entry->url) {
                    throw new Exception('Each topic_map entry must have an url, "'.$key.'" does not.');
                }
                $topicMap[$id] = $entry->url;
            }
        }

        return $topicMap;
    }
}
