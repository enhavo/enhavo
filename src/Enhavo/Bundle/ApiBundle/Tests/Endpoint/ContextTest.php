<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ApiBundle\Tests\Endpoint;

use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class ContextTest extends TestCase
{
    public function testSetGetData()
    {
        $context = new Context($this->getMockBuilder(Request::class)->getMock());

        $context->set('property1', 'something');
        $context->set('property2', new \stdClass());

        $this->assertFalse($context->has('test'));
        $this->assertTrue($context->has('property1'));
        $this->assertEquals('something', $context->get('property1'));
        $this->assertTrue($context->get('property2', 'something!!') instanceof \stdClass);
    }
}
