<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 27.05.19
 * Time: 11:07
 */

namespace Enhavo\Bundle\TemplateBundle\DependencyInjection;

use Enhavo\Bundle\ResourceBundle\DependencyInjection\PrependExtensionTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EnhavoTemplateExtension extends Extension implements PrependExtensionInterface
{
    use PrependExtensionTrait;

    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $container->setParameter('enhavo_template.template', $config['template']);

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
            __DIR__.'/../Resources/config/resources/template.yaml',
        ];
    }
}
