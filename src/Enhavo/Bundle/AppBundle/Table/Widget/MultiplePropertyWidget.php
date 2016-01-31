<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:11
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class MultiplePropertyWidget extends AbstractTableWidget {

    public function render($options, $property, $item) {
        $templateEngine = $this->container->get('templating');
        $separator = ',';
        if(array_key_exists('separator', $options)){
            $separator = $options['separator'];
        }
        return $templateEngine->render('EnhavoAppBundle:TableWidgets:MultiplePropertyWidget.html.twig', array(
            'properties' => $options['properties'],
            'separator' => $separator,
            'item' => $item,
            'viewer' => $this
        ));
    }

    public function getType()
    {
        return 'multiple_property';
    }

}