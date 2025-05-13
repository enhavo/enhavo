<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return [
            new TwigFunction('routes', [$this, 'getRoutes']),
        ];
    }

    public function getRoutes(): ?string
    {
        $file = $this->container->getParameter('kernel.project_dir').'/public/js/fos_js_routes.json';
        if (file_exists($file)) {
            return file_get_contents($file);
        }

        return null;
    }
}
