<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Client;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\TranslationBundle\Client\UrlTranslationClient;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UrlTranslationClientTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new UrlTranslationClientDependencies();
        $dependencies->routeRepository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->domains = [];

        return $dependencies;
    }

    public function createInstance(UrlTranslationClientDependencies $dependencies)
    {
        $instance = new UrlTranslationClient(
            $dependencies->routeRepository,
            $dependencies->translationManager,
            $dependencies->domains,
        );

        return $instance;
    }

    private function configureDependencies(UrlTranslationClientDependencies $dependencies)
    {
        $dependencies->routeRepository->method('findBy')->willReturnCallback(function ($parameters) {
            if ($parameters['staticPrefix'] === '/test') {
                return [(new Route())
                    ->setStaticPrefix('/test')
                    ->setContent(new \stdClass())
                ];
            }
            return [];
        });

        $dependencies->translationManager->method('getProperty')->willReturnCallback(function ($content, $property, $targetLang) {
            if ($content instanceof \stdClass && $property === 'route' && $targetLang === 'en') {
                return (new Route())
                    ->setStaticPrefix('/en/test');
            }
            return null;
        });

        $dependencies->domains = ['domain.tld'];
    }

    public function testTranslateWithDomain()
    {
        $dependencies = $this->createDependencies();
        $this->configureDependencies($dependencies);
        $instance = $this->createInstance($dependencies);

        $text = 'Lorem ipsum dolor sit amet, <a href="http://domain.tld/test">consectetur</a> adipiscing elit';
        $translatedText = $instance->translate($text, 'de', 'en', [
            'html' => true,
        ]);

        $this->assertEquals('Lorem ipsum dolor sit amet, <a href="/en/test">consectetur</a> adipiscing elit', $translatedText);
    }

    public function testTranslateWithoutDomain()
    {
        $dependencies = $this->createDependencies();
        $this->configureDependencies($dependencies);
        $instance = $this->createInstance($dependencies);

        $text = 'Lorem ipsum dolor sit amet, <a href="/test">consectetur</a> adipiscing elit';
        $translatedText = $instance->translate($text, 'de', 'en', [
            'html' => true,
        ]);

        $this->assertEquals('Lorem ipsum dolor sit amet, <a href="/en/test">consectetur</a> adipiscing elit', $translatedText);
    }

    public function testPreventTranslationWithDomain()
    {
        $dependencies = $this->createDependencies();
        $this->configureDependencies($dependencies);
        $instance = $this->createInstance($dependencies);

        $text = 'Lorem ipsum dolor sit amet, <a href="http://domain.tld2/test">consectetur</a> adipiscing elit';
        $translatedText = $instance->translate($text, 'de', 'en', [
            'html' => true,
        ]);

        $this->assertEquals('Lorem ipsum dolor sit amet, <a href="http://domain.tld2/test">consectetur</a> adipiscing elit', $translatedText);
    }

    public function testTranslationMultiple()
    {
        $dependencies = $this->createDependencies();
        $this->configureDependencies($dependencies);
        $instance = $this->createInstance($dependencies);

        $text = 'Lorem ipsum dolor sit amet, <a href="http://domain.tld/test">consectetur</a> adipiscing elit' .
            'Lorem ipsum dolor sit amet, <a href="http://domain.tld/test">consectetur</a> adipiscing elit' .
            'Lorem ipsum dolor sit amet, <a href="http://domain.tld/test">consectetur</a> adipiscing elit';

        $translatedText = $instance->translate($text, 'de', 'en', [
            'html' => true,
        ]);

        $expectedText = 'Lorem ipsum dolor sit amet, <a href="/en/test">consectetur</a> adipiscing elit' .
            'Lorem ipsum dolor sit amet, <a href="/en/test">consectetur</a> adipiscing elit' .
            'Lorem ipsum dolor sit amet, <a href="/en/test">consectetur</a> adipiscing elit';

        $this->assertEquals($expectedText, $translatedText);
    }

    public function testKeepHtml()
    {
        $dependencies = $this->createDependencies();
        $this->configureDependencies($dependencies);
        $instance = $this->createInstance($dependencies);

        $text = '<p>lorem ipsum <strong>dolor</strong>&nbsp;</p>';
        $translatedText = $instance->translate($text, 'de', 'en', [
            'html' => true,
        ]);
        $this->assertEquals($text, $translatedText);
    }
}

class UrlTranslationClientDependencies
{
    public EntityRepository|MockObject $routeRepository;
    public TranslationManager|MockObject $translationManager;
    public array $domains;
}
