<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation;

use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationTypeInterface;
use Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\TranslatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractTranslationTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new AbstractTranslationTypeTestDependencies();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $dependencies->translation = $this->getMockBuilder(TranslationTypeInterface::class)->getMock();

        return $dependencies;
    }

    public function testSetTranslation()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translation->expects($this->once())->method('setTranslation');
        $type = new ConcreteTranslationType($dependencies->translator);
        $type->setParent($dependencies->translation);

        $type->setTranslation([], null, null, 'en', 'value');
    }

    public function testGetTranslation()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translation->expects($this->once())->method('getTranslation');
        $type = new ConcreteTranslationType($dependencies->translator);
        $type->setParent($dependencies->translation);

        $type->getTranslation([], null, null, 'en');
    }

    public function testGetValidationConstraint()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translation->expects($this->once())->method('getValidationConstraints');
        $type = new ConcreteTranslationType($dependencies->translator);
        $type->setParent($dependencies->translation);

        $type->getValidationConstraints([], null, null, 'en');
    }

    public function testGetParentType()
    {
        $this->assertEquals(TranslationType::class, ConcreteTranslationType::getParentType());
    }
}

class AbstractTranslationTypeTestDependencies
{
    /** @var TranslatorInterface|MockObject */
    public $translator;

    /** @var TranslationTypeInterface|MockObject */
    public $translation;
}

class ConcreteTranslationType extends AbstractTranslationType
{

}
