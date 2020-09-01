<?php

namespace Enhavo\Bundle\AppBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return array(
            new TwigFunction('routes', array($this, 'getRoutes'))
        );
    }

    public function getRoutes(): ?string
    {
        $file = $this->container->getParameter('kernel.project_dir').'/public/js/fos_js_routes.json';
        if(file_exists($file)) {
            return file_get_contents($file);
        }
        return null;
    }
}
