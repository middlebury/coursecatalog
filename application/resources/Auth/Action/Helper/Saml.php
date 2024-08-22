<?php
/**
 * @since 8/13/2024
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

use OneLogin\Saml2\Auth as SamlAuth;

/**
 * A helper for authenticating via SAML.
 *
 * @since 8/13/2024
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Auth_Action_Helper_Saml
	extends Zend_Controller_Action_Helper_Abstract
	implements Auth_Action_Helper_AuthInterface
{
	protected $auth;

	/**
	 * Initialize this helper.
	 *
	 * @return void
	 * @access public
	 */
	public function init () {
		if (empty($this->auth)) {
			require_once(BASE_PATH . '/vendor/onelogin/php-saml/_toolkit_loader.php');
			$this->auth = new SamlAuth($this->getSamlConfig());
		}
	}

	/**
	 * Answer the SAML configuration.
	 *
	 * @return array
	 */
	protected function getSamlConfig() {
		$config = Zend_Registry::getInstance()->config;
		return [
			// If 'strict' is True, then the PHP Toolkit will reject unsigned
			// or unencrypted messages if it expects them to be signed or encrypted.
			// Also it will reject the messages if the SAML standard is not strictly
			// followed: Destination, NameId, Conditions ... are validated too.
			'strict' => true,

			// Enable debug mode (to print errors).
			'debug' => true,

			// Set a BaseURL to be used instead of try to guess
			// the BaseURL of the view that process the SAML Message.
			// Ex http://sp.example.com/
			//	http://example.com/sp/
			// 'baseurl' => rtrim($this->getAbsoluteUrl(), '/'),

			// Service Provider Data that we are deploying.
			'sp' => [
				// Identifier of the SP entity  (must be a URI)
				'entityId' => $this->getAbsoluteUrl(),
				// Specifies info about where and how the <AuthnResponse> message MUST be
				// returned to the requester, in this case our SP.
				'assertionConsumerService' => [
					// URL Location where the <Response> from the IdP will be returned
					'url' => $this->getAbsoluteUrl('/auth/login'),
					// SAML protocol binding to be used when returning the <Response>
					// message. SAML Toolkit supports this endpoint for the
					// HTTP-POST binding only.
					'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
				],
				// Specifies info about where and how the <Logout Response> message MUST be
				// returned to the requester, in this case our SP.
				'singleLogoutService' => [
					// URL Location where the <Response> from the IdP will be returned
					'url' => $this->getAbsoluteUrl('/auth/logout'),
					// SAML protocol binding to be used when returning the <Response>
					// message. SAML Toolkit supports the HTTP-Redirect binding
					// only for this endpoint.
					'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
				],
				/*
				 * Key rollover
				 * If you plan to update the SP x509cert and privateKey
				 * you can define here the new x509cert and it will be
				 * published on the SP metadata so Identity Providers can
				 * read them and get ready for rollover.
				 */
				// 'x509certNew' => '',
			],

			// Identity Provider Data that we want connected with our SP.
			'idp' => [
				// Identifier of the IdP entity  (must be a URI)
				'entityId' => $config->saml->idp->entityId,
				// SSO endpoint info of the IdP. (Authentication Request protocol)
				'singleSignOnService' => [
					// URL Target of the IdP where the Authentication Request Message
					// will be sent.
					'url' => $config->saml->idp->singleSignOnService->url,
					// SAML protocol binding to be used when returning the <Response>
					// message. SAML Toolkit supports the HTTP-Redirect binding
					// only for this endpoint.
					'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
				],
				// SLO endpoint info of the IdP.
				'singleLogoutService' => [
					// URL Location of the IdP where SLO Request will be sent.
					'url' => $config->saml->idp->singleLogoutService->url,
					// URL location of the IdP where the SP will send the SLO Response (ResponseLocation)
					// if not set, url for the SLO Request will be used
					'responseUrl' => '',
					// SAML protocol binding to be used when returning the <Response>
					// message. SAML Toolkit supports the HTTP-Redirect binding
					// only for this endpoint.
					'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
				],
				// Public x509 certificate of the IdP
				'x509cert' => $config->saml->idp->x509cert,
				/*
				 *  Instead of use the whole x509cert you can use a fingerprint in order to
				 *  validate a SAMLResponse, but we don't recommend to use that
				 *  method on production since is exploitable by a collision attack.
				 *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it,
				 *   or add for example the -sha256 , -sha384 or -sha512 parameter)
				 *
				 *  If a fingerprint is provided, then the certFingerprintAlgorithm is required in order to
				 *  let the toolkit know which algorithm was used. Possible values: sha1, sha256, sha384 or sha512
				 *  'sha1' is the default value.
				 *
				 *  Notice that if you want to validate any SAML Message sent by the HTTP-Redirect binding, you
				 *  will need to provide the whole x509cert.
				 */
				// 'certFingerprint' => '',
				// 'certFingerprintAlgorithm' => 'sha1',

				/* In some scenarios the IdP uses different certificates for
				 * signing/encryption, or is under key rollover phase and
				 * more than one certificate is published on IdP metadata.
				 * In order to handle that the toolkit offers that parameter.
				 * (when used, 'x509cert' and 'certFingerprint' values are
				 * ignored).
				 */
				// 'x509certMulti' => [
				// 	'signing' => [
				// 		0 => '<cert1-string>',
				// 	],
				// 	'encryption' => [
				// 		0 => '<cert2-string>',
				// 	],
			],


			// Security settings
			'security' => [
				// Authentication context.
				// Set to false and no AuthContext will be sent in the AuthNRequest.
				// Set true or don't present this parameter and you will get an AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'.
				// Set an array with the possible auth context values: array('urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509').
				'requestedAuthnContext' => false,
			],
		];
	}

	protected function getAbsoluteUrl($path = '/') {
		return 'https://' . $_SERVER['HTTP_HOST'] . rtrim($this->getFrontController()->getBaseUrl(), '/') . '/' . ltrim($path, '/');
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
	 * @param optional string $returnUrl A url to return to after successful login.
	 * @return boolean TRUE on successful login.
	 * @access public
	 */
	public function login($returnUrl = null)
	{
		$this->init();

		// Trigger SSO login.
		if ($_SERVER['REQUEST_METHOD'] != 'POST') {
			$this->auth->login($returnUrl);
			return true;
		}
		// Process SSO response.
		else {
			if (isset($_SESSION) && isset($_SESSION['AuthNRequestID'])) {
				$requestID = $_SESSION['AuthNRequestID'];
			} else {
				$requestID = null;
			}

			$this->auth->processResponse($requestID);
			unset($_SESSION['AuthNRequestID']);

			$errors = $this->auth->getErrors();
			if (!empty($errors)) {
				throw new \Exception("Authentication failed with these errors: " . implode(', ', $errors));
			}

			if (!$this->auth->isAuthenticated()) {
				throw new \Exception("Authentication failed.");
			}

			$_SESSION['SAML_AUTH_NAMEID'] = $this->auth->getNameId();
			$_SESSION['SAML_AUTH_ATTRIBUTES'] = $this->auth->getAttributes();
			// Redirect to the page we started the Login flow from.
			if (isset($_REQUEST['RelayState'])) {
				$this->auth->redirectTo();
			}

			return true;
		}
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
		unset($_SESSION['SAML_AUTH_NAMEID']);
		unset($_SESSION['SAML_AUTH_ATTRIBUTES']);
		$this->init();
		$this->auth->logout($returnUrl);
	}

	/**
	 * Answer true if a user is currently authenticated.
	 *
	 * @return boolean
	 * @access public
	 */
	public function isAuthenticated() {
		if (!empty($_SESSION['SAML_AUTH_NAMEID'])) {
			return true;
		}

		$this->init();
		return $this->auth->isAuthenticated();
	}

	/**
	 * Answer the user id if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return string
	 * @access public
	 */
	public function getUserId() {
		if (!empty($_SESSION['SAML_AUTH_NAMEID'])) {
			return $_SESSION['SAML_AUTH_NAMEID'];
		}

		if ($this->isAuthenticated()) {
			return $this->auth->getNameId();
		}
		else {
			throw new Exception("No user is authenticated, cannot provide a user id.");
		}
	}

	/**
	 * Answer a name for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return string
	 * @access public
	 */
	public function getUserDisplayName() {
		return trim($this->getAttribute('http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname')) . ' ' .
			trim($this->getAttribute('http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname'));
	}

	/**
	 * Answer an email address for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return string
	 * @access public
	 */
	public function getUserEmail() {
		return trim($this->getAttribute('http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'));
	}

	/**
	 * Answer an array of groups for the user if a user is currently authenticated or throw an Exception
	 * if isAuthenticated is false.
	 *
	 * @return array
	 * @access public
	 */
	public function getUserGroups() {
		return $this->getAttribute('http://schemas.microsoft.com/ws/2008/06/identity/claims/groups', false);
	}

	/**
	 * Answer an attribute from the SAML response.
	 *
	 * @param string $name
	 *   The attribute name.
	 * @param bool $singleValue
	 *   Return a single value from the attribute array rather than multiple.
	 * @return string|array|null
	 *   The attribute value.
	 */
	protected function getAttribute($name, $singleValue = true) {
		// Prefer attributes stored in the session already.
		if (!empty($_SESSION['SAML_AUTH_ATTRIBUTES'])) {
			if (empty($_SESSION['SAML_AUTH_ATTRIBUTES'][$name])) {
				return null;
			}
			else {
				if ($singleValue) {
					if (isset($_SESSION['SAML_AUTH_ATTRIBUTES'][$name][0])) {
						return $_SESSION['SAML_AUTH_ATTRIBUTES'][$name][0];
					}
					else {
						return null;
					}
				}
				else {
					return $_SESSION['SAML_AUTH_ATTRIBUTES'][$name];
				}
			}
		}
		// Get the attributes from the current authentication response if
		// available.
		else {
			if ($this->isAuthenticated()) {
				return $this->auth->getAttribute($name);
			}
			else {
				throw new Exception("No user is authenticated, cannot provide a $name attribute.");
			}
		}
	}
}
