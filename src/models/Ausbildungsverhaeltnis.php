<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class Ausbildungsverhaeltnis extends DatabaseModel
{

    public $id;
    public $ausbilder;
    public $azubi;
    public $start;
    public $end;
    public $beruf;
    public $key;

    function __construct($ausbilder = null, $azubi = null, $start = null, $end = null, $beruf = null, $id = null, $key = null)
    {
        $this->id = $id;
        $this->ausbilder = strtolower($ausbilder);
        $this->azubi = strtolower($azubi);
        $this->start = $start;
        $this->end = $end;
        $this->beruf = $beruf;
        $this->key = password_hash($ausbilder . $azubi . $beruf . \time() . \bin2hex(\openssl_random_pseudo_bytes(16)), PASSWORD_BCRYPT);
    }

    function persist()
    {
        $db = self::getDatabaseConnection();
        $statement = $db->prepareStatement("INSERT INTO `Ausbildungsverhaeltnis`(`ausbilder`, `azubi`, `start`, `end`,`beruf`, `key`) VALUES (?,?,?,?,?,?)");
        $statement->bind_param("ssssss", $this->ausbilder, $this->azubi, $this->start, $this->end, $this->beruf, $this->key);
        $result = $statement->execute();
        $id = $db->getLastInsertedRow();
        if ($result) {
            $this->id = $id;
            return true;
        }
        return false;


    }


}