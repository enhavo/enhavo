<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class TextWidget extends AbstractTableWidget
{
    public function render($options, $item)
    {
        $template = $this->getOption('template', $options, 'EnhavoAppBundle:TableWidget:text.html.twig');
        $property = $this->getProperty($item, $options['property']);
        return $this->renderTemplate($template, array(
            'value' => $property
        ));
    }

    public function getType()
    {
        return 'text';
    }
}