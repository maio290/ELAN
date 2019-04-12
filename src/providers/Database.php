<?PHP

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class Database
{
    private $link = null;

    function __construct($host, $username, $password, $database)
    {
        $this->link = mysqli_connect($host, $username, $password, $database);
        if ($this->link === false) {
            throw new Exception("Database not reachable!");
        }
    }

    function executeQuery($SQL)
    {
        return mysqli_query($this->link, $SQL);
    }

    function executeMultiQuery($SQL)
    {
        return mysqli_multi_query($this->link, $SQL);
    }

    function prepareStatement($SQL)
    {
        $statement = $this->link->stmt_init() or die("failed to initialize statement");
        $statement->prepare($SQL) or die("failed to prepare statement!");
        return $statement;
    }

    function getLastInsertedRow()
    {
        return $this->link->insert_id;
    }

    function escapeString($str)
    {
        return $this->link->real_escape_string($str);
    }

    function dumpError()
    {
        var_dump($this->link->error);
    }
}


