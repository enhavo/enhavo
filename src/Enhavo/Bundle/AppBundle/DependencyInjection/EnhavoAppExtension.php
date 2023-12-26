<?php

namespace Enhavo\Bundle\AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoAppExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('enhavo_app.stylesheets', $config['stylesheets']);
        $container->setParameter('enhavo_app.javascripts', $config['javascripts']);
        $container->setParameter('enhavo_app.apps', $config['apps']);
        $container->setParameter('enhavo_app.mailer.mails', $config['mailer']['mails']);
        $container->setParameter('enhavo_app.mailer.defaults', $config['mailer']['defaults']);
        $container->setParameter('enhavo_app.mailer.model', $config['mailer']['model']);
        $container->setParameter('enhavo_app.menu', $config['menu']);
        $container->setParameter('enhavo_app.branding', $config['branding']);
        $container->setParameter('enhavo_app.login.route', $config['login']['route']);
        $container->setParameter('enhavo_app.login.route_parameters', $config['login']['route_parameters']);
        $container->setParameter('enhavo_app.template_paths', $config['template_paths']);
        $container->setParameter('enhavo_app.webpack_build', $config['webpack_build']);
        $container->setParameter('enhavo_app.roles', $config['roles']);
        $container->setParameter('enhavo_app.form_themes', $config['form_themes']);
        $container->setParameter('enhavo_app.locale', $config['locale']);
        $container->setParameter('enhavo_app.locale_resolver', $config['locale_resolver']);
        $container->setParameter('enhavo_app.toolbar_widget.primary', $config['toolbar_widget']['primary']);
        $container->setParameter('enhavo_app.toolbar_widget.secondary', $config['toolbar_widget']['secondary']);
        $container->setParameter('enhavo_app.vue.route_providers', $config['vue']['route_providers'] ?? []);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services/services.yaml');
        $loader->load('services/controller.yaml');
        $loader->load('services/endpoint.yaml');
        $loader->load('services/twig.yaml');
        $loader->load('services/init.yaml');
        $loader->load('services/locale.yaml');
        $loader->load('services/command.yaml');
        $loader->load('services/grid.yaml');
        $loader->load('services/view.yaml');
        $loader->load('services/filter.yaml');
        $loader->load('services/column.yaml');
        $loader->load('services/action.yaml');
        $loader->load('services/batch.yaml');
        $loader->load('services/menu.yaml');
        $loader->load('services/chart.yaml');
        $loader->load('services/maker.yaml');
        $loader->load('services/widget.yaml');
        $loader->load('services/toolbar.yaml');
        $loader->load('services/preview.yaml');
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $configs = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/app/config.yaml'));
        foreach($configs as $name => $config) {
            if (is_array($config)) {
                $container->prependExtensionConfig($name, $config);
            }
        }
    }
}
