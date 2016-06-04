<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:18
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class TemplateWidget extends AbstractTableWidget
{
    public function render($options, $resource)
    {
        return $this->renderTemplate($options['template'], array(
            'value' => $this->getProperty($resource, $options['property']),
            'data' => $resource
        ));
    }

    public function getType()
    {
        return 'template';
    }
}