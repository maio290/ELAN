<?php


namespace ELAN;


class AusbildungsnachweisView
{


    static function generateAusbilderQueueView($ausbildungsnachweis, $ausbildungsverhaeltnis)
    {
        $start = strtotime($ausbildungsnachweis->Start . " UTC");
        $end = TimeUtils::setDateToSpecificWeekday($start, Weekdays::SUNDAY);
        $nachweisView = '
        <span id="start" hidden>' . $start . '</span>
        <br><div class="name"><h2>Ausbildungsnachweis für ' . $ausbildungsverhaeltnis->azubi . '</h2></div>
        <div class="nachweisNavigationBar">
        <span class="nachweisID" id="nachweisID">' . $ausbildungsnachweis->ID . '</span>
        <span id="AVID" hidden>' . $ausbildungsverhaeltnis->id . '</span>
        <span hidden id="isQueueView"></span>
        </div>
        <div class="duration">' . TimeUtils::epochToGermanDate($start) . ' bis ' . TimeUtils::epochToGermanDate($end) . '</div>
        <div class="informationBar"  id="informationBar" hidden><br><span id="informationBarContent"></span><br><br></div>
        <div class="nachweisWrapper">
        ';


        $content = [];
        $korrekturvermerke = [];
        $arbeitszeiten = [];

        if ($ausbildungsnachweis->Korrekturvermerk !== null) {
            $korrekturvermerke = explode(";", $ausbildungsnachweis->Korrekturvermerk);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $korrekturvermerke[] = "";
            }
        }

