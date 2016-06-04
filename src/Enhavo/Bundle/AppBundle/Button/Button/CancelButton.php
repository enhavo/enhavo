<?php

/**
 * CancelButton.php
 *
 * @since 29/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Button\Button;

use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class CancelButton extends AbstractType implements ButtonInterface
{
    public function render($options, $resource)
    {
        return $this->renderTemplate('EnhavoAppBundle:Button:default.html.twig', [
            'type' => 'cancel',
            'icon' => isset($options['icon']) ? $options['icon'] : 'cross',
            'display' =>  isset($options['display']) ? $options['display'] : true,
            'role' => isset($options['role']) ? $options['role'] : true,
            'label' => isset($options['role']) ? $options['label'] : 'label.cancel',
            'translationDomain' => isset($options['translationDomain']) ? $options['translationDomain'] : 'EnhavoAppBundle'
        ]);
    }

    public function getType()
    {
        return 'cancel';
    }
}