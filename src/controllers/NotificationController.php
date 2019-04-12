<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class   NotificationController
{

    static function generate($GET)
    {
        if (isset($GET['success'])) {
            $message = MessageKeyResolver::getSuccessStringForKey($GET['success']);
            return NotificationView::generate("success", $message);
        }

        if (isset($GET['error'])) {
            $message = MessageKeyResolver::getErrorStringForKey($GET['error']);
            return NotificationView::generate("error", $message);
        }

        if (isset($GET['errno'])) {
            $message = MessageKeyResolver::getErrorStringForErrno($GET['errno']);
            return NotificationView::generate("error", $message);
        }

    }

}