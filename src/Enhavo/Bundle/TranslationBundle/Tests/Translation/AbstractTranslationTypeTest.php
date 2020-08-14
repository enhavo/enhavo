<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation;

use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
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

        $type->setTranslation([], null, 'field', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translation->expects($this->once())->method('getTranslation');
        $type = new ConcreteTranslationType($dependencies->translator);
        $type->setParent($dependencies->translation);

        $type->getTranslation([], null, 'field', 'en');
    }

    public function testGetValidationConstraint()
    {
        $dependencies = $this->createDependencies();
        $dependencies->translation->expects($this->once())->method('getValidationConstraints');
        $type = new ConcreteTranslationType($dependencies->translator);
        $type->setParent($dependencies->translation);

        $type->getValidationConstraints([], null, 'field', 'en');
    }

    public function testGetParentType()
    {
        $this->assertEquals(TranslationType::class, ConcreteTranslationType::getParentType());
    }


    public function testEntityUpdates()
    {
        $entity = new TranslatableMock();
        $dependencies = $this->createDependencies();
        $dependencies->translator->expects($this->once())->method('translate');
        $dependencies->translator->expects($this->once())->method('detach');
        $dependencies->translator->expects($this->once())->method('delete');
        $dependencies->translator->method('translate')->willReturnCallback(function ($data, $property, $locale) {
            $data->setName($property . '-' . $locale);
        });
        $dependencies->translator->method('detach')->willReturnCallback(function ($data, $property, $locale) {
            $data->setName($property . '-' . $locale . '.old');
        });
        $dependencies->translator->method('delete')->willReturnCallback(function ($data, $property) {
            $data->setName(null);
        });

        $type = new ConcreteTranslationType($dependencies->translator);
        $type->setParent($dependencies->translation);

        $type->translate($entity, 'field', 'de', []);
        $this->assertEquals('field-de', $entity->getName());
        $type->detach($entity, 'field', 'de', []);
        $this->assertEquals('field-de.old', $entity->getName());
        $type->delete($entity, 'field');
        $this->assertNull($entity->getName());
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
