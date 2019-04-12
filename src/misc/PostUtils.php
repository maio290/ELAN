<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class PostUtils
{

    static function validatePostRequest($POST, $expectedKeys)
    {
        $matches = [];
        foreach ($POST as $key => $value) {

            if (!in_array($key, $expectedKeys)) {
                return false;
            } else {
                $matches[$key] = true;
            }
        }

        return count($matches) === count($expectedKeys);

    }

}