<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-22
 * Time: 14:27
 */

namespace Enhavo\Bundle\BlockBundle\Tests\Block\Type;

use Enhavo\Bundle\BlockBundle\Block\BlockTypeInterface;
use Enhavo\Bundle\BlockBundle\Block\Type\BaseBlockType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseBlockTypeTest extends TestCase
{
    public function testGetType()
    {
        $type = new BaseBlockType();
        $this->assertEquals('base', $type->getType());
    }

    public function testConfigureOption()
    {
        $type = new BaseBlockType();
        $options = $this->createOptions($type, [
            'factory' => 'factory',
            'form' => 'form',
            'label' => 'label',
            'model' => 'model',
            'template' => 'template',
            'repository' => 'repository',
        ]);
        $this->assertIsArray($options);
    }

    public function testConfigurationRequiredOptions()
    {
        $this->expectException(MissingOptionsException::class);
        $type = new BaseBlockType();
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
