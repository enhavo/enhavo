<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 05/09/16
 * Time: 16:28
 */

namespace Enhavo\Bundle\AppBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigCompilerPass
 *
 * This class reads yaml files inside bundles and save the content in one json file.
 *
 * The configFilename is the file that should be read in the config directory of the bundle.
 * For example the configFilename is "search" and the cacheFilename "search_metadata",
 * than all files in "XXXBundle/Resources/config/search.yaml" will be read and save to "CACHE/search_metadata.json"
 *
 * @package Enhavo\Bundle\AppBundle\DependencyInjection\Compiler
 */
class ConfigCompilerPass implements CompilerPassInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var string
     */
    private $configFilename;

    /**
     * @var string
     */
    private $cacheFilename;

    public function __construct(KernelInterface $kernel, $configFilename, $cacheFilename)
    {
        $this->kernel = $kernel;
        $this->configFilename = $configFilename;
        $this->cacheFilename = $cacheFilename;
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
                $data = Yaml::parse(file_get_contents($absolutePath));
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

    private function composeSettingPathFor($bundle)
    {
        $className = get_class($bundle);
        $classNameParts = explode('\\', $className);
        $bundleName = array_pop($classNameParts);
        $resource = sprintf('@%s', $bundleName);
        $pathToBundle = $this->kernel->locateResource($resource);
        return sprintf('%sResources/config/%s.yaml', $pathToBundle, $this->configFilename);
    }

    private function writeToCache($content)
    {
        $cacheFilePath = sprintf('%s/%s.json', $this->kernel->getCacheDir(), $this->cacheFilename);
        $filesystem = new Filesystem();
        $filesystem->dumpFile($cacheFilePath, $content);
    }
}
