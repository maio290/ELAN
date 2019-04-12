<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class NavbarController extends BaseController
{

    static function generate()
    {
        return NavbarView::generate($_SESSION);
    }

}