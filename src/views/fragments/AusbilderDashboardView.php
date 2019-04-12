<?php


namespace ELAN;
require_once(__DIR__ . '/../../../vendor/autoload.php');

class AusbilderDashboardView
{

    static function generate($ausbildungsverhaeltnisse)
    {
        $ausbilderView = '<div class="ausbildungsverhaeltnisWrapper"><span><h2>Auszubildende</h2></span>';
        $id = 0;
        foreach ($ausbildungsverhaeltnisse as $ausbildungsverhaeltnis) {

            //onclick="toggleAzubiData(\''.$ausbildungsverhaeltnis->azubi.'-'.$id.'\')
            $ausbildungsverhaeltnisView = '<div class="ausbildungsverhaeltnis" " ><img src="img/baseline-chevron_right-24px.svg" class="svg" id="chevron-' . $ausbildungsverhaeltnis->azubi . '-' . $id . '" "/><span class="azubiname">' . $ausbildungsverhaeltnis->azubi . '</span>';
            $ausbildungsverhaeltnisView .= '<div class="ausbildungsnachweise">';
            $ausbildungsnachweise = AusbildungsnachweisController::getAusbildungsnachweiseByAVID($ausbildungsverhaeltnis->id);

            $ausbildungsverhaeltnisView .= AusbildungsnachweisFormView::generate($ausbildungsnachweise, $ausbildungsverhaeltnis);

            $ausbildungsverhaeltnisView .= "</div>";
            $ausbilderView .= $ausbildungsverhaeltnisView;
            $ausbilderView .= "</div>";
            $id++;
        }

        return $ausbilderView;
    }
}