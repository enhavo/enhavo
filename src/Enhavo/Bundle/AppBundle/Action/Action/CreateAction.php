<?php

/**
 * CreateAction.php
 *
 * @since 30/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Action\Action;

use Enhavo\Bundle\AppBundle\Action\ActionInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class CreateAction extends AbstractType implements ActionInterface
{
    public function render($parameters)
    {
        return $this->renderTemplate('EnhavoAppBundle:Action:default.html.twig', [
            'type' => $this->getType(),
            'actionType' => 'overlay',
            'route' => isset($parameters['route']) ? $parameters['route'] : '',
            'routeParameters' => isset($parameters['routeParameters']) ? $parameters['routeParameters'] : [],
            'label' => isset($parameters['label']) ? $parameters['label'] : 'label.create',
            'icon' => isset($parameters['icon']) ? $parameters['icon'] : 'create',
            'translationDomain' => isset($parameters['translationDomain']) ? $parameters['translationDomain'] : 'EnhavoAppBundle',
            'display' =>  isset($parameters['display']) ? $parameters['display'] : true,
        ]);
    }

    public function getType()
    {
        return 'create';
    }
}