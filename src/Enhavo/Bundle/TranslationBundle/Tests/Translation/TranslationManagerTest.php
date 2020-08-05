<?php


namespace Enhavo\Bundle\TranslationBundle\Tests\Translation;

use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\PropertyNode;
use Enhavo\Bundle\TranslationBundle\Translation\Translation;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Enhavo\Component\Metadata\MetadataRepository;
use Enhavo\Component\Type\FactoryInterface;
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
        $dependencies->locales = ['en', 'de'];
        $dependencies->defaultLocale = 'en';
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
            $dependencies->locales,
            $dependencies->defaultLocale,
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
}

class TranslationManagerDependencies
{
    /** @var MetadataRepository|\PHPUnit_Framework_MockObject_MockObject */
    public $metadataRepository;

    /** @var FactoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    public $factory;

    /** @var array */
    public $locales;

    /** @var string */
    public $defaultLocale;

    /** @var boolean */
    public $enabled;

    /** @var array */
    public $translationPaths;

    /** @var RequestStack|\PHPUnit_Framework_MockObject_MockObject */
    public $requestStack;
}

class TranslatableMock
{
    private $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}
