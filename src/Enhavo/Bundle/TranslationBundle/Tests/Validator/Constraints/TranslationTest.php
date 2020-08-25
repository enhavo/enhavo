<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Validator\Constraints;


use Enhavo\Bundle\TranslationBundle\Validator\Constraints\Translation;
use Enhavo\Bundle\TranslationBundle\Validator\Constraints\TranslationValidator;
use PHPUnit\Framework\TestCase;

class TranslationTest extends TestCase
{
    private function createDependencies(array $options = [])
    {
        return array_merge(['constraints' => []], $options);
    }

    private function createInstance($options)
    {
        return new Translation($options);
    }

    public function testValidatedBy()
    {
        $this->assertEquals(TranslationValidator::class, $this->createInstance($this->createDependencies())->validatedBy());
    }

    public function testGetTargets()
    {
        $this->assertEquals(Translation::PROPERTY_CONSTRAINT, $this->createInstance($this->createDependencies())->getTargets());
    }
}
