<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class AdminView
{

    static function generate($userSelectOptions, $ausbildungsverhaeltnisse)
    {
        //yolo
        $adminView = '
        <div class="adminContainer">
            <button onclick="unhideByClass(\'createAusbildungsverhaeltnis\')">Neues Ausbildungsverhältnis erstellen</button><br>';
        $adminView .= CreateAusbildungsverhaeltnisView::generate($userSelectOptions);
        $adminView .= '<button onclick="unhideByClass(\'editAusbildungsverhaeltnis\')">Bestehendes Ausbildungsverhältnis bearbeiten</button><br>';
        $adminView .= EditAusbildungsverhaeltnisView::generate($ausbildungsverhaeltnisse);
        $adminView .= '<button>Ausbildungsnachweise anzeigen</button><br>';
        $adminView .= '</div>';

        return $adminView;
    }

}