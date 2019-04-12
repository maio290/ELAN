<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class MessageKeyResolver
{

    static function getErrorStringForErrno($errno)
    {
        switch ($errno) {
            case 1:
                return "Fehler bei der Authentifizierung mit dem LDAP Server, falscher Benutzername / Passwort?";
            case 2:
                return "Kein Anzeigename im LDAP gefunden";
            case 3:
                return "Keine Session vorhanden";
            case 4:
                return "Keine Adminrechte";
            default:
                return "Unerwarteter Fehler";
        }
    }

    static function getErrorStringForKey($key)
    {
        switch ($key) {
            case "create":
                return "Fehler beim Erstellen eines neuen Ausbildungsverhältnisses";
            default:
                return "Unerwarteter Fehler";
        }

    }

    static function getSuccessStringForKey($key)
    {
        switch ($key) {
            case "success":
                return "Aktion erfolgreich";
            case "create":
                return "Neues Ausbildungsverhältnis erfolgreich erstellt";
            default:
                return "Unerwarteter Erfolg";
        }
    }

}