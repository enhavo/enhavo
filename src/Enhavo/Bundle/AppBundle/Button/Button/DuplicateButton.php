<?php
namespace Enhavo\Bundle\AppBundle\Button\Button;


use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class DuplicateButton extends AbstractType implements ButtonInterface
{
    public function render($options, $resource)
    {
        if(!array_key_exists('route', $options)) {
            throw new \InvalidArgumentException('route option is not defined for button');
        }

        return $this->renderTemplate('EnhavoAppBundle:Button:duplicate.html.twig', [
            'type' => $this->getType(),
            'icon' => isset($options['icon']) ? $options['icon'] : 'docs',
            'display' =>  isset($options['display']) ? $options['display'] : true,
            'role' => isset($options['role']) ? $options['role'] : null,
            'label' => isset($options['label']) ? $options['label'] : 'label.duplicate',
            'translationDomain' => isset($options['translationDomain']) ? $options['translationDomain'] : 'EnhavoAppBundle',
            'route' => $options['route']
        ]);
    }

    public function getType()
    {
        return 'duplicate';
    }
}
