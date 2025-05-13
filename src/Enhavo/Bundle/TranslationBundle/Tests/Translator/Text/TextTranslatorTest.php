<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Translator\Text;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleProviderInterface;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRepository;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TextTranslatorTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new TextTranslatorTestDependencies();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->repository = $this->getMockBuilder(TranslationRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->entityManager->method('getRepository')->willReturn($dependencies->repository);
        $dependencies->entityResolver = $this->getMockBuilder(EntityResolverInterface::class)->getMock();
        $dependencies->localeProvider = $this->getMockBuilder(LocaleProviderInterface::class)->getMock();
        $dependencies->localeProvider->method('getDefaultLocale')->willReturn('es');

        return $dependencies;
    }

    private function createInstance($dependencies)
    {
        $translator = new TextTranslator(
            $dependencies->entityManager,
            $dependencies->entityResolver,
            $dependencies->localeProvider
        );

        return $translator;
    }

    public function testSetTranslation()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $entity = new TranslatableMock();
        $entity->setName('spanish');
        $translator->setTranslation($entity, 'name', 'fr', 'french');
        $this->assertEquals('french', $translator->getTranslation($entity, 'name', 'fr'));

        // translate default locale should not change value
        $translator->setTranslation($entity, 'name', 'es', 'spanish2');
        $this->assertEquals('french', $translator->getTranslation($entity, 'name', 'fr'));

        // translate again should change value
        $translator->setTranslation($entity, 'name', 'fr', 'french2');
        $this->assertEquals('french2', $translator->getTranslation($entity, 'name', 'fr'));
    }

    public function testGetLoadTranslation()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);
        $translation = $this->getMockBuilder(Translation::class)->getMock();

        $dependencies->repository->method('findOneBy')->willReturnCallback(function (array $params) use ($translation) {
            $params['class'] = 'translated';
            $paramstring = implode(',', $params);
            $translation->method('getTranslation')->willReturn($paramstring);

            return $translation;
        });

        $entity = new TranslatableMock();
        $translator->getTranslation($entity, 'name', 'fr');

        $this->assertEquals('translated,,name,fr', $translator->getTranslation($entity, 'name', 'fr'));
    }

    public function testSetLoadTranslation()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);
        $translation = $this->getMockBuilder(Translation::class)->getMock();

        $dependencies->repository->method('findOneBy')->willReturnCallback(function (array $params) use ($translation) {
            $params['class'] = 'translated';
            $paramstring = implode(',', $params);
            $translation->method('getTranslation')->willReturn($paramstring);

            return $translation;
        });

        $entity = new TranslatableMock();
        $translator->setTranslation($entity, 'name', 'fr', 'bingobongo');

        $this->assertEquals('translated,,name,fr', $translator->getTranslation($entity, 'name', 'fr'));
    }

    public function testGetTranslationDefaultLocale()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $entity = new TranslatableMock();
        $entity->setName('spanish');

        $this->assertNull($translator->getTranslation($entity, 'name', 'es'));
    }

    public function testGetTranslationMissing()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $entity = new TranslatableMock();
        $entity->setName('spanish');

        $this->assertNull($translator->getTranslation($entity, 'name', 'fr'));
    }

    public function testTranslateDetachDelete()
    {
        $dependencies = $this->createDependencies();
        $translator = $this->createInstance($dependencies);

        $translation = $this->getMockBuilder(Translation::class)->getMock();

        $dependencies->repository->method('findBy')->willReturnCallback(function (array $params) use ($translation) {
            return [$translation];
        });

        $entity = new TranslatableMock();
        $entity->setName('spanish');
        $translator->setTranslation($entity, 'name', 'fr', 'french');
        $translator->setTranslation($entity, 'name', 'es', 'spanish');
        $dependencies->entityManager->expects($this->once())->method('remove');

        $translator->translate($entity, 'name', 'fr', []);
        $this->assertEquals('french', $entity->getName());

        // do not change if locale is defaultLocale
        $translator->translate($entity, 'name', 'es', []);
        $this->assertEquals('french', $entity->getName());

        $translator->detach($entity, 'name', 'fr', []);
        $this->assertEquals('spanish', $entity->getName());

        $translator->delete($entity, 'name');
    }
}

class TextTranslatorTestDependencies
{
    /** @var EntityManagerInterface|MockObject */
    public $entityManager;

    /** @var EntityResolverInterface|MockObject */
    public $entityResolver;

    /** @var TranslationRepository|MockObject */
    public $repository;

    /** @var LocaleProviderInterface|MockObject */
    public $localeProvider;
}
