<?php


namespace Enhavo\Bundle\ThemeBundle\Tests\Theme\Loader;

use Enhavo\Bundle\ThemeBundle\Exception\ThemeLoadException;
use Enhavo\Bundle\ThemeBundle\Theme\Loader\FileThemeLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

class FileThemeLoaderTest extends TestCase
{
    private function getSerializer()
    {
        $classMetadataFactory = new ClassMetadataFactory(new YamlFileLoader(__DIR__.'/../../../Resources/config/serialization.yaml'));
        $serializer = new Serializer([new ObjectNormalizer($classMetadataFactory, null, null, new ReflectionExtractor())]);
        return $serializer;
    }

    public function testEmptyFile()
    {
        $this->expectException(ThemeLoadException::class);
        $loader = new FileThemeLoader(__DIR__.'/../../Fixtures/loader/empty.yml', $this->getSerializer());
        $loader->load();
    }

    public function testFileWithKeyOnly()
    {
        $loader = new FileThemeLoader(__DIR__.'/../../Fixtures/loader/key_only.yml', $this->getSerializer());
        $theme = $loader->load();
        $this->assertEquals('some_key', $theme->getKey());
        $this->assertEquals(sprintf('%s/templates', realpath(__DIR__.'/../../Fixtures/loader/')), $theme->getTemplate()->getPath());
        $this->assertNotNull($theme->getMeta());
    }
}
