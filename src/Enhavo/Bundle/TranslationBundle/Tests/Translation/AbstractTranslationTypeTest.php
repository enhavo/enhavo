<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Translation;

use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationTypeInterface;
use Enhavo\Bundle\TranslationBundle\Translation\Type\TranslationType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractTranslationTypeTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new AbstractTranslationTypeTestDependencies();
        $dependencies->parent = $this->getMockBuilder(TranslationTypeInterface::class)->getMock();

        return $dependencies;
    }

    public function testSetTranslation()
    {
        $dependencies = $this->createDependencies();
        $dependencies->parent->expects($this->once())->method('setTranslation');
        $type = new ConcreteTranslationType();
        $type->setParent($dependencies->parent);

        $type->setTranslation([], null, 'field', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $dependencies = $this->createDependencies();
        $dependencies->parent->expects($this->once())->method('getTranslation');
        $type = new ConcreteTranslationType();
        $type->setParent($dependencies->parent);

        $type->getTranslation([], null, 'field', 'en');
    }

    public function testGetDefaultValue()
    {
        $dependencies = $this->createDependencies();
        $dependencies->parent->expects($this->once())->method('getDefaultValue');
        $type = new ConcreteTranslationType();
        $type->setParent($dependencies->parent);

        $type->getDefaultValue([], null, 'field');
    }

    public function testGetParentType()
    {
        $this->assertEquals(TranslationType::class, ConcreteTranslationType::getParentType());
    }

    public function testEntityUpdates()
    {
        $entity = new TranslatableMock();
        $dependencies = $this->createDependencies();
        $dependencies->parent->expects($this->once())->method('translate');
        $dependencies->parent->expects($this->once())->method('detach');
        $dependencies->parent->expects($this->once())->method('delete');
        $dependencies->parent->method('translate')->willReturnCallback(function ($data, $property, $locale): void {
            $data->setName($property.'-'.$locale);
        });
        $dependencies->parent->method('detach')->willReturnCallback(function ($data, $property, $locale): void {
            $data->setName($property.'-'.$locale.'.old');
        });
        $dependencies->parent->method('delete')->willReturnCallback(function ($data, $property): void {
            $data->setName(null);
        });

        $type = new ConcreteTranslationType();
        $type->setParent($dependencies->parent);

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
    /** @var TranslationTypeInterface|MockObject */
    public $parent;
}

class ConcreteTranslationType extends AbstractTranslationType
{
}
