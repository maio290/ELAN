<?PHP
namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class LDAP_Provider
{

    public $users = array();
    public $blacklist = array("svn", "otrs_vpn", "Kan tine", "CI Server", "search daimon", "sonar", "Test Testus", "WebCITest", "AplusTest");
    private $LDAP_CONNECTION = null;
    private $LDAP_CONFIG = null;

    function __construct(Config_Provider $config)
    {
        $this->LDAP_CONFIG = $config->ldapConfig;
        $this->LDAP_CONNECTION = ldap_connect($this->LDAP_CONFIG->host, $this->LDAP_CONFIG->port) or die("Failed to connect to LDAP on " . $this->LDAP_CONFIG->host . " aborting..:");
        ldap_set_option($this->LDAP_CONNECTION, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_bind($this->LDAP_CONNECTION, $this->LDAP_CONFIG->username, $this->LDAP_CONFIG->password) or die("Cannot bind to LDAP" . ldap_error($this->LDAP_CONNECTION));
        $this->searchUsers();
    }

    function searchUsers()
    {
        $userFilter = "(sAMAccountType=805306368)";
        $userAttributes = array("cn", "displayName", "sAMAccountName");
        $userSearch = ldap_search($this->LDAP_CONNECTION, $this->LDAP_CONFIG->base_dn, $userFilter, $userAttributes);
        $this->users = ldap_get_entries($this->LDAP_CONNECTION, $userSearch);
        $this->users = array_slice($this->users, 1);
        sort($this->users);
    }

    function getDisplayNameForCN($username)
    {
        foreach ($this->users as $user) {
            if (strcasecmp($username, $user['samaccountname'][0]) === 0) {
                return $user['displayname'][0];
            }
        }
        return false;
    }

    function login($username, $password)
    {
        $LDAP_CONNECTION = ldap_connect($this->LDAP_CONFIG->host, $this->LDAP_CONFIG->port) or die("Failed to connect to LDAP on " . $this->LDAP_CONFIG->host . " aborting..:");
        ldap_set_option($LDAP_CONNECTION, LDAP_OPT_PROTOCOL_VERSION, 3);
        $bind = ldap_bind($LDAP_CONNECTION, $username . $this->LDAP_CONFIG->appendix, $password);
        var_dump($bind);
        return $bind;
    }

    function createHTMLOptionsFromUsers()
    {
        $selectOptions = "";
        foreach ($this->users as $user) {
            $selectOptions .= '<option value="' . $user['samaccountname'][0] . '">' . $user['displayname'][0] . '</option>';
        }
        return $selectOptions;
    }

}

?>
