<?php
/**
 * MetadataTest.php
 *
 * @since 23/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SearchBundle\Tests\Metadata;

use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use Enhavo\Bundle\SearchBundle\Metadata\PropertyNode;

class MetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testSetAndGetClassName()
    {
        $metadata = new Metadata();
        $metadata->setClassName('ClassName');
        $this->assertEquals('ClassName', $metadata->getClassName());
    }

    public function testSetAndGetProperties()
    {
        $metadata = new Metadata();
        $node = new PropertyNode();
        $metadata->setProperties([
            $node
        ]);
        $this->assertArraySubset([
            $node
        ], $metadata->getProperties());
    }

    public function testSetAndGetBundleName()
    {
        $metadata = new Metadata();
        $metadata->setBundleName('BundleName');
        $this->assertEquals('BundleName', $metadata->getBundleName());
    }
}