<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 19.05.16
 * Time: 17:45
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\DependencyInjection\EnhavoAppExtension;
use Symfony\Component\DependencyInjection\Container;

class Version extends \Twig_Extension
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
            new \Twig_SimpleFunction('showVersion', array($this, 'showVersion')),
            new \Twig_SimpleFunction('version', array($this, 'getVersion')),
        );
    }

    public function showVersion()
    {
        $showVersion = $this->container->getParameter('enhavo_app.show_version');
        return $showVersion;
    }

    public function getVersion()
    {
        return EnhavoAppExtension::VERSION;
    }

    public function getName()
    {
        return 'version';
    }
}