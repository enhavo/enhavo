<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2/22/16
 * Time: 2:39 AM
 */

namespace Enhavo\Bundle\AppBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Column\Type\DateTimeType;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;

class DateTimeTypeTest extends AbstractTypeTestCase
{
    public function testInitialize()
    {
        $type = new DateTimeType();
        $this->assertInstanceOf(DateTimeType::class, $type);
        $this->assertEquals('datetime', $type->getType());
    }

    public function testCreateResourceView()
    {
        $column = $this->createColumn(new DateTimeType(), [
            'property' => 'data',
            'format' => 'd.m.Y H:i',
        ]);

        $entity = new EntityMock();
        $entity->setData(new \DateTime('1970-01-01 12:34'));
        $value = $column->createResourceViewData($entity);
        $this->assertEquals('01.01.1970 12:34', $value);
    }
}
