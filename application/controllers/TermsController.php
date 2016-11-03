<?php
/**
 * @since 4/21/09
 * @package catalog.controllers
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A controller for working with terms
 *
 * @since 4/21/09
 * @package catalog.controllers
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class TermsController
	extends AbstractCatalogController
{

	/**
	 * Print out a list of all terms
	 *
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function listAction () {
		if ($this->_getParam('catalog')) {
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$lookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
			$this->view->title = 'Terms in '.$lookupSession->getCourseCatalog()->getDisplayName();
		} else {
			$lookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
			$this->view->title = 'Terms in All Catalogs';
		}
		$lookupSession->useFederatedCourseCatalogView();

		$this->view->terms = $lookupSession->getTerms();

		$this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
		$this->view->headTitle($this->view->title);

		$this->view->menuIsTerms = true;
	}

	/**
	 * Print out an XML list of all terms
	 *
	 * @return void
	 */
	public function listxmlAction () {
		$this->_helper->layout->disableLayout();
		$this->getResponse()->setHeader('Content-Type', 'text/xml');

		$this->listAction();
	}

	/**
	 * View a catalog details
	 *
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function viewAction () {
		$id = $this->_helper->osidId->fromString($this->_getParam('term'));
		$lookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		$this->view->term = $lookupSession->getTerm($id);

		$lookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		$this->view->offerings = $lookupSession->getCourseOfferingsByTerm($id);

		// Set the selected Catalog Id.
		$catalogSession = $this->_helper->osid->getCourseManager()->getTermCatalogSession();
		$catalogIds = $catalogSession->getCatalogIdsByTerm($id);
		if ($catalogIds->hasNext()) {
			$this->setSelectedCatalogId($catalogIds->getNextId());
		}

		// Set the title
		$this->view->title = $this->view->term->getDisplayName();
		$this->view->headTitle($this->view->title);

		$this->view->menuIsTerms = true;
	}

	/**
	 * View a catalog details
	 *
	 * @return void
	 * @access public
	 */
	public function detailsAction () {
		$id = $this->_helper->osidId->fromString($this->_getParam('term'));
		$lookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		$this->view->term = $lookupSession->getTerm($id);

		// Set the selected Catalog Id.
		$catalogSession = $this->_helper->osid->getCourseManager()->getTermCatalogSession();
		$catalogIds = $catalogSession->getCatalogIdsByTerm($id);
		if ($catalogIds->hasNext()) {
			$this->setSelectedCatalogId($catalogIds->getNextId());
		}

		// Set the title
		$this->view->title = $this->view->term->getDisplayName();
		$this->view->headTitle($this->view->title);

		$this->view->menuIsTerms = true;
	}

	/**
	 * View a catalog details
	 *
	 * @return void
	 * @access public
	 */
	public function detailsxmlAction () {
		$this->_helper->layout->disableLayout();
		$this->detailsAction();
	}

}
