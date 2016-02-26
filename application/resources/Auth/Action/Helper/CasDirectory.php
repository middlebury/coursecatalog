<?php
/**
 * @since 6/14/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A helper for authenticating via CAS.
 *
 * @since 6/14/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Auth_Action_Helper_CasDirectory
	extends Zend_Controller_Action_Helper_Abstract
	implements Auth_Action_Helper_AuthInterface,
	Auth_Action_Helper_MasqueradeInterface
{

	/**
	 * Initialize this helper.
	 *
	 * @return void
	 * @access public
	 * @since 6/14/10
	 */
	public function init () {

	}

	/**
	 * Answer true if this authentication method allows login.
	 *
	 * @return boolean
	 * @access public
	 */
	public function isAuthenticationEnabled () {
		return true;
	}

	/**
	 * Log in. Throw an exception if isAuthenticationEnabled is false.
	 *
	 * @return boolean TRUE on successful login.
	 * @access public
	 */
	public function login()
	{
		throw new Exception("Direct authentication not supported by this helper");
	}

	/**
	 * Log out. Throw an exception if isAuthenticationEnabled is false.
	 *
	 * @param optional string $returnUrl A url to return to after successful logout.
	 * @return void
	 * @access public
	 */
	public function logout($returnUrl = null)
	{
		unset($_SESSION['masquerade.CasDirectory']);
	}

	/**
	 * Answer true if a user is currently authenticated.
	 *
	 * @return boolean
	 * @access public
	 */
	public function isAuthenticated() {
		return isset($_SESSION['masquerade.CasDirectory']);
	}

	/**
	 * Answer the user id if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return string
	 * @access public
	 */
	public function getUserId() {
			if (!$this->isAuthenticated())
				throw new Exception("No user authenticated.");
			return $_SESSION['masquerade.CasDirectory']['id'];
	}

	/**
	 * Answer a name for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return string
	 * @access public
	 */
	public function getUserDisplayName() {
		if (!$this->isAuthenticated())
			throw new Exception("No user authenticated.");
		return $_SESSION['masquerade.CasDirectory']['name'];
	}

	/**
	 * Answer an email address for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return string
	 * @access public
	 */
	public function getUserEmail() {
		if (!$this->isAuthenticated())
			throw new Exception("No user authenticated.");
		return $_SESSION['masquerade.CasDirectory']['email'];
	}

	/**
	 * Answer an array of groups for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return array
	 * @access public
	 */
	public function getUserGroups() {
		if (!$this->isAuthenticated())
			throw new Exception("No user authenticated.");

		return $_SESSION['masquerade.CasDirectory']['memberof'];
	}

	/**
	 * Change to a different user account. Clients are responsible for checking that
	 * the current user is authorized before calling this method. Clients are also
	 * responsible for logging.
	 *
	 * Throws an exception if unsupported.
	 *
	 * @param string $userId
	 * @return void
	 */
	public function changeUser ($userId) {
		$config = Zend_Registry::getInstance()->config;
		if (empty($config->masquerade->CasDirectory->url))
			throw new Exception("No masquerade.CasDirectory.url configured.");

		$extraParams = array();
		if (!empty($config->masquerade->CasDirectory->extra_params)) {
			parse_str($config->masquerade->CasDirectory->extra_params, $extraParams);
		}

		$params = array_merge(array(
				'action'	=> 'get_user',
				'id'		=> $userId,
			), $extraParams);
		$url = $config->masquerade->CasDirectory->url.'?'.http_build_query($params);

		$headers = array("User-Agent: Drupal CAS-MM-Sync");
		if (!empty($config->masquerade->CasDirectory->headers)) {
			foreach ($config->masquerade->CasDirectory->headers as $header) {
				if (!preg_match('/.+:.+/', $header))
					throw new Exception('Each element of masquerade.CasDirectory.headers[] must be a "key: value" string.');
				$headers[] = $header;
			}
		}
		foreach ($headers as $k => $v) {
			$headers[$k] = rtrim($v)."\r\n";
		}
		$opts = array(
			'http' => array(
				'header' => implode("", $headers),
			)
		);
		$context = stream_context_create($opts);
		$xmlstring = file_get_contents($url, false, $context);
		if (empty($xmlstring))
			throw new Exception("Could not load user information.");
		$doc = new DomDocument;
		if (!$doc->loadXML($xmlstring))
			throw new Exception("Could not parse user information.");

		$xpath = new DOMXPath($doc);
		$xpath->registerNamespace('cas', 'http://www.yale.edu/tp/cas');

		$id = $xpath->query('/cas:results/cas:entry/cas:user')->item(0)->nodeValue;
		if ($id == $userId) {
			$_SESSION['masquerade.CasDirectory'] = array(
				'id' => $userId,
			);
		} else {
			throw new Exception("Id found didn't match query.");
		}

		$name = '';
		$elements = $xpath->query('/cas:results/cas:entry/cas:attribute[@name="FirstName"]');
		if ($elements->length)
			$name .= $elements->item(0)->getAttribute('value');
		$name .= " ";
		$elements = $xpath->query('/cas:results/cas:entry/cas:attribute[@name="LastName"]');
		if ($elements->length)
			$name .= $elements->item(0)->getAttribute('value');
		if (strlen(trim($name)))
			$_SESSION['masquerade.CasDirectory']['name'] = $name;
		else
			$_SESSION['masquerade.CasDirectory']['name'] = 'name unknown';

		$elements = $xpath->query('/cas:results/cas:entry/cas:attribute[@name="EMail"]');
		if ($elements->length)
			$_SESSION['masquerade.CasDirectory']['email'] = $elements->item(0)->getAttribute('value');
		else
			$_SESSION['masquerade.CasDirectory']['email'] = '';

		$elements = $xpath->query('/cas:results/cas:entry/cas:attribute[@name="MemberOf"]');
		$_SESSION['masquerade.CasDirectory']['memberof'] = array();
		foreach ($elements as $element) {
			$_SESSION['masquerade.CasDirectory']['memberof'][] = $element->getAttribute('value');
		}
	}

}