        if ($ausbildungsnachweis->Arbeitszeiten !== null) {
            $arbeitszeiten = explode(";", $ausbildungsnachweis->Arbeitszeiten);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $arbeitszeiten[$i] = "00:00";
            }
        }

        if ($ausbildungsnachweis->Inhalt !== null) {
            $content = explode(";", $ausbildungsnachweis->Inhalt);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $content[] = "";
            }
        }

        for ($i = 0; $i < count($content); $i++) {
            if ($i > 0) {
                $start = TimeUtils::addOneDayToEpoch($start);
            }

            $nachweisView .= '<div class="weekdayContainer"><span class="weekday">' . Weekdays::WOCHENTAGE[$i] . ', ' . TimeUtils::epochToGermanDate($start) . '</span><input type="time" class="arbeitszeit" id="arbeitszeit-' . $i . '" value="' . $arbeitszeiten[$i] . '"></input><textarea class="nachweisEintrag" readonly id="' . ($i + 1) . '">' . $content[$i] . '</textarea><textarea class="nachweisKorrekturvermerk red"  id="korrekturvermerk-' . ($i + 1) . '">' . $korrekturvermerke[$i] . '</textarea></div>';

        }

        switch ($ausbildungsnachweis->Freigabestatus) {
            case Freigabestatus::FREIGEGEBEN:
                $nachweisView .= '<div id="buttonWrapper"><button class="green" onclick="sign()">Signieren</button><div id="buttonWrapper"><button class="red" onclick="faulty()">Zur Korrektur geben</button></div>';
                break;
            case Freigabestatus::FEHLERHAFT:
                $nachweisView .= '<div id="buttonWrapper"><span class="red">Korrektur ausstehend</span></div>';
                break;
            case Freigabestatus::SIGNIERT:
                $nachweisView .= '<div id="buttonWrapper">Signiert am ' . TimeUtils::epochToGermanDate(strtotime($ausbildungsnachweis->Signaturdatum)) . ' durch ' . $ausbildungsnachweis->Signaturgeber . '</div>';
                break;
            default:
                $nachweisView .= '<div id="buttonWrapper"></div>';
                break;
        }

        return $nachweisView . '</div></div>';
    }

    static function generateAzubiQueueView($ausbildungsnachweis, $ausbildungsverhaeltnis)
    {
        $start = strtotime($ausbildungsnachweis->Start . " UTC");
        $end = TimeUtils::setDateToSpecificWeekday($start, Weekdays::SUNDAY);
        $nachweisView = '
        <span id="start" hidden>' . $start . '</span>
        <div class="nachweisNavigationBar">
        <span class="nachweisID" id="nachweisID">' . $ausbildungsnachweis->ID . '</span>
        <span id="AVID" hidden>' . $ausbildungsverhaeltnis->id . '</span>
        <span hidden id="isQueueView"></span>
        </div>
        <div class="duration">' . TimeUtils::epochToGermanDate($start) . ' bis ' . TimeUtils::epochToGermanDate($end) . '</div>
        <div class="informationBar"  id="informationBar" hidden><br><span id="informationBarContent"></span><br><br></div>
        <div class="nachweisWrapper">
        ';


        $content = [];
        $korrekturvermerke = [];
        $arbeitszeiten = [];

        if ($ausbildungsnachweis->Korrekturvermerk !== null) {
            $korrekturvermerke = explode(";", $ausbildungsnachweis->Korrekturvermerk);
        }

        if ($ausbildungsnachweis->Inhalt !== null) {
            $content = explode(";", $ausbildungsnachweis->Inhalt);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $content[] = "";
            }
        }

        if ($ausbildungsnachweis->Arbeitszeiten !== null) {
            $arbeitszeiten = explode(";", $ausbildungsnachweis->Arbeitszeiten);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $arbeitszeiten[$i] = "00:00";
            }
        }

        for ($i = 0; $i < count($content); $i++) {
            if ($i > 0) {
                $start = TimeUtils::addOneDayToEpoch($start);
            }

            if (count($korrekturvermerke) > 0) {
                $nachweisView .= '<div class="weekdayContainer"><span class="weekday">' . Weekdays::WOCHENTAGE[$i] . ', ' . TimeUtils::epochToGermanDate($start) . '</span><input type="time" class="arbeitszeit" id="arbeitszeit-' . $i . '" value="' . $arbeitszeiten[$i] . '"></input><textarea class="nachweisEintrag" onfocusout="acquireAndStoreAusbildungsnachweis(\'true\')" id="' . ($i + 1) . '">' . $content[$i] . '</textarea><textarea  class="nachweisKorrekturvermerk red"  id="korrekturvermerk-' . ($i + 1) . '" readonly>' . $korrekturvermerke[$i] . '</textarea></div>';
            } else {
                $nachweisView .= '<div class="weekdayContainer"><span class="weekday">' . Weekdays::WOCHENTAGE[$i] . ', ' . TimeUtils::epochToGermanDate($start) . '</span><input type="time" class="arbeitszeit" id="arbeitszeit-' . $i . '" value="' . $arbeitszeiten[$i] . '"></input><textarea onfocusout="acquireAndStoreAusbildungsnachweis(\'true\')" class="nachweisEintrag" id="' . ($i + 1) . '">' . $content[$i] . '</textarea></div>';
            }


        }

        switch ($ausbildungsnachweis->Freigabestatus) {
            case Freigabestatus::FEHLERHAFT:
                $nachweisView .= '<div id="buttonWrapper"><button onclick="publish()">Korrektur freigeben</button></div>';
                break;
            case Freigabestatus::BEARBEITET:
                $nachweisView .= '<div id="buttonWrapper"><button onclick="publish()">Freigeben</button></div>';
                break;
            default:
                $nachweisView .= '<div id="buttonWrapper"></div>';
                break;
        }

        return $nachweisView . '</div></div>';
    }

    static function generateAusbilderView($ausbildungsnachweis, $ausbildungsverhaeltnis)
    {
        $start = strtotime($ausbildungsnachweis->Start . " UTC");
        $end = TimeUtils::setDateToSpecificWeekday($start, Weekdays::SUNDAY);
        $nachweisView = '
        <span id="start" hidden>' . $start . '</span>
        <br><div class="name"><h2>Ausbildungsnachweis für ' . $ausbildungsverhaeltnis->azubi . '</h2></div>
        <div class="nachweisNavigationBar">
        <button class="left" id="left" onclick="loadAusbildungsnachweisAsAusbilder(-1)"><-</button>
        <span class="nachweisID" id="nachweisID">' . $ausbildungsnachweis->ID . '</span>
        <span id="AVID" hidden>' . $ausbildungsverhaeltnis->id . '</span>
        <button class="right" id="right" onclick="loadAusbildungsnachweisAsAusbilder(1)">-></button>
        </div>
        <div class="duration">' . TimeUtils::epochToGermanDate($start) . ' bis ' . TimeUtils::epochToGermanDate($end) . '</div>
        <div class="informationBar"  id="informationBar" hidden><br><span id="informationBarContent"></span><br><br></div>
        <div class="nachweisWrapper">
        ';


        $content = [];
        $korrekturvermerke = [];
        $arbeitszeiten = [];
        $hasKorrekturvermerk = false;
        if ($ausbildungsnachweis->Korrekturvermerk !== null) {
            $hasKorrekturvermerk = true;
            $korrekturvermerke = explode(";", $ausbildungsnachweis->Korrekturvermerk);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $korrekturvermerke[] = "";
            }
        }

        if ($ausbildungsnachweis->Arbeitszeiten !== null) {
            $arbeitszeiten = explode(";", $ausbildungsnachweis->Arbeitszeiten);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $arbeitszeiten[$i] = "00:00";
            }
        }

        if ($ausbildungsnachweis->Inhalt !== null) {
            $content = explode(";", $ausbildungsnachweis->Inhalt);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $content[] = "";
            }
        }

        for ($i = 0; $i < count($content); $i++) {
            if ($i > 0) {
                $start = TimeUtils::addOneDayToEpoch($start);
            }

            $nachweisView .= '<div class="weekdayContainer"><span class="weekday">' . Weekdays::WOCHENTAGE[$i] . ', ' . TimeUtils::epochToGermanDate($start) . '</span><input type="time" class="arbeitszeit" id="arbeitszeit-' . $i . '" value="' . $arbeitszeiten[$i] . '" readonly></input><textarea class="nachweisEintrag" readonly id="' . ($i + 1) . '">' . $content[$i] . '</textarea>';


            if ($hasKorrekturvermerk) {
                $nachweisView .= '<textarea class="nachweisKorrekturvermerk red" onfocusout="acquireAndStoreAusbildungsnachweis(\'true\')"  id="korrekturvermerk-' . ($i + 1) . '">' . $korrekturvermerke[$i] . '</textarea></div>';
            } else {
                $nachweisView .= '<textarea class="nachweisKorrekturvermerk red"  id="korrekturvermerk-' . ($i + 1) . '" hidden>' . $korrekturvermerke[$i] . '</textarea></div>';
            }


        }

        switch ($ausbildungsnachweis->Freigabestatus) {
            case Freigabestatus::FREIGEGEBEN:
                $nachweisView .= '<div id="buttonWrapper"><button class="green" onclick="sign()">Signieren</button><div id="buttonWrapper"><button class="red" onclick="faulty()">Zur Korrektur geben</button></div>';
                break;
            case Freigabestatus::FEHLERHAFT:
                $nachweisView .= '<div id="buttonWrapper"><span class="red">Korrektur ausstehend</span></div>';
                break;
            case Freigabestatus::SIGNIERT:
                $nachweisView .= '<div id="buttonWrapper">Signiert am ' . TimeUtils::epochToGermanDate(strtotime($ausbildungsnachweis->Signaturdatum)) . ' durch ' . $ausbildungsnachweis->Signaturgeber . '</div>';
                break;
            default:
                $nachweisView .= '<div id="buttonWrapper"></div>';
                break;
        }

        return $nachweisView . '</div>';
    }

    static function generateExternalView($ausbildungsnachweis, $displayname)
    {
        $start = strtotime($ausbildungsnachweis->Start . " UTC");
        $end = TimeUtils::setDateToSpecificWeekday($start, Weekdays::SUNDAY);
        $nachweisView = '
        <span id="start" hidden>' . $start . '</span>
        <div class="name"><h2>Ausbildungsnachweis für: ' . $displayname . '</h2></div>
        <div class="nachweisNavigationBar">
        <button class="left" id="left" onclick="loadAusbildungsnachweisWithKey(-1,\'' . $_GET['key'] . '\')"><-</button>
        <span class="nachweisID" id="nachweisID">' . $ausbildungsnachweis->ID . '</span>
        <button class="right" id="right" onclick="loadAusbildungsnachweisWithKey(1,\'' . $_GET['key'] . '\')">-></button>
        </div>
        <div class="duration">' . TimeUtils::epochToGermanDate($start) . ' bis ' . TimeUtils::epochToGermanDate($end) . '</div>';
        $nachweisView .= '<div id="buttonWrapper"></div>
        <div class="informationBar"  id="informationBar" hidden><br><span id="informationBarContent"></span><br><br></div>
        <div class="nachweisWrapper">
        ';


        $content = [];
        $arbeitszeiten = [];
        if ($ausbildungsnachweis->Inhalt !== null) {
            $content = explode(";", $ausbildungsnachweis->Inhalt);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $content[] = "";
            }
        }

        if ($ausbildungsnachweis->Arbeitszeiten !== null) {
            $arbeitszeiten = explode(";", $ausbildungsnachweis->Arbeitszeiten);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $arbeitszeiten[$i] = "00:00";
            }
        }

        for ($i = 0; $i < count($content); $i++) {
            if ($i > 0) {
                $start = TimeUtils::addOneDayToEpoch($start);
            }

            $nachweisView .= '<div class="weekdayContainer"><span class="weekday">' . Weekdays::WOCHENTAGE[$i] . ', ' . TimeUtils::epochToGermanDate($start) . '</span><input type="time" class="arbeitszeit" id="arbeitszeit-' . $i . '" value="' . $arbeitszeiten[$i] . '" readonly></input><textarea class="nachweisEintrag" readonly id="' . ($i + 1) . '">' . $content[$i] . '</textarea></div>';

        }


        return $nachweisView . '</div></div>';

    }

    static function generate($ausbildungsnachweis)
    {
        $start = strtotime($ausbildungsnachweis->Start . " UTC");
        $end = TimeUtils::setDateToSpecificWeekday($start, Weekdays::SUNDAY);
        $nachweisView = '
        <span id="start" hidden>' . $start . '</span>
        <div class="nachweisNavigationBar">
        <button class="left" id="left" onclick="loadAusbildungsnachweis(-1)"><-</button>
        <span class="nachweisID" id="nachweisID">' . $ausbildungsnachweis->ID . '</span>
        <button class="right" id="right" onclick="loadAusbildungsnachweis(1)">-></button>
        </div>
        <div class="duration">' . TimeUtils::epochToGermanDate($start) . ' bis ' . TimeUtils::epochToGermanDate($end) . '</div>
        <div class="informationBar"  id="informationBar" hidden><br><span id="informationBarContent"></span><br><br></div>
        <div class="nachweisWrapper">
        ';


        $content = [];
        $korrekturvermerke = [];
        $arbeitszeiten = [];

        if ($ausbildungsnachweis->Korrekturvermerk !== null) {
            $korrekturvermerke = explode(";", $ausbildungsnachweis->Korrekturvermerk);
        }

        if ($ausbildungsnachweis->Inhalt !== null) {
            $content = explode(";", $ausbildungsnachweis->Inhalt);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $content[] = "";
            }
        }

        if ($ausbildungsnachweis->Arbeitszeiten !== null) {
            $arbeitszeiten = explode(";", $ausbildungsnachweis->Arbeitszeiten);
        } else {
            for ($i = 0; $i < 7; $i++) {
                $arbeitszeiten[$i] = "";
            }
        }

        for ($i = 0; $i < count($content); $i++) {
            if ($i > 0) {
                $start = TimeUtils::addOneDayToEpoch($start);
            }

            $nachweisView .= '<div class="weekdayContainer"><span class="weekday">' . Weekdays::WOCHENTAGE[$i] . ', ' . TimeUtils::epochToGermanDate($start) . '</span><input type="time" class="arbeitszeit" onfocusout="acquireAndStoreAusbildungsnachweis(\'true\')" id="arbeitszeit-' . $i . '" value="' . $arbeitszeiten[$i] . '"></input><textarea class="nachweisEintrag" onfocusout="acquireAndStoreAusbildungsnachweis(\'true\')" id="' . ($i + 1) . '">' . $content[$i] . '</textarea>';

            if (count($korrekturvermerke) > 0) {
                $nachweisView .= '<textarea class="nachweisKorrekturvermerk red" id="korrekturvermerk-' . ($i + 1) . '" readonly>' . $korrekturvermerke[$i] . '</textarea>';
            } else {
                $nachweisView .= '<textarea class="nachweisKorrekturvermerk red" id="korrekturvermerk-' . ($i + 1) . '" style="display:none" readonly></textarea>';
            }

            $nachweisView .= '</div>';

        }

        switch ($ausbildungsnachweis->Freigabestatus) {
            case Freigabestatus::NEU:
                $nachweisView .= '<div id="buttonWrapper"><button class="speichern" onclick="acquireAndStoreAusbildungsnachweis(\'true\')">Speichern</button></div>';
                break;
            case Freigabestatus::FREIGEGEBEN:
                $nachweisView .= '<div id="buttonWrapper"><button class="freigeben" onclick="unpublish()">Zurückziehen</button></div>';
                break;
            case Freigabestatus::FEHLERHAFT:
            case Freigabestatus::BEARBEITET:
                $nachweisView .= '<div id="buttonWrapper"><button class="speichern" onclick="acquireAndStoreAusbildungsnachweis(\'true\')">Speichern</button><button class="freigeben" onclick="publish()">Freigeben</button></div>';
                break;
            case Freigabestatus::SIGNIERT:
                $nachweisView .= '<div id="buttonWrapper">Signiert am ' . TimeUtils::epochToGermanDate(strtotime($ausbildungsnachweis->Signaturdatum)) . ' durch ' . $ausbildungsnachweis->Signaturgeber . '</div>';
                break;
        }

        return $nachweisView . '</div></div>';

    }

}