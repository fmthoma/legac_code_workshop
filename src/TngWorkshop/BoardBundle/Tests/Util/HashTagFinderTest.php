<?php

namespace TngWorkshop\BoardBundle\Tests\Util;


use TngWorkshop\BoardBundle\Util\HashTagFinder;

/** @group unit */
class HashTagFinderTest extends \PHPUnit_Framework_TestCase
{
    public function testFindingHashTags()
    {
        $testTextsAndExpectedTags = array(
            "hello world" => array(),
            "hello #world" => array("world"),
            "#hello world" => array("hello"),
            "#hello #world" => array("hello", "world"),
            "#hello\nworld" => array("hello")
        );

        foreach ($testTextsAndExpectedTags as $inputText => $expectedTags) {
            $this->assertThat(
                HashTagFinder::findHashTagsIn($inputText),
                $this->equalTo($expectedTags)
            );
        }
    }
}