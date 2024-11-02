<?php

namespace Enhavo\Bundle\PageBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class EnhavoPageExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $container->setParameter('enhavo_page.special_pages', array_merge(
            [
                'error_default' => [
                    'label' => 'page.label.error_page_default',
                    'translation_domain' => 'EnhavoPageBundle',
                ],
                'error_403' => [
                    'label' => 'page.label.error_page_403',
                    'translation_domain' => 'EnhavoPageBundle',
                ],
                'error_404' => [
                    'label' => 'page.label.error_page_404',
                    'translation_domain' => 'EnhavoPageBundle',
                ],
            ],
            $config['special_pages'])
        );

        $configFiles = array(
            'services.yaml',
        );
        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }

    protected function prependFiles(): array
    {
        return [
            __DIR__.'/../Resources/config/app/config.yaml',
            __DIR__.'/../Resources/config/resources/page.yaml'
        ];
    }
}
