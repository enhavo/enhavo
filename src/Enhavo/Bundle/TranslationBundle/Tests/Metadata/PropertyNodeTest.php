<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Metadata;

use Enhavo\Bundle\TranslationBundle\Metadata\PropertyNode;
use PHPUnit\Framework\TestCase;

class PropertyNodeTest extends TestCase
{
    public function testGetDefaultOptions()
    {
        $property = new PropertyNode();
        $this->assertEquals([], $property->getOptions());
    }
}
