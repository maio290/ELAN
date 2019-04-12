<?php


namespace ELAN;
require_once(__DIR__ . '/../../../vendor/autoload.php');

class AusbildungsnachweisFormView
{

    static function generate($ausbildungsnachweise, $ausbildungsverhaeltnis)
    {
        if ($ausbildungsverhaeltnis->ausbilder !== User::deserialize($_SESSION['user'])->username) {
            $currentAusbildungsnachweis = AusbildungsnachweisController::getCurrentAusbildungsnachweis($_SESSION);
        }

        $AVID = $ausbildungsverhaeltnis->id;
        $ausbildungsnachweisFormView = '<form action="view.php" method="GET">
        <input type="hidden" name="AVID" value="' . $AVID . '"/>
        <select name="id" id="id" onchange  ="getAndNavigate(\'view.php\', \'id\', \'' . $AVID . '\')">
        ';

        foreach ($ausbildungsnachweise as $ausbildungsnachweis) {
            if (empty($currentAusbildungsnachweis)) {
                $currentAusbildungsnachweis = null;
            }
            $ausbildungsnachweisFormView .= self::generateOption($ausbildungsnachweis, $currentAusbildungsnachweis);
        }

        $ausbildungsnachweisFormView .= "</select>
        <br><input type='submit' value='Auswählen'></form>
";

        return $ausbildungsnachweisFormView;
    }

    static function generateOption($ausbildungsnachweis, $currentAusbildungsnachweis)
    {
        if ($currentAusbildungsnachweis !== false && !empty($currentAusbildungsnachweis)) {
            if ($currentAusbildungsnachweis->ID === $ausbildungsnachweis->ID) {
                return '<option value="' . $ausbildungsnachweis->ID . '" selected>' . $ausbildungsnachweis->ID . ' - ' . TimeUtils::epochToGermanDate(strtotime($ausbildungsnachweis->Start)) . '</option>';
            }
        }
        return '<option value="' . $ausbildungsnachweis->ID . '">' . $ausbildungsnachweis->ID . ' - ' . TimeUtils::epochToGermanDate(strtotime($ausbildungsnachweis->Start)) . '</option>';
    }

}