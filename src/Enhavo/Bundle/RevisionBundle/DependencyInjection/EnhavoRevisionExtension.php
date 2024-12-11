<?php

namespace Enhavo\Bundle\RevisionBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\Configuration;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge\GridConfigurationMerger;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge\InputConfigurationMerger;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Merge\ResourceMerger;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class EnhavoRevisionExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_revision.restore', $config['restore'] ?? []);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services/services.yaml');
    }

    protected function prependFiles(): array
    {
        return [
            __DIR__.'/../Resources/config/app/config.yaml',
            __DIR__.'/../Resources/config/resources/bin.yaml',
            __DIR__.'/../Resources/config/resources/archive.yaml',
        ];
    }
}
