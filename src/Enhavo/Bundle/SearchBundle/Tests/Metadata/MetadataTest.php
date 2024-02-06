<?php

namespace Metadata;

use Enhavo\Bundle\SearchBundle\Metadata\Metadata;
use PHPUnit\Framework\TestCase;

class MetadataTest extends TestCase
{
    public function testGetEntityName()
    {
        $instance = new Metadata('Path\To\Entity');
        $this->assertEquals('Entity', $instance->getEntityName());

        $instance = new Metadata('Entity');
        $this->assertEquals('Entity', $instance->getEntityName());
    }
}
