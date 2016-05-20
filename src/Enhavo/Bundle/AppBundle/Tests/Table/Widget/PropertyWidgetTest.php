<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2/22/16
 * Time: 2:39 AM
 */

namespace Enhavo\Bundle\AppBundle\Tests\Table\Widget;


use Enhavo\Bundle\AppBundle\Table\Widget\PropertyWidget;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;

class PropertyWidgetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test if the function getProperty works well
     */
    function testPropertyAccessCorrectly()
    {
        $object = new EntityMock();
        $object->setName('my name is route');

        $widget = new PropertyWidget();
        $this->assertEquals('my name is route', $widget->render([], 'name', $object));
    }

    /**
     * Test if you try to access an not existing property, will throw an exception
     *
     * @expectedException \Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException
     */
    function testPropertyAccessIfNotExists()
    {
        $object = new EntityMock();
        $object->setName('my name is route');

        $widget = new PropertyWidget();
        $this->assertEquals('my name is route', $widget->render([], 'somePropertyWhichDoesNotExist', $object));
    }

    function testType()
    {
        $widget = new PropertyWidget();
        $this->assertEquals('property', $widget->getType());
    }
}