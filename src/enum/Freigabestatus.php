<?php


namespace ELAN;


abstract class Freigabestatus
{
    const NEU = -1;
    const BEARBEITET = 0;
    const FREIGEGEBEN = 1;
    const FEHLERHAFT = 2;
    const SIGNIERT = 3;
    const STATES = [self::NEU, self::BEARBEITET, self::FREIGEGEBEN, self::FEHLERHAFT, self::SIGNIERT];

    static function checkChange($currentState, $newState)
    {
        if ($currentState == Freigabestatus::SIGNIERT) {
            return false;
        }
        return true;
    }

}