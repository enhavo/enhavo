<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 05/09/16
 * Time: 16:28
 */

namespace Enhavo\Bundle\SearchBundle\DependencyInjection\Compiler;


use Enhavo\Bundle\SearchBundle\Metadata\MetadataCollector;
use Enhavo\Bundle\SearchBundle\Metadata\MetadataFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class ConfigCompilerPass implements CompilerPassInterface
{
    protected $kernel;
    protected $filesystem;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function process(ContainerBuilder $container)
    {
        $this->compile();
    }

    public function compile()
    {
        $bundles = $this->kernel->getBundles();

        $configuration = [];
        foreach($bundles as $bundle) {
            $absolutePath = $this->composeSettingPathFor($bundle);
            if(file_exists($absolutePath)) {
                try {
                    $data = Yaml::parse(file_get_contents($absolutePath));
                } catch(\Exception $e) {
                    continue;
                }

                if (is_array($data)) {
                    foreach ($data as $class => $config) {
                        $configuration[$class] = $config;
                    }
                }
            }
        }
        $settingArrayJson = json_encode($configuration);
        $this->writeToCache($settingArrayJson);
    }

    protected function composeSettingPathFor($bundle)
    {
        $className = get_class($bundle);
        $classNameParts = explode('\\', $className);
        $bundleName = array_pop($classNameParts);
        $resource = sprintf('@%s', $bundleName);
        $pathToBundle = $this->kernel->locateResource($resource);
        return sprintf('%sResources/config/search.yml', $pathToBundle);
    }

    protected function writeToCache($file)
    {
        $cacheFilePath = sprintf('%s/%s', $this->kernel->getCacheDir(), MetadataCollector::CACHE_FILE_NAME);
        $filesystem = new Filesystem();
        $filesystem->dumpFile($cacheFilePath, $file);
    }
}