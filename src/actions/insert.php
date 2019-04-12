<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

if ($_POST) {
    session_start();
    if (!isset($_SESSION['user'])) {
        die();
    }

    $user = User::deserialize($_SESSION['user']);

    $keys = ["ID", "Inhalt", "Arbeitszeiten"];

    if (PostUtils::validatePostRequest($_POST, $keys)) {
        $editData = [
            "Inhalt" => $_POST['Inhalt'],
            "Arbeitszeiten" => $_POST['Arbeitszeiten'],
            "Freigabestatus" => Freigabestatus::BEARBEITET
        ];

        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForUser($user->username);
        $ausbildungsnachhweis = AusbildungsnachweisController::getAusbildungsnachweisByIDAndAVID($_POST['ID'], $ausbildungsverhaeltnis->id);

        if (Freigabestatus::checkChange($ausbildungsnachhweis->Freigabestatus, Freigabestatus::BEARBEITET)) {
            $ausbildungsnachhweis->edit($ausbildungsnachhweis, $editData);
            $result = $ausbildungsnachhweis->update();
            if ($result) {
                return true;
            } else {
                http_response_code(500);
            }
        } else {
            http_response_code(400);
        }


    } else {
        http_response_code(400);
        return;
    }
}
