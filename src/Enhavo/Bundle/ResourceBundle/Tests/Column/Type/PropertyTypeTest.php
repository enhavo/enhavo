<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2/22/16
 * Time: 2:39 AM
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;


use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\ResourceBundle\Column\Type\PropertyColumnType;

class PropertyTypeTest extends AbstractTypeTestCase
{
    function testInitialize()
    {
        $type = new PropertyColumnType();
        $this->assertInstanceOf(PropertyColumnType::class, $type);
        $this->assertEquals('property', $type->getType());
    }

    /**
     * Test if the function getProperty works well
     */
    function testPropertyAccessCorrectly()
    {
        $column = $this->createColumn(new PropertyColumnType(), [
            'property' => 'data'
        ]);

        $entity = new EntityMock();
        $entity->setData('test');
        $value = $column->createResourceViewData($entity);
        $this->assertEquals('test', $value);
    }

    /**
     * Test if you try to access an not existing property, will throw an exception
     */
    function testPropertyAccessIfNotExists()
    {
        $this->expectException(PropertyNotExistsException::class);

        $column = $this->createColumn(new PropertyColumnType(), [
            'property' => 'property_not_exists'
        ]);

        $entity = new EntityMock();
        $entity->setData('test');
        $column->createResourceViewData($entity);
    }
}
