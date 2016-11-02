<?php

/**
 * CancelButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Button\Button;

namespace Enhavo\Bundle\AppBundle\Button\Button;

use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class SaveButton extends AbstractType implements ButtonInterface
{
    public function render($options, $resource)
    {
        if(!isset($options['routeParameters'])) {
            $routeParameters = [
                'id' => isset($resource) && $this->getProperty($resource, 'id') ? $this->getProperty($resource, 'id') : null,
            ];
        } else {
            $routeParameters = $options['routeParameters'];
            if(is_array($routeParameters)) {
                foreach($routeParameters as $key => $parameterValue) {
                    $routeParameters[$key] = $this->parseValue($parameterValue, $resource);
                }
            }
        }

        return $this->renderTemplate('EnhavoAppBundle:Button:save.html.twig', [
            'type' => $this->getType(),
            'icon' => $this->getOption('icon', $options, 'save'),
            'display' =>  $this->getOption('display', $options, true),
            'role' => $this->getOption('role', $options),
            'label' => $this->getOption('label', $options, 'label.save'),
            'translationDomain' => $this->getOption('translationDomain', $options, 'EnhavoAppBundle'),
            'route' => $this->getOption('route', $options),
            'close' => $this->getOption('close', $options, true),
            'routeParameters' => $routeParameters,
            'id' => isset($resource) && $this->getProperty($resource, 'id') ? $this->getProperty($resource, 'id') : null
        ]);
    }

    public function getType()
    {
        return 'save';
    }
}