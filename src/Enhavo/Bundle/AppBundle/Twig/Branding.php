<?php
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 12.12.16
 * Time: 13:14
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\DependencyInjection\EnhavoAppExtension;
use Symfony\Component\DependencyInjection\Container;

class Branding extends \Twig_Extension
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
            new \Twig_SimpleFunction('showBranding', array($this, 'showBranding'))
        );
    }

    public function showBranding()
    {
        $showBranding = $this->container->getParameter('enhavo_app.show_branding');
        return $showBranding;
    }

    public function getName()
    {
        return 'branding';
    }
}