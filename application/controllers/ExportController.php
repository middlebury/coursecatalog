<?php
/**
 * @since 8/23/17
 *
 * @copyright Copyright &copy; 2017, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A controller for working with courses.
 *
 * @since 1/23/18
 *
 * @copyright Copyright &copy; 2018, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class ExportController extends AbstractCatalogController
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
        parent::init();
        $this->view->csrf_key = $this->_helper->csrfKey();

        if (!$this->_helper->auth()->isAuthenticated()) {
            $this->_helper->auth()->login();
        }

        $config = Zend_Registry::getInstance()->config;
        if (!isset($config->admin->administrator_ids)) {
            throw new PermissionDeniedException('No admins are defined for this application.');
        }
        $admins = explode(',', $config->admin->administrator_ids);
        if (!in_array($this->_helper->auth()->getUserId(), $admins)) {
            throw new PermissionDeniedException('You are not authorized to administer this application.'.$admins[1]);
        }
    }
}
