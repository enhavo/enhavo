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
        return $this->renderTemplate('EnhavoAppBundle:TableWidget:position.html.twig');
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