<?php
class AvaneTest extends \PHPUnit_Framework_TestCase
{
    function testRender()
    {
        $Avane = new Avane\Main(__DIR__ . '/template');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }

    function testPjax()
    {
        $Avane = new Avane\Main(__DIR__ . '/template');

        /** Simulate the PJAX header content */
        $Avane->isPJAX = 'title, html, content, footer';

        ob_start();

        $Avane->header('header')
              ->render('test')
              ->footer('footer');

        $returned = ob_get_clean();

        $this->assertEquals($returned, '{"title":null,"html":"<html><head><\/head><body><div>Hello, World!<\/div><\/body><\/html>","content":"<div>Hello, World!<\/div>","footer":"<\/body><\/html>"}');
    }

    function testFullyPjax()
    {
        $Avane = new Avane\Main(__DIR__ . '/template');

        /** Simulate the PJAX header content */
        $Avane->isPJAX = 'title, html, content, footer, wasted';

        $Avane->header('header')
              ->render('test')
              ->footer('footer');
    }

    function testSetVariables()
    {
        $Avane = new Avane\Main(__DIR__ . '/template');

        $Avane->setVariables('test2', ['foo' => 'bar']);

        $Avane->render('test2');

        $this->assertEquals($Avane->fetch('test2'), '<div>bar</div>');
    }

    function testCoffee()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_coffee');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
        $this->assertEquals(file_get_contents(__DIR__ . '/template_coffee/scripts/a.js'), file_get_contents(__DIR__ . '/compiled_coffee/a.js'));
        $this->assertEquals(file_get_contents(__DIR__ . '/template_coffee/scripts/c.js'), file_get_contents(__DIR__ . '/compiled_coffee/c.js'));
    }

    function testCoffeeCache()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_coffee');

        $Avane->fetch('test');
        $Avane->fetch('test');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
        $this->assertEquals(file_get_contents(__DIR__ . '/template_coffee/scripts/a.js'), file_get_contents(__DIR__ . '/compiled_coffee/a.js'));
        $this->assertEquals(file_get_contents(__DIR__ . '/template_coffee/scripts/c.js'), file_get_contents(__DIR__ . '/compiled_coffee/c.js'));
    }

    function testCoffeeError()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_coffeeError');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }

    function testRubySass()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_sass');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }

    function testRubySassCache()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_sass');

        $Avane->fetch('test');
        $Avane->fetch('test');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }

    function testSassC()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_sassc');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }

    function testSassTracker()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_sassTracker');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }
}
?>