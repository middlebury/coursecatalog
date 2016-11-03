<?php
/**
 * @since 10/26/09
 * @package catalog.controlers
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * The front controller plugin handles redirecting to external URIs for departments
 * and other items that might be configured to live externally.
 *
 * @since 10/26/09
 * @package catalog.controlers
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CatalogExternalRedirector
	extends Zend_Controller_Plugin_Abstract
{

	/**
	 * Check if we have a URL configured for the route and id.
	 *
	 * @param Zend_Controller_Request_Abstract $request
	 * @return void
	 * @access public
	 * @since 10/26/09
	 */
	public function routeShutdown (Zend_Controller_Request_Abstract $request) {
		if ( $request->getControllerName() == 'topics' && $request->getActionName() == 'view' ) {
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
	 * Answer the topic mapping
	 *
	 * @return array
	 * @access private
	 * @since 10/26/09
	 */
	private function getTopicMap () {
		$config = Zend_Registry::getInstance()->config;
		$entries = $config->catalog->topic_map;
		$topicMap = array();
		if ($entries && count($entries)) {
			foreach ($entries as $key => $entry) {
				if (!isset($entry->id) || !$entry->id)
					throw new Exception('Each topic_map entry must have an id, "'.$key.'" does not.');
				if (!isset($entry->url) || !$entry->url)
					throw new Exception('Each topic_map entry must have an url, "'.$key.'" does not.');
				$topicMap[$entry->id] = $entry->url;
			}
		}
		return $topicMap;
	}

}
