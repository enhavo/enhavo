<?php
/**
 * Created by PhpStorm.
 * User: jungch
 * Date: 26/08/16
 * Time: 12:12
 */

namespace Enhavo\Bundle\SettingBundle\DependencyInjection\Compiler;

use Enhavo\Bundle\AppBundle\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Enhavo\Bundle\SettingBundle\Provider\DatabaseProvider;

class ConfigCompilerPass implements CompilerPassInterface
{
    protected $kernel;

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

        $settingArray = [];
        foreach($bundles as $bundle) {
            $settingPath = $this->composeSettingPathFor($bundle);
            if(file_exists($settingPath)) {
                try {
                    $settings = Yaml::parse(file_get_contents($settingPath));
                } catch (ParseException $e) {
                    printf("Unable to parse the YAML string: %s", $e->getMessage());
                }
                foreach($settings as $key => $setting) {
                    $settingArray[$key] = $setting;
                }
            }
        }
        $settingArrayJson = json_encode($settingArray);
        $this->writeToCache($settingArrayJson);
    }

    protected function composeSettingPathFor($bundle)
    {
        $className = get_class($bundle);
        $classNameParts = explode('\\', $className);
        $bundleName = array_pop($classNameParts);
        $resource = sprintf('@%s', $bundleName);
        $pathToBundle = $this->kernel->locateResource($resource);
        return sprintf('%sResources/config/setting.yml', $pathToBundle);
    }

    protected function writeToCache($file)
    {
        $cacheFilePath = sprintf('%s/%s', $this->kernel->getCacheDir(), DatabaseProvider::CACHE_FILE_NAME);
        $filesystem = new Filesystem();
        $filesystem->dumpFile($cacheFilePath, $file);
    }

}