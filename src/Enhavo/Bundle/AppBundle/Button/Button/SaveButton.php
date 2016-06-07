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
        return $this->renderTemplate('EnhavoAppBundle:Button:save.html.twig', [
            'type' => $this->getType(),
            'icon' => isset($options['icon']) ? $options['icon'] : 'save',
            'display' =>  isset($options['display']) ? $options['display'] : true,
            'role' => isset($options['role']) ? $options['role'] : null,
            'label' => isset($options['label']) ? $options['label'] : 'label.save',
            'translationDomain' => isset($options['translationDomain']) ? $options['translationDomain'] : 'EnhavoAppBundle',
            'route' => isset($options['route']) ? $options['route'] : null,
            'id' => isset($resource) && $this->getProperty($resource, 'id') ? $this->getProperty($resource, 'id') : null
        ]);
    }

    public function getType()
    {
        return 'save';
    }
}