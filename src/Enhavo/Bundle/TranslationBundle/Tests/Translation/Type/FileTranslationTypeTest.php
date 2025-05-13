<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Translation\Type;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\TranslationBundle\Translation\Type\FileTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Media\FileTranslator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileTranslationTypeTest extends TestCase
{
    private function createDependencies()
    {
        /** @var FileTranslator|MockObject $fileTranslator */
        $fileTranslator = $this->getMockBuilder(FileTranslator::class)->disableOriginalConstructor()->getMock();

        return $fileTranslator;
    }

    private function createInstance($fileTranslator)
    {
        return new FileTranslationType($fileTranslator);
    }

    public function testGetName()
    {
        $this->assertEquals('file', FileTranslationType::getName());
    }

    public function testSetTranslation()
    {
        $fileTranslator = $this->createDependencies();
        $fileTranslator->expects($this->once())->method('setTranslation')->willReturnCallback(function ($data, $property, $locale, $value): void {
            $this->assertTrue(is_object($data));
            $this->assertEquals('name', $property);
            $this->assertEquals('en', $locale);
            $this->assertEquals('value', $value);
        });

        $type = $this->createInstance($fileTranslator);
        $type->setTranslation(['option' => 'value'], new \stdClass(), 'name', 'en', 'value');
    }

    public function testGetTranslation()
    {
        $fileTranslator = $this->createDependencies();
        $file = $this->getMockBuilder(FileInterface::class)->getMock();
        $fileTranslator->expects($this->once())->method('getTranslation')->willReturn($file);

        $type = $this->createInstance($fileTranslator);
        $this->assertEquals($file, $type->getTranslation([], new \stdClass(), 'file', 'de'));
    }

    public function testGetDefaultValue()
    {
        $fileTranslator = $this->createDependencies();
        $file = $this->getMockBuilder(FileInterface::class)->getMock();
        $fileTranslator->expects($this->once())->method('getDefaultValue')->willReturn($file);

        $type = $this->createInstance($fileTranslator);
        $this->assertEquals($file, $type->getDefaultValue([], new \stdClass(), 'file'));
    }

    public function testTranslate()
    {
        $routeTranslator = $this->createDependencies();
        $routeTranslator->expects($this->once())->method('translate');

        $data = new \stdClass();

        $type = $this->createInstance($routeTranslator);

        $type->translate($data, 'field', 'de', []);
    }

    public function testDetach()
    {
        $routeTranslator = $this->createDependencies();
        $routeTranslator->expects($this->once())->method('detach');

        $data = new \stdClass();

        $type = $this->createInstance($routeTranslator);

        $type->detach($data, 'field', 'de', []);
    }

    public function testDelete()
    {
        $routeTranslator = $this->createDependencies();
        $routeTranslator->expects($this->once())->method('delete');

        $data = new \stdClass();

        $type = $this->createInstance($routeTranslator);

        $type->delete($data, 'field');
    }
}
