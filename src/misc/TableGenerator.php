<?php


namespace ELAN;


class TableGenerator
{

    static function generateTableForObjects($objects, $class = "", $hidden = false)
    {
        if (count($objects) === 0) {
            return "<table></table>";
        }

        if ($hidden) {
            $table = "<table class='$class' hidden><tr>";
        } else {
            $table = "<table class='$class'><tr>";
        }


        $objects[0] = (array)$objects[0];
        $keys = array_keys($objects[0]);

        foreach ($keys as $key) {
            $table .= "<th>" . ucfirst($key) . "</th>";
        }

        $table .= "</tr>";
        foreach ($objects as $object) {
            $table .= "<tr>";

            foreach ($object as $value) {
                $table .= "<td>$value</td>";
            }

            $table .= "</tr>";
        }

        $table .= "</table>";
        return $table;


    }

}