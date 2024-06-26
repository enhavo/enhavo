<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2/22/16
 * Time: 2:39 AM
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\ResourceBundle\Column\Type\DateTimeColumnType;

class DateTimeTypeTest extends AbstractTypeTestCase
{
    public function testInitialize()
    {
        $type = new DateTimeColumnType();
        $this->assertInstanceOf(DateTimeColumnType::class, $type);
        $this->assertEquals('datetime', $type->getType());
    }

    public function testCreateResourceView()
    {
        $column = $this->createColumn(new DateTimeColumnType(), [
            'property' => 'data',
            'format' => 'd.m.Y H:i',
        ]);

        $entity = new EntityMock();
        $entity->setData(new \DateTime('1970-01-01 12:34'));
        $value = $column->createResourceViewData($entity);
        $this->assertEquals('01.01.1970 12:34', $value);
    }
}
