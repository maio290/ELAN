<?php


namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class StateController extends BaseController
{
    static function publish($POST, $user)
    {
        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForUser($user->username);
        $ausbildungsnachweis = AusbildungsnachweisController::getAusbildungsnachweisByIDAndAVID($POST['id'], $ausbildungsverhaeltnis->id);

        if (Freigabestatus::checkChange($ausbildungsnachweis->Freigabestatus, Freigabestatus::FREIGEGEBEN)) {
            $ausbildungsnachweis->Freigabestatus = Freigabestatus::FREIGEGEBEN;
            $ausbildungsnachweis->Freigabedatum = TimeUtils::getCurrentTimeForSQL();
            $ausbildungsnachweis->update();
            return true;
        } else {
            return false;
        }

    }

    static function unpublish($POST, $user)
    {
        $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisForUser($user->username);
        $ausbildungsnachweis = AusbildungsnachweisController::getAusbildungsnachweisByIDAndAVID($POST['id'], $ausbildungsverhaeltnis->id);

        if (Freigabestatus::checkChange($ausbildungsnachweis->Freigabestatus, Freigabestatus::BEARBEITET)) {
            $ausbildungsnachweis->Freigabestatus = Freigabestatus::BEARBEITET;
            $ausbildungsnachweis->Freigabedatum = null;
            $ausbildungsnachweis->update();
            return true;
        } else {
            return false;
        }

    }

    static function sign($POST, $user)
    {
        if (AusbildungsverhaeltnisController::checkAusbilderPermission($POST['AVID'], $user->username)) {
            $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisByID($POST['AVID']);
            $ausbildungsnachweis = AusbildungsnachweisController::getAusbildungsnachweisByIDAndAVID($POST['id'], $POST['AVID']);

            if (Freigabestatus::checkChange($ausbildungsnachweis->Freigabestatus, Freigabestatus::SIGNIERT)) {
                $ausbildungsnachweis->Freigabestatus = Freigabestatus::SIGNIERT;
                $ausbildungsnachweis->Signaturdatum = TimeUtils::getCurrentTimeForSQL();
                $ausbildungsnachweis->Signaturgeber = self::getLDAP()->getDisplayNameForCN($user->username);
                $ausbildungsnachweis->update();
                return true;
            }

        }
        return false;
    }

    static function setFaulty($POST, $user)
    {
        if (AusbildungsverhaeltnisController::checkAusbilderPermission($POST['AVID'], $user->username)) {
            $ausbildungsverhaeltnis = AusbildungsverhaeltnisController::getAusbildungsverhaeltnisByID($POST['AVID']);
            $ausbildungsnachweis = AusbildungsnachweisController::getAusbildungsnachweisByIDAndAVID($POST['id'], $POST['AVID']);

            if (Freigabestatus::checkChange($ausbildungsnachweis->Freigabestatus, Freigabestatus::FEHLERHAFT)) {
                $ausbildungsnachweis->Freigabestatus = Freigabestatus::FEHLERHAFT;

                if (strlen($POST['Korrekturvermerk']) !== 6) {
                    $ausbildungsnachweis->Korrekturvermerk = $POST['Korrekturvermerk'];
                }
                $ausbildungsnachweis->update();
                return true;


            }

        }
        return false;
    }

}