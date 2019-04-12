<?php

namespace ELAN;
require_once(__DIR__ . '/../../vendor/autoload.php');

class HeaderView
{

    static function generate($title)
    {
        return '
        <!DOCTYPE html>
        <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="src/styles/base.css"> 
        <link href="src/styles/Roboto-Light.ttf" rel="stylesheet">
        <script type="text/javascript" src="src/scripts/router.js"></script>
        <script type="text/javascript" src="src/scripts/manipulator.js"></script>
        <script type="text/javascript" src="src/scripts/requester.js"></script>
        <title>' . $title . '</title>
        </head>
        <body>
        ';
    }

}