<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:18
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class TemplateWidget extends AbstractTableWidget {

    public function render($options, $property, $item) {
        $templateEngine = $this->container->get('templating');
        return $templateEngine->render($options['template'], array(
            'data' => $item,
            'value' => $this->getProperty($item, $property)
        ));
    }

    public function getType()
    {
        return 'template';
    }
}