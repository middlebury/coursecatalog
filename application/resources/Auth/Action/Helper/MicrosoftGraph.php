<?php
/**
 * @since 8/15/2024
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

use Microsoft\Graph\Generated\Models\User;
use Microsoft\Graph\Generated\Users\UsersRequestBuilderGetRequestConfiguration;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;

/**
 * A helper for masquerading via Microsoft Graph user lookup.
 *
 * @since 8/15/2024
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Auth_Action_Helper_MicrosoftGraph extends Zend_Controller_Action_Helper_Abstract implements Auth_Action_Helper_AuthInterface, Auth_Action_Helper_MasqueradeInterface
{
    /**
     * The O365 Graph Api client.
     *
     * @var Microsoft\Graph\Graph|null
     */
    protected $graph;

    /**
     * Initialize this helper.
     *
     * @return void
     *
     * @since 6/14/10
     */
    public function init()
    {
    }

    /**
     * Answer true if this authentication method allows login.
     *
     * @return bool
     */
    public function isAuthenticationEnabled()
    {
        return true;
    }

    /**
     * Log in. Throw an exception if isAuthenticationEnabled is false.
     *
     * @param optional string $returnUrl A url to return to after successful login
     *
     * @return bool TRUE on successful login
     */
    public function login($returnUrl = null)
    {
        throw new Exception('Direct authentication not supported by this helper');
    }

    /**
     * Log out. Throw an exception if isAuthenticationEnabled is false.
     *
     * @param optional string $returnUrl A url to return to after successful logout
     *
     * @return void
     */
    public function logout($returnUrl = null)
    {
        unset($_SESSION['masquerade.MicrosoftGraph']);
    }

    /**
     * Answer true if a user is currently authenticated.
     *
     * @return bool
     */
    public function isAuthenticated()
    {
        return isset($_SESSION['masquerade.MicrosoftGraph']);
    }

    /**
     * Answer the user id if a user is currently authenticated or throw an Exception
     * if isAuthenticated is false.
     *
     * @return string
     */
    public function getUserId()
    {
        if (!$this->isAuthenticated()) {
            throw new Exception('No user authenticated.');
        }

        return $_SESSION['masquerade.MicrosoftGraph']['id'];
    }

    /**
     * Answer a name for the user if a user is currently authenticated or throw an Exception
     * if isAuthenticated is false.
     *
     * @return string
     */
    public function getUserDisplayName()
    {
        if (!$this->isAuthenticated()) {
            throw new Exception('No user authenticated.');
        }

        return $_SESSION['masquerade.MicrosoftGraph']['name'];
    }

    /**
     * Answer an email address for the user if a user is currently authenticated or throw an Exception
     * if isAuthenticated is false.
     *
     * @return string
     */
    public function getUserEmail()
    {
        if (!$this->isAuthenticated()) {
            throw new Exception('No user authenticated.');
        }

        return $_SESSION['masquerade.MicrosoftGraph']['email'];
    }

    /**
     * Answer an array of groups for the user if a user is currently authenticated or throw an Exception
     * if isAuthenticated is false.
     *
     * @return array
     */
    public function getUserGroups()
    {
        if (!$this->isAuthenticated()) {
            throw new Exception('No user authenticated.');
        }

        return $_SESSION['masquerade.MicrosoftGraph']['memberof'];
    }

    /**
     * Change to a different user account. Clients are responsible for checking that
     * the current user is authorized before calling this method. Clients are also
     * responsible for logging.
     *
     * Throws an exception if unsupported.
     *
     * @param string $userId
     *
     * @return void
     */
    public function changeUser($userId)
    {
        $user = $this->fetchUserForLogin($userId);

        $properties = $this->extractUserInfo($user);
        $_SESSION['masquerade.MicrosoftGraph'] = [
            'id' => $properties['user_login'],
        ];

        $name = '';
        if (!empty($properties['first_name'])) {
            $name .= $properties['first_name'].' ';
        }
        if (!empty($properties['last_name'])) {
            $name .= $properties['last_name'];
        }
        if (strlen(trim($name))) {
            $_SESSION['masquerade.MicrosoftGraph']['name'] = $name;
        } else {
            $_SESSION['masquerade.MicrosoftGraph']['name'] = 'name unknown';
        }

        if (!empty($properties['user_email'])) {
            $_SESSION['masquerade.MicrosoftGraph']['email'] = $properties['user_email'];
        } else {
            $_SESSION['masquerade.MicrosoftGraph']['email'] = '';
        }

        $_SESSION['masquerade.MicrosoftGraph']['memberof'] = [];
    }

    /**
     * Answer our already-configured O365 API.
     *
     * @return Microsoft\Graph\Graph
     *                               The Graph object
     */
    protected function getGraph()
    {
        if (empty($this->graph)) {
            $this->graph = new GraphServiceClient(
                $this->getTokenRequestContext()
            );
        }

        return $this->graph;
    }

    /**
     * Get an O365 Access token context.
     */
    protected function getTokenRequestContext()
    {
        $config = Zend_Registry::getInstance()->config;
        if (empty($config->masquerade->MicrosoftGraph->tenantId)) {
            throw new Exception('No masquerade.MicrosoftGraph.tenantId configured.');
        }
        if (empty($config->masquerade->MicrosoftGraph->appId)) {
            throw new Exception('No masquerade.MicrosoftGraph.appId configured.');
        }
        if (empty($config->masquerade->MicrosoftGraph->appSecret)) {
            throw new Exception('No masquerade.MicrosoftGraph.appSecret configured.');
        }

        return new ClientCredentialContext(
            $config->masquerade->MicrosoftGraph->tenantId,
            $config->masquerade->MicrosoftGraph->appId,
            $config->masquerade->MicrosoftGraph->appSecret,
        );
    }

    /**
     * Answer an MS Graph User object matching a login string.
     *
     * @param string $login
     *
     * @return User
     */
    protected function fetchUserForLogin($login)
    {
        // First search by the primary unique ID.
        try {
            return $this->fetchUserByProperty($this->getPrimaryUniqueIdProperty(), $login);
        } catch (Exception $e) {
            // If we didn't find an account based on the primary id, try a secondary ID if configured.
            if (404 == $e->getCode() && !empty($this->getSecondaryUniqueIdProperty())) {
                return $this->fetchUserByProperty($this->getSecondaryUniqueIdProperty(), $login);
            } else {
                // If we don't support secondary ids or get another error, just throw it.
                throw $e;
            }
        }
    }

    /**
     * Answer an MS Graph User object matching a login string.
     *
     * @param string $property
     *                         The MSGraph property to match
     * @param string $value
     *                         The user-id value to match
     *
     * @return User
     */
    protected function fetchUserByProperty($property, $value)
    {
        $requestConfig = new UsersRequestBuilderGetRequestConfiguration(
            queryParameters: UsersRequestBuilderGetRequestConfiguration::createQueryParameters(
                filter: $property." eq '".urlencode($value)."'",
                select: $this->getUserGraphProperties(),
                orderby: ['displayName'],
                top: 10,
                count: true
            ),
            headers: ['ConsistencyLevel' => 'eventual']
        );
        $result = $this->getGraph()->users()->get($requestConfig)->wait();
        $users = [];
        foreach ($result->getValue() as $user) {
            $users[] = $user;
        }
        if (count($users) < 1) {
            throw new Exception('Could not get user. Expecting 1 entry, found '.count($users).' in AzureAD.', 404);
        } elseif (1 === count($users)) {
            return $users[0];
        } else {
            return $this->getPrimaryAccountFromUserList($users);
        }
    }

    protected function getUserGraphProperties()
    {
        return [
            'id',
            'displayName',
            'mail',
            'givenName',
            'surname',
            'userType',
            $this->getPrimaryUniqueIdProperty(),
            $this->getSecondaryUniqueIdProperty(),
        ];
    }

    /**
     * Filter a list of MS Graph User objects to find a single "primary" one.
     *
     * @param array $users
     *                     The MSGraph User list
     *
     * @return User
     *              A single user if one can be determined to be "primary"
     */
    protected function getPrimaryAccountFromUserList(array $users)
    {
        // Give priority to users with the type "Member" over "Guest" or other
        // account types.
        $memberUsers = [];
        foreach ($users as $user) {
            if ('member' == strtolower($user->getUserType())) {
                $memberUsers[] = $user;
            }
        }
        // If we only have a single user with type "Member", then return that user.
        if (1 === count($memberUsers)) {
            return $memberUsers[0];
        }

        // Not sure what to do if we have multiple "Member" accounts with the same
        // ID or multiple "Guest" accounts with the same ID.
        // Perhaps we could do some email filtering or other logic, but hopefully
        // this case won't come up.
        ob_start();
        foreach ($users as $user) {
            $properties = $user->getProperties();
            echo "\n\t<hr><dl>";
            echo "\n\t\t<dt>Primary ID property (".$this->getPrimaryUniqueIdProperty().'):</dt><dd>'.(empty($properties[$this->getPrimaryUniqueIdProperty()]) ? '' : $properties[$this->getPrimaryUniqueIdProperty()]).'</dd>';
            echo "\n\t\t<dt>Secondary ID property (".$this->getSecondaryUniqueIdProperty().'):</dt><dd>'.(empty($properties[$this->getSecondaryUniqueIdProperty()]) ? '' : $properties[$this->getSecondaryUniqueIdProperty()]).'</dd>';
            echo "\n\t\t<dt>User Type:</dt><dd>".$user->getUserType().'</dd>';
            echo "\n\t\t<dt>Mapped username in WordPress:</dt><dd>".$this->getLoginForGraphUser($user).'</dd>';
            echo "\n\t\t<dt>UserPrincipalName:</dt><dd>".$user->getUserPrincipalName().'</dd>';
            echo "\n\t\t<dt>Display Name:</dt><dd>".$user->getDisplayName().'</dd>';
            echo "\n\t\t<dt>Mail:</dt><dd>".$user->getMail().'</dd>';
            echo "\n\t</dl>";
        }
        throw new Exception('Could not get single user for ID. Expecting 1 entry, found '.count($users)." users in AzureAD that share an ID and User Type:\n".ob_get_clean());
    }

    /**
     * Answer the primary unique-id property key.
     *
     * @return string
     *                The property in MS Graph that holds the primary unique-id
     */
    protected function getPrimaryUniqueIdProperty()
    {
        static $primaryUserIdProperty;
        if (!isset($primaryUserIdProperty)) {
            $config = Zend_Registry::getInstance()->config;
            if (empty($config->masquerade->MicrosoftGraph->primaryUserIdProperty)) {
                throw new Exception('No masquerade.MicrosoftGraph.primaryUserIdProperty configured.');
            }
            $primaryUserIdProperty = $config->masquerade->MicrosoftGraph->primaryUserIdProperty;
        }

        return $primaryUserIdProperty;
    }

    /**
     * Answer the secondary unique-id property key.
     *
     * @return string
     *                The property in MS Graph that holds a secondary/fall-back unique-id
     */
    protected function getSecondaryUniqueIdProperty()
    {
        static $secondaryUserIdProperty;
        if (!isset($secondaryUserIdProperty)) {
            $config = Zend_Registry::getInstance()->config;
            if (empty($config->masquerade->MicrosoftGraph->secondaryUserIdProperty)) {
                throw new Exception('No masquerade.MicrosoftGraph.secondaryUserIdProperty configured.');
            }
            $secondaryUserIdProperty = $config->masquerade->MicrosoftGraph->secondaryUserIdProperty;
        }

        return $secondaryUserIdProperty;
    }

    /**
     * Answer the user info matching an MS Graph User object.
     *
     * @return array
     */
    protected function extractUserInfo(User $user)
    {
        $info = [];

        $info['user_login'] = $this->getLoginForGraphUser($user);
        $info['user_email'] = $user->getMail();

        preg_match('/^(.+)@(.+)$/', $info['user_email'], $matches);
        $emailUser = $matches[1];
        $emailDomain = $matches[2];

        $info['user_nicename'] = $emailUser;
        $info['nickname'] = $user->getGivenName();
        $info['first_name'] = $user->getGivenName();
        $info['last_name'] = $user->getSurname();
        $info['display_name'] = trim($user->getGivenName().' '.$user->getSurname());
        if (empty($info['display_name'])) {
            $info['display_name'] = trim($user->getDisplayName());
        }
        if (empty($info['display_name'])) {
            $info['display_name'] = $emailUser;
        }
        if (empty($info['nickname'])) {
            $info['nickname'] = $emailUser;
        }

        return $info;
    }

    /**
     * Answer the user login matching an MS Graph User object.
     *
     * @return string
     */
    protected function getLoginForGraphUser(User $user)
    {
        // Primary Unique ID.
        $id = $this->getUserProperty($user, $this->getPrimaryUniqueIdProperty());
        if (!empty($id)) {
            return $id;
        }
        // Secondary/Fallback unique ID.
        else {
            $id = $this->getUserProperty($user, $this->getSecondaryUniqueIdProperty());
            if (!empty($id)) {
                return $id;
            } else {
                throw new Exception('No id could be extracted for user '.$user->getUserPrincipalName());
            }
        }
    }

    /**
     * Answer a property from a User object.
     *
     * @param User   $user
     *                         The user object
     * @param string $property
     *                         The property name to fetch
     *
     * @return mixed
     *               The property or null
     */
    protected function getUserProperty(User $user, $property)
    {
        $getterMethod = 'get'.ucfirst($property);
        if (method_exists($user, $getterMethod)) {
            return $user->$getterMethod();
        } else {
            $additionalData = $user->getAdditionalData();
            if (!isset($additionalData[$property])) {
                throw new Exception("No '$property' could be found for user ".$user->getUserPrincipalName().' in '.print_r($addtionalData, true));
            }

            return $additionalData[$property];
        }
    }
}
