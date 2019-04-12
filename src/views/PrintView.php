<?php


namespace ELAN\views;


use ELAN\Config_Provider;
use ELAN\HeaderView;
use ELAN\LDAP_Provider;
use ELAN\TimeUtils;
use ELAN\Weekdays;

class PrintView
{

    static function generate($ausbildungsnachweis, $ausbildungsverhaeltnis)
    {
        $cfg = new Config_Provider();
        $ldap = new LDAP_Provider($cfg);
        $displayname = $ldap->getDisplayNameForCN($ausbildungsverhaeltnis->azubi);
        $ausbilungsjahr = TimeUtils::guessAusbildungsjahr($ausbildungsverhaeltnis->start,$ausbildungsnachweis->Start);
        $firstDayOfWeek = TimeUtils::epochToGermanDate(\strtotime($ausbildungsnachweis->Start." UTC"));
        $lastDayOfWeek = TimeUtils::epochToGermanDate(TimeUtils::setDateToSpecificWeekday(strtotime($ausbildungsnachweis->Start." UTC"),Weekdays::SUNDAY));
        $id = $ausbildungsnachweis->ID;

        $arbeitszeiten = explode(";",$ausbildungsnachweis->Arbeitszeiten);
        $content = explode(";",$ausbildungsnachweis->Inhalt);

        if(count($content) == 0)
        {
            for($i = 0; $i<7; $i++)
            {
                $content[$i] = "";
            }
        }

        if(count($arbeitszeiten) < 7)
        {

            for($i = 0; $i<7; $i++)
            {
                $arbeitszeiten[$i] = "00:00";
            }
        }

        $printView = HeaderView::generate("ELAN - Druckansicht");
        $printView .= '<div class="centeredPrint"><img src="img/company.png" width="200rem"/>
        <h2>Wöchentlicher Ausbildungsnachweis für: <b>'.$displayname.'</b></h2>
        <h2>Beruf: '.$ausbildungsverhaeltnis->beruf.'</h2>
        <h3>Ausbildungsnachweis: '.$id.' - Ausbildungsjahr: '.$ausbilungsjahr.'</h3>
        <h3>Woche vom '.$firstDayOfWeek.' bis '.$lastDayOfWeek.'</h3>';


        $start = strtotime($firstDayOfWeek." UTC");
        $printView .= '<table>';
        $printView .= '<tr><th>Datum</th><th>Tätigkeiten</th><th>Uhrzeit</th></tr>';
        if($cfg->hideWeekendInPrintView)
        {
            for($i = 0; $i<5; $i++)
            {
                if($i > 0)
                {
                    $start = TimeUtils::addOneDayToEpoch($start);
                }
                $printView .= '<tr>
                <td><b>'. Weekdays::WOCHENTAGE[$i] . ', ' . TimeUtils::epochToGermanDate($start).'</b></td>
                <td class="printTextareaWrapper"><textarea class="printTextarea" cols="50"  rows="30" readonly>'.$content[$i].'</textarea></td>
                <td>'.$arbeitszeiten[$i].'</td>
                </tr>
                <tr><td><hr></td><td><hr></td><td><hr></td></tr>';

            }
        }
        else
        {
            for($i = 0; $i<7; $i++)
            {
                if($i > 0)
                {
                    $start = TimeUtils::addOneDayToEpoch($start);
                }
                $printView .= '<div class="weekdayContainer"><span class="weekday">' . Weekdays::WOCHENTAGE[$i] . ', ' . TimeUtils::epochToGermanDate($start) . '</span><input type="time" class="arbeitszeit" id="arbeitszeit-' . $i . '" value="' . $arbeitszeiten[$i] . '" readonly></input><textarea class="nachweisEintrag" readonly id="' . ($i + 1) . '">' . $content[$i] . '</textarea>';
            }
        }

        return $printView;
    }

}