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
use Enhavo\Bundle\TranslationBundle\Translation\Translation;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationTypeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TranslationTest extends TestCase
{
    private function createDependencies()
    {
        /** @var TranslationTypeInterface|MockObject $translationType */
        $translationType = $this->getMockBuilder(TranslationTypeInterface::class)->getMock();

        return $translationType;
    }

    public function testSetTranslation()
    {
        $typeMock = $this->createDependencies();
        $typeMock->expects($this->once())->method('setTranslation');
        $type = new Translation($typeMock, [], []);
        $type->setTranslation([], 'field', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $typeMock = $this->createDependencies();
        $typeMock->expects($this->once())->method('getTranslation');
        $type = new Translation($typeMock, [], []);
        $type->getTranslation(null, 'field', 'en');
    }

    public function testGetDefaultValue()
    {
        $typeMock = $this->createDependencies();
        $typeMock->expects($this->once())->method('getDefaultValue');
        $type = new Translation($typeMock, [], []);
        $type->getDefaultValue(null, 'field');
    }

    public function testEntityUpdates()
    {
        $entity = new TranslatableMock();
        $typeMock = $this->createDependencies();
        $typeMock->expects($this->once())->method('translate');
        $typeMock->expects($this->once())->method('detach');
        $typeMock->expects($this->once())->method('delete');
        $typeMock->method('translate')->willReturnCallback(function ($data, $property, $locale): void {
            $data->setName($property.'-'.$locale);
        });
        $typeMock->method('detach')->willReturnCallback(function ($data, $property, $locale): void {
            $data->setName($property.'-'.$locale.'.old');
        });
        $typeMock->method('delete')->willReturnCallback(function ($data, $property): void {
            $data->setName(null);
        });
        $type = new Translation($typeMock, [], []);
        $type->translate($entity, 'field', 'de');
        $this->assertEquals('field-de', $entity->getName());
        $type->detach($entity, 'field', 'de');
        $this->assertEquals('field-de.old', $entity->getName());
        $type->delete($entity, 'field');
        $this->assertNull($entity->getName());
    }
}
