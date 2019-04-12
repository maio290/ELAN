<?php


namespace ELAN\controllers;


class EasterEggController
{

    static function getSomeMusic()
    {
        $music = ['1Bvej14BrFU', 'HxqaE6voLD8', 'T-SCPlYBY9U', 'Lv9pP_QeVFM', '7s8bMyfjx90', 'FTskgVFZUuQ'];
        $selected = array_rand($music);
        return '<iframe width="560" height="315"src="https://www.youtube.com/embed/' . $music[$selected] . '" frameborder="0" autoplay allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

    }

}