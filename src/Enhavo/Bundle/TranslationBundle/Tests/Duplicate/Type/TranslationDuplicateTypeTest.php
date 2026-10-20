<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Duplicate\Type;

use Enhavo\Bundle\MediaBundle\Duplicate\Type\FileDuplicateType;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ResourceBundle\Duplicate\Duplicate;
use Enhavo\Bundle\ResourceBundle\Duplicate\DuplicateFactory;
use Enhavo\Bundle\ResourceBundle\Duplicate\SourceValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\TargetValue;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\BaseDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\PropertyDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\StringDuplicateType;
use Enhavo\Bundle\TranslationBundle\Duplicate\Type\TranslationFileDuplicateType;
use Enhavo\Bundle\TranslationBundle\Duplicate\Type\TranslationPropertyDuplicateType;
use Enhavo\Bundle\TranslationBundle\Duplicate\Type\TranslationStringDuplicateType;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Component\Type\TypeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationDuplicateTypeTest extends TestCase
{
    public function createDependencies(array $sourceTranslations): TranslationDuplicateTypeDependencies
    {
        $dependencies = new TranslationDuplicateTypeDependencies();
        $dependencies->translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->translationManager->method('getDefaultLocale')->willReturn('de');
        $dependencies->translationManager->method('getLocales')->willReturn(['de', 'en', 'fr']);
        $dependencies->translationManager->method('isTranslatable')->willReturn(true);
        $dependencies->translationManager->method('getProperty')->willReturnCallback(function ($resource, $property, $locale) use ($sourceTranslations) {
            return $sourceTranslations[$locale] ?? null;
        });
        $dependencies->translationManager->method('setTranslation')->willReturnCallback(function ($resource, $property, $locale, $value) use ($dependencies) {
            $dependencies->translations[$locale] = $value;
        });

        return $dependencies;
    }

    /**
     * Builds the duplicate container like the factory does: root parent first, each type knows its direct parent.
     */
    private function createDuplicate(TypeInterface $type, TypeInterface $parent, array $options = []): Duplicate
    {
        $base = new BaseDuplicateType();
        $parent->setParent($base);
        $type->setParent($parent);

        return new Duplicate($type, [$base, $parent], $options);
    }

    public function testDuplicateProperty()
    {
        $dependencies = $this->createDependencies(['de' => 'Hallo', 'en' => 'Hello', 'fr' => null]);
        $duplicate = $this->createDuplicate(new TranslationPropertyDuplicateType($dependencies->translationManager), new PropertyDuplicateType());

        $targetValue = new TargetValue(null, new \stdClass(), 'title');
        $duplicate->duplicate(new SourceValue('Hallo', new \stdClass(), 'title'), $targetValue);

        $this->assertEquals('Hallo', $targetValue->getValue());
        $this->assertEquals(['en' => 'Hello'], $dependencies->translations);
    }

    public function testNotTranslatable()
    {
        $translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $translationManager->method('isTranslatable')->willReturn(false);
        $translationManager->expects($this->never())->method('setTranslation');
        $duplicate = $this->createDuplicate(new TranslationPropertyDuplicateType($translationManager), new PropertyDuplicateType());

        $targetValue = new TargetValue(null, new \stdClass(), 'title');
        $duplicate->duplicate(new SourceValue('Hallo', new \stdClass(), 'title'), $targetValue);

        $this->assertEquals('Hallo', $targetValue->getValue());
    }

    public function testDuplicateString()
    {
        $dependencies = $this->createDependencies(['de' => 'Hallo', 'en' => 'Hello', 'fr' => 'Bonjour']);
        $translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();
        $translator->method('trans')->willReturnArgument(0);
        $duplicate = $this->createDuplicate(
            new TranslationStringDuplicateType($dependencies->translationManager),
            new StringDuplicateType($translator),
            ['postfix' => ' (Copy)'],
        );

        $targetValue = new TargetValue(null, new \stdClass(), 'title');
        $duplicate->duplicate(new SourceValue('Hallo', new \stdClass(), 'title'), $targetValue);

        $this->assertEquals('Hallo (Copy)', $targetValue->getValue());
        $this->assertEquals(['en' => 'Hello (Copy)', 'fr' => 'Bonjour (Copy)'], $dependencies->translations);
    }

    public function testDuplicateFile()
    {
        $file = $this->getMockBuilder(FileInterface::class)->getMock();
        $fileEn = $this->getMockBuilder(FileInterface::class)->getMock();
        $newFile = $this->getMockBuilder(FileInterface::class)->getMock();
        $newFileEn = $this->getMockBuilder(FileInterface::class)->getMock();

        $dependencies = $this->createDependencies(['en' => $fileEn]);
        $duplicateFactory = $this->getMockBuilder(DuplicateFactory::class)->disableOriginalConstructor()->getMock();
        $duplicateFactory->method('duplicate')->willReturnCallback(function ($source) use ($file, $newFile, $newFileEn) {
            return $source === $file ? $newFile : $newFileEn;
        });
        $duplicate = $this->createDuplicate(new TranslationFileDuplicateType($dependencies->translationManager), new FileDuplicateType($duplicateFactory));

        $targetValue = new TargetValue(null, new \stdClass(), 'picture');
        $duplicate->duplicate(new SourceValue($file, new \stdClass(), 'picture'), $targetValue);

        $this->assertSame($newFile, $targetValue->getValue());
        $this->assertSame(['en' => $newFileEn], $dependencies->translations);
    }
}

class TranslationDuplicateTypeDependencies
{
    public TranslationManager|MockObject $translationManager;
    public array $translations = [];
}
