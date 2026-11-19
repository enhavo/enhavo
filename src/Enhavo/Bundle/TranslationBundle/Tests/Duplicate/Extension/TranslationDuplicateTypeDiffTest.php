<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Duplicate\Extension;

use Enhavo\Bundle\ResourceBundle\Duplicate\Diff\Change;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\PropertyDuplicateType;
use Enhavo\Bundle\ResourceBundle\Duplicate\Type\StringDuplicateType;
use Enhavo\Bundle\ResourceBundle\Tests\Duplicate\DiffTestTrait;
use Enhavo\Bundle\TranslationBundle\Duplicate\Extension\TranslationPropertyDuplicateTypeExtension;
use Enhavo\Bundle\TranslationBundle\Duplicate\Extension\TranslationStringDuplicateTypeExtension;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslationDuplicateTypeDiffTest extends TestCase
{
    use DiffTestTrait;

    /**
     * @param \SplObjectStorage $translations object => [locale => value]
     */
    private function diff(string $type, DiffArticle $old, DiffArticle $new, \SplObjectStorage $translations): array
    {
        $translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $translationManager->method('getDefaultLocale')->willReturn('de');
        $translationManager->method('getLocales')->willReturn(['de', 'en', 'fr']);
        $translationManager->method('isTranslatable')->willReturn(true);
        $translationManager->method('getProperty')->willReturnCallback(function ($object, $property, $locale) use ($translations) {
            return $translations[$object][$locale] ?? null;
        });

        $propertyType = new PropertyDuplicateType();
        $stringType = new StringDuplicateType($this->getMockBuilder(TranslatorInterface::class)->getMock());

        $factory = $this->createDiffFactory([$propertyType, $stringType], [
            DiffArticle::class => [
                'title' => [['type' => $type, 'groups' => ['revision']]],
            ],
        ], [
            new TranslationPropertyDuplicateTypeExtension($translationManager, $propertyType),
            new TranslationStringDuplicateTypeExtension($translationManager, $stringType),
        ]);

        $changes = $factory->diff($old, $new, ['groups' => 'revision'])->getChanges();

        return array_map(fn (Change $change) => [$change->getField(), $change->getOld(), $change->getNew()], $changes);
    }

    public function testTranslationsPerLocale()
    {
        $old = new DiffArticle('Hallo');
        $new = new DiffArticle('Hallo Welt');
        $translations = new \SplObjectStorage();
        $translations[$old] = ['en' => 'Hello', 'fr' => 'Bonjour'];
        $translations[$new] = ['en' => 'Hello world', 'fr' => 'Bonjour'];

        foreach (['property', 'string'] as $type) {
            $this->assertEquals([
                ['Title', 'Hallo', 'Hallo Welt'],
                ['Title (en)', 'Hello', 'Hello world'],
            ], $this->diff($type, $old, $new, $translations), $type);
        }
    }

    public function testAddedTranslation()
    {
        $old = new DiffArticle('Hallo');
        $new = new DiffArticle('Hallo');
        $translations = new \SplObjectStorage();
        $translations[$old] = [];
        $translations[$new] = ['fr' => 'Bonjour'];

        $this->assertEquals([
            ['Title (fr)', null, 'Bonjour'],
        ], $this->diff('property', $old, $new, $translations));
    }
}

class DiffArticle
{
    public function __construct(
        public ?string $title = null,
    ) {
    }
}