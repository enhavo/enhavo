<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationTypeTest extends TestCase
{
    public function testEmptyFunctions()
    {
        $type = new TranslationType();
        $this->assertNull($type->setTranslation([], null, 'prop', 'locale', 'value'));
        $this->assertNull($type->getTranslation([], null, 'prop', 'locale'));
        $type->translate(null, 'prop', 'locale', []);
        $type->detach(null, 'prop', 'locale', []);
        $type->delete(null, 'prop');

    }

    public function testConfigureOptions()
    {
        $resolver = new OptionsResolver();

        $type = new TranslationType();

        $type->configureOptions($resolver);

        $this->assertEquals(['constraints'], $resolver->getDefinedOptions());
    }
}
