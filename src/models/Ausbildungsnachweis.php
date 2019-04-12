<?php


namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');


class Ausbildungsnachweis extends DatabaseModel
{

    public $ID;
    public $AVID;
    public $Start;
    public $Arbeitszeiten;
    public $Inhalt;
    public $Korrekturvermerk;
    public $Freigabestatus;
    public $Freigabedatum;
    public $Signaturdatum;
    public $Signaturgeber;

    function __construct($ID = null, $AVID = null, $Start = null, $Arbeitszeiten = null, $Inhalt = null, $Korrekturvermerk = null, $Freigabestatus = -1, $Freigabedatum = null, $Signaturdatum = null, $Signaturgeber = null)
    {
        $this->ID = $ID;
        $this->AVID = $AVID;
        $this->Start = $Start;
        $this->Arbeitszeiten = $Arbeitszeiten;
        $this->Inhalt = $Inhalt;
        $this->Korrekturvermerk = $Korrekturvermerk;
        $this->Freigabestatus = $Freigabestatus;
        $this->Freigabedatum = $Freigabedatum;
        $this->Signaturdatum = $Signaturdatum;
        $this->Signaturgeber = $Signaturgeber;
    }

    function persist()
    {
        $db = self::getDatabaseConnection();
        $statement = $db->prepareStatement("INSERT INTO `Ausbildungsnachweis`(`ID`, `AVID`, `Start`, `Arbeitszeiten`, `Inhalt`, `Korrekturvermerk`, `Freigabestatus`, `Freigabedatum`, `Signaturdatum`, `Signaturgeber`) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $statement->bind_param("iissssisss", $this->ID, $this->AVID, $this->Start, $this->Arbeitszeiten, $this->Inhalt, $this->Korrekturvermerk, $this->Freigabestatus, $this->Freigabedatum, $this->Signaturdatum, $this->Signaturgeber);
        $result = $statement->execute();
        return $result;
    }

    function update()
    {
        $db = self::getDatabaseConnection();
        $statement = $db->prepareStatement("UPDATE `Ausbildungsnachweis` SET `Start`= ?, `Arbeitszeiten`= ?, `Inhalt`= ?,  `Korrekturvermerk`= ?,`Freigabestatus`= ?,`Freigabedatum`= ?,`Signaturdatum`= ?, `Signaturgeber`= ? WHERE `ID`= ? AND `AVID` =  ?");
        $statement->bind_param("ssssisssii", $this->Start, $this->Arbeitszeiten, $this->Inhalt, $this->Korrekturvermerk, $this->Freigabestatus, $this->Freigabedatum, $this->Signaturdatum, $this->Signaturgeber, $this->ID, $this->AVID);
        $result = $statement->execute();
        $db->dumpError();
        return $result;
    }

    function edit(&$oldObject, $newData)
    {
        $keys = array_keys($newData);

        foreach ($keys as $key) {
            $oldObject->$key = $newData[$key];
        }
    }

}