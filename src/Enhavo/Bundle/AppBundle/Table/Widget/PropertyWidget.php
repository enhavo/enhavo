<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Table\TableWidgetInterface;

class PropertyWidget extends AbstractType implements TableWidgetInterface
{
    public function render($options, $property, $item)
    {
        return $this->getProperty($item, $property);
    }

    public function getType()
    {
        return 'property';
    }
}