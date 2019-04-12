<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');


if (isset($_POST)) {
    session_start();
    if (!isset($_SESSION['user'])) {
        die();
    }

    $user = User::deserialize($_SESSION['user']);

    if (!$user->isAdmin) {
        die();
    }

    $keys = ["ausbilder", "azubi", "start", "end", "beruf"];

    if (PostUtils::validatePostRequest($_POST, $keys)) {
        if ($_POST['ausbilder'] === $_POST['azubi'] || $_POST['start'] === $_POST['end']) {
            http_response_code(400);
            return;
        }

        $_POST['start'] = date("Y-m-d", TimeUtils::setDateToSpecificWeekday(strtotime($_POST['start']), Weekdays::MONDAY));
        $_POST['end'] = date("Y-m-d", TimeUtils::setDateToSpecificWeekday(strtotime($_POST['end']), Weekdays::SUNDAY));


        $ausbildungsverhaeltnis = ClassMapper::mapMapToObject($_POST, Ausbildungsverhaeltnis::class);
        $result = $ausbildungsverhaeltnis->persist();
        if (!$result) {
            header("Location: ../../admin.php?error=create");
            die();
        }
        AusbildungsnachweisController::generateEmptyAusbildungsnachweiseForNewAusbildungsverhaeltnis($ausbildungsverhaeltnis);
        header("Location: ../../admin.php?success=create");
        die();


    } else {
        http_response_code(400);
        return;
    }
}