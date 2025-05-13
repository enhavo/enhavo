<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TemplateBundle\Factory;

use Enhavo\Bundle\BlockBundle\Entity\Node;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\TemplateBundle\Template\Template;

class TemplateFactory extends Factory
{
    public function createByTemplate(Template $template)
    {
        /** @var \Enhavo\Bundle\TemplateBundle\Entity\Template $new */
        $new = $this->createNew();

        $route = new Route();
        $route->setPath(sprintf('/%s/{slug}', $template->getKey()));
        $new->setRoute($route);

        $block = new Node();
        $new->setContent($block);

        return $new;
    }
}
