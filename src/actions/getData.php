<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

if ($_POST) {

    session_start();
    if (!isset($_SESSION['user']) || !isset($_POST['action'])) {
        die();
    }


    switch ($_POST['action']) {
        case "getAusbildungsnachweis":
            $expectedFields = ["ID", "action"];
            if (PostUtils::validatePostRequest($_POST, $expectedFields)) {
                $user = User::deserialize($_SESSION['user']);
                $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForUser($user->username);
                $ausbildungsnachweis = AusbildungsnachweisController::getAusbildungsnachweisByIDAndAVID($_POST['ID'], $ausbildungsverhaeltnis->id);
                if ($ausbildungsnachweis !== false) {
                    echo \json_encode($ausbildungsnachweis);
                    return;
                }
                http_response_code(404);
                die();
            }
            break;
        case "getAusbildungsnachweisWithKey":
            $expectedFields = ["ID", "action", "key"];
            if (PostUtils::validatePostRequest($_POST, $expectedFields)) {
                $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForKey($_POST['key']);
                if ($ausbildungsverhaeltnis == false) {
                    http_response_code(400);
                    die();
                }
                $ausbildungsnachweis = AusbildungsnachweisController::getAusbildungsnachweisByIDAndAVID($_POST['ID'], $ausbildungsverhaeltnis->id);
                if ($ausbildungsnachweis !== false) {
                    $ausbildungsnachweis->Korrekturvermerk = null;
                    $json =  \json_encode($ausbildungsnachweis);
                    echo $json;
                    return;
                }
                http_response_code(404);
                die();
            }
            http_response_code(400);
            die();
        case "getAusbildungsnachweisAsAusbilder":
            $expectedFields = ["ID", "AVID", "action"];
            if (PostUtils::validatePostRequest($_POST, $expectedFields)) {
                $user = User::deserialize($_SESSION['user']);
                $permission = AusbildungsverhaeltnisController::checkAusbilderPermission($_POST['AVID'], $user->username);
                if ($permission) {
                    $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisByID($_POST['AVID']);
                    $ausbildungsnachweis = AusbildungsnachweisController::getAusbildungsnachweisByIDAndAVID($_POST['ID'], $ausbildungsverhaeltnis->id);
                    if ($ausbildungsnachweis !== false) {
                        echo \json_encode($ausbildungsnachweis);
                        return;
                    }
                    http_response_code(404);
                    die();
                }
                http_response_code(403);
                die();

            }
            break;
        default:
            http_response_code(400);
            die();
    }


}