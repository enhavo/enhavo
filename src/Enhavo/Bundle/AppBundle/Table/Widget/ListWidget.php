<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:10
 */

namespace Enhavo\Bundle\AppBundle\Table\Widget;

use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;

class ListWidget extends AbstractTableWidget {

    public function render($options, $property, $item) {
        $entity = 'get'.$property;
        $widgetProperty = 'get'.$options['property'];
        $entities = array();
        foreach($item->$entity() as $current) {
            $currentEntity = $current->$widgetProperty();
            $entities[] = $currentEntity;
        }
        $separator = ',';
        if(array_key_exists('separator', $options)){
            $separator = $options['separator'];
        }
        $templateEngine = $this->container->get('templating');
        return $templateEngine->render('EnhavoAppBundle:TableWidgets:ListWidget.html.twig', array(
            'entities' => $entities,
            'separator' => $separator
        ));
    }

    public function getType()
    {
        return 'list';
    }
}