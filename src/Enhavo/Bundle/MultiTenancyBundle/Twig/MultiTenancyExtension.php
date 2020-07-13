<?php

namespace Bundle\MultiTenancyBundle\Twig;

use Bundle\MultiTenancyBundle\Manager\MultiTenancyManager;
use Bundle\MultiTenancyBundle\Model\MultiTenancyConfiguration;
use Bundle\MultiTenancyBundle\Resolver\MultiTenancyResolver;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MultiTenancyExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('multiTenancy_manager', array($this, 'getMultiTenancyManager')),
            new \Twig_SimpleFunction('multiTenancy_configuration', array($this, 'getMultiTenancyConfiguration')),
        );
    }

    /**
     * @return MultiTenancyManager
     */
    public function getMultiTenancyManager()
    {
        /** @var MultiTenancyManager $manager */
        $manager = $this->container->get(MultiTenancyManager::class);
        return $manager;
    }

    /**
     * @return MultiTenancyConfiguration
     */
    public function getMultiTenancyConfiguration()
    {
        return $this->container->get(MultiTenancyResolver::class)->getConfiguration();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'multiTenancy_extension';
    }
}
