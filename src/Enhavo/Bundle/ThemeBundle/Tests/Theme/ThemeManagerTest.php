<?php

namespace Enhavo\Bundle\ThemeBundle\Tests\Theme;

use Enhavo\Bundle\ThemeBundle\Theme\ThemeManager;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ThemeManagerTest extends TestCase
{
    private function createDependencies()
    {
        $dependencies = new ThemeManagerDependencies();
        $dependencies->themesDir = [__DIR__.'/../Fixtures/themes'];
        $dependencies->serializer = $this->getSerializer();
        $dependencies->locator = $this->getMockBuilder(FileLocatorInterface::class)->getMock();
        $dependencies->dynamicEnable = true;
        $dependencies->theme = 'base';
        $dependencies->repository = $this->getMockBuilder(EntityRepository::class)->disableOriginalConstructor()->getMock();
        $dependencies->customFile = '';
        return $dependencies;
    }

    private function createInstance(ThemeManagerDependencies $dependencies)
    {
        $instance = new ThemeManager($dependencies->themesDir, $dependencies->serializer, $dependencies->locator, $dependencies->dynamicEnable, $dependencies->theme, $dependencies->repository, $dependencies->customFile);
        return $instance;
    }

    private function getSerializer()
    {
        $classMetadataFactory = new ClassMetadataFactory(new YamlFileLoader(__DIR__.'/../../Resources/config/serialization.yaml'));
        $serializer = new Serializer([new ObjectNormalizer($classMetadataFactory, null, null, new ReflectionExtractor())]);
        return $serializer;
    }

    public function testGetThemes()
    {
        $dependencies = $this->createDependencies();
        $manager = $this->createInstance($dependencies);
        $themes = $manager->getThemes();

        $this->assertCount(2, $themes);
        $this->assertArrayHasKey('base', $themes);
        $this->assertArrayHasKey('other', $themes);
        $this->assertEquals('base', $themes['base']->getKey());
        $this->assertEquals('other', $themes['other']->getKey());
    }
}

class ThemeManagerDependencies
{
    /** @var string */
    public $themesDir;
    /** @var Serializer  */
    public $serializer;
    /** @var FileLocatorInterface */
    public $locator;
    /** @var boolean */
    public $dynamicEnable;
    /** @var string */
    public $theme;
    /** @var EntityRepository */
    public $repository;
    /** @var string */
    public $customFile;
}
