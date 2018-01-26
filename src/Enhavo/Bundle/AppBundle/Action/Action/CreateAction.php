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
        $template = $this->getOption('template', $parameters, 'EnhavoAppBundle:Action:default.html.twig');

        $display = $this->getOption('display', $parameters, true);
        $role = $this->getOption('role', $parameters);
        if($role !== null && !$this->container->get('security.authorization_checker')->isGranted($role)) {
            $display = false;
        }

        return $this->renderTemplate($template, [
            'type' => $this->getType(),
            'actionType' => 'overlay',
            'route' => $this->getOption('route', $parameters, ''),
            'routeParameters' => $this->getOption('routeParameters', $parameters, []),
            'label' => $this->getOption('label', $parameters, 'label.create'),
            'icon' => $this->getOption('icon', $parameters, 'create'),
            'translationDomain' => $this->getOption('translationDomain', $parameters, 'EnhavoAppBundle'),
            'display' => $display
        ]);
    }

    public function getType()
    {
        return 'create';
    }
}