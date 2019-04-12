<?php

use ELAN\AusbildungsnachweisController;
use ELAN\AusbildungsnachweisView;
use ELAN\AusbildungsverhaeltnisController;
use ELAN\Freigabestatus;
use ELAN\NavbarController;
use ELAN\SignatureView;
use ELAN\User;
use ELAN\views\fragments\OverflowHideView;

require_once(__DIR__ . '/vendor/autoload.php');
session_start();
if (!isset($_SESSION['user']) && !isset($_GET['key'])) {
    header("Location: index.php?errno=3");
}

echo ELAN\HeaderView::generate("ELAN - Ausbildungsnachweis");


if (isset($_GET['queue'])) {
    switch ($_GET['queue']) {
        case "sign":
            echo AusbildungsnachweisController::selectNextEntryToSignForAusbilder(User::deserialize($_SESSION['user'])) . OverflowHideView::generate();
            return;
        case "written":
            echo AusbildungsnachweisController::getNextAusbildungsnachweisByStateForAzubi(User::deserialize($_SESSION['user']), Freigabestatus::BEARBEITET) . OverflowHideView::generate();
            return;
        case "faulty":
            echo AusbildungsnachweisController::getNextAusbildungsnachweisByStateForAzubi(User::deserialize($_SESSION['user']), Freigabestatus::FEHLERHAFT) . OverflowHideView::generate();
            return;
        case "new":
            echo AusbildungsnachweisController::getNextAusbildungsnachweisByStateForAzubi(User::deserialize($_SESSION['user']), Freigabestatus::NEU) . OverflowHideView::generate();
        default:
            return;
    }
}

if (isset($_GET['key'])) {
    // display stuff
    $ausbildungsnachweis = AusbildungsnachweisController::getCurrentAusbildungsnachweisForKey($_GET['key']);
    $ausibldungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForKey($_GET['key']);
    $ldap = AusbildungsverhaeltnisController::getLDAP();
    $displayname = $ldap->getDisplayNameForCN($ausibldungsverhaeltnis->azubi);
    if ($ausbildungsnachweis !== false) {
        if($ausbildungsnachweis->Signaturdatum !== null)
        {
            echo AusbildungsnachweisView::generateExternalView($ausbildungsnachweis, $displayname) . SignatureView::generate($ausbildungsnachweis).OverflowHideView::generate();
        }
        else
        {
            echo AusbildungsnachweisView::generateExternalView($ausbildungsnachweis, $displayname) .OverflowHideView::generate();
        }

    } else {
        header("Location: index.php");
    }
} else {
    echo NavbarController::generate();

    if (isset($_GET['id']) && isset($_GET['AVID'])) {
        if (AusbildungsverhaeltnisController::checkPermission($_GET['AVID'], User::deserialize($_SESSION['user'])->username)) {
            echo AusbildungsnachweisController::getViewForIDAndAVID($_GET['id'], $_GET['AVID']) . OverflowHideView::generate();
        } elseif (AusbildungsverhaeltnisController::checkAusbilderPermission($_GET['AVID'], User::deserialize($_SESSION['user'])->username)) {
            echo AusbildungsnachweisController::getAusbilderViewForIDAndAVID($_GET['id'], $_GET['AVID']) . OverflowHideView::generate();
        }
    } else {
        echo AusbildungsnachweisController::getViewForSession($_SESSION) . OverflowHideView::generate();
    }


}