<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.11.17
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Route\UrlResolverInterface;
use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class UrlWidget extends AbstractTableWidget
{
    public function render($options, $resource)
    {
        $urlResolverName = $this->getOption('urlResolver', $options, 'enhavo_app.url_resolver');
        /** @var UrlResolverInterface $urlResolver */
        $urlResolver = $this->container->get($urlResolverName);
        $url = $urlResolver->resolve($resource);

        $template = $this->getOption('template', $options, 'EnhavoAppBundle:TableWidget:url.html.twig');
        $target = $this->getOption('target', $options, '_blank');
        $icon = $this->getOption('icon', $options, 'link');

        return $this->renderTemplate($template, array(
            'url' => $url,
            'target' => $target,
            'icon' => $icon,
        ));
    }

    public function getType()
    {
        return 'url';
    }
}