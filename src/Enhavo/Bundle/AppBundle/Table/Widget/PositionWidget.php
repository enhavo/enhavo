<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class PositionWidget extends AbstractTableWidget
{
    public function render($options, $item)
    {
        $template = $this->getOption('template', $options, 'EnhavoAppBundle:TableWidget:position.html.twig');
        return $this->renderTemplate($template);
    }

    public function getLabel($options)
    {
        if(isset($options['label'])) {
            return parent::getLabel($options);
        }
        return '';
    }

    public function getType()
    {
        return 'position';
    }
}