<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-22
 * Time: 14:31
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Block\Type;

use Enhavo\Bundle\BlockBundle\Block\Type\BlockquoteBlockType;
use PHPUnit\Framework\TestCase;
use Enhavo\Bundle\BlockBundle\Block\BlockTypeInterface;
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
