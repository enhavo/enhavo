<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Twig;

use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Bundle\TranslationBundle\Twig\TranslationExtension;
use PHPUnit\Framework\TestCase;

class TranslationExtensionTest extends TestCase
{
    private function createDependencies()
    {
        return $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
    }

    private function createInstance($entityManager)
    {
        return new TranslationExtension($entityManager);
    }

    public function testLocale()
    {
        $entityManager = $this->createDependencies();
        $entityManager->method('getDefaultLocale')->willReturn('hr');
        $entityManager->method('getLocales')->willReturn(['hr', 'sl']);
        $ext = $this->createInstance($entityManager);

        $this->assertEquals('hr', $ext->getDefaultLocale());
        $this->assertEquals(['hr', 'sl'], $ext->getLocales());
    }

    public function testProperty()
    {
        $entityManager = $this->createDependencies();
        $entityManager->method('getProperty')->willReturnCallback(function ($resource, $property, $locale) {
            return $resource.'-'.$property.'-'.$locale;
        });
        $ext = $this->createInstance($entityManager);

        $this->assertEquals('object-name-sl', $ext->getProperty('object', 'name', 'sl'));
    }
}
