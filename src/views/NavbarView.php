<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class NavbarView
{

    static function generate($session)
    {
        $user = User::deserialize($session['user']);
        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForUser($user->username);
        $navbar = '<div class="navbar">
        <span class="username">' . $user->displayname . ' (' . $user->username . ')</span>        
        ';

        $navbar .= '<div><a href="dashboard.php">Dashboard</a></div>';
        if ($user->isAdmin) {
            $navbar .= '<div class="admin"><a href="admin.php">Adminbereich</a></div> ';
        } else {
            $navbar .= '<div class="admin">Auszubildender</div>';
        }

        if($ausbildungsverhaeltnis)
        {
            $navbar .= '<div><a href="datenblatt.php?key='.$ausbildungsverhaeltnis->key.'" target="_blank">Datenblatt</a></div>';
        }



        $navbar .= "<div class='logoutContainer'><img height='32px' onclick=\"navigate('src/actions/logout.php')\" class=\"logout svg\" src=\"img/baseline-power_settings_new-24px.svg\"\"></img></div></div>";

        return $navbar;

    }


}