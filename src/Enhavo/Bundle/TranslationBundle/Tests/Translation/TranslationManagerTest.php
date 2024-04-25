<?php

namespace Enhavo\Bundle\TranslationBundle\Tests\Translation;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\DoctrineExtensionBundle\EntityResolver\EntityResolverInterface;
use Enhavo\Bundle\TranslationBundle\Exception\TranslationException;
use Enhavo\Bundle\TranslationBundle\Locale\LocaleProviderInterface;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\PropertyNode;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRepository;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
use Enhavo\Bundle\TranslationBundle\Translation\Translation;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\FactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TranslationManagerTest extends TestCase
{
    protected function createDependencies()
    {
        $dependencies = new TranslationManagerDependencies();
        $dependencies->metadataRepository = $this->getMockBuilder(MetadataRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->factory = $this->getMockBuilder(FactoryInterface::class)->getMock();
        $dependencies->entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $dependencies->localeResolver = $this->getMockBuilder(LocaleResolverInterface::class)->getMock();
        $dependencies->entityResolver = $this->getMockBuilder(EntityResolverInterface::class)->getMock();
        $dependencies->localeProvider = $this->getMockBuilder(LocaleProviderInterface::class)->getMock();
        $dependencies->localeProvider->method('getLocales')->willReturn(['en', 'de']);
        $dependencies->localeProvider->method('getDefaultLocale')->willReturn('en');
        $dependencies->enabled = true;
        $dependencies->translationPaths = ['#^/admin#'];
        $dependencies->requestStack = $this->getMockBuilder(RequestStack::class)->disableOriginalConstructor()->getMock();
        return $dependencies;
    }

    protected function createInstance(TranslationManagerDependencies $dependencies)
    {
        return new TranslationManager(
            $dependencies->metadataRepository,
            $dependencies->factory,
            $dependencies->entityManager,
            $dependencies->localeResolver,
            $dependencies->entityResolver,
            $dependencies->localeProvider,
            $dependencies->enabled,
            $dependencies->translationPaths,
            $dependencies->requestStack
        );
    }

    public function testGetters()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);

        $this->assertTrue($manager->isEnabled());
        $this->assertEquals(['en', 'de'], $manager->getLocales());
        $this->assertEquals('en', $manager->getDefaultLocale());
    }

    public function testIsTranslatableWithoutProperty()
    {
        $metadata = $this->getMockBuilder(Metadata::class)->disableOriginalConstructor()->getMock();
        $metadata->method('getProperty')->willReturn(null);

        $dependencies = $this->createDependencies();
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);
        $dependencies->metadataRepository->method('getMetadata')->willReturn($metadata);
        $manager = $this->createInstance($dependencies);

        $mock = new TranslatableMock();

        $this->assertTrue($manager->isTranslatable($mock));
        $this->assertFalse($manager->isTranslatable($mock, 'something'));
    }

    public function testIsTranslatableWithProperty()
    {
        $property = $this->getMockBuilder(PropertyNode::class)->disableOriginalConstructor()->getMock();
        $metadata = $this->getMockBuilder(Metadata::class)->disableOriginalConstructor()->getMock();
        $metadata->method('getProperty')->willReturn($property);

        $dependencies = $this->createDependencies();
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);
        $dependencies->metadataRepository->method('getMetadata')->willReturn($metadata);
        $manager = $this->createInstance($dependencies);

        $mock = new TranslatableMock();

        $this->assertTrue($manager->isTranslatable($mock, 'something'));
    }


    public function testIsTranslatableWithoutMetadata()
    {
        $property = $this->getMockBuilder(PropertyNode::class)->disableOriginalConstructor()->getMock();
        $metadata = $this->getMockBuilder(Metadata::class)->disableOriginalConstructor()->getMock();
        $metadata->method('getProperty')->willReturn($property);

        $dependencies = $this->createDependencies();
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(false);
        $manager = $this->createInstance($dependencies);

        $mock = new TranslatableMock();

        $this->assertFalse($manager->isTranslatable($mock, 'something'));
    }

    public function testIsTranslatableWithNull()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);

        $this->assertFalse($manager->isTranslatable(null));
        $this->assertFalse($manager->isTranslatable(null, 'something'));
    }

    public function testIsTranslationAdmin()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->method('getPathInfo')->willReturn('/admin');

        $dependencies = $this->createDependencies();
        $dependencies->requestStack->method('getMasterRequest')->willReturn($request);
        $manager = $this->createInstance($dependencies);

        $this->assertTrue($manager->isTranslation());
    }

    public function testIsTranslationRoot()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->method('getPathInfo')->willReturn('/');

        $dependencies = $this->createDependencies();
        $dependencies->requestStack->expects($this->once())->method('getMasterRequest')->willReturn($request);
        $manager = $this->createInstance($dependencies);

        $this->assertFalse($manager->isTranslation());
        $this->assertFalse($manager->isTranslation()); // test caching
    }

    public function testGetTranslations()
    {
        $property = $this->getMockBuilder(PropertyNode::class)->disableOriginalConstructor()->getMock();
        $property->method('getType')->willReturn('text');
        $property->method('getOptions')->willReturn([]);
        $metadata = $this->getMockBuilder(Metadata::class)->disableOriginalConstructor()->getMock();
        $metadata->method('getProperty')->willReturn($property);
        $translation = $this->getMockBuilder(Translation::class)->disableOriginalConstructor()->getMock();
        $translation->method('getTranslation')->willReturnCallback(function($data, $property, $locale) {
            return $property.'-'.$locale;
        });

        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($translation);
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);
        $dependencies->metadataRepository->method('getMetadata')->willReturn($metadata);
        $manager = $this->createInstance($dependencies);

        $mock = new TranslatableMock();
        $this->assertEquals([
            'de' => 'name-de'
        ], $manager->getTranslations($mock, 'name'));
    }

    public function testSetTranslations()
    {
        $property = $this->getMockBuilder(PropertyNode::class)->disableOriginalConstructor()->getMock();
        $property->method('getType')->willReturn('text');
        $property->method('getOptions')->willReturn([]);
        $metadata = $this->getMockBuilder(Metadata::class)->disableOriginalConstructor()->getMock();
        $metadata->method('getProperty')->willReturn($property);
        $translation = $this->getMockBuilder(Translation::class)->disableOriginalConstructor()->getMock();
        $translation->expects($this->once())->method('setTranslation')->willReturnCallback(function($data, $property, $locale, $value) {
            $this->assertEquals('de', $locale);
            $this->assertEquals('value', $value);
        });

        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($translation);
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);
        $dependencies->metadataRepository->method('getMetadata')->willReturn($metadata);
        $manager = $this->createInstance($dependencies);

        $manager->setTranslation(new TranslatableMock(), 'name', 'de', 'value');
    }

    public function testDelete()
    {
        $node = $this->getMockBuilder(PropertyNode::class)->getMock();
        $node->method('getType')->willReturn('slug');
        $node->method('getProperty')->willReturn('name');
        $node->method('getOptions')->willReturn(['allow_fallback' => true]);

        $metadata = $this->getMockBuilder(Metadata::class)->disableOriginalConstructor()->getMock();
        $metadata->method('getProperties')->willReturnCallback(function () use ($node) {
            return [$node];
        });
        $metadata->method('getProperty')->willReturnCallback(function ($property) use ($node) {
            return $node;
        });
        $metadata->method('getClassName')->willReturn(TranslatableMock::class);

        $translation = $this->getMockBuilder(Translation::class)->disableOriginalConstructor()->getMock();

        $translation->method('delete')->willReturnCallback(function($data) {
            $data->setName(null);
        });

        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($translation);
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);
        $dependencies->metadataRepository->method('getMetadata')->willReturn($metadata);
        $manager = $this->createInstance($dependencies);

        $entity = new TranslatableMock();
        $entity->setName('Harry');

        $manager->delete($entity);
        $this->assertNull($entity->getName());
    }

    public function testDetach()
    {
        $node = $this->getMockBuilder(PropertyNode::class)->getMock();
        $node->method('getType')->willReturn('slug');
        $node->method('getProperty')->willReturn('name');
        $node->method('getOptions')->willReturn(['allow_fallback' => true]);

        $metadata = $this->getMockBuilder(Metadata::class)->disableOriginalConstructor()->getMock();
        $metadata->method('getProperties')->willReturnCallback(function () use ($node) {
            return [$node];
        });
        $metadata->method('getProperty')->willReturnCallback(function ($property) use ($node) {
            return $node;
        });
        $metadata->method('getClassName')->willReturn(TranslatableMock::class);

        $translation = $this->getMockBuilder(Translation::class)->disableOriginalConstructor()->getMock();
        $translation->method('translate')->willReturnCallback(function($data, $property, $locale) {
            $data->setName($property.'-'.$locale);
        });
        $translation->method('detach')->willReturnCallback(function($data, $property, $locale) {
            $data->setName($property.'-' . $locale . '.old');
        });

        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($translation);
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);
        $dependencies->metadataRepository->method('getMetadata')->willReturn($metadata);
        $manager = $this->createInstance($dependencies);

        $entity = new TranslatableMock();
        $entity->setName('Harry');

        $manager->translate($entity, 'de');
        $manager->detach($entity);
        $this->assertEquals('name-de.old', $entity->getName());

        // after second detach nothing should happen
        $manager->detach($entity);
        $this->assertEquals('name-de.old', $entity->getName());
    }

    public function testTranslateSlug()
    {
        $node = $this->getMockBuilder(PropertyNode::class)->getMock();
        $node->method('getType')->willReturn('slug');
        $node->method('getProperty')->willReturn('name');
        $node->method('getOptions')->willReturn(['allow_fallback' => true]);

        $metadata = $this->getMockBuilder(Metadata::class)->disableOriginalConstructor()->getMock();
        $metadata->method('getProperties')->willReturnCallback(function () use ($node) {
            return [$node];
        });
        $metadata->method('getProperty')->willReturnCallback(function ($property) use ($node) {
            return $node;
        });
        $metadata->method('getClassName')->willReturn(TranslatableMock::class);

        $translation = $this->getMockBuilder(Translation::class)->disableOriginalConstructor()->getMock();
        $translation->method('translate')->willReturnCallback(function($data, $property, $locale) {
            $data->setName($property.'-'.$locale);
        });

        $dependencies = $this->createDependencies();
        $dependencies->factory->method('create')->willReturn($translation);
        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);
        $dependencies->metadataRepository->method('getMetadata')->willReturn($metadata);
        $manager = $this->createInstance($dependencies);

        $entity = new TranslatableMock();
        $entity->setName('Harry');

        $manager->translate($entity, 'de');
        $this->assertEquals('name-de', $entity->getName());

        $this->expectException(TranslationException::class);
        $manager->translate($entity, 'de');
    }

    public function testNoMetadataException()
    {
        $this->expectException(TranslationException::class);

        $dependencies = $this->createDependencies();

        $dependencies->metadataRepository->method('hasMetadata')->willReturn(false);
        $manager = $this->createInstance($dependencies);

        $manager->translate(new TranslatableMock(), 'de');

    }

    public function testGetProperty()
    {
        $dependencies = $this->createDependencies();

        $translation = $this->getMockBuilder(Translation::class)->disableOriginalConstructor()->getMock();
        $translation->expects($this->once())->method('getDefaultValue')->willReturnCallback(function($data, $property) {
            return ($property.'-default');
        });
        $translation->method('getTranslation')->willReturnCallback(function($data, $property, $locale) {
            return ($property.'-'.$locale);
        });
        $dependencies->factory->method('create')->willReturn($translation);

        $node = $this->getMockBuilder(PropertyNode::class)->getMock();
        $node->method('getType')->willReturn('text');
        $node->method('getProperty')->willReturn('name');
        $node->method('getOptions')->willReturn(['allow_fallback' => true]);

        $metadata = $this->getMockBuilder(Metadata::class)->disableOriginalConstructor()->getMock();
        $metadata->method('getProperties')->willReturnCallback(function () use ($node) {
            return [$node];
        });
        $metadata->method('getProperty')->willReturnCallback(function ($property) use ($node) {
            return $node;
        });
        $metadata->method('getClassName')->willReturn(TranslatableMock::class);

        $dependencies->metadataRepository->method('hasMetadata')->willReturn(true);
        $dependencies->metadataRepository->method('getMetadata')->willReturn($metadata);
        $manager = $this->createInstance($dependencies);
        $entity = new TranslatableMock();
        $entity->setName('value-a');

        $value = $manager->getProperty($entity, 'name', 'de');
        $this->assertEquals('name-de', $value);

        $value = $manager->getProperty($entity, 'name', 'en');
        $this->assertEquals('name-default', $value);
    }

    public function testFetchBySlug()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);

        $repository = $this->getMockBuilder(TranslationRepository::class)->disableOriginalConstructor()->getMock();

        $repository->method('findOneBy')->willReturnCallback(function ($options) {

            $translation = new \Enhavo\Bundle\TranslationBundle\Entity\Translation();
            $translation->setObject(implode(',', $options));

            return $translation;
        });

        $dependencies->entityManager->method('getRepository')->willReturn($repository);

        $result = $manager->fetchBySlug('test.class', 'sa-lug');

        $this->assertEquals(',slug,sa-lug,', $result);

        $result = $manager->fetchBySlug('test.class', 'sa-lug', 'en')->getObject();

        $this->assertEquals('sa-lug', $result);
    }
}

class TranslationManagerDependencies
{
    /** @var MetadataRepository|MockObject */
    public $metadataRepository;

    /** @var FactoryInterface|MockObject */
    public $factory;

    /** @var EntityManagerInterface|MockObject */
    public $entityManager;

    /** @var LocaleResolverInterface|MockObject */
    public $localeResolver;

    /** @var EntityResolverInterface|MockObject */
    public $entityResolver;

    /** @var LocaleProviderInterface|MockObject */
    public $localeProvider;

    /** @var boolean */
    public $enabled;

    /** @var array */
    public $translationPaths;

    /** @var RequestStack|MockObject */
    public $requestStack;
}
