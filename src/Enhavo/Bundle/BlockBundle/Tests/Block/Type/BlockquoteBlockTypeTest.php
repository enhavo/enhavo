<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Block\Type;

use Enhavo\Bundle\BlockBundle\Block\BlockTypeInterface;
use Enhavo\Bundle\BlockBundle\Block\Type\BlockquoteBlockType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockquoteBlockTypeTest extends TestCase
{
    public function testGetName()
    {
        $this->assertEquals('blockquote', BlockquoteBlockType::getName());
    }

    public function testConfigureOption()
    {
        $type = new BlockquoteBlockType();
        $options = $this->createOptions($type);
        $this->assertIsArray($options);
    }

    protected function createOptions(BlockTypeInterface $type, $options = [])
    {
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);

        return $resolver->resolve($options);
    }
}
