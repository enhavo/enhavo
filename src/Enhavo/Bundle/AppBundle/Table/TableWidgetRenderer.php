<?php

namespace Enhavo\Bundle\AppBundle\Table;

use Enhavo\Bundle\AppBundle\Type\AbstractRenderer;

class TableWidgetRenderer extends AbstractRenderer
{
    public function getFunctions()
    {
        $functions = parent::getFunctions();
        return array_merge($functions, [
            new \Twig_SimpleFunction('table_widget_label', array($this, 'getLabel'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('table_widget_width', array($this, 'getWidth')),
        ]);
    }

    public function getLabel($type, $options)
    {
        /** @var $type TableWidgetInterface */
        $type = $this->getType($type);
        return $type->getLabel($options);
    }

    public function getWidth($type, $options)
    {
        /** @var $type TableWidgetInterface */
        $type = $this->getType($type);
        return $type->getWidth($options);
    }

    public function render($type, $options, $resource)
    {
        /** @var $type TableWidgetInterface */
        $type = $this->getType($type);
        return $type->render($options, $resource);
    }

    public function getName()
    {
        return 'table_widget_render';
    }
}