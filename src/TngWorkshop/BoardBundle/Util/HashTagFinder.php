<?php

namespace TngWorkshop\BoardBundle\Util;

class HashTagFinder
{
    /**
     * @param string $text
     * @return string[] Hashtags
     */
    public static function findHashTagsIn($text)
    {
        $m = array();
        preg_match_all('/#(\w*)/', $text, $m);
        return empty($m) ? array() : $m[1];
    }
}
