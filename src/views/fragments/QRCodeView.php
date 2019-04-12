<?php


namespace ELAN;
require_once(__DIR__ . '/../../../vendor/autoload.php');


class QRCodeView
{

    static function generate($key)
    {
        $cfg = new Config_Provider();
        $url = $cfg->host."/view.php?key=".$key;
        \QRcode::png($url, false, QR_ECLEVEL_H, 5, 1);
    }

}