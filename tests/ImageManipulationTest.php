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

    // Test Resizing a gif
    public function testResizeGif()
    {
        $this->setUp();

        // Set input
        $source_filepath = dirname(dirname(__FILE__)).'/data/gif-test.gif';
        $destination_filepath = dirname(dirname(__FILE__)).'/data/gif-test-output.gif';
        if( file_exists($destination_filepath) )
          unlink($destination_filepath);

        $actual = $this->ImageManipulation->resize($source_filepath, $destination_filepath, 192, 192);
        $this->assertFileExists($destination_filepath);

        $this->tearDown();
    }

    // Test Resizing a png
    public function testResizePng()
    {
        $this->setUp();

        // Set input
        $source_filepath = dirname(dirname(__FILE__)).'/data/png-8-test.png';
        $destination_filepath = dirname(dirname(__FILE__)).'/data/png-8-test-output.png';
        if( file_exists($destination_filepath) )
          unlink($destination_filepath);

        $actual = $this->ImageManipulation->resize($source_filepath, $destination_filepath, 192, 192);
        $this->assertFileExists($destination_filepath);

        $this->tearDown();
    }

    // Test Resizing a jpg
    public function testResizeJpeg()
    {
        $this->setUp();

        // Set input
        $source_filepath = dirname(dirname(__FILE__)).'/data/jpeg-full-quality-test.jpg';
        $destination_filepath = dirname(dirname(__FILE__)).'/data/jpeg-test-output.jpg';
        if( file_exists($destination_filepath) )
          unlink($destination_filepath);

        $actual = $this->ImageManipulation->resize($source_filepath, $destination_filepath, 192, 192);
        $this->assertFileExists($destination_filepath);

        $this->tearDown();
    }

    // Test Resizing a jpg
    public function testJpegWhiteConversion()
    {
        $this->setUp();

        // Set input
        $source_filepath = dirname(dirname(__FILE__)).'/data/jpeg-full-quality-test.jpg';
        $destination_filepath = dirname(dirname(__FILE__)).'/data/jpeg-to-png-output.png';
        if( file_exists($destination_filepath) )
          unlink($destination_filepath);

        $actual = $this->ImageManipulation->convert_white_jpg_to_transparent_png($source_filepath, $destination_filepath);
        $this->assertFileExists($destination_filepath);

        $this->tearDown();
    }

    // Test Resizing a jpg
    public function testTrim()
    {
        $this->setUp();

        // Set input
        // $source_filepath = dirname(dirname(__FILE__)).'/data/png-8-test.png';
        // $destination_filepath = dirname(dirname(__FILE__)).'/data/png-trim-output.png';
        $source_filepath = dirname(dirname(__FILE__)).'/data/jpeg-full-quality-test.jpg';
        $destination_filepath = dirname(dirname(__FILE__)).'/data/jpeg-trim-output.jpg';
        if( file_exists($destination_filepath) )
          unlink($destination_filepath);

        $this->ImageManipulation->trim($source_filepath, $destination_filepath);
        // $this->ImageManipulation->imageCrop($source_filepath);

        $this->assertFileExists($destination_filepath);

        $this->tearDown();
    }

}
