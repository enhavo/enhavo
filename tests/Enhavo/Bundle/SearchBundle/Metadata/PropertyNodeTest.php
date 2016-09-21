<?php
/**
 * PropertyNodeTest.php
 *
 * @since 23/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SearchBundle\Tests\Metadata;


use Enhavo\Bundle\SearchBundle\Metadata\PropertyNode;

class PropertyNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testSetAndGetType()
    {
        $node = new PropertyNode();
        $node->setType('Type');
        $this->assertEquals('Type', $node->getType());
    }

    public function testSetAndGetOptions()
    {
        $node = new PropertyNode();
        $node->setOptions([
            'option1'
        ]);
        $this->assertArraySubset([
            'option1'
        ], $node->getOptions());
    }

    public function testSetAndGetProperty()
    {
        $node = new PropertyNode();
        $node->setProperty('Property');
        $this->assertEquals('Property', $node->getProperty());
    }
}