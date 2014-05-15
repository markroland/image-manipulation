<?php

namespace markroland;

use markroland\ImageManipulation;

/*
  Assume PHPUnit is installed globally (/usr/local/bin/phpunit) so no include is necessary
  Otherwise, use following:
*/
// require_once "PHPUnit/Autoload.php";

// Include class to be tested
require_once dirname(dirname(__FILE__)).'/src/ImageManipulation.class.php';

class ImageManipulationTest extends \PHPUnit_Framework_TestCase
{

    protected $ImageManipulation;

    protected function setUp()
    {
        $this->ImageManipulation = new ImageManipulation;
    }

    protected function tearDown()
    {
        unset($this->ImageManipulation);
    }

    // test the talk method
    public function testHello()
    {
        $this->setUp();
        $expected = "Hello world!";
        $actual = $this->ImageManipulation->hello();
        $this->assertEquals($expected, $actual);
        $this->tearDown();
    }
}
