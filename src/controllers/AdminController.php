<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class AdminController extends BaseController
{

    static function generate()
    {
        $userSelectOptions = self::getLDAP()->createHTMLOptionsFromUsers();
        $ausbildungsverhaeltnisse = AusbildungsverhaeltnisController::getCurrentAusbildungsverhaeltnisse();
        return AdminView::generate($userSelectOptions, $ausbildungsverhaeltnisse);
    }

}