<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-01-03
 * Time: 00:58
 */

namespace Enhavo\Bundle\AppBundle\Behat\Extension;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PantherExtension implements ExtensionInterface
{
    public function getConfigKey()
    {
        return 'panther';
    }

    public function initialize(ExtensionManager $extensionManager)
    {

    }

    public function configure(ArrayNodeDefinition $builder)
    {

    }

    public function load(ContainerBuilder $container, array $config)
    {

    }

    public function process(ContainerBuilder $container)
    {

    }
}
