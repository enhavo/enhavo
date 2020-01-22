<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-22
 * Time: 14:31
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Block\Type;


use Enhavo\Bundle\BlockBundle\Block\Type\TextPictureBlockType;
use PHPUnit\Framework\TestCase;
use Enhavo\Bundle\BlockBundle\Block\BlockTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextPictureBlockTypeTest extends TestCase
{
    public function testGetType()
    {
        $type = new TextPictureBlockType();
        $this->assertEquals('text_picture', $type->getType());
    }

    public function testConfigureOption()
    {
        $type = new TextPictureBlockType();
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
