<?php


namespace ELAN;


class TimeUtils
{

    static function guessAusbildungsjahr($start,$targetDate)
    {
        $startTime = \strtotime($start);
        $targetDateTime = \strtotime($targetDate);
        $difference = $targetDateTime-$startTime;
        if($difference < 31536000)
        {
            return 1;
        }
        if($difference < 63072000)
        {
            return 2;
        }
        return 3;
    }

    static function getCurrentTimeForSQL()
    {
        return date("Y-m-d H:i:s");
    }

    static function epochToGermanDate($epoch)
    {
        return date("d.m.Y", $epoch);
    }

    static function addOneDayToEpoch($epoch)
    {
        return $epoch + 86400;
    }

    static function setDateToSpecificWeekday($epoch, $weekday)
    {
        $actualWeekday = date("w", $epoch);

        if ($actualWeekday === 0) {
            $actualWeekday = 7;
        }

        if ($weekday != $actualWeekday) {
            $difference = $actualWeekday - $weekday;
            $absoluteDifference = abs($difference);

            if ($difference < 0) {
                $epoch += $absoluteDifference * 86400;
            } else {
                $epoch -= $absoluteDifference * 86400;
            }


            return $epoch;
        }
        return $epoch;
    }

}