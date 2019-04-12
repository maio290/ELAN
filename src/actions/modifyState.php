<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');


if ($_POST) {
    session_start();
    if (isset($_POST['action']) && isset($_SESSION)) {
        switch ($_POST['action']) {
            case "publish":
                $expectedFields = ['id', 'action'];
                if (PostUtils::validatePostRequest($_POST, $expectedFields)) {
                    $result = StateController::publish($_POST, User::deserialize($_SESSION['user']));
                    if ($result) {
                        return;
                    }
                } else {
                    http_response_code(400);
                    die();
                }
            case "unpublish":
                {
                    $expectedFields = ['id', 'action'];
                    if (PostUtils::validatePostRequest($_POST, $expectedFields)) {
                        $result = StateController::unpublish($_POST, User::deserialize($_SESSION['user']));
                        if ($result) {
                            return;
                        }
                    } else {
                        http_response_code(400);
                        die();
                    }
                }
            case "setFaulty":
                $expectedFields = ['id', 'action', 'AVID', 'Korrekturvermerk'];
                if (PostUtils::validatePostRequest($_POST, $expectedFields)) {
                    $result = StateController::setFaulty($_POST, User::deserialize($_SESSION['user']));
                    if ($result) {
                        return;
                    }
                } else {
                    http_response_code(400);
                    die();
                }
                break;
            case "sign":
                $expectedFields = ['id', 'action', 'AVID'];
                if (PostUtils::validatePostRequest($_POST, $expectedFields)) {
                    $result = StateController::sign($_POST, User::deserialize($_SESSION['user']));
                    if ($result) {
                        return;
                    }
                } else {
                    http_response_code(400);
                    die();
                }
                break;
            default:
                break;
        }
    }
}
