<?php

/**
 * PageWidget.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\PageBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Widget\WidgetInterface;

class PageWidget extends AbstractType implements WidgetInterface
{
    /**
     * @inheritDoc
     */
    public function getType()
    {
        return 'page';
    }

    public function render($options)
    {
        $template = 'EnhavoPageBundle:Widget:page.html.twig';
        if(isset($options['template'])) {
            $template = $options['template'];
        }

        $repository = $this->container->get('enhavo_page.repository.page');
        $pages = $repository->findBy([
            'public' => true,
        ]);

        return $this->renderTemplate($template, [
            'pages' => $pages,
        ]);
    }
}
