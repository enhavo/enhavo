<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\Translation;
use Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType;
use PHPUnit\Framework\TestCase;

class TranslationTypeTest extends TestCase
{
    public function testValidationConstraints()
    {
        $type = new TranslationType();

        $translation = new Translation($type, [], [
            'constraints' => ['Constraint']
        ]);

        $this->assertEquals(['Constraint'], $translation->getValidationConstraints(null, null, 'de'));
    }
}
