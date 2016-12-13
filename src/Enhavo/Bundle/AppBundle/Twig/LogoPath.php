<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class LogoPath extends \Twig_Extension
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('enhavo_logo_path', array($this, 'getLogoPath')),
        );
    }

    public function getLogoPath()
    {
        return $this->container->getParameter('enhavo_app.logo_path');
    }

    public function getName()
    {
        return 'logo_path';
    }
}