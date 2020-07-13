<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 11.07.18
 * Time: 23:06
 */

namespace Bundle\MultiTenancyBundle\Manager;

use Bundle\MultiTenancyBundle\Exception\MultiTenancyConfigurationException;
use Bundle\MultiTenancyBundle\Factory\ConfigurationFactoryInterface;
use Bundle\MultiTenancyBundle\Model\MultiTenancyConfiguration;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MultiTenancyManager
{
    use ContainerAwareTrait;

    /**
     * @var MultiTenancyConfiguration[]
     */
    private $configurations;

    public function __construct(ContainerInterface $container, $configuration)
    {
        if(!$container->hasParameter($configuration['parameters_path'])) {
            throw new MultiTenancyConfigurationException(sprintf('Parameters "%s" does not exists', $configuration['parameters_path']));
        }

        if(!$container->has($configuration['factory'])) {
            throw new MultiTenancyConfigurationException(sprintf('Configuration factory "%s" is not a service', $configuration['factory']));
        }

        $multiTenancys = $container->getParameter($configuration['parameters_path']);
        $factory = $container->get($configuration['factory']);

        if(!$factory instanceof ConfigurationFactoryInterface) {
            throw new MultiTenancyConfigurationException(sprintf('Factory must by type of "%s"', ConfigurationFactoryInterface::class));
        }

        $this->configurations = [];
        foreach($multiTenancys as $key => $config) {
            $configuration = $factory->create($config);

            $configuration->setKey($key);
            $this->configurations[$key] = $configuration;
        }

        $this->setContainer($container);
    }

    public function getConfigurations()
    {
        return $this->configurations;
    }

    public function getConfiguration($key)
    {
        if(isset($this->configurations[$key])) {
            return $this->configurations[$key];
        }
        return null;
    }
}
