<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2/22/16
 * Time: 2:39 AM
 */

namespace Enhavo\Bundle\AppBundle\Tests\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\Widget\DateTimeWidget;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;

class DateTimeWidgetTest extends \PHPUnit_Framework_TestCase
{
    function testDefaultFormatDate()
    {
        $object = new EntityMock();
        $object->setName(new \DateTime('1970-01-01 12:34'));

        $options = [
            'property' => 'name'
        ];

        $widget = new DateTimeWidget();
        $this->assertEquals('01.01.1970 12:34', $widget->render($options, $object));
    }

    function testType()
    {
        $widget = new DateTimeWidget();
        $this->assertEquals('datetime', $widget->getType());
    }
}