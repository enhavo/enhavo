<?php

namespace Enhavo\Bundle\TranslationBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EnhavoTranslationExtension extends AbstractResourceExtension implements PrependExtensionInterface
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
        $container->setParameter('enhavo_translation.translator.access_control', $config['translator']['access_control']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/translator.yaml');
        $loader->load('services/translation.yaml');
        $loader->load('services/form.yaml');
        $loader->load('services/metadata.yaml');
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $path = __DIR__ . '/../Resources/config/app/';
        $files = scandir($path);

        foreach ($files as $file) {
            if (preg_match('/\.yaml$/', $file)) {
                $settings = Yaml::parse(file_get_contents($path . $file));
                if (is_array($settings)) {
                    foreach ($settings as $name => $value) {
                        if (is_array($value)) {
                            $container->prependExtensionConfig($name, $value);
                        }
                    }
                }
            }
        }
    }
}
