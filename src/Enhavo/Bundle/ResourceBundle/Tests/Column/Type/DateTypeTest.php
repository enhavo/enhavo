<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2/22/16
 * Time: 2:39 AM
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\ResourceBundle\Column\Type\DateColumnType;

class DateTypeTest extends AbstractTypeTestCase
{
    public function testInitialize()
    {
        $type = new DateColumnType();
        $this->assertInstanceOf(DateColumnType::class, $type);
        $this->assertEquals('date', $type->getType());
    }

    public function testCreateResourceView()
    {
        $column = $this->createColumn(new DateColumnType(), [
            'property' => 'data',
            'format' => 'd.m.Y',
        ]);

        $entity = new EntityMock();
        $entity->setData(new \DateTime('1970-01-01'));
        $value = $column->createResourceViewData($entity);
        $this->assertEquals('01.01.1970', $value);
    }
}
