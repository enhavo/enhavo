<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 27.05.19
 * Time: 11:07
 */

namespace Enhavo\Bundle\SidebarBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EnhavoSidebarExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $this->registerResources('enhavo_sidebar', $config['driver'], $config['resources'], $container);
        $configFiles = array(
            'services.yaml',
        );
        foreach ($configFiles as $configFile) {
            $loader->load($configFile);
        }
    }
}
