<?php
namespace ELAN;
use ELAN\views\PrintView;

require_once(__DIR__ . '/vendor/autoload.php');

if($_GET)
{
    if(isset($_GET['key']) && isset($_GET['id']))
    {
        $ausbildungsnachweis = AusbildungsnachweisController::getCurrentAusbildungsnachweisForKeyAndID($_GET['key'],$_GET['id']);
        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForKey($_GET['key']);
        echo PrintView::generate($ausbildungsnachweis, $ausbildungsverhaeltnis);
    }
}
