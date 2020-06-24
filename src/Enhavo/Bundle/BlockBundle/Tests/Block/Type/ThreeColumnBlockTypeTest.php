<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-22
 * Time: 14:30
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Block\Type;

use Enhavo\Bundle\BlockBundle\Block\Type\ThreeColumnBlockType;
use PHPUnit\Framework\TestCase;
use Enhavo\Bundle\BlockBundle\Block\BlockTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreeColumnBlockTypeTest extends TestCase
{
    public function testGetType()
    {
        $this->assertEquals('three_column', ThreeColumnBlockType::getName());
    }

    public function testConfigureOption()
    {
        $type = new ThreeColumnBlockType();
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
