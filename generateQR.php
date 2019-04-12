<?php
namespace ELAN;
require_once(__DIR__ . '/vendor/autoload.php');

if($_GET)
{

    if(isset($_GET['key']))
    {
        QRCodeView::generate($_GET['key']);
    }

}
