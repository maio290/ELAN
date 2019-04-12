<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class Config_Provider
{

    /*
     * The contents of the file config/ELAN.json (decoded)
     */
    public $admins = [];

    /*
     * The external URL of the server
     */
    public $host;

    /*
     * Defines if the weekend is hidden in printView
     */
    public $hideWeekendInPrintView;

    /*
     * The admin array
     */
    public $ldapConfig;

    /*
     * The configuration of the LDAP server
     */
    public $databaseConfig;

    /*
     * The configuration of the database
     */
    private $configJSON;

    function __construct()
    {
        $this->configJSON = json_decode(file_get_contents(__DIR__ . "/../config/ELAN.json"));
        $this->host = $this->configJSON->host;
        $this->hideWeekendInPrintView = $this->configJSON->hideWeekendInPrintView;
        $this->admins = $this->configJSON->admins;
        $this->databaseConfig = $this->configJSON->database;
        $this->ldapConfig = new LDAPConfigModel($this->configJSON->LDAP->host, $this->configJSON->LDAP->port, $this->configJSON->LDAP->base_dn, $this->configJSON->LDAP->username, $this->configJSON->LDAP->password, $this->configJSON->LDAP->appendix);
    }

    public function createDatabaseConnection()
    {
        return new Database($this->databaseConfig->host, $this->databaseConfig->username, $this->databaseConfig->password, $this->databaseConfig->database);
    }

}
