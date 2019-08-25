<?php

namespace Enhavo\Bundle\TranslationBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EnhavoTranslationExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_translation.enable', $config['enable']);
        $container->setParameter('enhavo_translation.default_locale', $config['default_locale']);
        $container->setParameter('enhavo_translation.locales', $config['locales']);
        $container->setParameter('enhavo_translation.metadata', $config['metadata']);
        $container->setParameter('enhavo_translation.translation_paths', $config['translation_paths']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/translator.yml');
        $loader->load('services/translation.yml');
        $loader->load('services/form.yml');
        $loader->load('services/metadata.yml');
    }
}
