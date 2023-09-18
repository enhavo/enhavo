<?php

namespace Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{
    public function testSetGetData()
    {
        $context = new Context();

        $context->set('property1', 'something');

        $this->assertFalse($context->has('test'));
        $this->assertTrue($context->has('property1'));
        $this->assertEquals('something', $context->get('property1'));
        $this->assertEquals('something!!', $context->get('property2', 'something!!'));
    }
}
