<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 19.05.16
 * Time: 17:45
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

class ShowVersion extends \Twig_Extension {
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     * @param $template string
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('showVersion', array($this, 'showVersion')),
        );
    }

    public function showVersion()
    {
        $showVersion = $this->container->getParameter('enhavo_app.show_version');
        return $showVersion;
    }

    public function getName()
    {
        return 'show_version';
    }
}