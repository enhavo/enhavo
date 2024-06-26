<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-11
 * Time: 15:36
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

        $route  = new Route();
        $route->setPath(sprintf('/%s/{slug}', $template->getKey()));
        $new->setRoute($route);

        $block = new Node();
        $new->setContent($block);

        return $new;
    }
}
