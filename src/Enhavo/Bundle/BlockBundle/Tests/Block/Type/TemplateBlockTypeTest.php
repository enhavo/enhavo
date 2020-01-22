<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-20
 * Time: 17:01
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Block\Type;

use Enhavo\Bundle\BlockBundle\Block\BlockTypeInterface;
use Enhavo\Bundle\BlockBundle\Block\Type\TemplateBlockType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateBlockTypeTest extends TestCase
{
    public function testGetType()
    {
        $type = new TemplateBlockType([]);
        $this->assertEquals('template', $type->getType());
    }

    public function testConfigureOption()
    {
        $type = new TemplateBlockType([]);
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
