<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2/22/16
 * Time: 2:39 AM
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\Widget\DateWidget;
use Enhavo\Bundle\AppBundle\Mock\EntityMock;
use PHPUnit\Framework\TestCase;

class DateWidgetTest extends TestCase
{
    function testDefaultFormatDate()
    {
        $object = new EntityMock();
        $object->setName(new \DateTime('1970-01-01 12:34'));

        $options = [
            'property' => 'name'
        ];

        $widget = new DateWidget();
        $this->assertEquals('01.01.1970', $widget->render($options, $object));
    }

    function testType()
    {
        $widget = new DateWidget();
        $this->assertEquals('date', $widget->getType());
    }
}