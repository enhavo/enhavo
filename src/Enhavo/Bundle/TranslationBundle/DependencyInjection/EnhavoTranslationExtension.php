<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EnhavoTranslationExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_translation.enable', $config['enable']);
        $container->setParameter('enhavo_translation.default_locale', $config['default_locale']);
        $container->setParameter('enhavo_translation.locales', $config['locales']);
        $container->setParameter('enhavo_translation.metadata', $config['metadata']);
        $container->setParameter('enhavo_translation.translator.access_control', $config['translator']['access_control']);
        $container->setParameter('enhavo_translation.form.access_control', $config['form']['access_control']);
        $container->setParameter('enhavo_translation.translator.default_access', $config['translator']['default_access']);
        $container->setParameter('enhavo_translation.form.default_access', $config['form']['default_access']);
        $container->setParameter('enhavo_translation.provider', $config['provider']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/translator.yaml');
        $loader->load('services/translation.yaml');
        $loader->load('services/generator.yaml');
        $loader->load('services/form.yaml');
        $loader->load('services/metadata.yaml');
    }

    protected function prependFiles(): array
    {
        return [
            __DIR__.'/../Resources/config/app/config.yaml',
        ];
    }
}
