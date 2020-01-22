<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-22
 * Time: 14:30
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Block\Type;


use Enhavo\Bundle\BlockBundle\Block\Type\PictureBlockType;
use PHPUnit\Framework\TestCase;
use Enhavo\Bundle\BlockBundle\Block\BlockTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureBlockTypeTest extends TestCase
{
    public function testGetType()
    {
        $type = new PictureBlockType();
        $this->assertEquals('picture', $type->getType());
    }

    public function testConfigureOption()
    {
        $type = new PictureBlockType();
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
