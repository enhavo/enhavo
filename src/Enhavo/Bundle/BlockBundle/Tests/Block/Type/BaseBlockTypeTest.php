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
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseBlockTypeTest extends TestCase
{
    public function testGetName()
    {
        $this->assertEquals('base', BaseBlockType::getName());
    }

    private function createInstance()
    {
        return new BaseBlockType($this->getMockBuilder(TranslatorInterface::class)->getMock());
    }

    public function testConfigureOption()
    {
        $type = $this->createInstance();
        $options = $this->createOptions($type, [
            'factory' => 'factory',
            'form' => 'form',
            'label' => 'label',
            'model' => 'model',
            'template' => 'template',
        ]);
        $this->assertIsArray($options);
    }

    public function testConfigurationRequiredOptions()
    {
        $this->expectException(MissingOptionsException::class);
        $type = $this->createInstance();
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
