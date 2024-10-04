<?php

namespace Enhavo\Bundle\ResourceBundle\DependencyInjection;


use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

trait PrependExtensionTrait
{
    public function prepend(ContainerBuilder $container): void
    {
        $files = $this->prependFiles();

        foreach ($files as $file) {
            $container->addResource(new FileResource($file));
            $configs = Yaml::parse(file_get_contents($file));
            foreach ($configs as $name => $config) {
                if (is_array($config)) {
                    $container->prependExtensionConfig($name, $config);
                }
            }
        }
    }

    protected function prependFiles(): array
    {
        return [];
    }
}
