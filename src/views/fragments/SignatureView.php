<?php


namespace ELAN;
require_once(__DIR__ . '/../../../vendor/autoload.php');


class SignatureView
{

    static function generate($ausbildungsnachweis)
    {
        $signatureLine = '<div class="centered>"<div class="azubiSignature">
        <span>Freigegeben am '.$ausbildungsnachweis->Freigabedatum.'</span>
        </div>
        <div class="ausbiliderSignature">
        <span>Signiert am '.$ausbildungsnachweis->Signaturdatum.' durch '.$ausbildungsnachweis->Signaturgeber.' </span>
        </div></div>
        ';

        return $signatureLine;
    }

}