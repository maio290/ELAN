<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class ClassMapper
{

    static function mapMapToObject($row, $targetClass)
    {
        $reflectedClass = new \ReflectionClass($targetClass);
        $reflectedProperties = $reflectedClass->getProperties();
        $targetObject = new $targetClass;
        foreach ($reflectedProperties as $property) {
            if (isset($row[$property->name])) {
                $name = $property->name;
                $targetObject->$name = $row[$property->name];
            }
        }

        return $targetObject;
    }

}