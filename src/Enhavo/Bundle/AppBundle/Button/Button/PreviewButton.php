<?php

/**
 * PreviewButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Button\Button;

use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class PreviewButton extends AbstractType implements ButtonInterface
{
    public function render($options, $resource)
    {
        if(!array_key_exists('route', $options)) {
            throw new \InvalidArgumentException('route option is not defined for button');
        }

        return $this->renderTemplate('EnhavoAppBundle:Button:preview.html.twig', [
            'type' => $this->getType(),
            'icon' => isset($options['icon']) ? $options['icon'] : 'eye',
            'display' =>  isset($options['display']) ? $options['display'] : true,
            'role' => isset($options['role']) ? $options['role'] : true,
            'label' => isset($options['role']) ? $options['label'] : 'label.preview',
            'translationDomain' => isset($options['translationDomain']) ? $options['translationDomain'] : 'EnhavoAppBundle',
            'route' => $options['route']
        ]);
    }

    public function getType()
    {
        return 'preview';
    }
}