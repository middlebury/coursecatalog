<?php
/**
 * @since 11/28/16
 * @package catalog.controllers
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A controller for providing the Kurogo JSON API
 *
 * @since 11/28/16
 * @package catalog.controllers
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class JsonController
	extends Zend_Controller_Action
{
	/**
	 * Initialize our view with common properties
	 *
	 * @return void
	 * @access public
	 */
	public function init () {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->getResponse()->setHeader('Content-Type', 'text/json');
	}

	/**
	 * Print out a list of all terms.
	 * Kurogo Docs: https://support.modolabs.com/support/solutions/articles/5000659578
	 *
	 * @return void
	 * @access public
	 */
	public function termsAction () {
		if ($this->_getParam('catalog')) {
			$catalogId = $this->_helper->osidId->fromString("catalog/".$this->_getParam('catalog'));
			$lookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
		} else {
			$lookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
		}
		$lookupSession->useFederatedCourseCatalogView();

		$terms = $lookupSession->getTerms();
		$result = array('terms' => array());
		while ($terms->hasNext()) {
			$term = $terms->getNextTerm();
			$result['terms'][] = array(
				'code' => preg_replace('/^term\//', '', $term->getId()->getIdentifier()),
				'description' => $term->getDisplayName(),
			);
		}
		print json_encode($result, JSON_PRETTY_PRINT);
	}
}
