<?php

namespace Enhavo\Bundle\ThemeBundle\Widget\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\ThemeBundle\Widget\WidgetInterface;

/**
 * TemplateWidget.php
 *
 * @since 07/07/16
 * @author gseidel
 */
class TemplateWidget extends AbstractType implements WidgetInterface
{
    public function render($options)
    {
        if(!isset($options['template'])) {
            throw new \InvalidArgumentException('template needs to defined in template widget');
        }
        $template = $options['template'];

        $parameters = [];
        if(isset($options['parameters'])) {
            $parameters = $options['parameters'];
        }

        return $this->container->get('templating')->render($template, $parameters);
    }

    public function getType()
    {
        return 'template';
    }
}