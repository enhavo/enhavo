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

    public function compile(){
        $kernel = $this->kernel;
        $bundles = $kernel->getBundles();

        $setting_array = [];
        foreach($bundles as $bundle) {
            $className = get_class($bundle);
            $classParts = explode('\\', $className);
            $bundleName = array_pop($classParts);
            $resource = sprintf('@%s', $bundleName);
            $pathToBundle = $kernel->locateResource($resource);
            $settingPath = sprintf('%sResources/config/setting.yml', $pathToBundle);
            if(file_exists($settingPath)) {
                try {
                    $setting = Yaml::parse(file_get_contents($settingPath));
                } catch (ParseException $e) {
                    printf("Unable to parse the YAML string: %s", $e->getMessage());
                }
                $setting_array[] = $setting;
            }

        }
        var_dump($setting_array);

        $setting_array_json = json_encode($setting_array);
        $cacheFilePath = sprintf('%s/setting_array.json', $kernel->getCacheDir());
        $filesystem = new Filesystem();
        $filesystem->dumpFile($cacheFilePath, $setting_array_json);

//        var_dump(json_decode(file_get_contents($cacheFilePath), $assoc=true)); //use to decode
    }
}