<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationTypeTest extends TestCase
{
    public function testConfigureOptions()
    {
        $resolver = new OptionsResolver();

        $type = new TranslationType();

        $type->configureOptions($resolver);

        $this->assertEquals(['constraints'], $resolver->getDefinedOptions());
    }
}
