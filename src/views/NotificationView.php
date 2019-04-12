<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class NotificationView
{

    static function generate($class, $message)
    {
        return '<div class="' . $class . '">' . $message . '</div>';
    }

}