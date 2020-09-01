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
        $this->assertNull($type->getDefaultValue([], null, 'prop'));
        $type->translate(null, 'prop', 'locale', []);
        $type->detach(null, 'prop', 'locale', []);
        $type->delete(null, 'prop');

    }
}
